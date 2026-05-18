<?php

namespace App\Support;

use App\Models\AdminUser;
use App\Models\Order;
use App\Models\OrderComment;

class TeamNavigation
{
    public static function counts(int $teamUserId, ?int $teamUserTypeId = null): array
    {
        $teamUser = AdminUser::query()->find($teamUserId);

        if (! $teamUser) {
            $teamUser = new AdminUser([
                'user_id'      => $teamUserId,
                'usre_type_id' => $teamUserTypeId ?? AdminUser::TYPE_TEAM,
            ]);
        }

        $isSupervisor = (int) $teamUser->usre_type_id === AdminUser::TYPE_SUPERVISOR;

        $memberIds = $isSupervisor
            ? TeamAccess::teamMembers($teamUser)->pluck('user_id')->map(fn ($id) => (int) $id)->all()
            : [];

        $base = fn () => Order::query()->active()->tap(
            fn ($q) => TeamAccess::applyVisibilityScope($q, $teamUser)
        );

        return [
            'new_orders' => $base()
                ->whereIn('order_type', ['order', 'vector', 'color'])
                ->where('status', 'Underprocess')
                ->where(function ($q) {
                    $q->whereNull('working')->orWhere('working', '');
                })
                ->count(),
            'working_orders' => $base()
                ->whereIn('order_type', ['order', 'vector', 'color'])
                ->where('status', 'Underprocess')
                ->where(function ($q) {
                    $q->whereNotNull('working')->where('working', '!=', '');
                })
                ->count(),
            'disapproved_orders' => $base()
                ->whereIn('order_type', ['order', 'vector', 'color'])
                ->whereIn('status', ['disapprove', 'disapproved'])
                ->count(),
            'quotes' => $base()
                ->whereIn('order_type', ['quote', 'digitzing', 'q-vector', 'qcolor'])
                ->where('status', 'Underprocess')
                ->count(),
            'quick_quotes' => $base()
                ->where('order_type', 'qquote')
                ->where('status', 'Underprocess')
                ->count(),
            'ready_review' => $isSupervisor
                ? Order::query()
                    ->active()
                    ->where('status', 'Ready')
                    ->whereIn('assign_to', $memberIds)
                    ->count()
                : 0,
            'verified_jobs' => $isSupervisor
                ? OrderComment::query()
                    ->where('comment_source', 'supervisorReview')
                    ->whereIn('order_id', Order::query()
                        ->active()
                        ->whereIn('assign_to', $memberIds)
                        ->pluck('order_id'))
                    ->distinct('order_id')
                    ->count('order_id')
                : 0,
            'team_members' => $isSupervisor ? count($memberIds) : 0,
            'assigned_jobs' => $isSupervisor
                ? Order::query()
                    ->active()
                    ->where('status', 'Underprocess')
                    ->whereIn('assign_to', array_filter($memberIds))
                    ->where(function ($q) {
                        $q->whereNotNull('assign_to')->where('assign_to', '!=', 0)->where('assign_to', '!=', '');
                    })
                    ->count()
                : 0,
        ];
    }
}
