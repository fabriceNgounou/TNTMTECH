<?php

use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

$databasePath = $_SERVER['DB_DATABASE'] ?? $_ENV['DB_DATABASE'] ?? null;
if ($databasePath && str_starts_with($databasePath, '/tmp') && ! file_exists($databasePath)) {
    @touch($databasePath);
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

if ($databasePath && str_starts_with($databasePath, '/tmp')) {
    $consoleKernel = $app->make(ConsoleKernelContract::class);
    $migrationMarker = '/tmp/.vercel_sqlite_migrated';

    if (! file_exists($migrationMarker)) {
        $consoleKernel->call('migrate', ['--force' => true]);
        @touch($migrationMarker);
    }
}

$kernel = $app->make(Kernel::class);
$request = Request::capture();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
