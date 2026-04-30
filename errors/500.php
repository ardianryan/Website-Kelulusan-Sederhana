<?php require_once __DIR__ . '/../data-kelulusan/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Kesalahan Server</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; color: white; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .glow-amber { text-shadow: 0 0 20px rgba(245, 158, 11, 0.5); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6 text-center">
    <div class="max-w-md w-full">
        <h1 class="text-[120px] font-black leading-none mb-4 text-amber-500 glow-amber opacity-20">500</h1>
        <div class="glass p-10 rounded-[32px] -mt-20 relative z-10 shadow-2xl">
            <h2 class="text-2xl font-bold mb-4">Ups, Ada Masalah!</h2>
            <p class="text-slate-400 mb-8 leading-relaxed">Terjadi kesalahan internal pada server kami. Kami sedang mencoba memperbaikinya. Silakan coba lagi nanti.</p>
            <button onclick="location.reload()" class="inline-block bg-amber-500 hover:bg-amber-600 text-white px-8 py-3 rounded-2xl font-bold transition">
                Muat Ulang Halaman
            </button>
        </div>
    </div>
</body>
</html>
