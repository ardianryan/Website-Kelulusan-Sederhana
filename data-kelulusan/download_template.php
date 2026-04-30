<?php
require_once __DIR__ . '/config.php';
checkAuth();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set Header
$headers = ['NISN', 'Nama Lengkap', 'Jenis Kelamin (L/P)', 'Kelas', 'Password', 'Lulus (1/0)'];
foreach ($headers as $index => $header) {
    $col = Coordinate::stringFromColumnIndex($index + 1);
    $sheet->setCellValue($col . '1', $header);
}

// Add Example Data
$examples = [
    ['0012345001', 'Ahmad Rizky Pratama', 'L', 'XII IPA 1', 'pass001', '1'],
    ['0012345002', 'Siti Nurhaliza', 'P', 'XII IPA 2', 'pass002', '1'],
];

foreach ($examples as $rowIndex => $row) {
    foreach ($row as $colIndex => $value) {
        $col = Coordinate::stringFromColumnIndex($colIndex + 1);
        $sheet->setCellValue($col . ($rowIndex + 2), $value);
    }
}

// Styling
$sheet->getStyle('A1:F1')->getFont()->setBold(true);
foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Force Download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Template_Import_Siswa.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
