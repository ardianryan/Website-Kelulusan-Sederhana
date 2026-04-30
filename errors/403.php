<?php require_once __DIR__ . '/../data-kelulusan/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Dilarang</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; color: white; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .glow-red { text-shadow: 0 0 20px rgba(239, 68, 68, 0.5); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6 text-center">
    <div class="max-w-md w-full">
        <h1 class="text-[120px] font-black leading-none mb-4 text-red-500 glow-red opacity-20">403</h1>
        <div class="glass p-10 rounded-[32px] -mt-20 relative z-10 shadow-2xl">
            <h2 class="text-2xl font-bold mb-4">Akses Ditolak!</h2>
            <p class="text-slate-400 mb-8 leading-relaxed">Maaf, Anda tidak memiliki izin untuk mengakses area ini. Silakan hubungi administrator jika ini kesalahan.</p>
            <a href="<?= baseUrl() ?>" class="inline-block bg-slate-800 hover:bg-slate-700 text-white px-8 py-3 rounded-2xl font-bold transition">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
