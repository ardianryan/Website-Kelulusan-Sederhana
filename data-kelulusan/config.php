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

function baseUrl($path = '') {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $scriptDir = rtrim($scriptDir, '/');
    if (strpos($scriptDir, '/data-kelulusan') !== false) {
        $scriptDir = str_replace('/data-kelulusan', '', $scriptDir);
    }
    $fullPath = $scriptDir . '/' . ltrim($path, '/');
    return rtrim($fullPath, '/') ?: '/';
}

function getSetting($key, $default = '') {
    global $pdo;
    static $cachedSettings = null;
    if ($cachedSettings === null) {
        try {
            $stmt = $pdo->query("SELECT `key`, `value` FROM settings");
            $cachedSettings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        } catch (Exception $e) {
            return $default;
        }
    }
    return $cachedSettings[$key] ?? $default;
}

function checkAuth() {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: " . baseUrl('login'));
        exit;
    }
}
