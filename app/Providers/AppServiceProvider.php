<?php

namespace App\Providers;

use App\Support\SharedUploads;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $forcedUrl = trim((string) env('APP_FORCE_URL', ''));
        if ($forcedUrl !== '') {
            URL::forceRootUrl(rtrim($forcedUrl, '/'));
        }

        if (filter_var(env('APP_FORCE_HTTPS', false), FILTER_VALIDATE_BOOL)) {
            URL::forceScheme('https');
        }

        SharedUploads::ensureReady();
        Paginator::defaultView('pagination.admin');
        Paginator::defaultSimpleView('pagination.admin');

        // Hard-block any write operations on the legacy DB connection.
        // The legacy connection is READ-ONLY: v2 only pulls data from it during
        // account upgrades. Any INSERT/UPDATE/DELETE/DROP reaching this connection
        // is a bug and must be caught immediately.
        if (config('database.connections.legacy')) {
            DB::connection('legacy')->beforeExecuting(function (string &$sql): void {
                $verb = strtoupper(strtok(ltrim($sql), " \t\n\r\0"));
                $allowed = ['SELECT', 'SHOW', 'DESCRIBE', 'EXPLAIN', 'PRAGMA'];
                if (! in_array($verb, $allowed, true)) {
                    throw new \RuntimeException(
                        "Attempted write on the read-only legacy connection (verb: {$verb}). "
                        . "V2 must never modify the legacy database."
                    );
                }
            });
        }
    }
}
