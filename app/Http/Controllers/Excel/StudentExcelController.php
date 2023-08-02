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
use App\Models\Classes;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\Master\StudentService;
use Carbon\Carbon;
use App\Helpers\FileHelper;
use App\Types\FileMetadata;
use Exception;
use Illuminate\Support\Facades\Crypt;

class StudentExcelController extends Controller
{
    private $service;
    public function __construct(StudentService $service)
    {
        $this->service = $service;
    }

    public function storeFile(mixed $file): FileMetadata | Exception
    {
        return FileHelper::storeFile($file, "uploads/import/students", "public");
    }

    public function template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the column titles
        $sheet->setCellValue('A1', 'Import Data Siswa');

        // Set the column headers
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Nama Lengkap');
        $sheet->setCellValue('C3', 'Email');
        $sheet->setCellValue('D3', 'Password');
        $sheet->setCellValue('E3', 'NIS');
        $sheet->setCellValue('F3', 'NISN');
        $sheet->setCellValue('G3', 'Tanggal Lahir');
        $sheet->setCellValue('H3', 'Kelas');
        $sheet->setCellValue('I3', 'Alamat');

        // default value for date
        $sheet->setCellValue('G4', '01-01-2000');

        // Merge the cells
        $sheet->mergeCells('A1:I1');

        $this->applyStyling($sheet);

        // Set the dropdown values and selected item
        $dropdownValues = Classes::pluck('name')->toArray();
        $selectedItem = $dropdownValues[0]; // Replace with your desired selected item

        // Set the data validation with dropdown list
        $validation = $sheet->getCell('H4')->getDataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $validation->setFormula1('"'.implode(',', $dropdownValues).'"');
        $validation->setShowDropDown(true);

        // Set the selected item as the default value in the dropdown
        $selectedCell = $sheet->getCell('H4');
        $selectedCell->setValue($selectedItem);

        // Apply formatting to the dropdown cell
        $selectedCell->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Create a new Excel writer
        $writer = new Xlsx($spreadsheet);

        // Set the appropriate headers for the response
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="template_siswa.xlsx"',
        ];

        $filename = 'template_siswa.xlsx';
        // Return the template file as a download
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, $headers);
    }

    public function applyStyling(Worksheet $sheet)
    {
        // Styling sheet for title
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:I1')->getAlignment()->setWrapText(true);

        // Styling sheet for header
        $sheet->getStyle('A3:I3')->getFont()->setBold(true);
        $sheet->getStyle('A3:I3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3:I3')->getAlignment()->setWrapText(true);
        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Styling sheet for text
        $sheet->getStyle('E4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('F4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('G4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
    }

    public function create()
    {
        $data['title'] = 'Import Data Siswa';
        $data['action'] = route('api.student.import');
        return view('pages.master.student.upload', ['data' => $data]);
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
                'password' => Hash::make($value[3]),
            ]);
            $user->email_verified_at = now();
            $user->remember_token = Str::random(10);
            $user->assignRole('student');
            $user->save();

            $student = DB::table('students')->insert([
                'user_id' => $user->id,
                'nis' => $value[4],
                'nisn' => $value[5],
                'birth_date' => Carbon::parse($value[6]),
                'class_id' => Classes::where('name', $value[7])->first()->id,
                'address' => $value[8],
                'password' => Crypt::encryptString($value[3]),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Import data siswa berhasil.'
        ], 200);
    }

    public function export()
    {
        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the column titles
        $sheet->setCellValue('A1', 'Export Data Siswa');

        // Set the column headers
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Nama Lengkap');
        $sheet->setCellValue('C3', 'Email');
        $sheet->setCellValue('D3', 'Password');
        $sheet->setCellValue('E3', 'NIS');
        $sheet->setCellValue('F3', 'NISN');
        $sheet->setCellValue('G3', 'Tanggal Lahir');
        $sheet->setCellValue('H3', 'Kelas');
        $sheet->setCellValue('I3', 'Alamat');

        // Merge the cells
        $sheet->mergeCells('A1:I1');

        $this->applyStyling($sheet);

        $data = $this->service->getStudents();
        $row = 4;
        foreach ($data as $key => $item) {
            $sheet->setCellValue('A' . $row, ++$key);
            $sheet->setCellValue('B' . $row, $item->name);
            $sheet->setCellValue('C' . $row, $item->email);
            $sheet->setCellValue('D' . $row, Crypt::decryptString($item->password));
            $sheet->setCellValue('E' . $row, $item->nis);
            $sheet->setCellValue('F' . $row, $item->nisn);
            $sheet->setCellValue('G' . $row, Carbon::parse($item->birth_date)->format('d-m-Y'));
            // $sheet->setCellValue('H' . $row, $item->class->name);
            $sheet->setCellValue('I' . $row, $item->address);

            // Set the dropdown values for column H
            $dropdownValues = Classes::pluck('name')->toArray();
            $selectedItem = $item->class;

            // Set data validation with dropdown list for column H
            $validation = $sheet->getCell('H' . $row)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setFormula1('"' . implode(',', $dropdownValues) . '"');
            $validation->setShowDropDown(true);

            // Set the selected item as the default value in the dropdown for column H
            $selectedCell = $sheet->getCell('H' . $row);
            $selectedCell->setValue($selectedItem);

            // Apply formatting to the dropdown cell
            $selectedCell->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

            $row++;
        }

        // Create a new Excel writer
        $writer = new Xlsx($spreadsheet);

        // Set the appropriate headers for the response
        $filename = Carbon::now()->format('Y-m-d').'-export-siswa.xlsx';
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
