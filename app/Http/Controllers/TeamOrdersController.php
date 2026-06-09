<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Order;
use App\Support\TeamAccess;
use App\Support\TeamNavigation;
use App\Support\TeamWorkQueues;
use App\Support\TurnaroundTracking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TeamOrdersController extends Controller
{
    public function queue(Request $request, string $queue)
    {
        return match (TeamWorkQueues::normalize($queue)) {
            'new-orders' => $this->newOrders($request, 'new-orders'),
            'working-orders' => $this->workingOrders($request, 'working-orders'),
            'disapproved-orders' => $this->disapprovedOrders($request, 'disapproved-orders'),
            'quotes' => $this->quotes($request, 'quotes'),
            'quick-quotes' => $this->quickQuotes($request, 'quick-quotes'),
            default => $this->newOrders($request, 'new-orders'),
        };
    }

    public function compatibilityQueueRedirect(Request $request, string $queue)
    {
        $query = $request->getQueryString();
        $url = TeamWorkQueues::url($queue);

        return redirect()->to($query ? $url.'?'.$query : $url);
    }

    public function underProcess(Request $request)
    {
        $queue = $request->query('process') === 'working' ? 'working-orders' : 'new-orders';

        return redirect()->to(TeamWorkQueues::url($queue));
    }

    public function newOrders(Request $request, string $queue = 'new-orders')
    {
        return $this->renderOrders($request, $queue, false);
    }

    public function workingOrders(Request $request, string $queue = 'working-orders')
    {
        return $this->renderOrders($request, $queue, true);
    }

    public function disapprovedOrders(Request $request, string $queue = 'disapproved-orders')
    {
        $teamUser = $request->attributes->get('teamUser');
        $navCounts = TeamNavigation::counts($teamUser->user_id, (int) $teamUser->usre_type_id);

        $ordersQuery = Order::query()
            ->with('assignee:user_id,user_name')
            ->active()
            ->tap(fn ($q) => TeamAccess::applyVisibilityScope($q, $teamUser))
            ->whereIn('order_type', ['order', 'vector', 'color'])
            ->whereIn('status', ['disapprove', 'disapproved'])
            ->orderBy('completion_date')
            ->orderByDesc('order_id');

        if ($request->query('export') === 'csv') {
            return $this->queueCsvResponse(
                'team-'.$queue,
                $this->decorateQueueRows((clone $ordersQuery)->get()),
                (bool) ($teamUser->is_supervisor ?? false)
            );
        }

        $orders = $ordersQuery->paginate(25)->withQueryString();
        $this->decorateQueueRows($orders->getCollection());

        return view('team.orders.index', [
            'teamUser' => $teamUser,
            'navCounts' => $navCounts,
            'orders' => $orders,
            'pageTitle' => TeamWorkQueues::label($queue),
            'pageSummary' => TeamWorkQueues::summary($queue),
            'queueNavigation' => TeamWorkQueues::navigation($navCounts),
            'currentQueueKey' => TeamWorkQueues::normalize($queue),
            'detailUrl' => fn (Order $order) => TeamWorkQueues::detailUrl($order, 'disapproved', $queue),
            'showWorking' => false,
            'allowStartWork' => false,
        ]);
    }

    public function quotes(Request $request, string $queue = 'quotes')
    {
        $teamUser = $request->attributes->get('teamUser');
        abort_if($teamUser->isFreelance(), 403);

        $navCounts = TeamNavigation::counts($teamUser->user_id, (int) $teamUser->usre_type_id);

        $ordersQuery = Order::query()
            ->with('assignee:user_id,user_name')
            ->active()
            ->whereIn('order_type', ['quote', 'digitzing', 'q-vector', 'qcolor'])
            ->where('status', 'Underprocess')
            ->tap(fn ($q) => TeamAccess::applyVisibilityScope($q, $teamUser))
            ->orderBy('completion_date')
            ->orderByDesc('order_id');

        if ($request->query('export') === 'csv') {
            return $this->queueCsvResponse(
                'team-'.$queue,
                $this->decorateQueueRows((clone $ordersQuery)->get()),
                (bool) ($teamUser->is_supervisor ?? false)
            );
        }

        $orders = $ordersQuery->paginate(25)->withQueryString();
        $this->decorateQueueRows($orders->getCollection());

        return view('team.orders.index', [
            'teamUser' => $teamUser,
            'navCounts' => $navCounts,
            'orders' => $orders,
            'pageTitle' => TeamWorkQueues::label($queue),
            'pageSummary' => TeamWorkQueues::summary($queue),
            'queueNavigation' => TeamWorkQueues::navigation($navCounts),
            'currentQueueKey' => TeamWorkQueues::normalize($queue),
            'detailUrl' => fn (Order $order) => TeamWorkQueues::detailUrl($order, 'quote', $queue),
            'showWorking' => false,
            'allowStartWork' => false,
        ]);
    }

    public function quickQuotes(Request $request, string $queue = 'quick-quotes')
    {
        $teamUser = $request->attributes->get('teamUser');
        $navCounts = TeamNavigation::counts($teamUser->user_id, (int) $teamUser->usre_type_id);

        $ordersQuery = Order::query()
            ->with('assignee:user_id,user_name')
            ->active()
            ->where('order_type', 'qquote')
            ->where('status', 'Underprocess')
            ->tap(fn ($q) => TeamAccess::applyVisibilityScope($q, $teamUser))
            ->orderBy('completion_date')
            ->orderByDesc('order_id');

        if ($request->query('export') === 'csv') {
            return $this->queueCsvResponse(
                'team-'.$queue,
                $this->decorateQueueRows((clone $ordersQuery)->get()),
                (bool) ($teamUser->is_supervisor ?? false)
            );
        }

        $orders = $ordersQuery->paginate(25)->withQueryString();
        $this->decorateQueueRows($orders->getCollection());

        return view('team.orders.index', [
            'teamUser' => $teamUser,
            'navCounts' => $navCounts,
            'orders' => $orders,
            'pageTitle' => TeamWorkQueues::label($queue),
            'pageSummary' => TeamWorkQueues::summary($queue),
            'queueNavigation' => TeamWorkQueues::navigation($navCounts),
            'currentQueueKey' => TeamWorkQueues::normalize($queue),
            'detailUrl' => fn (Order $order) => url('/team/quick-quotes/'.$order->order_id.'/detail'),
            'showWorking' => false,
            'allowStartWork' => false,
        ]);
    }

    public function saveWorking(Request $request, Order $order)
    {
        $teamUser = $request->attributes->get('teamUser');

        abort_if($teamUser->isFreelance(), 403);
        abort_unless(TeamAccess::canAccessOrder($teamUser, $order), 404);
        abort_unless(in_array((string) $order->status, ['Underprocess', 'disapprove', 'disapproved'], true), 404);

        $validated = $request->validate([
            'queue' => ['nullable', 'string'],
        ], [], [], [
            'queue' => 'queue',
        ]);

        $acceptedAt = now()->format('Y-m-d H:i:s');
        $userId = (int) $teamUser->user_id;

        DB::transaction(function () use ($order, $acceptedAt, $teamUser, $userId) {
            $fresh = Order::query()->lockForUpdate()->find($order->order_id);

            if (! $fresh || ! TeamAccess::canAccessOrder($teamUser, $fresh)) {
                return;
            }

            $updates = ['working' => $acceptedAt];

            // Group-pool job: claim it by setting assign_to
            if ((int) $fresh->assign_to === 0 || $fresh->assign_to === null || $fresh->assign_to === '') {
                $updates['assign_to'] = $userId;
            }

            $fresh->update($updates);
        });

        return redirect()->to(TeamWorkQueues::url((string) ($validated['queue'] ?? 'new-orders')))
            ->with('success', 'Job accepted successfully.');
    }

    private function renderOrders(Request $request, string $queue, bool $workingOnly)
    {
        $teamUser = $request->attributes->get('teamUser');
        $navCounts = TeamNavigation::counts($teamUser->user_id, (int) $teamUser->usre_type_id);

        $ordersQuery = Order::query()
            ->with('assignee:user_id,user_name')
            ->active()
            ->whereIn('order_type', ['order', 'vector', 'color'])
            ->where('status', 'Underprocess')
            ->tap(fn ($q) => TeamAccess::applyVisibilityScope($q, $teamUser))
            ->when($workingOnly, function (Builder $query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereNotNull('working')->where('working', '!=', '');
                });
            }, function (Builder $query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereNull('working')->orWhere('working', '');
                });
            })
            ->orderBy('completion_date')
            ->orderByDesc('order_id');

        if ($request->query('export') === 'csv') {
            return $this->queueCsvResponse(
                'team-'.$queue,
                $this->decorateQueueRows((clone $ordersQuery)->get()),
                (bool) ($teamUser->is_supervisor ?? false)
            );
        }

        $orders = $ordersQuery->paginate(25)->withQueryString();
        $this->decorateQueueRows($orders->getCollection());

        return view('team.orders.index', [
            'teamUser' => $teamUser,
            'navCounts' => $navCounts,
            'orders' => $orders,
            'pageTitle' => TeamWorkQueues::label($queue),
            'pageSummary' => TeamWorkQueues::summary($queue),
            'queueNavigation' => TeamWorkQueues::navigation($navCounts),
            'currentQueueKey' => TeamWorkQueues::normalize($queue),
            'detailUrl' => fn (Order $order) => TeamWorkQueues::detailUrl($order, null, $queue),
            'showWorking' => true,
            'allowStartWork' => ! $workingOnly,
        ]);
    }

    private function normalizeWorkingTime(string $value): string
    {
        // Accept datetime-local format: YYYY-MM-DDTHH:MM
        $value = trim($value);
        if (preg_match('/^(\d{4}-\d{2}-\d{2})T(\d{2}:\d{2})$/', $value, $m)) {
            return $m[1].' '.$m[2];
        }

        return $value;
    }

    private function decorateQueueRows(\Illuminate\Support\Collection $orders): \Illuminate\Support\Collection
    {
        return $orders->transform(function (Order $order) {
            $order->team_upload_count = Attachment::query()
                ->where('order_id', $order->order_id)
                ->where('file_source', 'team')
                ->count();
            $turnaround = TurnaroundTracking::summary($order);
            $order->turnaround_label = $turnaround['label'];
            $order->turnaround_status_label = $turnaround['status_label'];
            $order->turnaround_status_tone = $turnaround['status_tone'];
            $order->hours_left = $turnaround['remaining_label'];

            return $order;
        });
    }

    private function queueCsvResponse(string $prefix, \Illuminate\Support\Collection $orders, bool $includeAssignee): StreamedResponse
    {
        $headers = ['Order ID'];

        if ($includeAssignee) {
            $headers[] = 'Assigned To';
        }

        array_push($headers, 'Turnaround', 'Schedule', 'Time Left', 'Work Type');

        $rows = $orders->map(function (Order $order) use ($includeAssignee) {
            $row = [(string) ($order->order_num ?: ($order->user_id.'-'.$order->order_id))];

            if ($includeAssignee) {
                if ($order->assign_to && (int) $order->assign_to !== 0) {
                    $row[] = $order->assignee_name;
                } elseif ($order->assigned_group === 'freelance') {
                    $row[] = 'Freelance (Pool)';
                } elseif ($order->assigned_group === 'inhouse') {
                    $row[] = 'In-House (Pool)';
                } else {
                    $row[] = '-';
                }
            }

            array_push(
                $row,
                $order->turnaround_label ?: ($order->turn_around_time ?: '-'),
                $order->turnaround_status_label ?: 'Schedule Unknown',
                $order->hours_left,
                $order->work_type_label
            );

            return $row;
        })->all();

        $filename = sprintf('%s-%s.csv', $prefix, now()->format('Ymd-His'));

        return response()->streamDownload(function () use ($headers, $rows) {
            $output = fopen('php://output', 'w');
            fputcsv($output, $headers);

            foreach ($rows as $row) {
                fputcsv($output, $row);
            }

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
