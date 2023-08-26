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
        $sheet->setCellValue('C3', 'Jenis Kelamin');
        $sheet->setCellValue('D3', 'Email');
        $sheet->setCellValue('E3', 'Password');
        $sheet->setCellValue('F3', 'NIS');
        $sheet->setCellValue('G3', 'NISN');
        $sheet->setCellValue('H3', 'Tanggal Lahir');
        $sheet->setCellValue('I3', 'Kelas');
        $sheet->setCellValue('J3', 'Alamat');

        // default value for date
        $sheet->setCellValue('H4', '01-01-2000');

        // Merge the cells
        $sheet->mergeCells('A1:J1');

        $this->applyStyling($sheet);

        // Set the dropdown values and selected item
        $dropdownValues = Classes::pluck('name')->toArray();
        $selectedItem = $dropdownValues[0]; // Replace with your desired selected item

        $jenis_kelamin = ['L', 'P'];
        $selectedItemJK = $jenis_kelamin[0];

        $dropdownCell = $sheet->getCell('I4');
        $dropdownCell->setValue($selectedItem);

        $validation = $dropdownCell->getDataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $validation->setFormula1('"'.implode(',', $dropdownValues).'"');
        $validation->setShowDropDown(true);

        $dropdownCell->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $dropdownCellJK = $sheet->getCell('C4');
        $dropdownCellJK->setValue($selectedItemJK);

        $validationJK = $dropdownCellJK->getDataValidation();
        $validationJK->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $validationJK->setFormula1('"'.implode(',', $jenis_kelamin).'"');
        $validationJK->setShowDropDown(true);

        $dropdownCellJK->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // // Set the data validation with dropdown list
        // $validation = $sheet->getCell('I4')->getDataValidation();
        // $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        // $validation->setFormula1('"'.implode(',', $dropdownValues).'"');
        // $validation->setShowDropDown(true);

        // $validationJK = $sheet->getCell('C4')->getDataValidation();
        // $validationJK->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        // $validationJK->setFormula1('"'.implode(',', $jenis_kelamin).'"');
        // $validationJK->setShowDropDown(true);

        // // Set the selected item as the default value in the dropdown
        // $selectedCell = $sheet->getCell('I4');
        // $selectedCell->setValue($selectedItem);

        // $selectedCellJK = $sheet->getCell('C4');
        // $selectedCellJK->setValue($selectedItemJK);

        // // Apply formatting to the dropdown cell
        // $selectedCell->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // $selectedCellJK->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

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
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:J1')->getAlignment()->setWrapText(true);

        // Styling sheet for header
        $sheet->getStyle('A3:J3')->getFont()->setBold(true);
        $sheet->getStyle('A3:J3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3:J3')->getAlignment()->setWrapText(true);
        foreach (range('A', 'J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Styling sheet for text
        $sheet->getStyle('F4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('G4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('H4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
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
                'email' => $value[3],
                'password' => Hash::make($value[4]),
            ]);
            $user->email_verified_at = now();
            $user->remember_token = Str::random(10);
            $user->assignRole('student');
            $user->save();

            $student = DB::table('students')->insert([
                'user_id' => $user->id,
                'nis' => $value[5],
                'nisn' => $value[6],
                'birth_date' => Carbon::parse($value[7]),
                'gender' => $value[2],
                'class_id' => Classes::where('name', $value[8])->first()->id,
                'address' => $value[9],
                'password' => Crypt::encryptString($value[4]),
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
        $sheet->setCellValue('C3', 'Jenis Kelamin');
        $sheet->setCellValue('D3', 'Email');
        $sheet->setCellValue('E3', 'Password');
        $sheet->setCellValue('F3', 'NIS');
        $sheet->setCellValue('G3', 'NISN');
        $sheet->setCellValue('H3', 'Tanggal Lahir');
        $sheet->setCellValue('I3', 'Kelas');
        $sheet->setCellValue('J3', 'Alamat');

        // Merge the cells
        $sheet->mergeCells('A1:J1');

        $this->applyStyling($sheet);

        $data = $this->service->getStudents();
        $row = 4;
        foreach ($data as $key => $item) {
            $sheet->setCellValue('A' . $row, ++$key);
            $sheet->setCellValue('B' . $row, $item->name);
            // $sheet->setCellValue('C' . $row, $item->gender);
            $sheet->setCellValue('D' . $row, $item->email);
            $sheet->setCellValue('E' . $row, Crypt::decryptString($item->password));
            $sheet->setCellValue('F' . $row, $item->nis);
            $sheet->setCellValue('G' . $row, $item->nisn);
            $sheet->setCellValue('H' . $row, Carbon::parse($item->birth_date)->format('d-m-Y'));
            // $sheet->setCellValue('H' . $row, $item->class->name);
            $sheet->setCellValue('J' . $row, $item->address);

            // Set the dropdown values for column H
            $dropdownValues = Classes::pluck('name')->toArray();
            $selectedItem = $item->class;

            // Set data validation with dropdown list for column H
            $validation = $sheet->getCell('I' . $row)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setFormula1('"' . implode(',', $dropdownValues) . '"');
            $validation->setShowDropDown(true);

            // Set the selected item as the default value in the dropdown for column H
            $selectedCell = $sheet->getCell('I' . $row);
            $selectedCell->setValue($selectedItem);

            // Apply formatting to the dropdown cell
            $selectedCell->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $dropdownJK = ['L', 'P'];
            $selectedJK = $item->gender;

            // Set data validation with dropdown list for column C
            $validationJK = $sheet->getCell('C' . $row)->getDataValidation();
            $validationJK->setType(DataValidation::TYPE_LIST);
            $validationJK->setFormula1('"' . implode(',', $dropdownJK) . '"');
            $validationJK->setShowDropDown(true);

            // Set the selected item as the default value in the dropdown for column C
            $selectedCellJK = $sheet->getCell('C' . $row);
            $selectedCellJK->setValue($selectedJK);

            // Apply formatting to the dropdown cell
            $selectedCellJK->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
            $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

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
