<?php
require_once __DIR__ . '/config.php';
checkAuth();

// Get Parameters
$page_num = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$limit = isset($_GET['limit']) ? $_GET['limit'] : 25;
$search = isset($_GET['q']) ? $_GET['q'] : '';

// Calculate Offset
$offset = ($page_num - 1) * (int)$limit;

// Base Query
$where = "1=1";
$params = [];
if ($search) {
    $where .= " AND (nisn LIKE ? OR nama LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Get Total for Pagination
$total_stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE $where");
$total_stmt->execute($params);
$total_items = $total_stmt->fetchColumn();

// Fetch Students
$query = "SELECT * FROM students WHERE $where ORDER BY kelas ASC, nama ASC, lulus DESC";
if ($limit !== 'all') {
    $query .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
}
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$students = $stmt->fetchAll();

// Pagination Math
$total_pages = ($limit === 'all') ? 1 : ceil($total_items / (int)$limit);

// Stats
$totalStudents = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$lulusCount = $pdo->query("SELECT COUNT(*) FROM students WHERE lulus = 1")->fetchColumn();
$tidakLulusCount = $pdo->query("SELECT COUNT(*) FROM students WHERE lulus = 0")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SMAN 1 Sooko</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; color: white; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .sidebar { width: 260px; border-right: 1px solid rgba(255, 255, 255, 0.1); }
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: #94a3b8; transition: all 0.3s; }
        .nav-link:hover, .nav-link.active { background: rgba(64, 166, 159, 0.15); color: #40A69F; }
        .input-glass { background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; color: white; padding: 10px 16px; outline: none; transition: all 0.3s; }
        .input-glass:focus { border-color: #40A69F; background: rgba(255, 255, 255, 0.1); }
        select.input-glass { appearance: none; padding-right: 32px; }
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
            <a href="<?= baseUrl('dashboard') ?>" class="nav-link active">
                <span class="material-icons">dashboard</span> Dashboard
            </a>
            <a href="<?= baseUrl('import') ?>" class="nav-link">
                <span class="material-icons">upload_file</span> Import Data
            </a>
            <a href="<?= baseUrl('settings') ?>" class="nav-link">
                <span class="material-icons">settings</span> Settings
            </a>
        </nav>

        <a href="<?= baseUrl('logout') ?>" class="nav-link text-red-400 hover:bg-red-500/10 hover:text-red-400 mt-auto">
            <span class="material-icons">logout</span> Logout
        </a>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10">
        <header class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-10">
            <div>
                <h2 class="text-2xl font-bold text-white">Ringkasan Data Siswa</h2>
                <p class="text-slate-400">Total data yang terdaftar saat ini.</p>
            </div>
            <a href="<?= baseUrl('import') ?>" class="bg-[#40A69F] hover:bg-[#2B7A6D] text-white px-6 py-2.5 rounded-xl font-semibold flex items-center gap-2 transition whitespace-nowrap">
                <span class="material-icons text-lg">add</span> Import Data
            </a>
        </header>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="glass p-6 rounded-3xl border-l-4 border-l-blue-500">
                <p class="text-slate-400 text-xs uppercase tracking-wider mb-1">Total Siswa</p>
                <h3 class="text-3xl font-bold text-white"><?= number_format($totalStudents) ?></h3>
            </div>
            <div class="glass p-6 rounded-3xl border-l-4 border-l-emerald-500">
                <p class="text-slate-400 text-xs uppercase tracking-wider mb-1">Siswa Lulus</p>
                <h3 class="text-3xl font-bold text-white"><?= number_format($lulusCount) ?></h3>
            </div>
            <div class="glass p-6 rounded-3xl border-l-4 border-l-red-500">
                <p class="text-slate-400 text-xs uppercase tracking-wider mb-1">Tidak Lulus</p>
                <h3 class="text-3xl font-bold text-white"><?= number_format($tidakLulusCount) ?></h3>
            </div>
        </div>

        <!-- Toolbar -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
            <div class="flex items-center gap-3">
                <span class="text-sm text-slate-400">Tampilkan</span>
                <div class="relative">
                    <select onchange="window.location.href='?limit='+this.value+'&q=<?= urlencode($search) ?>'" class="input-glass text-sm">
                        <?php foreach ([25, 50, 100, 500, 'all'] as $opt): ?>
                            <option value="<?= $opt ?>" <?= $limit == $opt ? 'selected' : '' ?>><?= ucfirst($opt) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="material-icons absolute right-2 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none text-sm">expand_more</span>
                </div>
                <span class="text-sm text-slate-400">data per halaman</span>
            </div>
            
            <form action="" method="GET" class="relative w-full md:w-64">
                <input type="hidden" name="limit" value="<?= htmlspecialchars($limit) ?>">
                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm">search</span>
                <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Cari NISN atau Nama..." class="input-glass pl-10 w-full text-sm">
            </form>
        </div>

        <!-- Student List -->
        <div class="glass rounded-3xl overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 text-slate-400 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4">NISN</th>
                            <th class="px-6 py-4">Nama Lengkap</th>
                            <th class="px-6 py-4">JK</th>
                            <th class="px-6 py-4">Kelas</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Password</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php foreach ($students as $s): ?>
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="px-6 py-4 text-sm font-mono"><?= htmlspecialchars($s['nisn']) ?></td>
                            <td class="px-6 py-4 text-sm font-semibold"><?= htmlspecialchars($s['nama']) ?></td>
                            <td class="px-6 py-4 text-sm text-slate-400"><?= htmlspecialchars($s['jk']) ?></td>
                            <td class="px-6 py-4 text-sm text-slate-400"><?= htmlspecialchars($s['kelas']) ?></td>
                            <td class="px-6 py-4">
                                <?php if ($s['lulus']): ?>
                                    <span class="bg-emerald-500/10 text-emerald-400 px-2.5 py-1 rounded-lg text-xs font-bold uppercase">Lulus</span>
                                <?php else: ?>
                                    <span class="bg-red-500/10 text-red-400 px-2.5 py-1 rounded-lg text-xs font-bold uppercase">Tidak Lulus</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500 font-mono"><?= htmlspecialchars($s['password']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">Data siswa tidak ditemukan.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="flex justify-between items-center gap-4">
            <p class="text-sm text-slate-500">
                Menampilkan <?= number_format(count($students)) ?> dari <?= number_format($total_items) ?> data
            </p>
            <div class="flex gap-2">
                <?php if ($page_num > 1): ?>
                    <a href="?p=<?= $page_num - 1 ?>&limit=<?= $limit ?>&q=<?= urlencode($search) ?>" class="glass p-2 rounded-xl hover:bg-white/10 transition">
                        <span class="material-icons text-sm">chevron_left</span>
                    </a>
                <?php endif; ?>

                <?php 
                $start = max(1, $page_num - 2);
                $end = min($total_pages, $page_num + 2);
                for ($i = $start; $i <= $end; $i++): 
                ?>
                    <a href="?p=<?= $i ?>&limit=<?= $limit ?>&q=<?= urlencode($search) ?>" class="px-4 py-2 rounded-xl text-sm font-semibold transition <?= $i == $page_num ? 'bg-[#40A69F] text-white' : 'glass hover:bg-white/10 text-slate-400' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page_num < $total_pages): ?>
                    <a href="?p=<?= $page_num + 1 ?>&limit=<?= $limit ?>&q=<?= urlencode($search) ?>" class="glass p-2 rounded-xl hover:bg-white/10 transition">
                        <span class="material-icons text-sm">chevron_right</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </main>
</body>
</html>
