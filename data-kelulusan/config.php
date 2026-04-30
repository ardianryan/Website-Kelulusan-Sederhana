<?php
/**
 * Database Configuration
 */
require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Load .env
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

$host = $_ENV['DB_HOST'] ?? 'localhost';
$dbname = $_ENV['DB_NAME'] ?? '';
$username = $_ENV['DB_USER'] ?? 'root';
$password = $_ENV['DB_PASS'] ?? '';

// Initialize Eloquent
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => $host,
    'database' => $dbname,
    'username' => $username,
    'password' => $password,
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// Maintain $pdo for backward compatibility
try {
    $pdo = Capsule::connection()->getPdo();
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}

require_once __DIR__ . '/models.php';

if (session_status() === PHP_SESSION_NONE) {
    session_name('sman1sooko_admin');
    session_set_cookie_params([
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

/**
 * Dynamic Base URL Helper
 * Simplified to prevent double paths and loops
 */
function baseUrl($path = '') {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $scriptDir = rtrim($scriptDir, '/');
    
    // Normalize if running from data-kelulusan directly
    if (strpos($scriptDir, '/data-kelulusan') !== false) {
        $scriptDir = str_replace('/data-kelulusan', '', $scriptDir);
    }
    
    $fullPath = $scriptDir . '/' . ltrim($path, '/');
    return rtrim($fullPath, '/') ?: '/';
}

/**
 * Helper to get settings
 */
function getSetting($key, $default = '') {
    global $pdo;
    $stmt = $pdo->prepare("SELECT `value` FROM settings WHERE `key` = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetch();
    return $result ? $result['value'] : $default;
}

/**
 * Check if logged in
 */
function checkAuth() {
    if (!isset($_SESSION['admin_id'])) {
        session_write_close();
        header("Location: " . baseUrl('login'));
        exit;
    }
}
?>
