<?php
/**
 * Deploy hook — CI sonrası otomatik tetiklenir.
 *
 * Görevler:
 *   1) vendor.zip dosyası varsa: extract et → vendor.zip'i sil
 *   2) Laravel artisan komutlarını çalıştır: migrate, config:cache, vb.
 *
 * Mimari:
 *   - ZipArchive ile extract (shell_exec'siz)
 *   - Laravel Kernel::call() ile artisan (shell_exec'siz)
 *
 * Güvenlik:
 *   - Hook key .env'deki DEPLOY_HOOK_KEY'den okunur
 *   - URL: https://.../deploy-hook.php?key=<DEPLOY_HOOK_KEY>
 *   - hash_equals() ile timing-safe karşılaştırma
 */

declare(strict_types=1);

// ────────── 1) Güvenlik kontrolü ──────────
$envPath = __DIR__ . '/.env';
if (!file_exists($envPath)) {
    http_response_code(500);
    die('.env not found');
}

$env = parse_ini_file($envPath, false, INI_SCANNER_RAW);
$hookKey = $env['DEPLOY_HOOK_KEY'] ?? '';

// Bazı .env'lerde değer tırnak içinde olabilir
$hookKey = trim((string)$hookKey, "\"'");

if (empty($hookKey)) {
    http_response_code(500);
    die('DEPLOY_HOOK_KEY not configured in .env');
}

$provided = $_GET['key'] ?? '';
if (!hash_equals($hookKey, (string)$provided)) {
    http_response_code(403);
    die('Forbidden');
}

// ────────── 2) Log header ──────────
header('Content-Type: text/plain; charset=utf-8');
@set_time_limit(300);  // 5 dakika
@ini_set('memory_limit', '512M');

echo "═══════════════════════════════════════════════════════\n";
echo "  Deploy hook starting — " . date('Y-m-d H:i:s') . "\n";
echo "═══════════════════════════════════════════════════════\n\n";

chdir(__DIR__);

// ────────── 3) vendor.zip extract (varsa) ──────────
$zipPath = __DIR__ . '/vendor.zip';

if (file_exists($zipPath)) {
    echo "→ vendor.zip found (" . formatBytes(filesize($zipPath)) . ")\n";

    if (!class_exists('ZipArchive')) {
        echo "::: ERROR: ZipArchive PHP extension not available\n";
        http_response_code(500);
        exit;
    }

    // Eski vendor/ sil (composer.lock değiştiyse stale dosyalar kalmasın)
    if (is_dir(__DIR__ . '/vendor')) {
        echo "  - Removing old vendor/...\n";
        $t0 = microtime(true);
        rrmdir(__DIR__ . '/vendor');
        echo "  ✓ removed in " . round(microtime(true) - $t0, 2) . "s\n";
    }

    // Extract
    echo "  - Extracting vendor.zip...\n";
    $t0 = microtime(true);

    $zip = new ZipArchive();
    $opened = $zip->open($zipPath);
    if ($opened !== true) {
        echo "::: ERROR: failed to open vendor.zip (code $opened)\n";
        http_response_code(500);
        exit;
    }

    if (!$zip->extractTo(__DIR__)) {
        echo "::: ERROR: extractTo() failed\n";
        $zip->close();
        http_response_code(500);
        exit;
    }

    $zip->close();
    unlink($zipPath);

    echo "  ✓ extracted in " . round(microtime(true) - $t0, 2) . "s\n\n";
} else {
    echo "→ no vendor.zip — skipping extract\n\n";
}

// ────────── 4) Vendor doğrulama ──────────
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "::: ERROR: vendor/autoload.php not found\n";
    http_response_code(500);
    exit;
}

// ────────── 5) Laravel'i bootstrap'le ──────────
echo "→ Bootstrapping Laravel...\n";

try {
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    /** @var \Illuminate\Contracts\Console\Kernel $kernel */
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    echo "  ✓ Laravel " . $app->version() . "\n\n";
} catch (\Throwable $e) {
    echo "::: ERROR: bootstrap failed — " . $e->getMessage() . "\n";
    http_response_code(500);
    exit;
}

// ────────── 6) Artisan komutları ──────────
$commands = [
    'migrate'       => ['--force' => true],
    'storage:link'  => ['--force' => true],
    'config:clear'  => [],
    'route:clear'   => [],
    'view:clear'    => [],
    'event:clear'   => [],
    'config:cache'  => [],
    'route:cache'   => [],
    'view:cache'    => [],
    'event:cache'   => [],
];

$allOk = true;
foreach ($commands as $cmd => $opts) {
    echo "→ artisan $cmd\n";
    try {
        $exitCode = $kernel->call($cmd, $opts);
        $output   = trim((string)$kernel->output());
        if ($output !== '') {
            echo "    " . str_replace("\n", "\n    ", $output) . "\n";
        }
        if ($exitCode !== 0) {
            echo "  ⚠ exit $exitCode\n";
        }
    } catch (\Throwable $e) {
        echo "  ✗ " . $e->getMessage() . "\n";
        $allOk = false;
    }
    echo "\n";
}

echo "═══════════════════════════════════════════════════════\n";
echo "  Deploy hook completed — " . date('Y-m-d H:i:s') . "\n";
echo "═══════════════════════════════════════════════════════\n";

if (!$allOk) {
    http_response_code(500);
}

// ────────── Helpers ──────────
function rrmdir(string $dir): void
{
    if (!is_dir($dir)) {
        return;
    }
    $items = array_diff(scandir($dir) ?: [], ['.', '..']);
    foreach ($items as $item) {
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        if (is_dir($path) && !is_link($path)) {
            rrmdir($path);
        } else {
            @unlink($path);
        }
    }
    @rmdir($dir);
}

function formatBytes(int $bytes): string
{
    if ($bytes < 1024) return "$bytes B";
    if ($bytes < 1024 * 1024) return round($bytes / 1024, 1) . " KB";
    if ($bytes < 1024 * 1024 * 1024) return round($bytes / (1024 * 1024), 1) . " MB";
    return round($bytes / (1024 * 1024 * 1024), 2) . " GB";
}
