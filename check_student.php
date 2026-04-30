<?php
require_once 'data-kelulusan/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisn = $_POST['nisn'] ?? '';
    $password = $_POST['password'] ?? '';
    $action = $_POST['action'] ?? '';

    if ($action === 'check_nisn') {
        $stmt = $pdo->prepare("SELECT nisn, nama, jk, kelas FROM students WHERE nisn = ?");
        $stmt->execute([$nisn]);
        $student = $stmt->fetch();

        if ($student) {
            echo json_encode(['success' => true, 'student' => $student]);
        } else {
            echo json_encode(['success' => false, 'message' => 'NISN tidak ditemukan. Periksa kembali.']);
        }
    } elseif ($action === 'check_password') {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE nisn = ?");
        $stmt->execute([$nisn]);
        $student = $stmt->fetch();

        if ($student && $password === $student['password']) {
            // Note: In production use password_hash/verify, but per original logic it matches directly
            echo json_encode(['success' => true, 'student' => $student]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Password salah. Silakan coba lagi.']);
        }
    }
}
?>
