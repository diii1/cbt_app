<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\TeacherDataTable;
use Spatie\Permission\Models\Role;
use App\Http\Requests\TeacherRequest;
use App\Services\Master\TeacherService;
use App\Services\Master\SubjectService;
use App\Types\Entities\TeacherEntity;
use App\Models\Teacher;
use Symfony\Component\Console\Output\ConsoleOutput;

class TeacherController extends Controller
{
    private $service;
    private $subjectService;

    public function __construct(TeacherService $service, SubjectService $subjectService)
    {
        $this->service = $service;
        $this->subjectService = $subjectService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TeacherDataTable $dataTables)
    {
        $this->authorize('list_teacher');
        $data['nav_title'] = 'Master | Teachers';
        $data['title'] = 'Data Pengguna Guru';
        $data['button_add'] = 'Tambah Data Guru';
        return $dataTables->render('pages.master.teacher.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create_teacher');
        $data['action'] = route('teachers.store');
        $data['type'] = 'create';
        $data['title'] = 'Tambah Data Guru';
        $data['subjects'] = $this->subjectService->getSubjects();
        return view('pages.master.teacher.form', ['data' => $data, 'teacher' => new Teacher()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeacherRequest $request)
    {
        $this->authorize('create_teacher');
        $validated = $request->validated();
        $teacher = new TeacherEntity();
        $teacher->formRequest($validated);

        $this->service->insertTeacher($teacher);
        return response()->json([
            'status' => 'success',
            'message' => 'Data guru berhasil disimpan.'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['title'] = 'Detail Data Guru';
        $teacher = $this->service->getTeacherByID((int)$id);
        return view('pages.master.teacher.show', ['data' => $data, 'teacher' => $teacher]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update_teacher');
        $data['action'] = route('teachers.update', $id);
        $data['type'] = 'edit';
        $data['title'] = 'Edit Data Guru';
        $data['subjects'] = $this->subjectService->getSubjects();
        $teacher = $this->service->getTeacherByID((int)$id);
        return view('pages.master.teacher.form', ['data' => $data, 'teacher' => $teacher]);
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
        $teacher = new TeacherEntity();
        $teacher->updateRequest($request->all());

        $this->service->updateTeacher($teacher, $id);
        return response()->json([
            'status' => 'success',
            'message' => 'Data guru berhasil diperbarui.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete_teacher');
        $this->service->deleteTeacher($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Data guru berhasil dihapus.'
        ], 200);
    }
}