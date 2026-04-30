<?php
/**
 * Installer for Kelulusan SMAN 1 Sooko
 */

$message = '';
$error = false;
$lockFile = __DIR__ . '/data-kelulusan/install.lock';

if (file_exists($lockFile)) {
    header("Location: /login");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db_host = $_POST['db_host'] ?? 'localhost';
    $db_name = $_POST['db_name'] ?? '';
    $db_user = $_POST['db_user'] ?? '';
    $db_pass = $_POST['db_pass'] ?? '';

    try {
        // 1. Try to connect to MySQL
        $pdo = new PDO("mysql:host=$db_host;charset=utf8mb4", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 2. Create Database if not exists
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$db_name`");

        // 3. Create Tables and Default Data directly (Bulletproof method)
        $queries = [
            "CREATE TABLE IF NOT EXISTS admins (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
            "CREATE TABLE IF NOT EXISTS students (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nisn VARCHAR(255) NOT NULL UNIQUE,
                nipd VARCHAR(50),
                nama VARCHAR(100) NOT NULL,
                jk VARCHAR(10) NOT NULL,
                tempat_lahir VARCHAR(100),
                tanggal_lahir VARCHAR(50),
                rombel VARCHAR(50) NOT NULL,
                password VARCHAR(255) NOT NULL,
                lulus BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
            "CREATE TABLE IF NOT EXISTS settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                `key` VARCHAR(50) NOT NULL UNIQUE,
                `value` TEXT,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )",
            "INSERT IGNORE INTO settings (`key`, `value`) VALUES ('school_name', 'SMA Negeri 1 Sooko')",
            "INSERT IGNORE INTO settings (`key`, `value`) VALUES ('welcome_text', 'Selamat Datang di Portal Pengumuman Kelulusan')",
            "INSERT IGNORE INTO settings (`key`, `value`) VALUES ('meta_description', 'Portal Resmi Pengumuman Kelulusan Siswa SMA Negeri 1 Sooko Tahun Pelajaran " . (date('Y')-1) . "/" . date('Y') . ".')",
            "INSERT IGNORE INTO settings (`key`, `value`) VALUES ('skl_info', 'Pengambilan SKL dapat dilakukan pada 5 Mei 2026')",
            "INSERT IGNORE INTO settings (`key`, `value`) VALUES ('countdown_date', '2026-05-05 07:00:00')",
            "INSERT IGNORE INTO admins (username, password) VALUES ('admin', '\$2y\$10\$BiE3omqwQy2KaDF/7ZkkNuc16g55cK4krhB84M8in3iLnAx.G/rce')"
        ];

        foreach ($queries as $query) {
            try {
                $pdo->exec($query);
            } catch (PDOException $e) {
                throw new Exception("Gagal menjalankan query: " . substr($query, 0, 50) . "... | Error: " . $e->getMessage());
            }
        }

        // 4. Generate .env
        $envContent = "DB_HOST=$db_host\nDB_NAME=$db_name\nDB_USER=$db_user\nDB_PASS=$db_pass\n";
        file_put_contents(__DIR__ . '/.env', $envContent);

        // 4b. Generate config.php
        $configContent = "<?php
/**
 * Database Configuration
 */
require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Load .env
if (file_exists(__DIR__ . '/../.env')) {
    \$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    \$dotenv->load();
}

\$host = \$_ENV['DB_HOST'] ?? 'localhost';
\$dbname = \$_ENV['DB_NAME'] ?? '';
\$username = \$_ENV['DB_USER'] ?? 'root';
\$password = \$_ENV['DB_PASS'] ?? '';

// Initialize Eloquent
\$capsule = new Capsule;
\$capsule->addConnection([
    'driver' => 'mysql',
    'host' => \$host,
    'database' => \$dbname,
    'username' => \$username,
    'password' => \$password,
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);

\$capsule->setAsGlobal();
\$capsule->bootEloquent();

try {
    \$pdo = Capsule::connection()->getPdo();
} catch (Exception \$e) {
    die(\"Connection failed: \" . \$e->getMessage());
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

function baseUrl(\$path = '') {
    \$scriptDir = str_replace('\\\\', '/', dirname(\$_SERVER['SCRIPT_NAME']));
    \$scriptDir = rtrim(\$scriptDir, '/');
    if (strpos(\$scriptDir, '/data-kelulusan') !== false) {
        \$scriptDir = str_replace('/data-kelulusan', '', \$scriptDir);
    }
    \$fullPath = \$scriptDir . '/' . ltrim(\$path, '/');
    return rtrim(\$fullPath, '/') ?: '/';
}

function getSetting(\$key, \$default = '') {
    global \$pdo;
    static \$cachedSettings = null;
    if (\$cachedSettings === null) {
        try {
            \$stmt = \$pdo->query(\"SELECT `key`, `value` FROM settings\");
            \$cachedSettings = \$stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        } catch (Exception \$e) {
            return \$default;
        }
    }
    return \$cachedSettings[\$key] ?? \$default;
}

function checkAuth() {
    if (!isset(\$_SESSION['admin_id'])) {
        header(\"Location: \" . baseUrl('login'));
        exit;
    }
}
";
        file_put_contents(__DIR__ . '/data-kelulusan/config.php', $configContent);

        // 5. Create lock file
        file_put_contents($lockFile, date('Y-m-d H:i:s'));

        $message = "Instalasi Berhasil! Silakan hapus file install.php untuk keamanan.";
        $success = true;

    } catch (Exception $e) {
        $message = "Kesalahan: " . $e->getMessage();
        $error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installer - SMAN 1 Sooko</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; color: white; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .input-glass { background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; color: white; padding: 12px 16px; width: 100%; outline: none; transition: all 0.3s; }
        .input-glass:focus { border-color: #40A69F; background: rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body>
    <div class="max-w-md w-full p-6">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-white mb-2">Setup Portal</h1>
            <p class="text-slate-400">Konfigurasi Database & Sistem</p>
        </div>

        <?php if ($message): ?>
            <div class="<?= $error ? 'bg-red-500/20 border-red-500/50 text-red-200' : 'bg-emerald-500/20 border-emerald-500/50 text-emerald-200' ?> border p-4 rounded-2xl mb-6 flex items-center gap-3">
                <span class="material-icons"><?= $error ? 'error' : 'check_circle' ?></span>
                <p class="text-sm"><?= $message ?></p>
            </div>
            <?php if (!$error): ?>
                <a href="/login" class="block text-center bg-[#40A69F] hover:bg-[#2B7A6D] text-white px-10 py-4 rounded-2xl font-bold transition shadow-lg shadow-emerald-500/20">
                    Buka Login Admin
                </a>
                <?php exit; ?>
            <?php endif; ?>
        <?php endif; ?>

        <form method="POST" class="glass p-8 rounded-3xl space-y-6">
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Database Host</label>
                <input type="text" name="db_host" class="input-glass" value="localhost" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Database Name</label>
                <input type="text" name="db_name" class="input-glass" value="kelulusan_sman1sooko" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Database User</label>
                <input type="text" name="db_user" class="input-glass" placeholder="root" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Database Password</label>
                <input type="password" name="db_pass" class="input-glass" placeholder="Kosongkan jika tidak ada">
            </div>

            <button type="submit" class="w-full bg-[#40A69F] hover:bg-[#2B7A6D] text-white px-10 py-4 rounded-2xl font-bold transition shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2">
                <span class="material-icons">settings</span>
                Mulai Instalasi
            </button>
        </form>
    </div>
</body>
</html>
