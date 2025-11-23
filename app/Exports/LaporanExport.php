<?php

namespace App\Exports;

use App\Models\Transaction;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanExport
{
    public static function generate($start = null, $end = null)
    {
        $query = Transaction::with('category');

        if ($start && $end) {
            $query->whereBetween('transaction_date', [$start, $end]);
        }

        $data = $query->orderBy('transaction_date')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = ['Tanggal', 'Kategori', 'Deskripsi', 'Tipe', 'Jumlah'];
        $sheet->fromArray($headers, null, 'A1');

        // Body
        $row = 2;
        foreach ($data as $t) {
            $sheet->setCellValue("A{$row}", $t->transaction_date->format('d-m-Y'));
            $sheet->setCellValue("B{$row}", $t->category->name);
            $sheet->setCellValue("C{$row}", $t->description);
            $sheet->setCellValue("D{$row}", $t->transaction_type);
            $sheet->setCellValue("E{$row}", $t->total_amount);
            $row++;
        }

        $fileName = 'laporan-keuangan.xlsx';
        $filePath = storage_path("app/public/{$fileName}");

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return $filePath;
    }
}
