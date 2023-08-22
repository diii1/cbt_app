<?php

namespace App\Http\Controllers\Excel;

use App\Http\Controllers\Controller;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use App\Services\Exam\ExamService;


class ResultExcelController extends Controller
{
    private $service;

    public function __construct(ExamService $service)
    {
        $this->service = $service;
    }

    private function applyStyling(Worksheet $sheet)
    {
        // Styling sheet for title
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:F1')->getAlignment()->setWrapText(true);

        // Styling sheet for header
        $sheet->getStyle('A3:F3')->getFont()->setBold(true);
        $sheet->getStyle('A3:F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3:F3')->getAlignment()->setWrapText(true);
        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Styling sheet for text
        $sheet->getStyle('B4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('C4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('F4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
    }

    public function export($exam_id)
    {
        $exam = $this->service->getExamByID($exam_id);

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the column titles
        $sheet->setCellValue('A1', 'Export Data Hasil Ujian : '. $exam->title);

        // Set the column headers
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'NIS');
        $sheet->setCellValue('C3', 'NISN');
        $sheet->setCellValue('D3', 'Nama Lengkap');
        $sheet->setCellValue('E3', 'Kelas');
        $sheet->setCellValue('F3', 'Nilai');

        // Merge the cells
        $sheet->mergeCells('A1:F1');

        $this->applyStyling($sheet);

        // Set the data rows
        $data = ExamResult::where('exam_id', $exam_id)->with('student')->get();
        $row = 4;
        foreach($data as $key => $item){
            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, $item->student->nis);
            $sheet->setCellValue('C' . $row, $item->student->nisn);
            $sheet->setCellValue('D' . $row, $item->student->user->name);
            $sheet->setCellValue('E' . $row, $item->student->class->name);
            $sheet->setCellValue('F' . $row, $item->score);

            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
        }

        // Create a new Excel writer
        $writer = new Xlsx($spreadsheet);

        // Set the appropriate headers for the response
        $filename = 'Export Data Hasil Ujian - ' . $exam->title . '.xlsx';
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        // Return the Excel file as a download
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, $headers);
    }
}
