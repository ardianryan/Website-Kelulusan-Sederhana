<?php
require_once __DIR__ . '/config.php';
checkAuth();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set Header
$headers = ['No', 'Nama', 'NISN', 'NIPD', 'Tempat Lahir', 'Tanggal Lahir', 'Rombel Saat Ini', 'JK', 'Keterangan'];
foreach ($headers as $index => $header) {
    $col = Coordinate::stringFromColumnIndex($index + 1);
    $sheet->setCellValue($col . '1', $header);
}

// Add Example Data
$examples = [
    ['1', 'Ahmad Rizky Pratama', '0012345001', '12345', 'Sidoarjo', '2005-05-05', 'XII IPA 1', 'L', 'LULUS'],
    ['2', 'Siti Nurhaliza', '0012345002', '12346', 'Mojokerto', '2005-06-12', 'XII IPA 2', 'P', 'LULUS'],
];

foreach ($examples as $rowIndex => $row) {
    foreach ($row as $colIndex => $value) {
        $col = Coordinate::stringFromColumnIndex($colIndex + 1);
        $sheet->setCellValue($col . ($rowIndex + 2), $value);
    }
}

// Styling
$sheet->getStyle('A1:I1')->getFont()->setBold(true);
foreach (range('A', 'I') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Force Download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Template_Import_Siswa.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
