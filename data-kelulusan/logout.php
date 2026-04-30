<?php
require_once __DIR__ . '/config.php';

// Clear session
session_unset();
session_destroy();

// If it's an AJAX request, just return success
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    echo json_encode(['success' => true]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out...</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; color: white; overflow: hidden; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .spinner {
            width: 40px; height: 40px;
            border: 3px solid rgba(64, 166, 159, 0.1);
            border-top: 3px solid #40A69F;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .fade-out { animation: fadeOut 0.5s forwards; }
        @keyframes fadeOut { from { opacity: 1; transform: scale(1); } to { opacity: 0; transform: scale(0.95); } }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">
    <div id="logout-card" class="glass p-12 rounded-[32px] text-center max-w-sm w-full shadow-2xl">
        <div class="flex justify-center mb-6">
            <div class="spinner"></div>
        </div>
        <h1 class="text-xl font-bold mb-2">Mengakhiri Sesi...</h1>
        <p class="text-slate-400 text-sm">Terima kasih telah berkunjung. Anda akan dialihkan sebentar lagi.</p>
    </div>

    <script>
        setTimeout(() => {
            document.getElementById('logout-card').classList.add('fade-out');
            setTimeout(() => {
                window.location.href = '<?= baseUrl() ?>';
            }, 500);
        }, 1500);
    </script>
</body>
</html>
