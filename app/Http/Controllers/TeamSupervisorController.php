<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\Models\Attachment;
use App\Models\FreelanceQuote;
use App\Models\Order;
use App\Models\OrderComment;
use App\Models\SupervisorTeamMember;
use App\Models\TeamFine;
use App\Support\PasswordManager;
use App\Support\PortalMailer;
use App\Support\TeamAccess;
use App\Support\TeamNavigation;
use App\Support\TeamWorkQueues;
use App\Support\TurnaroundTracking;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TeamSupervisorController extends Controller
{
    public function members(Request $request)
    {
        $supervisor = $request->attributes->get('teamUser');
        $members = TeamAccess::teamMembers($supervisor)
            ->when($request->filled('txtUserID'), fn ($collection) => $collection->filter(fn ($user) => str_contains((string) $user->user_id, trim((string) $request->string('txtUserID')))))
            ->when($request->filled('txtUserName'), function ($collection) use ($request) {
                $needle = strtolower(trim((string) $request->string('txtUserName')));

                return $collection->filter(fn ($user) => str_contains(strtolower((string) $user->user_name), $needle));
            })
            ->sortBy('user_name')
            ->values();

        $memberStats = $this->memberStats($supervisor, $members);

        return view('team.supervisor.members', [
            'teamUser' => $supervisor,
            'navCounts' => TeamNavigation::counts($supervisor->user_id, (int) $supervisor->usre_type_id),
            'members' => $members,
            'memberStats' => $memberStats,
        ]);
    }

    public function memberDetail(Request $request)
    {
        $supervisor = $request->attributes->get('teamUser');
        $member = $this->managedMember($supervisor, (int) $request->query('user_id'));

        $activeOrders = Order::query()
            ->with('assignee:user_id,user_name')
            ->active()
            ->where('assign_to', $member->user_id)
            ->whereIn('status', ['Underprocess', 'disapprove', 'disapproved'])
            ->orderByDesc('order_id')
            ->get();
        $this->decorateTurnaroundRows($activeOrders);

        $readyOrders = Order::query()
            ->with('assignee:user_id,user_name')
            ->active()
            ->where('assign_to', $member->user_id)
            ->where('status', 'Ready')
            ->orderByDesc('order_id')
            ->get();
        $this->decorateTurnaroundRows($readyOrders);

        $reviewComments = OrderComment::query()
            ->where('comment_source', 'supervisorReview')
            ->whereIn('order_id', $readyOrders->pluck('order_id'))
            ->get()
            ->keyBy('order_id');

        $stats = $this->memberStats($supervisor, collect([$member]))[$member->user_id] ?? $this->emptyStats();

        return view('team.supervisor.member-detail', [
            'teamUser' => $supervisor,
            'navCounts' => TeamNavigation::counts($supervisor->user_id, (int) $supervisor->usre_type_id),
            'member' => $member,
            'stats' => $stats,
            'activeOrders' => $activeOrders,
            'readyOrders' => $readyOrders,
            'reviewComments' => $reviewComments,
            'detailUrl' => fn (Order $order) => $this->detailUrl($order),
        ]);
    }

    public function reviewQueue(Request $request)
    {
        $supervisor = $request->attributes->get('teamUser');

        // Show ALL pending supervisor-review jobs (supervisor sees everyone's work)
        $orders = Order::query()
            ->with('assignee:user_id,user_name')
            ->active()
            ->where('status', 'Ready')
            ->where('supervisor_status', 'pending')
            ->when($request->filled('txtUserID'), fn ($query) => $query->where('assign_to', (int) $request->query('txtUserID')))
            ->when($request->filled('txtOrderID'), fn ($query) => $query->where('order_id', 'like', '%'.trim((string) $request->string('txtOrderID')).'%'))
            ->orderByDesc('vender_complete_date')
            ->orderByDesc('order_id')
            ->get();
        $this->decorateTurnaroundRows($orders);

        $allTeamMembers = AdminUser::teamPortalUsers()->active()->orderBy('user_name')->get(['user_id', 'user_name']);

        return view('team.supervisor.review-queue', [
            'teamUser'  => $supervisor,
            'navCounts' => TeamNavigation::counts($supervisor->user_id, (int) $supervisor->usre_type_id),
            'orders'    => $orders,
            'members'   => $allTeamMembers,
            'detailUrl' => fn (Order $order) => $this->detailUrl($order),
        ]);
    }

    public function assignmentMonitor(Request $request)
    {
        $supervisor = $request->attributes->get('teamUser');
        $groupFilter = in_array($request->query('group'), ['inhouse', 'freelance'], true)
            ? $request->query('group')
            : '';

        $ordersQuery = Order::query()
            ->with('assignee:user_id,user_name')
            ->active()
            ->where('status', 'Underprocess')
            ->when($groupFilter !== '', fn ($q) => $q->where('assigned_group', $groupFilter))
            ->orderBy('completion_date')
            ->orderByDesc('order_id');

        $orders = $ordersQuery->get();
        $this->decorateTurnaroundRows($orders);

        $orderIds = $orders->pluck('order_id')->map(fn ($id) => (int) $id)->all();

        $quoteCounts = FreelanceQuote::query()
            ->selectRaw('order_id, COUNT(*) as quote_count')
            ->whereIn('order_id', $orderIds)
            ->where('status', 'pending')
            ->groupBy('order_id')
            ->pluck('quote_count', 'order_id');

        $acceptedQuotes = FreelanceQuote::query()
            ->with('teamUser:user_id,user_name,display_name')
            ->whereIn('order_id', $orderIds)
            ->where('status', 'accepted')
            ->get()
            ->keyBy('order_id');

        foreach ($orders as $order) {
            $order->setAttribute('pending_quote_count', (int) ($quoteCounts[(int) $order->order_id] ?? 0));
            $order->setAttribute('accepted_quote', $acceptedQuotes[(int) $order->order_id] ?? null);
        }

        return view('team.supervisor.assignment-monitor', [
            'teamUser'    => $supervisor,
            'navCounts'   => TeamNavigation::counts($supervisor->user_id, (int) $supervisor->usre_type_id),
            'orders'      => $orders,
            'groupFilter' => $groupFilter,
            'detailUrl'   => fn (Order $order) => $this->detailUrl($order),
        ]);
    }

    public function memberForm(Request $request)
    {
        $supervisor = $request->attributes->get('teamUser');
        $member = $request->filled('user_id')
            ? $this->managedMember($supervisor, (int) $request->query('user_id'))
            : new AdminUser(['usre_type_id' => AdminUser::TYPE_TEAM, 'is_active' => 1]);

        return view('team.supervisor.member-form', [
            'teamUser' => $supervisor,
            'navCounts' => TeamNavigation::counts($supervisor->user_id, (int) $supervisor->usre_type_id),
            'member' => $member,
            'mode' => $member->exists ? 'edit' : 'create',
        ]);
    }

    public function memberSave(Request $request)
    {
        $supervisor = $request->attributes->get('teamUser');
        $member = $request->filled('user_id')
            ? $this->managedMember($supervisor, (int) $request->input('user_id'))
            : new AdminUser();

        $validated = $request->validate([
            'user_id' => ['nullable', 'integer'],
            'txtTeamName' => ['required', 'string', 'max:150'],
            'txtPassword' => [$member->exists ? 'nullable' : 'required', 'string', 'min:6', 'max:100'],
            'txtCPassword' => [$member->exists ? 'nullable' : 'required', 'same:txtPassword'],
            'txtEmail' => ['required', 'email', 'max:150'],
        ], [
            'txtCPassword.same' => 'The confirm password must match the password.',
        ], [
            'txtTeamName' => 'user name',
            'txtPassword' => 'password',
            'txtCPassword' => 'confirm password',
            'txtEmail' => 'email address',
        ]);

        $duplicateQuery = AdminUser::query()
            ->teamPortalUsers()
            ->where('user_name', $validated['txtTeamName']);

        if ($member->exists) {
            $duplicateQuery->where('user_id', '!=', $member->user_id);
        }

        if ($duplicateQuery->exists()) {
            return back()->withErrors(['txtTeamName' => 'User name already exists.'])->withInput();
        }

        $member->fill([
            'user_name' => $validated['txtTeamName'],
            'user_email' => $validated['txtEmail'],
            'usre_type_id' => AdminUser::TYPE_TEAM,
            'is_active' => $member->exists ? (int) ($member->is_active ?? 1) : 1,
        ]);

        if (! $member->exists) {
            $member->fill([
                'security_key' => Str::random(40),
                'date_added' => now()->format('Y-m-d H:i:s'),
                'first_name' => '',
                'last_name' => '',
                'company' => '',
                'company_type' => '',
                'alternate_email' => '',
                'company_address' => '',
                'zip_code' => '',
                'user_city' => '',
                'user_country' => '',
                'user_phone' => '',
                'contact_person' => '',
                'middle_fee' => 1.50,
                'super_fee' => 0,
                'userip_addrs' => '',
                'digitzing_format' => '',
                'vertor_format' => '',
                'topup' => '',
                'exist_customer' => '0',
                'user_term' => '',
                'package_type' => '',
                'real_user' => '0',
                'ref_code' => '',
                'ref_code_other' => '',
                'register_by' => $supervisor->user_name ?: 'supervisor',
            ]);
        }

        if ($request->filled('txtPassword')) {
            $member->forceFill(PasswordManager::payload((string) $validated['txtPassword']));
        }

        $member->save();

        if (Schema::hasTable('supervisor_team_members')) {
            SupervisorTeamMember::query()->updateOrCreate([
                'supervisor_user_id' => $supervisor->user_id,
                'member_user_id' => $member->user_id,
            ], [
                'date_added' => now()->format('Y-m-d H:i:s'),
                'end_date' => null,
                'deleted_by' => null,
            ]);
        }

        return redirect()->to(url('/team/manage-team.php'))
            ->with('success', $member->wasRecentlyCreated ? 'Team member created successfully.' : 'Team member updated successfully.');
    }

    public function assignForm(Request $request)
    {
        $supervisor = $request->attributes->get('teamUser');
        $order = $this->accessibleOrder($supervisor, (int) $request->query('design_id'));
        $page = in_array($request->query('page'), ['order', 'quote', 'qquote', 'vector'], true) ? (string) $request->query('page') : 'order';
        $order->loadMissing(['customer:user_id,user_name,first_name,last_name,user_email', 'assignee:user_id,user_name,user_email']);

        $shareableAttachments = Attachment::query()
            ->where('order_id', $order->order_id)
            ->whereIn('file_source', $page === 'quote' ? ['quote', 'vector', 'color', 'edit quote'] : ['order', 'vector', 'color', 'edit order'])
            ->orderByDesc('id')
            ->get();

        $freelanceQuotes = null;
        $isFreelancePool = $order->assigned_group === 'freelance'
            && ((int) $order->assign_to === 0 || $order->assign_to === null || $order->assign_to === '');
        if ($isFreelancePool) {
            $freelanceQuotes = FreelanceQuote::query()
                ->with('teamUser:user_id,user_name,display_name')
                ->where('order_id', $order->order_id)
                ->orderBy('created_at')
                ->get();
        }

        return view('team.supervisor.assign', [
            'teamUser'           => $supervisor,
            'navCounts'          => TeamNavigation::counts($supervisor->user_id, (int) $supervisor->usre_type_id),
            'order'              => $order,
            'page'               => $page,
            'backUrl'            => $this->supervisorBackUrl($order, $page),
            'shareableAttachments' => $shareableAttachments,
            'turnaround'         => TurnaroundTracking::summary($order),
            'freelanceQuotes'    => $freelanceQuotes,
            'isFreelancePool'    => $isFreelancePool,
        ]);
    }

    public function assignSave(Request $request)
    {
        $supervisor = $request->attributes->get('teamUser');

        $validated = $request->validate([
            'design_id'      => ['required', 'integer'],
            'page'           => ['required', 'in:order,quote,qquote,vector'],
            'group'          => ['required', 'in:inhouse,freelance'],
            'handoff_comment'=> ['nullable', 'string'],
        ], [], [
            'design_id'      => 'order',
            'page'           => 'page',
            'group'          => 'team group',
            'handoff_comment'=> 'handoff comment',
        ]);

        $order = $this->accessibleOrder($supervisor, (int) $validated['design_id']);

        if (filled($validated['handoff_comment'] ?? null)) {
            OrderComment::query()->create([
                'order_id'       => $order->order_id,
                'comments'       => $validated['handoff_comment'],
                'source_page'    => 'orderTeamComments',
                'comment_source' => 'orderTeamComments',
                'date_added'     => now()->format('Y-m-d H:i:s'),
                'date_modified'  => now()->format('Y-m-d H:i:s'),
            ]);
        }

        $order->update([
            'assign_to'      => 0,
            'assigned_group' => $validated['group'],
            'assigned_date'  => now()->format('Y-m-d H:i:s'),
            'working'        => '',
            'status'         => in_array((string) $order->status, ['disapprove', 'disapproved'], true) ? 'disapprove' : 'Underprocess',
        ]);

        return redirect()->to($this->supervisorBackUrl($order, (string) $validated['page']))
            ->with('success', 'Work assignment updated successfully.');
    }

    public function markReviewed(Request $request)
    {
        $supervisor = $request->attributes->get('teamUser');

        $validated = $request->validate([
            'order_id' => ['required', 'integer'],
            'review_note' => ['nullable', 'string'],
        ], [], [
            'order_id' => 'order',
            'review_note' => 'review note',
        ]);

        $order = $this->accessibleOrder($supervisor, (int) $validated['order_id']);
        abort_unless((string) $order->status === 'Ready', 404);
        abort_unless(in_array((int) $order->assign_to, TeamAccess::teamMembers($supervisor)->pluck('user_id')->map(fn ($id) => (int) $id)->all(), true), 404);

        $note = trim((string) ($validated['review_note'] ?? ''));
        $message = $note !== ''
            ? $note
            : 'Verified by supervisor '.$supervisor->user_name.' on '.now()->format('Y-m-d H:i:s');

        OrderComment::query()->updateOrCreate(
            [
                'order_id' => $order->order_id,
                'comment_source' => 'supervisorReview',
            ],
            [
                'comments' => $message,
                'source_page' => 'supervisorReview',
                'date_added' => now()->format('Y-m-d H:i:s'),
                'date_modified' => now()->format('Y-m-d H:i:s'),
            ]
        );

        return back()->with('success', 'Supervisor review saved successfully.');
    }

    private function managedMember(AdminUser $supervisor, int $memberId): AdminUser
    {
        abort_unless(TeamAccess::canManageUser($supervisor, $memberId), 404);

        return AdminUser::query()
            ->teams()
            ->active()
            ->where('is_active', 1)
            ->findOrFail($memberId);
    }

    private function accessibleOrder(AdminUser $supervisor, int $orderId): Order
    {
        return Order::query()
            ->active()
            ->where('order_id', $orderId)
            ->whereIn('assign_to', TeamAccess::accessibleUserIds($supervisor))
            ->firstOrFail();
    }

    private function supervisorBackUrl(Order $order, string $page): string
    {
        if ($page === 'qquote') {
            return url('/team/quick-quotes/'.$order->order_id.'/detail');
        }

        $act = in_array((string) $order->status, ['disapprove', 'disapproved'], true)
            ? 'disapproved'
            : ($page === 'quote' ? 'quote' : 'order');

        return TeamWorkQueues::detailUrl($order, $act);
    }

    private function memberStats(AdminUser $supervisor, Collection $members): array
    {
        if ($members->isEmpty()) {
            return [];
        }

        $memberIds = $members->pluck('user_id')->map(fn ($id) => (int) $id)->all();
        $orders = Order::query()
            ->active()
            ->whereIn('assign_to', $memberIds)
            ->get(['order_id', 'assign_to', 'status', 'working']);

        $reviewedOrderIds = OrderComment::query()
            ->where('comment_source', 'supervisorReview')
            ->whereIn('order_id', $orders->pluck('order_id'))
            ->pluck('order_id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $reviewedLookup = array_fill_keys($reviewedOrderIds, true);

        $stats = [];
        foreach ($members as $member) {
            $memberOrders = $orders->where('assign_to', $member->user_id);
            $stats[$member->user_id] = [
                'active' => $memberOrders->whereIn('status', ['Underprocess', 'disapprove', 'disapproved'])->count(),
                'working' => $memberOrders->filter(fn ($order) => (string) $order->status === 'Underprocess' && trim((string) $order->working) !== '')->count(),
                'ready' => $memberOrders->where('status', 'Ready')->count(),
                'disapproved' => $memberOrders->whereIn('status', ['disapprove', 'disapproved'])->count(),
                'verified' => $memberOrders->filter(fn ($order) => (string) $order->status === 'Ready' && isset($reviewedLookup[(int) $order->order_id]))->count(),
            ];
        }

        return $stats;
    }

    private function emptyStats(): array
    {
        return [
            'active' => 0,
            'working' => 0,
            'ready' => 0,
            'disapproved' => 0,
            'verified' => 0,
        ];
    }

    private function decorateTurnaroundRows(Collection $orders): void
    {
        $orders->transform(function (Order $order) {
            $turnaround = TurnaroundTracking::summary($order);
            $order->turnaround_label = $turnaround['label'];
            $order->turnaround_status_label = $turnaround['status_label'];
            $order->turnaround_status_tone = $turnaround['status_tone'];
            $order->turnaround_remaining_label = $turnaround['remaining_label'];

            return $order;
        });
    }

    public function supervisorApprove(Request $request, Order $order)
    {
        $supervisor = $request->attributes->get('teamUser');

        abort_unless((string) $order->status === 'Ready' && $order->supervisor_status === 'pending', 403);

        $validated = $request->validate([
            'fine_amount' => ['nullable', 'numeric', 'min:0.01'],
            'fine_reason' => ['required_with:fine_amount', 'nullable', 'string', 'max:1000'],
        ]);

        if (! empty($validated['fine_amount'])) {
            $this->createFine($order, $supervisor, (float) $validated['fine_amount'], (string) $validated['fine_reason']);
        }

        $order->update(['supervisor_status' => 'approved']);

        $this->notifyAdminsApproved($order, $supervisor);

        return redirect()->route('supervisor.review-queue')->with('success', 'Order #'.$order->order_id.' approved and sent to admin review.');
    }

    public function supervisorDisapprove(Request $request, Order $order)
    {
        $supervisor = $request->attributes->get('teamUser');

        abort_unless((string) $order->status === 'Ready' && $order->supervisor_status === 'pending', 403);

        $validated = $request->validate([
            'reason'      => ['required', 'string', 'max:1000'],
            'fine_amount' => ['nullable', 'numeric', 'min:0.01'],
            'fine_reason' => ['required_with:fine_amount', 'nullable', 'string', 'max:1000'],
        ], [], [
            'reason'      => 'disapproval reason',
            'fine_amount' => 'fine amount',
            'fine_reason' => 'fine reason',
        ]);

        if (! empty($validated['fine_amount'])) {
            $this->createFine($order, $supervisor, (float) $validated['fine_amount'], (string) $validated['fine_reason']);
        }

        $order->update([
            'status'               => 'disapprove',
            'supervisor_status'    => null,
            'vender_complete_date' => null,
        ]);

        OrderComment::create([
            'order_id'       => $order->order_id,
            'comment_source' => 'supervisorDisapproval',
            'source_page'    => 'supervisorReview',
            'comments'       => $validated['reason'],
            'comment_by'     => $supervisor->user_id,
            'date_added'     => now()->format('Y-m-d H:i:s'),
            'date_modified'  => now()->format('Y-m-d H:i:s'),
        ]);

        $assignee = $order->assign_to ? AdminUser::find((int) $order->assign_to) : null;
        if ($assignee && $assignee->user_email) {
            $body = view('admin.emails.supervisor-disapproved-job', [
                'teamMemberName' => trim((string) ($assignee->display_name ?: $assignee->user_name)),
                'orderId'        => $order->order_id,
                'designName'     => $order->design_name ?: 'Order #'.$order->order_id,
                'reason'         => $validated['reason'],
                'detailUrl'      => url('/team/orders/'.$order->order_id.'/detail/order'),
            ])->render();

            PortalMailer::sendHtml($assignee->user_email, 'Job returned for revision — Order #'.$order->order_id, $body);
        }

        return redirect()->route('supervisor.review-queue')->with('success', 'Order #'.$order->order_id.' returned to team for revision.');
    }

    public function acceptJob(Request $request, Order $order)
    {
        $supervisor = $request->attributes->get('teamUser');

        abort_unless(
            ((int) $order->assign_to === 0 || $order->assign_to === null || $order->assign_to === '')
            && (string) $order->status === 'Underprocess',
            403
        );

        DB::transaction(function () use ($order, $supervisor) {
            $fresh = Order::query()->where('order_id', $order->order_id)->lockForUpdate()->firstOrFail();

            abort_unless(
                ((int) $fresh->assign_to === 0 || $fresh->assign_to === null || $fresh->assign_to === '')
                && (string) $fresh->status === 'Underprocess',
                409
            );

            $fresh->update([
                'assign_to' => $supervisor->user_id,
                'working'   => now()->format('Y-m-d H:i:s'),
            ]);
        });

        $act = in_array((string) $order->order_type, ['quote', 'digitzing', 'q-vector', 'qcolor'], true) ? 'quote' : 'order';

        return redirect()->to(url('/team/orders/'.$order->order_id.'/detail/'.$act))
            ->with('success', 'You have accepted Order #'.$order->order_id.'.');
    }

    public function acceptFreelanceQuote(Request $request, Order $order)
    {
        $supervisor = $request->attributes->get('teamUser');

        abort_unless($order->assigned_group === 'freelance', 403);
        abort_unless((int) $order->assign_to === 0 || $order->assign_to === null || $order->assign_to === '', 403);

        $validated = $request->validate([
            'quote_id' => ['required', 'integer'],
        ]);

        $quote = FreelanceQuote::query()
            ->where('order_id', $order->order_id)
            ->where('status', 'pending')
            ->findOrFail((int) $validated['quote_id']);

        $freelancer = AdminUser::query()->findOrFail($quote->team_user_id);
        $now = now();

        $quote->update([
            'status'      => 'accepted',
            'reviewed_by' => $supervisor->user_id,
            'reviewed_at' => $now,
        ]);

        FreelanceQuote::query()
            ->where('order_id', $order->order_id)
            ->where('status', 'pending')
            ->where('id', '!=', $quote->id)
            ->update([
                'status'      => 'rejected',
                'reviewed_by' => $supervisor->user_id,
                'reviewed_at' => $now,
            ]);

        $order->update([
            'assign_to'     => $freelancer->user_id,
            'working'       => $now->format('Y-m-d H:i:s'),
            'assigned_date' => $now->format('Y-m-d G:i'),
        ]);

        if ($freelancer->user_email) {
            $body = view('admin.emails.freelance-quote-accepted', [
                'freelancerName' => trim((string) ($freelancer->display_name ?: $freelancer->user_name)),
                'orderId'        => $order->order_id,
                'quotedPrice'    => number_format((float) $quote->quoted_price, 2),
                'detailUrl'      => url('/team/orders/'.$order->order_id.'/detail/order'),
            ])->render();

            PortalMailer::sendHtml($freelancer->user_email, 'Your quote has been accepted — Order #'.$order->order_id, $body);
        }

        return redirect()->back()->with('success', 'Quote accepted. Order assigned to '.($freelancer->display_name ?: $freelancer->user_name).'.');
    }

    public function pullBackJob(Request $request, Order $order)
    {
        $supervisor = $request->attributes->get('teamUser');

        abort_unless(
            (int) $order->assign_to > 0
            && in_array((string) $order->status, ['Underprocess', 'disapprove', 'disapproved'], true),
            403
        );

        $order->update([
            'assign_to' => 0,
            'working'   => '',
        ]);

        return redirect()->back()->with('success', 'Order #'.$order->order_id.' has been pulled back to the group pool.');
    }

    private function createFine(Order $order, AdminUser $supervisor, float $amount, string $reason): void
    {
        $assignedUserId = (int) $order->assign_to ?: null;
        if (! $assignedUserId) {
            return;
        }

        $maxAmount = 200.00;
        if ($order->assigned_group === 'freelance') {
            $accepted = FreelanceQuote::where('order_id', $order->order_id)->where('status', 'accepted')->first();
            $maxAmount = $accepted ? (float) $accepted->quoted_price : 0.00;
        }

        $amount = min($amount, $maxAmount);

        TeamFine::create([
            'order_id'    => $order->order_id,
            'team_user_id'=> $assignedUserId,
            'imposed_by'  => $supervisor->user_id,
            'amount'      => $amount,
            'reason'      => $reason,
        ]);
    }

    private function notifyAdminsApproved(Order $order, AdminUser $supervisor): void
    {
        $adminEmail = (string) config('mail.admin_alert_address', '');
        if ($adminEmail === '') {
            return;
        }

        $body = view('admin.emails.supervisor-approved-job', [
            'supervisorName' => trim((string) ($supervisor->display_name ?: $supervisor->user_name)),
            'orderId'        => $order->order_id,
            'designName'     => $order->design_name ?: 'Order #'.$order->order_id,
            'reviewUrl'      => url('/v/orders/'.$order->order_id.'/detail/order'),
        ])->render();

        PortalMailer::sendHtml($adminEmail, 'Supervisor approved — Order #'.$order->order_id.' ready for review', $body);
    }

    private function detailUrl(Order $order): string
    {
        if ((string) $order->order_type === 'qquote') {
            return url('/team/quick-quotes/'.$order->order_id.'/detail');
        }

        $act = in_array((string) $order->status, ['disapprove', 'disapproved'], true)
            ? 'disapproved'
            : (in_array((string) $order->order_type, ['quote', 'digitzing', 'q-vector', 'qcolor'], true) ? 'quote' : 'order');

        return TeamWorkQueues::detailUrl($order, $act);
    }
}
