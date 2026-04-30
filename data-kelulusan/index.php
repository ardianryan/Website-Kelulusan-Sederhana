<?php
require_once __DIR__ . '/config.php';

$page = $_GET['page'] ?? '';

// Handle Logout
if ($page === 'logout') {
    require __DIR__ . '/logout.php';
    exit;
}

// Redirect to dashboard if already logged in and at login page
if (isset($_SESSION['admin_id']) && ($page === '' || $page === 'login')) {
    session_write_close();
    header("Location: " . baseUrl('dashboard'));
    exit;
}

// Auth Check for protected pages
if ($page !== '' && $page !== 'login') {
    checkAuth();
}

// Routing
switch ($page) {
    case 'dashboard':
        require __DIR__ . '/dashboard.php';
        break;
    case 'import':
        require __DIR__ . '/import.php';
        break;
    case 'settings':
        require __DIR__ . '/settings.php';
        break;
    case '':
    case 'login':
        // Login Logic (Previously in index.php)
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $_POST['username'] ?? '';
            $pass = $_POST['password'] ?? '';

            $stmt = $pdo->prepare("SELECT id, password FROM admins WHERE username = ?");
            $stmt->execute([$user]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($pass, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['username'] = $user;
                session_write_close();
                header("Location: " . baseUrl('dashboard'));
                exit;
            } else {
                $error = 'Username atau password salah.';
            }
        }
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Login Admin - Kelulusan SMAN 1 Sooko</title>
            <script src="https://cdn.tailwindcss.com/3.4.17"></script>
            <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
            <style>
                body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; color: white; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
                .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 24px; }
                .input-glass { background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; color: white; padding: 12px 16px; width: 100%; outline: none; transition: all 0.3s; }
                .input-glass:focus { border-color: #40A69F; background: rgba(255, 255, 255, 0.1); }
                .btn-primary { background: linear-gradient(135deg, #40A69F, #2B7A6D); color: white; padding: 12px; border-radius: 12px; font-weight: 600; width: 100%; transition: all 0.3s; }
                .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(64, 166, 159, 0.3); }
            </style>
        </head>
        <body>
            <div class="glass p-10 w-full max-w-md">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-extrabold mb-2">Admin Login</h1>
                    <p class="text-slate-400 text-sm">Kelulusan SMA Negeri 1 Sooko</p>
                </div>
                
                <?php if ($error): ?>
                    <div class="bg-red-500/20 border border-red-500/50 text-red-200 p-3 rounded-lg mb-6 text-sm text-center">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Username</label>
                        <input type="text" name="username" class="input-glass" required autofocus placeholder="admin">
                    </div>
                    <div class="mb-8">
                        <label class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Password</label>
                        <input type="password" name="password" class="input-glass" required placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn-primary">Masuk</button>
                </form>
            </div>
        </body>
        </html>
        <?php
        break;
    default:
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
        break;
}
?>
