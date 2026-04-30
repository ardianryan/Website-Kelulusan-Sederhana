<?php require_once __DIR__ . '/../data-kelulusan/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; color: white; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .glow { text-shadow: 0 0 20px rgba(64, 166, 159, 0.5); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6 text-center">
    <div class="max-w-md w-full">
        <h1 class="text-[120px] font-black leading-none mb-4 text-[#40A69F] glow opacity-20">404</h1>
        <div class="glass p-10 rounded-[32px] -mt-20 relative z-10 shadow-2xl">
            <h2 class="text-2xl font-bold mb-4">Halaman Hilang!</h2>
            <p class="text-slate-400 mb-8 leading-relaxed">Sepertinya Anda tersesat di ruang angkasa. Halaman yang Anda cari tidak ditemukan atau sudah pindah.</p>
            <a href="<?= baseUrl() ?>" class="inline-block bg-[#40A69F] hover:bg-[#2B7A6D] text-white px-8 py-3 rounded-2xl font-bold transition">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
