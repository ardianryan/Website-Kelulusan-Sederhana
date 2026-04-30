<?php require_once 'data-kelulusan/config.php';
$settings = [];
$stmt = $pdo->query("SELECT * FROM settings");
while ($row = $stmt->fetch()) {
    $settings[$row['key']] = $row['value'];
}

// Calculate Dynamic Academic Year
$currentYear = date('Y');
$prevYear = $currentYear - 1;
$academicYear = $prevYear . '/' . $currentYear;

$schoolName = $settings['school_name'] ?? 'SMA Negeri 1 Sooko';
$welcomeText = $settings['welcome_text'] ?? 'Selamat Datang di Portal Pengumuman Kelulusan';
$metaDesc = $settings['meta_description'] ?? "Portal Resmi Pengumuman Kelulusan Siswa SMA Negeri 1 Sooko Tahun Pelajaran $academicYear.";
$countdownDate = $settings['countdown_date'] ?? "$currentYear-05-05 07:00:00";
$schoolLogo = isset($settings['school_logo']) ? baseUrl($settings['school_logo']) : '/logo.png';
?>
<!doctype html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman Kelulusan - SMAN 1 Sooko</title>

    <!-- SEO & Metadata -->
    <meta name="description" content="<?= htmlspecialchars($metaDesc) ?>">
    <meta name="keywords" content="Pengumuman, Kelulusan, SMAN 1 Sooko, SKL, Siswa">
    <meta name="author" content="ArdianRyan">
    <meta name="theme-color" content="#0f172a">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://sman1sooko.sch.id/">
    <meta property="og:title" content="Pengumuman Kelulusan - SMAN 1 Sooko">
    <meta property="og:description" content="<?= htmlspecialchars($metaDesc) ?>">
    <meta property="og:image" content="<?= $schoolLogo ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://sman1sooko.sch.id/">
    <meta property="twitter:title" content="Pengumuman Kelulusan - SMAN 1 Sooko">
    <meta property="twitter:description" content="<?= htmlspecialchars($metaDesc) ?>">
    <meta property="twitter:image" content="<?= $schoolLogo ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= $schoolLogo ?>">
    <link rel="apple-touch-icon" href="<?= $schoolLogo ?>">

    <script src="/_sdk/element_sdk.js"></script>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box
        }

        html,
        body {
            height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden
        }

        body {
            background: #0f172a
        }

        .bubbles-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden
        }

        .bubble {
            position: absolute;
            border-radius: 50%;
            opacity: .15;
            animation: floatBubble linear infinite
        }

        @keyframes floatBubble {
            0% {
                transform: translateY(100%) translateX(0) scale(1)
            }

            25% {
                transform: translateY(75%) translateX(30px) scale(1.05)
            }

            50% {
                transform: translateY(50%) translateX(-20px) scale(1)
            }

            75% {
                transform: translateY(25%) translateX(25px) scale(1.05)
            }

            100% {
                transform: translateY(-100%) translateX(0) scale(1)
            }
        }

        .glass {
            background: rgba(255, 255, 255, .08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 24px
        }

        .glass-strong {
            background: rgba(255, 255, 255, .12);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 24px
        }

        .input-glass {
            background: rgba(255, 255, 255, .06);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, .15);
            border-radius: 14px;
            color: #fff;
            padding: 14px 18px;
            width: 100%;
            font-size: 15px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all .3s
        }

        .input-glass:focus {
            outline: none;
            border-color: #40A69F;
            box-shadow: 0 0 0 3px rgba(64, 166, 159, .25);
            background: rgba(255, 255, 255, .1)
        }

        .input-glass::placeholder {
            color: rgba(255, 255, 255, .4)
        }

        .btn-primary {
            background: linear-gradient(135deg, #40A69F, #2B7A6D);
            color: #fff;
            border: none;
            border-radius: 14px;
            padding: 14px 28px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all .3s;
            font-family: 'Plus Jakarta Sans', sans-serif;
            position: relative;
            overflow: hidden
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(64, 166, 159, .4)
        }

        .btn-primary:active {
            transform: translateY(0)
        }

        .btn-primary:disabled {
            opacity: .5;
            cursor: not-allowed;
            transform: none
        }

        .btn-secondary {
            background: linear-gradient(135deg, #4F46B4, #3E3B8B);
            color: #fff;
            border: none;
            border-radius: 14px;
            padding: 14px 28px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all .3s;
            font-family: 'Plus Jakarta Sans', sans-serif
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(79, 70, 180, .4)
        }

        .step {
            display: none;
            animation: fadeInUp .5s ease
        }

        .step.active {
            display: block
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .data-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .08)
        }

        .data-row:last-child {
            border-bottom: none
        }

        .data-label {
            color: rgba(255, 255, 255, .5);
            font-size: 13px
        }

        .data-value {
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            text-align: right
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .7);
            backdrop-filter: blur(8px);
            z-index: 100;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px
        }

        .modal-overlay.show {
            display: flex
        }

        .confetti-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 101;
            overflow: hidden
        }

        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            top: -10px;
            animation: confettiFall linear forwards
        }

        @keyframes confettiFall {
            0% {
                transform: translateY(0) rotate(0deg) scale(1);
                opacity: 1
            }

            80% {
                opacity: 1
            }

            100% {
                transform: translateY(800px) rotate(720deg) scale(.5);
                opacity: 0
            }
        }

        .balloon {
            position: absolute;
            bottom: -120px;
            animation: balloonRise ease-out forwards
        }

        @keyframes balloonRise {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1
            }

            70% {
                opacity: 1
            }

            100% {
                transform: translateY(-900px) rotate(15deg);
                opacity: 0
            }
        }

        .pulse-ring {
            animation: pulseRing 2s ease infinite
        }

        @keyframes pulseRing {
            0% {
                box-shadow: 0 0 0 0 rgba(64, 166, 159, .4)
            }

            70% {
                box-shadow: 0 0 0 20px rgba(64, 166, 159, 0)
            }

            100% {
                box-shadow: 0 0 0 0 rgba(64, 166, 159, 0)
            }
        }

        .shake {
            animation: shake .5s ease
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0)
            }

            20%,
            60% {
                transform: translateX(-8px)
            }

            40%,
            80% {
                transform: translateX(8px)
            }
        }

        .logo-float {
            animation: logoFloat 3s ease-in-out infinite
        }

        @keyframes logoFloat {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-8px)
            }
        }

        .glow-text {
            text-shadow: 0 0 40px rgba(64, 166, 159, .5)
        }

        .skl-banner {
            background: linear-gradient(135deg, rgba(253, 180, 2, .15), rgba(255, 87, 111, .1));
            border: 1px solid rgba(253, 180, 2, .25);
            border-radius: 16px;
            padding: 16px 20px
        }

        .countdown-container {
            background: linear-gradient(135deg, rgba(64, 166, 159, .15), rgba(79, 70, 180, .15));
            border: 1px solid rgba(64, 166, 159, .3);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            text-align: center
        }

        .countdown-label {
            color: rgba(255, 255, 255, .6);
            font-size: 13px;
            margin-bottom: 12px
        }

        .countdown-date {
            color: #A8E4DB;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 16px
        }

        .countdown-boxes {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 12px
        }

        .countdown-box {
            background: rgba(64, 166, 159, .2);
            border: 1px solid rgba(64, 166, 159, .4);
            border-radius: 12px;
            padding: 12px;
            min-height: 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center
        }

        .countdown-number {
            font-size: 20px;
            font-weight: 800;
            color: #40A69F;
            line-height: 1
        }

        .countdown-unit {
            font-size: 10px;
            color: rgba(255, 255, 255, .4);
            margin-top: 4px;
            text-transform: uppercase
        }
    </style>
    <style>
        body {
            box-sizing: border-box;
        }
    </style>
    <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js" type="text/javascript"></script>
</head>

<body class="h-full">
    <div class="bubbles-container" id="bubbles"></div><!-- Ambient Gradient Orbs -->
    <div
        style="position:fixed;top:-20%;left:-10%;width:500px;height:500px;background:radial-gradient(circle,rgba(64,166,159,.15),transparent 70%);border-radius:50%;z-index:0;pointer-events:none">
    </div>
    <div
        style="position:fixed;bottom:-20%;right:-10%;width:500px;height:500px;background:radial-gradient(circle,rgba(79,70,180,.15),transparent 70%);border-radius:50%;z-index:0;pointer-events:none">
    </div>
    <div id="app"
        style="position:relative;z-index:1;width:100%;display:flex;flex-direction:column;align-items:center;justify-content:flex-start;padding:60px 20px 0">
        <!-- STEP 1: Welcome -->
        <div class="step active" id="step1" style="width:100%;max-width:440px">
            <div class="text-center" style="margin-bottom:36px">
                <img src="<?= $schoolLogo ?>" alt="Logo Sekolah" class="logo-float"
                    style="width:100px;height:auto;margin:0 auto 24px;object-fit:contain;filter:drop-shadow(0 8px 20px rgba(64,166,159,0.4))"
                    loading="lazy">
                <h1 class="glow-text"
                    style="font-size:26px;font-weight:800;color:#fff;letter-spacing:-0.5px;margin-bottom:8px;line-height:1.3;">
                    <span id="welcomeTextDisplay"><?= htmlspecialchars($welcomeText) ?></span>
                </h1>
                <p style="color:rgba(255,255,255,.6);font-size:15px;line-height:1.5;margin-bottom:16px;">
                    Tahun Pelajaran <?= $academicYear ?>
                </p>
                <div
                    style="display:inline-flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);padding:6px 16px;border-radius:20px;gap:6px;">
                    <span class="material-icons" style="font-size:16px;color:#40A69F;">school</span>
                    <span id="schoolNameDisplay"
                        style="color:#A8E4DB;font-size:13px;font-weight:700;letter-spacing:0.5px;text-transform:uppercase;">
                        <?= htmlspecialchars($schoolName) ?>
                    </span>
                </div>
            </div>

            <div id="nisnContainer" class="glass" style="padding:28px;display:none">
                <div style="text-align:center;margin-bottom:20px;">
                    <h3 style="color:#fff;font-size:16px;font-weight:700;margin-bottom:4px;">Cek Status Kelulusan</h3>
                    <p style="color:rgba(255,255,255,.5);font-size:13px;">Silakan masukkan NISN Anda untuk melanjutkan
                    </p>
                </div>

                <input type="text" id="nisnInput" class="input-glass" placeholder="Contoh: 0012345678" maxlength="20"
                    inputmode="numeric"
                    style="margin-bottom:12px;text-align:center;font-size:16px;letter-spacing:1px;font-weight:600;">

                <div id="nisnError"
                    style="color:#FF576F;font-size:13px;margin-bottom:12px;text-align:center;display:none;background:rgba(255,87,111,0.1);padding:8px;border-radius:8px;">
                </div>

                <button id="checkButton" class="btn-primary" onclick="checkNISN()">
                    <span style="display:flex;align-items:center;justify-content:center;gap:10px;width:100%">
                        <span class="material-icons" style="font-size:20px">search</span>
                        <span>Cari Data Siswa</span>
                    </span>
                </button>
            </div>

            <div class="countdown-container" id="countdownContainer" style="display:block">
                <div style="display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:12px;">
                    <span class="material-icons"
                        style="color:#FDB402;font-size:20px;animation: pulseRing 2s infinite;border-radius:50%;">notifications_active</span>
                    <p
                        style="color:rgba(255,255,255,.8);font-size:14px;font-weight:700;letter-spacing:1px;text-transform:uppercase;">
                        Waktu Pengumuman</p>
                </div>
                <div
                    style="background:rgba(0,0,0,0.2);border-radius:12px;padding:12px;margin-bottom:24px;border:1px solid rgba(255,255,255,0.05);">
                    <p style="color:#A8E4DB;font-size:15px;font-weight:700;letter-spacing:0.5px;">

                        <?= date('j F Y • H:i', strtotime($countdownDate)) ?> WIB
                    </p>
                </div>
                <div class="countdown-boxes">
                    <div class="countdown-box">
                        <div class="countdown-number" id="days">0</div>
                        <div class="countdown-unit">Hari</div>
                    </div>
                    <div class="countdown-box">
                        <div class="countdown-number" id="hours">0</div>
                        <div class="countdown-unit">Jam</div>
                    </div>
                    <div class="countdown-box">
                        <div class="countdown-number" id="minutes">0</div>
                        <div class="countdown-unit">Menit</div>
                    </div>
                    <div class="countdown-box">
                        <div class="countdown-number" id="seconds">0</div>
                        <div class="countdown-unit">Detik</div>
                    </div>
                </div>
            </div>
        </div><!-- STEP 2: Password -->
        <div class="step" id="step2" style="width:100%;max-width:440px;margin:0 auto">
            <div class="text-center" style="margin-bottom:32px">
                <div
                    style="width:72px;height:72px;background:linear-gradient(135deg,rgba(64,166,159,0.2),rgba(79,70,180,0.2));border-radius:24px;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;border:1px solid rgba(255,255,255,0.1);box-shadow:0 10px 25px -5px rgba(0,0,0,0.3);position:relative;">
                    <div
                        style="position:absolute;inset:0;background:inherit;border-radius:inherit;filter:blur(10px);opacity:0.5;z-index:-1;">
                    </div>
                    <span class="material-icons" style="font-size:32px;color:#40A69F">lock</span>
                </div>
                <h2 style="font-size:24px;font-weight:800;color:#fff;letter-spacing:-0.5px;">Verifikasi Identitas</h2>
                <p style="color:rgba(255,255,255,0.5);font-size:14px;margin-top:8px;">Silakan masukkan password untuk
                    keamanan data Anda</p>
            </div>
            <div class="glass" style="padding:28px">
                <p style="color:rgba(255,255,255,0.4);font-size:11px;text-transform:uppercase;margin-bottom:12px;font-weight:700;letter-spacing:1px">Tanggal Lahir</p>
                <div style="display:grid; grid-template-columns: 1fr 1.8fr 1.2fr; gap:10px; margin-bottom:20px">
                    <div>
                        <input type="number" id="birthDay" class="input-glass" placeholder="Tgl" min="1" max="31" style="padding: 14px 10px; text-align: center;">
                    </div>
                    <div>
                        <select id="birthMonth" class="input-glass" style="padding: 14px 10px; cursor: pointer; appearance: none; -webkit-appearance: none;">
                            <option value="">Bulan</option>
                            <?php 
                            $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            foreach ($months as $m) echo "<option value='$m'>$m</option>";
                            ?>
                        </select>
                    </div>
                    <div>
                        <input type="number" id="birthYear" class="input-glass" placeholder="Tahun" min="1990" max="2020" style="padding: 14px 10px; text-align: center;">
                    </div>
                </div>
                <div id="passError" style="color:#FF576F;font-size:12px;margin-bottom:12px;display:none"></div>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <button class="btn-primary" onclick="checkPassword()">
                        <span style="display:flex;align-items:center;justify-content:center;gap:10px;width:100%">
                            <span class="material-icons" style="font-size:20px">lock_open</span>
                            <span>Verifikasi</span>
                        </span>
                    </button>
                    <button onclick="goBack(1)"
                        style="background:rgba(255,255,255,.03);color:rgba(255,255,255,.6);border:1px solid rgba(255,255,255,.1);border-radius:14px;padding:14px;width:100%;cursor:pointer;font-size:14px;font-weight:600;font-family:'Plus Jakarta Sans',sans-serif;transition:all .3s"
                        onmouseover="this.style.background='rgba(255,255,255,.08)';this.style.color='#fff'"
                        onmouseout="this.style.background='rgba(255,255,255,.03)';this.style.color='rgba(255,255,255,.6)'">
                        <span style="display:flex;align-items:center;justify-content:center;gap:8px">
                            <span class="material-icons" style="font-size:18px">arrow_back</span>
                            <span>Kembali</span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- STEP 3: Data Verification -->
        <div class="step" id="step3" style="width:100%;max-width:440px;margin:0 auto">
            <div class="text-center" style="margin-bottom:32px">
                <div
                    style="width:72px;height:72px;background:linear-gradient(135deg,rgba(64,166,159,0.2),rgba(168,228,219,0.2));border-radius:24px;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;border:1px solid rgba(255,255,255,0.1);box-shadow:0 10px 25px -5px rgba(0,0,0,0.3);position:relative;">
                    <div
                        style="position:absolute;inset:0;background:inherit;border-radius:inherit;filter:blur(10px);opacity:0.5;z-index:-1;">
                    </div>
                    <span class="material-icons" style="font-size:32px;color:#A8E4DB">how_to_reg</span>
                </div>
                <h2 style="font-size:24px;font-weight:800;color:#fff;letter-spacing:-0.5px;">Konfirmasi Data</h2>
                <p style="color:rgba(255,255,255,0.5);font-size:14px;margin-top:8px;">Pastikan identitas di bawah ini
                    adalah benar milik Anda</p>
            </div>
            <div class="glass-strong" style="padding:24px">
                <div id="studentInfo"></div>
                <div style="display:flex;flex-direction:column;gap:12px;margin-top:24px;">
                    <button class="btn-primary" onclick="showResult()">
                        <span style="display:flex;align-items:center;justify-content:center;gap:10px;width:100%">
                            <span class="material-icons" style="font-size:20px">emoji_events</span>
                            <span>Lihat Pengumuman Kelulusan</span>
                        </span>
                    </button>
                    <button onclick="goBack(1)"
                        style="background:rgba(255,255,255,.03);color:rgba(255,255,255,.6);border:1px solid rgba(255,255,255,.1);border-radius:14px;padding:14px;width:100%;cursor:pointer;font-size:14px;font-weight:600;font-family:'Plus Jakarta Sans',sans-serif;transition:all .3s"
                        onmouseover="this.style.background='rgba(255,255,255,.08)';this.style.color='#fff'"
                        onmouseout="this.style.background='rgba(255,255,255,.03)';this.style.color='rgba(255,255,255,.6)'">
                        <span style="display:flex;align-items:center;justify-content:center;gap:8px">
                            <span class="material-icons" style="font-size:18px">arrow_back</span>
                            <span>Kembali</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div><!-- FOOTER -->
    <footer
        style="position:relative;z-index:1;width:100%;text-align:center;padding:24px 20px;border-top:1px solid rgba(255,255,255,.08);margin-top:auto">
        <p style="color:rgba(255,255,255,.4);font-size:12px">© Tim IT SMA Negeri 1 Sooko</p>
    </footer><!-- RESULT MODAL -->
    <div class="modal-overlay" id="resultModal">
        <div id="confettiContainer" class="confetti-container"></div>
        <div class="glass-strong"
            style="padding:0;width:100%;max-width:420px;position:relative;z-index:102;max-height:90%;overflow-y:auto; border-radius: 20px; border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);"
            id="resultContent"></div>
    </div>
    <script>
        // Removed hardcoded dummy data
        let currentStudent = null;
        let currentStep = 1;

        // Bubbles
        function createBubbles() {
            const c = document.getElementById('bubbles');
            const colors = ['#40A69F', '#4F46B4', '#FF576F', '#FDB402', '#A8E4DB'];
            for (let i = 0; i < 15; i++) {
                const b = document.createElement('div');
                b.className = 'bubble';
                const size = Math.random() * 60 + 20;
                b.style.cssText = `width:${size}px;height:${size}px;left:${Math.random() * 100}%;background:${colors[i % colors.length]};animation-duration:${Math.random() * 12 + 10}s;animation-delay:${Math.random() * 8}s`;
                c.appendChild(b);
            }
        }
        createBubbles();

        function showStep(n) {
            document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
            document.getElementById('step' + n).classList.add('active');
            currentStep = n;
            lucide.createIcons();
        }

        function updateCountdown() {
            // Ensure timezone compatibility by appending +07:00 for WIB
            const targetStr = '<?= str_replace(' ', 'T', $countdownDate) ?>+07:00';
            const targetDate = new Date(targetStr).getTime();
            const now = new Date().getTime();
            const difference = targetDate - now;

            if (difference > 0) {
                const days = Math.floor(difference / (1000 * 60 * 60 * 24));
                const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((difference % (1000 * 60)) / 1000);

                document.getElementById('days').textContent = days.toString().padStart(2, '0');
                document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
                document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
                document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');

                // Show countdown, hide NISN form
                document.getElementById('countdownContainer').style.display = 'block';
                document.getElementById('nisnContainer').style.display = 'none';
            } else {
                document.querySelectorAll('.countdown-number').forEach(el => el.textContent = '00');

                // Hide countdown, show NISN form
                document.getElementById('countdownContainer').style.display = 'none';
                document.getElementById('nisnContainer').style.display = 'block';
            }
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);

        function goBack(steps) { showStep(currentStep - steps) }

        async function checkNISN() {
            const v = document.getElementById('nisnInput').value.trim();
            const err = document.getElementById('nisnError');
            if (!v) { err.textContent = 'NISN tidak boleh kosong'; err.style.display = 'block'; document.getElementById('nisnInput').parentElement.classList.add('shake'); setTimeout(() => document.getElementById('nisnInput').parentElement.classList.remove('shake'), 500); return }

            try {
                const formData = new FormData();
                formData.append('nisn', v);
                formData.append('action', 'check_nisn');

                const response = await fetch('check_student.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    err.style.display = 'none';
                    currentStudent = result.student;
                    showStep(2);
                    // Reset date inputs
                    document.getElementById('birthDay').value = '';
                    document.getElementById('birthMonth').value = '';
                    document.getElementById('birthYear').value = '';
                    document.getElementById('passError').style.display = 'none';
                } else {
                    err.textContent = result.message;
                    err.style.display = 'block';
                }
            } catch (e) {
                err.textContent = 'Gagal menghubungi server.';
                err.style.display = 'block';
            }
        }

        async function checkPassword() {
            const d = document.getElementById('birthDay').value;
            const m = document.getElementById('birthMonth').value;
            const y = document.getElementById('birthYear').value;
            const err = document.getElementById('passError');

            if (!d || !m || !y) { 
                err.textContent = 'Harap lengkapi tanggal lahir Anda'; 
                err.style.display = 'block'; 
                return; 
            }

            // Format: DD Bulan YYYY (e.g. 02 Desember 2007)
            const paddedDay = d.toString().padStart(2, '0');
            const passwordValue = `${paddedDay} ${m} ${y}`;

            try {
                const formData = new FormData();
                formData.append('nisn', currentStudent.nisn);
                formData.append('password', passwordValue);
                formData.append('action', 'check_password');

                const response = await fetch('check_student.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    err.style.display = 'none';
                    currentStudent = result.student;
                    renderStudentInfo();
                    showStep(3);
                } else {
                    err.textContent = result.message;
                    err.style.display = 'block';
                }
            } catch (e) {
                err.textContent = 'Gagal menghubungi server.';
                err.style.display = 'block';
            }
        }

        function renderStudentInfo() {
            const jkText = currentStudent.jk === 'L' ? 'Laki-laki' : 'Perempuan';
            const jkIcon = currentStudent.jk === 'L' ? 'boy' : 'girl';

            document.getElementById('studentInfo').innerHTML = `
                <div style="background: linear-gradient(135deg, rgba(64,166,159,0.1), rgba(79,70,180,0.1)); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 24px; position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -10px; right: -10px; opacity: 0.05;">
                        <span class="material-icons" style="font-size: 150px;">badge</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px; border-bottom: 1px dashed rgba(255,255,255,0.1); padding-bottom: 16px;">
                        <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid rgba(255,255,255,0.2);">
                            <span class="material-icons" style="font-size: 32px; color: #A8E4DB;">${jkIcon}</span>
                        </div>
                        <div>
                            <p style="font-size: 11px; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px;">Nama Lengkap</p>
                            <p style="font-size: 16px; font-weight: 700; color: #fff;">${currentStudent.nama}</p>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <p style="font-size: 11px; color: rgba(255,255,255,0.4); text-transform: uppercase;">NISN</p>
                            <p style="font-size: 14px; font-weight: 600; color: #fff;">${currentStudent.nisn}</p>
                        </div>
                        <div>
                            <p style="font-size: 11px; color: rgba(255,255,255,0.4); text-transform: uppercase;">Rombel</p>
                            <p style="font-size: 14px; font-weight: 600; color: #fff;">${currentStudent.rombel}</p>
                        </div>
                        <div style="grid-column: span 2;">
                            <p style="font-size: 11px; color: rgba(255,255,255,0.4); text-transform: uppercase;">Jenis Kelamin</p>
                            <p style="font-size: 14px; font-weight: 600; color: #fff;">${jkText}</p>
                        </div>
                    </div>
                </div>
            `;
        }

        function playSound(isLulus) {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioCtx.createOscillator();
                const gainNode = audioCtx.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);

                if (isLulus) {
                    // Success: Happy arpeggio
                    oscillator.type = 'sine';
                    const now = audioCtx.currentTime;
                    oscillator.frequency.setValueAtTime(523.25, now); // C5
                    oscillator.frequency.setValueAtTime(659.25, now + 0.1); // E5
                    oscillator.frequency.setValueAtTime(783.99, now + 0.2); // G5
                    oscillator.frequency.setValueAtTime(1046.50, now + 0.3); // C6

                    gainNode.gain.setValueAtTime(0, now);
                    gainNode.gain.linearRampToValueAtTime(0.5, now + 0.05);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, now + 0.6);

                    oscillator.start(now);
                    oscillator.stop(now + 0.6);
                } else {
                    // Failure: Sad descending tone
                    oscillator.type = 'triangle';
                    const now = audioCtx.currentTime;
                    oscillator.frequency.setValueAtTime(300, now);
                    oscillator.frequency.exponentialRampToValueAtTime(150, now + 1);

                    gainNode.gain.setValueAtTime(0, now);
                    gainNode.gain.linearRampToValueAtTime(0.5, now + 0.1);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, now + 1);

                    oscillator.start(now);
                    oscillator.stop(now + 1);
                }
            } catch (e) {
                console.log("Audio not supported or blocked");
            }
        }

        function showTransitionScreen(isLulus, callback) {
            playSound(isLulus);
            const overlay = document.createElement('div');

            if (isLulus) {
                overlay.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:#000;z-index:9999;display:flex;align-items:center;justify-content:center;flex-direction:column;opacity:0;transition:opacity 0.5s ease; overflow:hidden; perspective: 600px;';
                overlay.innerHTML = `
                    <style>
                        @keyframes starWarsCrawl {
                            0% { transform: rotateX(25deg) translateY(100vh); opacity: 0; }
                            10% { opacity: 1; }
                            85% { opacity: 1; }
                            100% { transform: rotateX(25deg) translateY(-100vh); opacity: 0; }
                        }
                        @keyframes twinkle {
                            0%, 100% { opacity: 0.3; }
                            50% { opacity: 0.8; }
                        }
                    </style>
                    <div style="position:absolute;top:0;left:0;width:100%;height:100%;background:radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 50px 50px; animation: twinkle 3s infinite;"></div>
                    <div style="font-size: 36px; font-weight: 900; color: #FFE81F; text-align: center; text-transform: uppercase; letter-spacing: 6px; animation: starWarsCrawl 5.5s linear forwards; max-width: 90%; line-height: 1.4; text-shadow: 0 0 15px rgba(255,232,31,0.6); position: relative; z-index: 10;">
                        SELAMAT!<br><br>ANDA TELAH DINYATAKAN<br>
                        <span style="font-size: 80px; display: block; margin-top: 30px; letter-spacing: 10px;">LULUS</span>
                    </div>
                `;

                document.body.appendChild(overlay);

                requestAnimationFrame(() => {
                    overlay.style.opacity = '1';
                });

                setTimeout(() => {
                    overlay.style.opacity = '0';
                    setTimeout(() => {
                        overlay.remove();
                        callback();
                    }, 500);
                }, 5500); // 5.5 seconds crawl

            } else {
                overlay.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:#0f172a;z-index:9999;display:flex;align-items:center;justify-content:center;flex-direction:column;opacity:0;transition:opacity 0.5s ease;';
                overlay.innerHTML = `
                    <style>@keyframes bouncePop { 0%, 100% { transform: translateY(0) scale(1); } 50% { transform: translateY(-20px) scale(1.1); } }</style>
                    <span class="material-icons" style="font-size: 80px; color: #DC2527; margin-bottom: 24px; animation: bouncePop 1s ease-in-out infinite;">info</span>
                    <h2 style="color: white; font-size: 32px; font-weight: 800; text-align: center; padding: 0 20px; line-height: 1.4;">Mohon Maaf, Anda dinyatakan Tidak Lulus.</h2>
                `;

                document.body.appendChild(overlay);

                requestAnimationFrame(() => {
                    overlay.style.opacity = '1';
                });

                setTimeout(() => {
                    overlay.style.opacity = '0';
                    setTimeout(() => {
                        overlay.remove();
                        callback();
                    }, 500);
                }, 2500);
            }
        }

        function showResult() {
            const modal = document.getElementById('resultModal');
            const content = document.getElementById('resultContent');

            // Read safely from PHP and ensure newlines don't break JS
            const dbSklInfo = <?= json_encode($settings['skl_info'] ?? 'Pengambilan SKL dapat dilakukan pada 5 Mei 2026') ?>;
            // If window.currentConfig is populated by the SDK, use it, otherwise fallback to DB
            const sklInfoText = (window.currentConfig && window.currentConfig.skl_info) ? window.currentConfig.skl_info : dbSklInfo;
            // Convert newlines to <br> for HTML rendering
            const formattedSklInfo = sklInfoText.replace(/\n/g, '<br>');

            // Support both boolean and string/number from DB
            const isLulus = currentStudent.lulus === true || currentStudent.lulus == 1 || currentStudent.lulus === '1';

            showTransitionScreen(isLulus, () => {
                if (isLulus) {
                    content.innerHTML = `
      <div class="text-center" style="position: relative;">
        <!-- Header Lulus -->
        <div style="background: linear-gradient(135deg, #40A69F, #2B7A6D); padding: 32px 24px; position: relative; overflow: hidden; border-bottom: 2px dashed rgba(255,255,255,0.2);">
            <div style="position: absolute; top: -10px; right: -10px; font-size: 120px; opacity: 0.1; transform: rotate(15deg);">🎓</div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <img src="<?= $schoolLogo ?>" style="height: 40px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));" alt="Logo">
                <span style="background: rgba(255,255,255,0.2); backdrop-filter: blur(4px); padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; color: #fff; letter-spacing: 1px;">SURAT KETERANGAN LULUS</span>
            </div>
            <h2 style="font-size:32px;font-weight:800;color:#fff;margin-bottom:4px; letter-spacing: 2px; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">LULUS</h2>
            <p style="color:rgba(255,255,255,.9);font-size:13px;text-transform: uppercase; letter-spacing: 1px;">Tahun Pelajaran <?= $academicYear ?></p>
            
            <!-- Left/Right Ticket Cutouts -->
            <div style="position: absolute; bottom: -12px; left: -12px; width: 24px; height: 24px; background: #0f172a; border-radius: 50%; border-top: 1px solid rgba(255,255,255,0.2); border-right: 1px solid rgba(255,255,255,0.2);"></div>
            <div style="position: absolute; bottom: -12px; right: -12px; width: 24px; height: 24px; background: #0f172a; border-radius: 50%; border-top: 1px solid rgba(255,255,255,0.2); border-left: 1px solid rgba(255,255,255,0.2);"></div>
        </div>

        <!-- Body Detail -->
        <div style="background: rgba(255,255,255,0.03); padding: 28px 24px;">
            <div style="text-align: left; margin-bottom: 20px;">
                <p style="font-size: 11px; color: rgba(255,255,255,0.4); text-transform: uppercase; margin-bottom: 2px;">Nama Lengkap</p>
                <p style="font-size: 18px; font-weight: 700; color: #fff;">${currentStudent.nama}</p>
            </div>
            <div style="display:flex; justify-content: space-between; text-align: left; margin-bottom: 24px; background: rgba(0,0,0,0.2); padding: 16px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="width: 50%;">
                    <p style="font-size: 11px; color: rgba(255,255,255,0.4); text-transform: uppercase; margin-bottom: 2px;">NISN</p>
                    <p style="font-size: 15px; font-weight: 600; color: #A8E4DB;">${currentStudent.nisn}</p>
                </div>
                <div style="width: 50%;">
                    <p style="font-size: 11px; color: rgba(255,255,255,0.4); text-transform: uppercase; margin-bottom: 2px;">Rombel</p>
                    <p style="font-size: 15px; font-weight: 600; color: #fff;">${currentStudent.rombel}</p>
                </div>
            </div>
            
            <div style="display: flex; align-items: center; gap: 16px; background: rgba(253, 180, 2, 0.1); border: 1px solid rgba(253, 180, 2, 0.2); border-radius: 12px; padding: 16px; text-align: left;">
                <span class="material-icons" style="font-size: 24px; color: #FDB402;">celebration</span>
                <div>
                    <p style="font-size: 13px; font-weight: 600; color: #FEDD8A; margin-bottom: 4px;">Informasi</p>
                    <p style="font-size: 12px; color: rgba(255,255,255,0.7); line-height: 1.5;">${formattedSklInfo}</p>
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div style="background: rgba(0,0,0,0.3); padding: 20px 24px; display: flex; gap: 12px;">
            <button onclick="closeModal()" class="btn-primary" style="flex:1;">
                Selesai
            </button>
        </div>
      </div>`;
                    launchCelebration();
                } else {
                    content.innerHTML = `
      <div class="text-center">
        <!-- Header Tidak Lulus -->
        <div style="background: linear-gradient(135deg, #DC2527, #9B1A1C); padding: 32px 24px; position: relative; overflow: hidden; border-bottom: 2px dashed rgba(255,255,255,0.2);">
            <div style="position: absolute; top: -10px; right: -10px; font-size: 120px; opacity: 0.1; transform: rotate(-10deg);">📋</div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <img src="<?= $schoolLogo ?>" style="height: 40px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1)); grayscale(100%); opacity: 0.8;" alt="Logo">
                <span style="background: rgba(255,255,255,0.2); backdrop-filter: blur(4px); padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; color: #fff; letter-spacing: 1px;">PENGUMUMAN HASIL</span>
            </div>
            <h2 style="font-size:26px;font-weight:800;color:#fff;margin-bottom:4px; letter-spacing: 1px; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">TIDAK LULUS</h2>
            <p style="color:rgba(255,255,255,.8);font-size:13px;text-transform: uppercase; letter-spacing: 1px;">Tahun Pelajaran <?= $academicYear ?></p>
            
            <!-- Left/Right Ticket Cutouts -->
            <div style="position: absolute; bottom: -12px; left: -12px; width: 24px; height: 24px; background: #0f172a; border-radius: 50%; border-top: 1px solid rgba(255,255,255,0.2); border-right: 1px solid rgba(255,255,255,0.2);"></div>
            <div style="position: absolute; bottom: -12px; right: -12px; width: 24px; height: 24px; background: #0f172a; border-radius: 50%; border-top: 1px solid rgba(255,255,255,0.2); border-left: 1px solid rgba(255,255,255,0.2);"></div>
        </div>

        <!-- Body Detail -->
        <div style="background: rgba(255,255,255,0.03); padding: 28px 24px;">
            <div style="margin-bottom: 24px;">
                <p style="font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 4px;">${currentStudent.nama}</p>
                <p style="font-size: 14px; color: rgba(255,255,255,0.5);">NISN: ${currentStudent.nisn} <span style="margin:0 8px">•</span> Rombel: ${currentStudent.rombel}</p>
            </div>
            
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; text-align: left; margin-bottom: 12px;">
                <p style="font-size: 14px; font-weight: 600; color: rgba(255,255,255,0.9); margin-bottom: 12px;">Tetap Semangat! 💪</p>
                <p style="font-size: 13px; color: rgba(255,255,255,0.6); line-height: 1.6; margin-bottom: 16px;">Hasil ini bukanlah akhir dari segalanya. Kesuksesan memiliki banyak jalan dan waktu untuk diraih.</p>
                
                <div style="display: flex; align-items: flex-start; gap: 12px; background: rgba(0,0,0,0.2); padding: 12px; border-radius: 8px;">
                    <span class="material-icons" style="font-size: 18px; color: #FCA2A4; margin-top: 2px;">info</span>
                    <p style="font-size: 12px; color: rgba(255,255,255,0.5); line-height: 1.5;">
                        Silakan menghubungi Wali Rombel atau bagian Administrasi sekolah untuk informasi lebih lanjut mengenai hasil kelulusan Anda.
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div style="background: rgba(0,0,0,0.3); padding: 20px 24px;">
            <button onclick="closeModal()" class="btn-secondary" style="width: 100%; padding: 14px;">
                <span style="display:flex; align-items:center; justify-content:center; gap:8px"><span class="material-icons" style="font-size:18px">arrow_back</span> Kembali ke Awal</span>
            </button>
        </div>
      </div>`;
                }
                modal.classList.add('show');
            });
        }

        function launchCelebration() {
            const c = document.getElementById('confettiContainer');
            c.innerHTML = '';
            const colors = ['#40A69F', '#4F46B4', '#FF576F', '#FDB402', '#A8E4DB', '#FEDD8A', '#FF8EA6', '#A5A0E2'];
            // Confetti
            for (let i = 0; i < 80; i++) {
                const p = document.createElement('div');
                p.className = 'confetti';
                const s = Math.random() * 8 + 6;
                const shapes = ['50%', '0', '30%'];
                p.style.cssText = `left:${Math.random() * 100}%;width:${s}px;height:${s * 1.4}px;background:${colors[i % colors.length]};border-radius:${shapes[i % 3]};animation-duration:${Math.random() * 2 + 2}s;animation-delay:${Math.random() * 1.5}s`;
                c.appendChild(p);
            }
            // Balloons
            const balloonEmojis = ['🎈', '🎊', '🎉', '⭐', '🌟', '✨'];
            for (let i = 0; i < 12; i++) {
                const b = document.createElement('div');
                b.className = 'balloon';
                b.textContent = balloonEmojis[i % balloonEmojis.length];
                b.style.cssText = `left:${Math.random() * 90 + 5}%;font-size:${Math.random() * 20 + 28}px;animation-duration:${Math.random() * 3 + 3}s;animation-delay:${Math.random() * 2}s`;
                c.appendChild(b);
            }
            // Second wave
            setTimeout(() => {
                for (let i = 0; i < 40; i++) {
                    const p = document.createElement('div');
                    p.className = 'confetti';
                    const s = Math.random() * 6 + 4;
                    p.style.cssText = `left:${Math.random() * 100}%;width:${s}px;height:${s * 1.2}px;background:${colors[i % colors.length]};border-radius:50%;animation-duration:${Math.random() * 2 + 2.5}s;animation-delay:${Math.random() * .5}s`;
                    c.appendChild(p);
                }
            }, 1500);
        }

        function closeModal() {
            document.getElementById('resultModal').classList.remove('show');
            document.getElementById('confettiContainer').innerHTML = '';
            showStep(1);
            document.getElementById('nisnInput').value = '';
            currentStudent = null;
        }
        const defaultConfig = {
            school_name: 'SMA Negeri 1 Sooko',
            welcome_text: 'Selamat Datang di Portal Pengumuman Kelulusan',
            meta_description: 'Cek hasil kelulusan siswa SMA Negeri 1 Sooko Tahun Pelajaran <?= $academicYear ?> secara online.',
            skl_info: 'Pengambilan SKL dapat dilakukan pada 5 Mei 2026',
            background_color: '#0f172a',
            surface_color: 'rgba(255,255,255,0.08)',
            text_color: '#ffffff',
            primary_action_color: '#40A69F',
            secondary_action_color: '#4F46B4',
            font_family: 'Plus Jakarta Sans',
            font_size: 15
        };

        function applyConfig(config) {
            const el = (id) => document.getElementById(id);
            const sn = config.school_name || defaultConfig.school_name;
            const wt = config.welcome_text || defaultConfig.welcome_text;
            const md = config.meta_description || defaultConfig.meta_description;

            if (el('schoolNameDisplay')) el('schoolNameDisplay').textContent = sn;
            if (el('welcomeTextDisplay')) el('welcomeTextDisplay').textContent = wt;

            // Update Meta Tags
            document.title = `Pengumuman Kelulusan - ${sn}`;
            const descMeta = document.querySelector('meta[name="description"]');
            if (descMeta) descMeta.setAttribute('content', md);
            const ogTitle = document.querySelector('meta[property="og:title"]');
            if (ogTitle) ogTitle.setAttribute('content', `Pengumuman Kelulusan - ${sn}`);
            const ogDesc = document.querySelector('meta[property="og:description"]');
            if (ogDesc) ogDesc.setAttribute('content', md);

            const ff = config.font_family || defaultConfig.font_family;
            const bs = config.font_size || defaultConfig.font_size;
            document.body.style.fontFamily = `${ff}, sans-serif`;
            document.body.style.fontSize = bs + 'px';
        }

        window.elementSdk.init({
            defaultConfig,
            onConfigChange: async (config) => { applyConfig(config); },
            mapToCapabilities: (config) => ({
                recolorables: [
                    { get: () => config.background_color || defaultConfig.background_color, set: v => { config.background_color = v; window.elementSdk.setConfig({ background_color: v }) } },
                    { get: () => config.surface_color || defaultConfig.surface_color, set: v => { config.surface_color = v; window.elementSdk.setConfig({ surface_color: v }) } },
                    { get: () => config.text_color || defaultConfig.text_color, set: v => { config.text_color = v; window.elementSdk.setConfig({ text_color: v }) } },
                    { get: () => config.primary_action_color || defaultConfig.primary_action_color, set: v => { config.primary_action_color = v; window.elementSdk.setConfig({ primary_action_color: v }) } },
                    { get: () => config.secondary_action_color || defaultConfig.secondary_action_color, set: v => { config.secondary_action_color = v; window.elementSdk.setConfig({ secondary_action_color: v }) } }
                ],
                borderables: [],
                fontEditable: { get: () => config.font_family || defaultConfig.font_family, set: v => { config.font_family = v; window.elementSdk.setConfig({ font_family: v }) } },
                fontSizeable: { get: () => config.font_size || defaultConfig.font_size, set: v => { config.font_size = v; window.elementSdk.setConfig({ font_size: v }) } }
            }),
            mapToEditPanelValues: (config) => new Map([
                ['school_name', config.school_name || defaultConfig.school_name],
                ['welcome_text', config.welcome_text || defaultConfig.welcome_text],
                ['meta_description', config.meta_description || defaultConfig.meta_description],
                ['skl_info', config.skl_info || defaultConfig.skl_info]
            ])
        });

        lucide.createIcons();
    </script>
    <script>(function () { function c() { var b = a.contentDocument || a.contentWindow.document; if (b) { var d = b.createElement('script'); d.innerHTML = "window.__CF$cv$params={r:'9f450039f73ab5a8',t:'MTc3NzUzNTkwMi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);"; b.getElementsByTagName('head')[0].appendChild(d) } } if (document.body) { var a = document.createElement('iframe'); a.height = 1; a.width = 1; a.style.position = 'absolute'; a.style.top = 0; a.style.left = 0; a.style.border = 'none'; a.style.visibility = 'hidden'; document.body.appendChild(a); if ('loading' !== document.readyState) c(); else if (window.addEventListener) document.addEventListener('DOMContentLoaded', c); else { var e = document.onreadystatechange || function () { }; document.onreadystatechange = function (b) { e(b); 'loading' !== document.readyState && (document.onreadystatechange = e, c()) } } } })();</script>
</body>

</html>