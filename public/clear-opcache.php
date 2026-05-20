<?php
/**
 * One-time helper: clears PHP OPcache and Laravel compiled views.
 * Delete this file after use.
 */

$cleared = false;

if (function_exists('opcache_reset')) {
    opcache_reset();
    $cleared = true;
    echo "OPcache cleared.\n";
}

$viewPath = __DIR__ . '/../storage/framework/views';
if (is_dir($viewPath)) {
    $files = glob($viewPath . '/*');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $count++;
        }
    }
    echo "Compiled views cleared: {$count} file(s).\n";
} else {
    echo "Views path not found.\n";
}

if (! $cleared) {
    echo "WARNING: opcache_reset() is not available. You may need to restart PHP-FPM.\n";
}

echo "Done. Refresh your archive page now.\n";
