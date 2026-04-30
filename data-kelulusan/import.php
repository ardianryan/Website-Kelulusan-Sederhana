<?php
require_once __DIR__ . '/config.php';
checkAuth();

use PhpOffice\PhpSpreadsheet\IOFactory;

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['import_file'])) {
    $file = $_FILES['import_file']['tmp_name'];
    $ext = pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION);
    
    if (is_uploaded_file($file)) {
        try {
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $dataRows = $sheet->toArray();
            
            // Skip header (first row)
            $header = array_shift($dataRows);
            
            $rowCount = 0;
            $successCount = 0;
            
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO students (nisn, nama, jk, kelas, password, lulus) 
                                 VALUES (?, ?, ?, ?, ?, ?) 
                                 ON DUPLICATE KEY UPDATE 
                                 nama = VALUES(nama), jk = VALUES(jk), kelas = VALUES(kelas), 
                                 password = VALUES(password), lulus = VALUES(lulus)");
            
            foreach ($dataRows as $row) {
                if (count($row) >= 6 && !empty($row[0])) {
                    // Smart JK Mapping
                    $jk = strtoupper(trim($row[2]));
                    if (strpos($jk, 'L') === 0) $jk = 'L';
                    elseif (strpos($jk, 'P') === 0) $jk = 'P';

                    $stmt->execute([
                        trim($row[0]), // nisn
                        trim($row[1]), // nama
                        $jk,           // jk
                        trim($row[3]), // kelas
                        trim($row[4]), // password
                        trim($row[5])  // lulus (1/0)
                    ]);
                    $successCount++;
                }
                $rowCount++;
            }
            $pdo->commit();
            $message = "Berhasil mengimpor $successCount data siswa dari file Excel/CSV.";
        } catch (Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = "Terjadi kesalahan: " . $e->getMessage();
        }
    } else {
        $error = "File tidak valid.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Data - SMAN 1 Sooko</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; color: white; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .sidebar { width: 260px; border-right: 1px solid rgba(255, 255, 255, 0.1); }
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: #94a3b8; transition: all 0.3s; }
        .nav-link:hover, .nav-link.active { background: rgba(64, 166, 159, 0.15); color: #40A69F; }
        .drop-zone { border: 2px dashed rgba(255,255,255,0.1); border-radius: 20px; transition: all 0.3s; }
        .drop-zone:hover { border-color: #40A69F; background: rgba(64, 166, 159, 0.05); }
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
            <a href="<?= baseUrl('import') ?>" class="nav-link active">
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
        <header class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">Import Data Kelulusan</h2>
                <p class="text-slate-400">Unggah file Excel (.xlsx) atau CSV untuk memperbarui data siswa secara massal.</p>
            </div>
            <a href="<?= baseUrl('download-template') ?>" class="bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-2xl font-semibold flex items-center gap-2 transition border border-white/10">
                <span class="material-icons">download</span> Download Template
            </a>
        </header>

        <?php if ($message): ?>
            <div class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-200 p-4 rounded-2xl mb-6 flex items-center gap-3">
                <span class="material-icons">check_circle</span> <?= $message ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="bg-red-500/20 border border-red-500/50 text-red-200 p-4 rounded-2xl mb-6 flex items-center gap-3">
                <span class="material-icons">error</span> <?= $error ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <!-- Form -->
            <div class="glass p-8 rounded-3xl">
                <h3 class="text-lg font-bold mb-6">Pilih File (Excel/CSV)</h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="drop-zone p-10 text-center mb-6 cursor-pointer relative">
                        <input type="file" name="import_file" accept=".xlsx, .xls, .csv" class="absolute inset-0 opacity-0 cursor-pointer" required>
                        <span class="material-icons text-5xl text-slate-500 mb-4">description</span>
                        <p class="text-slate-400">Klik atau seret file Excel/CSV di sini</p>
                        <p class="text-xs text-slate-600 mt-2">Mendukung format .xlsx, .xls, dan .csv</p>
                    </div>
                    <button type="submit" class="w-full bg-[#40A69F] hover:bg-[#2B7A6D] text-white py-4 rounded-2xl font-bold transition">
                        Mulai Import Data
                    </button>
                </form>
            </div>

            <!-- Guide -->
            <div class="bg-white/5 p-8 rounded-3xl border border-white/5">
                <h3 class="text-lg font-bold mb-4">Panduan Format Data</h3>
                <p class="text-slate-400 text-sm mb-6">Urutan kolom pada Sheet pertama harus seperti berikut:</p>
                
                <table class="w-full text-xs text-left text-slate-300 border-collapse mb-6">
                    <thead class="bg-white/10">
                        <tr>
                            <th class="p-2 border border-white/10">NISN</th>
                            <th class="p-2 border border-white/10">Nama</th>
                            <th class="p-2 border border-white/10">JK</th>
                            <th class="p-2 border border-white/10">Kelas</th>
                            <th class="p-2 border border-white/10">Pass</th>
                            <th class="p-2 border border-white/10">Lulus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white/5">
                            <td class="p-2 border border-white/10">00123...</td>
                            <td class="p-2 border border-white/10">Ahmad Rizky</td>
                            <td class="p-2 border border-white/10">L</td>
                            <td class="p-2 border border-white/10">XII IPA 1</td>
                            <td class="p-2 border border-white/10">pass001</td>
                            <td class="p-2 border border-white/10">1</td>
                        </tr>
                    </tbody>
                </table>

                <ul class="space-y-3 text-sm text-slate-400">
                    <li class="flex items-start gap-2">
                        <span class="material-icons text-emerald-500 text-sm mt-0.5">check</span>
                        <span><strong>Excel</strong>: Baris pertama dianggap sebagai Header (diabaikan).</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-icons text-emerald-500 text-sm mt-0.5">check</span>
                        <span><strong>JK</strong>: L (Laki-laki) atau P (Perempuan).</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-icons text-emerald-500 text-sm mt-0.5">check</span>
                        <span><strong>Lulus</strong>: 1 (Lulus) atau 0 (Tidak Lulus).</span>
                    </li>
                    <li class="flex items-start gap-2 bg-orange-500/10 p-3 rounded-xl border border-orange-500/20 mt-4">
                        <span class="material-icons text-orange-400 text-sm mt-0.5">info</span>
                        <span class="text-orange-200 text-xs leading-relaxed"><strong>Sistem Update Otomatis</strong>: Jika NISN sudah ada di database, data siswa tersebut akan diperbarui (update) secara otomatis dengan data terbaru dari file Anda.</span>
                    </li>
                </ul>
            </div>
        </div>
    </main>

    <script>
        const dropZone = document.querySelector('.drop-zone');
        const fileInput = dropZone.querySelector('input');
        const dropZoneText = dropZone.querySelector('p');

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                dropZoneText.innerText = `File terpilih: ${fileInput.files[0].name}`;
                dropZone.style.borderColor = '#40A69F';
                dropZone.style.background = 'rgba(64, 166, 159, 0.1)';
            }
        });

        ['dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, e => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        dropZone.addEventListener('dragover', () => {
            dropZone.classList.add('border-emerald-500');
            dropZone.style.background = 'rgba(64, 166, 159, 0.1)';
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-emerald-500');
            dropZone.style.background = 'transparent';
        });

        dropZone.addEventListener('drop', (e) => {
            dropZone.classList.remove('border-emerald-500');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                dropZoneText.innerText = `File terpilih: ${files[0].name}`;
            }
        });
    </script>
</body>
</html>
