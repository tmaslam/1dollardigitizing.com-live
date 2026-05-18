<?php

namespace App\Support;

use App\Models\AdminUser;

/**
 * Calculates the appropriate pending-orders limit for a customer based on
 * their current credit balance and active subscription plan.
 *
 * Tiers (balance-based, no subscription):
 *   $0 – $100   → 3
 *   $101 – $300  → 5
 *   $301 – $600  → 8
 *   $601 – $1000 → 12
 *   $1001+       → 15
 *
 * Subscription bonus (added on top of balance tier):
 *   growth     → +2
 *   studio     → +5
 *   production → +8
 *   corporate  → +12
 *   enterprise → +15
 */
class CustomerPendingLimit
{
    public static function calculate(AdminUser $customer): int
    {
        $balance = max(0.0, (float) ($customer->topup ?? 0));
        $plan    = strtolower(trim((string) ($customer->subscription_plan ?? '')));

        $base = match (true) {
            $balance <= 100   => 3,
            $balance <= 300   => 5,
            $balance <= 600   => 8,
            $balance <= 1000  => 12,
            default           => 15,
        };

        $bonus = match ($plan) {
            'growth'     => 2,
            'studio'     => 5,
            'production' => 8,
            'corporate'  => 12,
            'enterprise' => 15,
            default      => 0,
        };

        return $base + $bonus;
    }
}
