<?php
/**
 * Database Configuration
 */
require_once __DIR__ . '/../vendor/autoload.php';

// Load .env
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

$host = $_ENV['DB_HOST'] ?? 'localhost';
$dbname = $_ENV['DB_NAME'] ?? 'kelulusan_sman1sooko';
$username = $_ENV['DB_USER'] ?? 'root';
$password = $_ENV['DB_PASS'] ?? '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

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
