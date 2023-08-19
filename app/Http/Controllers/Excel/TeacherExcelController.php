<?php

namespace App\Http\Controllers\Excel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\Master\TeacherService;
use Carbon\Carbon;
use App\Helpers\FileHelper;
use App\Types\FileMetadata;
use Exception;

class TeacherExcelController extends Controller
{
    private $service;
    public function __construct(TeacherService $service)
    {
        $this->service = $service;
    }

    public function storeFile(mixed $file): FileMetadata | Exception
    {
        return FileHelper::storeFile($file, "uploads/import/teachers", "public");
    }

    public function template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the column titles
        $sheet->setCellValue('A1', 'Import Data Guru');

        // Set the column headers
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Nama Lengkap');
        $sheet->setCellValue('C3', 'Email');
        $sheet->setCellValue('D3', 'NIP');
        $sheet->setCellValue('E3', 'No. Handphone');
        $sheet->setCellValue('F3', 'Mata Pelajaran');
        $sheet->setCellValue('G3', 'Alamat');

        // Merge the cells
        $sheet->mergeCells('A1:G1');

        $this->applyStyling($sheet);

        // Set the dropdown values and selected item
        $dropdownValues = Subject::pluck('name')->toArray();
        $selectedItem = $dropdownValues[0]; // Replace with your desired selected item

        // Set the data validation with dropdown list
        $validation = $sheet->getCell('F4')->getDataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $validation->setFormula1('"'.implode(',', $dropdownValues).'"');
        $validation->setShowDropDown(true);

        // Set the selected item as the default value in the dropdown
        $selectedCell = $sheet->getCell('F4');
        $selectedCell->setValue($selectedItem);

        // Apply formatting to the dropdown cell
        $selectedCell->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Create a new Excel writer
        $writer = new Xlsx($spreadsheet);

        // Set the appropriate headers for the response
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="template_guru.xlsx"',
        ];

        $filename = 'template_guru.xlsx';
        // Return the template file as a download
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, $headers);
    }

    private function applyStyling(Worksheet $sheet)
    {
        // Styling sheet for title
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:G1')->getAlignment()->setWrapText(true);

        // Styling sheet for header
        $sheet->getStyle('A3:G3')->getFont()->setBold(true);
        $sheet->getStyle('A3:G3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3:G3')->getAlignment()->setWrapText(true);
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Styling sheet for text
        $sheet->getStyle('D4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('E4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
    }

    public function create()
    {
        $data['title'] = 'Import Data Guru';
        $data['action'] = route('api.teacher.import');
        return view('pages.master.teacher.upload', ['data' => $data]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);
        $file = $request->file('file');
        if ($request->hasFile('file')) {
            $this->storeFile($request->file);
        }

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();

        // Retrieve the data from the sheet
        $data = [];
        foreach ($sheet->getRowIterator() as $row) {
            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue();
            }
            $data[] = $rowData;
        }

        // Remove the header
        array_shift($data);
        array_shift($data);
        array_shift($data);

        foreach ($data as $value) {
            $user = User::create([
                'name' => $value[1],
                'email' => $value[2],
                'password' => Hash::make('12345678'),
            ]);
            $user->email_verified_at = now();
            $user->remember_token = Str::random(10);
            $user->assignRole('teacher');
            $user->save();

            $teacher = DB::table('teachers')->insert([
                'user_id' => $user->id,
                'subject_id' => Subject::where('name', $value[5])->first()->id,
                'nip' => (string)$value[3],
                'address' => $value[6],
                'phone' => (string)$value[4],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Import data guru berhasil.'
        ], 200);
    }

    public function export()
    {
        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the column titles
        $sheet->setCellValue('A1', 'Export Data Guru');

        // Set the column headers
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Nama Lengkap');
        $sheet->setCellValue('C3', 'Email');
        $sheet->setCellValue('D3', 'NIP');
        $sheet->setCellValue('E3', 'No. Handphone');
        $sheet->setCellValue('F3', 'Mata Pelajaran');
        $sheet->setCellValue('G3', 'Alamat');

        // Merge the cells
        $sheet->mergeCells('A1:G1');

        $this->applyStyling($sheet);

        // Set the data rows
        $data = $this->service->getTeachers();
        $row = 4;
        foreach ($data as $key => $item) {
            $sheet->setCellValue('A' . $row, ++$key);
            $sheet->setCellValue('B' . $row, $item->name);
            $sheet->setCellValue('C' . $row, $item->email);
            $sheet->setCellValue('D' . $row, $item->nip);
            $sheet->setCellValue('E' . $row, $item->phone);
            $sheet->setCellValue('G' . $row, $item->address);
            // $sheet->setCellValue('F' . $row, $item->subject);

            // Set the dropdown values for column F
            $dropdownValues = Subject::pluck('name')->toArray();
            $selectedItem = $item->subject;

            // Set data validation with dropdown list for column F
            $validation = $sheet->getCell('F' . $row)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setFormula1('"'.implode(',', $dropdownValues).'"');
            $validation->setShowDropDown(true);

            // Set the selected item as the default value in the dropdown for column F
            $selectedCell = $sheet->getCell('F' . $row);
            $selectedCell->setValue($selectedItem);

            // Apply formatting to the dropdown cell
            $selectedCell->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
            $sheet->getStyle('E' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

            $row++;
        }

        // Create a new Excel writer
        $writer = new Xlsx($spreadsheet);

        // Set the appropriate headers for the response
        $filename = Carbon::now()->format('Y-m-d').'-export-guru.xlsx';
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
