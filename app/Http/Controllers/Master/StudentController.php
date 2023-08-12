<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\StudentDataTable;
use App\Http\Requests\StudentRequest;
use App\Services\Master\StudentService;
use App\Services\Master\ClassService;
use App\Types\Entities\StudentEntity;
use App\Models\Student;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class StudentController extends Controller
{
    private $service;
    private $classService;

    public function __construct(StudentService $service, ClassService $classService)
    {
        $this->service = $service;
        $this->classService = $classService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StudentDataTable $dataTables)
    {
        $this->authorize('list_student');
        $data['nav_title'] = 'Master | Students';
        $data['title'] = 'Data Pengguna Siswa';
        $data['button_add'] = 'Tambah Data Siswa';
        return $dataTables->render('pages.master.student.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create_student');
        $data['action'] = route('students.store');
        $data['type'] = 'create';
        $data['title'] = 'Tambah Data Siswa';
        $data['classes'] = $this->classService->getClasses();
        return view('pages.master.student.form', ['data' => $data, 'student' => new Student()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentRequest $request)
    {
        $this->authorize('create_student');
        $validated = $request->validated();
        $student = new StudentEntity();
        $student->formRequest($validated);

        $inserted = $this->service->insertStudent($student);

        if($inserted != []){
            return response()->json([
                'status' => 'success',
                'message' => 'Data Siswa berhasil disimpan.'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data Siswa gagal disimpan.'
        ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('read_student');
        $data['title'] = 'Detail Data Siswa';
        $student = $this->service->getStudentByID((int)$id);
        $student->birth_date = Carbon::parse($student->birth_date)->format('d/m/Y');
        $student->password = Crypt::decryptString($student->password);
        return view('pages.master.student.show', ['data' => $data, 'student' => $student]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update_student');
        $data['action'] = route('students.update', $id);
        $data['type'] = 'edit';
        $data['title'] = 'Edit Data Siswa';
        $data['classes'] = $this->classService->getClasses();
        $student = $this->service->getStudentByID((int)$id);
        return view('pages.master.student.form', ['data' => $data, 'student' => $student]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $student = new StudentEntity();
        $student->updateRequest($request->all());

        $updated = $this->service->updateStudent($student, $id);
        if($updated != []){
            return response()->json([
                'status' => 'success',
                'message' => 'Data Siswa berhasil diperbarui.'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data Siswa gagal diperbarui.'
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete_student');
        $deleted = $this->service->deleteStudent($id);
        if($deleted != []){
            return response()->json([
                'status' => 'success',
                'message' => 'Data Siswa berhasil dihapus.'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data Siswa gagal dihapus.'
        ], 500);
    }
}
