<?php
require_once 'data-kelulusan/config.php';

use App\Models\Student;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisn = $_POST['nisn'] ?? '';
    $password = $_POST['password'] ?? '';
    $action = $_POST['action'] ?? '';

    if ($action === 'check_nisn') {
        $student = Student::where('nisn', $nisn)->first(['nisn', 'nama', 'jk', 'kelas']);

        if ($student) {
            echo json_encode(['success' => true, 'student' => $student->toArray()]);
        } else {
            echo json_encode(['success' => false, 'message' => 'NISN tidak ditemukan. Periksa kembali.']);
        }
    } elseif ($action === 'check_password') {
        $student = Student::where('nisn', $nisn)->first();

        if ($student && $password === $student->password) {
            echo json_encode(['success' => true, 'student' => $student->toArray()]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Password salah. Silakan coba lagi.']);
        }
    }
}
?>
