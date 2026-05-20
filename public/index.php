<?php
// --- ZORUNLU CACHE TEMİZLEYİCİ BAŞLANGICI ---
$cacheKlasoru = __DIR__ . '/../bootstrap/cache/';
$dosyalar = glob($cacheKlasoru . '*.php');
if ($dosyalar) {
    foreach ($dosyalar as $dosya) {
        @unlink($dosya);
    }
}
// --- ZORUNLU CACHE TEMİZLEYİCİ BİTİŞİ ---


use Illuminate\Foundation\Application;
use Illuminate\Http\Request;



define('LARAVEL_START', microtime(true));

$basePath = null;
$envFile = __DIR__ . '/../.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES) ?: [] as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (strpos($line, 'APP_BASE_PATH=') === 0) {
            $basePath = trim(substr($line, strlen('APP_BASE_PATH=')));
            $basePath = trim($basePath, "\"'");
            break;
        }
    }
}
if ($basePath) {
    $basePath = '/' . trim($basePath, '/');
    $_SERVER['SCRIPT_NAME'] = $basePath . '/index.php';
    $_SERVER['PHP_SELF']    = $basePath . '/index.php';
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
