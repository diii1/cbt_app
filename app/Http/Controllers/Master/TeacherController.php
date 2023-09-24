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
use App\Models\User;
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
        $teacher = new Teacher();
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
        $profile = null;

        if ($request->hasFile('profile')) {
            $profile = $this->service->storeProfile($request->profile);
        }

        $teacher = new TeacherEntity();
        $teacher->formRequest($validated, $profile->path);

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
        $validated = $request->all();
        $profile = null;

        if ($request->hasFile('profile')) {
            $user = User::findOrFail($id);
            if($user->profile) $this->service->deleteProfile($user->profile);
            $profile = $this->service->storeProfile($request->profile);
        }

        $teacher = new TeacherEntity();
        $teacher->updateRequest($validated, $profile->path);

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

    public function getBySubjectID($subjectID)
    {
        $teachers = $this->service->getTeacherBySubjectID($subjectID);
        return json_encode($teachers);
    }
}
