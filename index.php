<?php
/**
 * Main Entry Point & Router
 */

// 1. Installation Check
$lockFile = __DIR__ . '/data-kelulusan/install.lock';
$isInstalled = file_exists($lockFile);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$scriptDir = rtrim($scriptDir, '/');
$relativeUri = substr($uri, strlen($scriptDir));

// Normalize relativeUri
if (empty($relativeUri) || $relativeUri[0] !== '/') {
    $relativeUri = '/' . ltrim($relativeUri, '/');
}

$path = trim($relativeUri, '/');

// Handle Installer
if ($path === 'install') {
    if ($isInstalled) {
        header("Location: " . ($scriptDir ?: '/'));
        exit;
    }
    require_once 'install.php';
    exit;
}

// Force Install if not locked
if (!$isInstalled) {
    header("Location: " . ($scriptDir ?: '/') . "/install");
    exit;
}

// 2. Load Configuration
require_once __DIR__ . '/data-kelulusan/config.php';

// 3. Static File Handling (for PHP Built-in Server)
if (php_sapi_name() === 'cli-server') {
    if (file_exists(__DIR__ . $relativeUri) && !is_dir(__DIR__ . $relativeUri)) {
        return false;
    }
}

// 4. Admin Routing
$adminRoutes = [
    '/login' => 'login',
    '/dashboard' => 'dashboard',
    '/import' => 'import',
    '/settings' => 'settings',
    '/logout' => 'logout',
    '/download-template' => 'download-template'
];

if (isset($adminRoutes[$relativeUri])) {
    if ($adminRoutes[$relativeUri] === 'download-template') {
        require __DIR__ . '/data-kelulusan/download_template.php';
        exit;
    }
    $_GET['page'] = $adminRoutes[$relativeUri];
    require __DIR__ . '/data-kelulusan/index.php';
    exit;
}

// 5. Legacy Path Handling
if (strpos($relativeUri, '/data-kelulusan/') === 0) {
    $subPath = substr($relativeUri, strlen('/data-kelulusan/'));
    if ($subPath === 'login.php') { header("Location: " . baseUrl('login')); exit; }
    if ($subPath === 'index.php') { header("Location: " . baseUrl('dashboard')); exit; }
}

// 6. Default: Serve Frontend
require_once 'home.php';
