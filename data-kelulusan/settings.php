<?php
require_once __DIR__ . '/config.php';
use App\Models\Setting;

checkAuth();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle Text Settings
    if (isset($_POST['settings'])) {
        foreach ($_POST['settings'] as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    // Handle Logo Upload
    if (isset($_FILES['school_logo']) && $_FILES['school_logo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        
        $extension = pathinfo($_FILES['school_logo']['name'], PATHINFO_EXTENSION);
        $logoName = 'logo_' . time() . '.' . $extension;
        $targetPath = $uploadDir . $logoName;
        
        if (move_uploaded_file($_FILES['school_logo']['tmp_name'], $targetPath)) {
            $logoUrl = '/uploads/' . $logoName;
            Setting::updateOrCreate(['key' => 'school_logo'], ['value' => $logoUrl]);
        }
    }
    
    $message = "Pengaturan berhasil disimpan.";
}

$settings = Setting::pluck('value', 'key')->toArray();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - SMAN 1 Sooko</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; color: white; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .sidebar { width: 260px; border-right: 1px solid rgba(255, 255, 255, 0.1); }
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: #94a3b8; transition: all 0.3s; }
        .nav-link:hover, .nav-link.active { background: rgba(64, 166, 159, 0.15); color: #40A69F; }
        .input-glass { background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; color: white; padding: 12px 16px; width: 100%; outline: none; }
    </style>
</head>
<body class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="sidebar p-6 flex flex-col">
        <div class="mb-10">
            <h1 class="text-xl font-bold text-white">Admin Portal</h1>
            <p class="text-xs text-slate-500">SMAN 1 Sooko</p>
        </div>
        
        <nav class="flex-1 space-y-2">
            <a href="<?= baseUrl('dashboard') ?>" class="nav-link">
                <span class="material-icons">dashboard</span> Dashboard
            </a>
            <a href="<?= baseUrl('import') ?>" class="nav-link">
                <span class="material-icons">upload_file</span> Import Data
            </a>
            <a href="<?= baseUrl('settings') ?>" class="nav-link active">
                <span class="material-icons">settings</span> Settings
            </a>
        </nav>

        <a href="<?= baseUrl('logout') ?>" class="nav-link text-red-400 hover:bg-red-500/10 hover:text-red-400 mt-auto">
            <span class="material-icons">logout</span> Logout
        </a>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10">
        <header class="mb-10">
            <h2 class="text-2xl font-bold text-white">Pengaturan Aplikasi</h2>
            <p class="text-slate-400">Atur informasi sekolah dan jadwal pengumuman.</p>
        </header>

        <?php if ($message): ?>
            <div class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-200 p-4 rounded-2xl mb-6 flex items-center gap-3">
                <span class="material-icons">check_circle</span> <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="max-w-2xl space-y-8">
            <div class="glass p-8 rounded-3xl space-y-6">
                <h3 class="text-lg font-bold border-b border-white/10 pb-4">Informasi Umum</h3>
                
                <div class="flex items-start gap-6 mb-4">
                    <div class="w-24 h-24 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center overflow-hidden flex-shrink-0">
                        <?php if (isset($settings['school_logo'])): ?>
                            <img src="<?= $settings['school_logo'] ?>" class="w-full h-full object-contain">
                        <?php else: ?>
                            <span class="material-icons text-slate-600">image</span>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Logo Sekolah</label>
                        <input type="file" name="school_logo" accept="image/*" class="text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-[#40A69F]/10 file:text-[#40A69F] hover:file:bg-[#40A69F]/20 cursor-pointer">
                        <p class="text-[10px] text-slate-500 mt-2 italic">*Upload logo baru untuk mengganti logo saat ini (Format: PNG, JPG, WebP)</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Nama Sekolah</label>
                    <input type="text" name="settings[school_name]" class="input-glass" value="<?= htmlspecialchars($settings['school_name'] ?? '') ?>" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Teks Selamat Datang</label>
                    <input type="text" name="settings[welcome_text]" class="input-glass" value="<?= htmlspecialchars($settings['welcome_text'] ?? '') ?>" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Info SKL (Muncul jika Lulus)</label>
                    <textarea name="settings[skl_info]" class="input-glass min-h-[100px]"><?= htmlspecialchars($settings['skl_info'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="glass p-8 rounded-3xl space-y-6">
                <h3 class="text-lg font-bold border-b border-white/10 pb-4">Jadwal Pengumuman</h3>
                
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Tanggal & Waktu Buka (Countdown)</label>
                    <input type="datetime-local" name="settings[countdown_date]" class="input-glass" value="<?= date('Y-m-d\TH:i', strtotime($settings['countdown_date'] ?? 'now')) ?>" required>
                    <p class="text-xs text-slate-500 mt-2">Format: Tanggal dan Waktu (Asia/Jakarta)</p>
                </div>
            </div>

            <button type="submit" class="bg-[#40A69F] hover:bg-[#2B7A6D] text-white px-10 py-4 rounded-2xl font-bold transition shadow-lg shadow-emerald-500/20">
                Simpan Perubahan
            </button>
        </form>
    </main>
</body>
</html>
