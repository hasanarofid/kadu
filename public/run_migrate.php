<?php

use Illuminate\Support\Facades\Artisan;

// Load Composer Autoloader
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel Application
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Runner - KADU</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background: #020617; color: #f8fafc; padding: 40px 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; background: #0f172a; border: 1px solid #1e293b; padding: 30px; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); }
        h1 { color: #818cf8; font-size: 24px; margin-top: 0; }
        h3 { color: #cbd5e1; font-size: 16px; margin-top: 25px; }
        pre { background: #020617; color: #38bdf8; padding: 16px; border-radius: 12px; border: 1px solid #1e293b; overflow-x: auto; font-size: 13px; font-family: monospace; }
        .success { color: #34d399; background: #064e3b; padding: 15px; border-radius: 12px; border: 1px solid #059669; font-weight: bold; margin-top: 25px; }
        .error { color: #fca5a5; background: #450a0a; padding: 15px; border-radius: 12px; border: 1px solid #dc2626; font-weight: bold; margin-top: 25px; }
        a { color: #818cf8; font-weight: bold; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 KADU Database Migration & Seeder Runner</h1>

        <?php
        try {
            echo "<h3>1. Running Database Migration...</h3>";
            Artisan::call('migrate', ['--force' => true]);
            echo "<pre>" . (Artisan::output() ?: "Migration completed successfully.") . "</pre>";

            echo "<h3>2. Running Database Seeder...</h3>";
            Artisan::call('db:seed', ['--force' => true]);
            echo "<pre style='color:#34d399;'>" . (Artisan::output() ?: "Seeder completed successfully.") . "</pre>";

            echo "<h3>3. Clearing & Optimizing Caches...</h3>";
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('route:clear');
            echo "<pre style='color:#a78bfa;'>Config, Cache, and Routes cleared successfully!</pre>";

            echo "<div class='success'>✅ BERHASIL! Migration & Database Seeder Selesai 100%.</div>";
            echo "<p style='margin-top:20px;'><a href='/'>← Kembali ke Landing Page KADU</a> | <a href='/login'>Ke Halaman Login →</a></p>";

        } catch (\Throwable $e) {
            echo "<div class='error'>❌ Error Occurred: " . htmlspecialchars($e->getMessage()) . "</div>";
            echo "<pre style='color:#fca5a5;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        }
        ?>
    </div>
</body>
</html>
