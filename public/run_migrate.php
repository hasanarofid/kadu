<?php

use Illuminate\Contracts\Console\Kernel;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

try {
    $status = $kernel->call('migrate:fresh', [
        '--seed' => true,
        '--force' => true,
    ]);
    
    echo "<h1 style='color:green;'>SUCCESS: Migration & Seeder Finished!</h1>";
    echo "<pre>" . htmlspecialchars($kernel->output()) . "</pre>";
    echo "<p><a href='/login'>Go to Login Page</a></p>";
} catch (\Throwable $e) {
    echo "<h1 style='color:red;'>ERROR: Migration Failed</h1>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "\n\n" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
