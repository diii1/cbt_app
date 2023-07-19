<?php

namespace App\Http\Controllers\Master;

use App\Models\Subject;
use App\Http\Controllers\Controller;
use App\Services\Master\SubjectService;
use App\DataTables\SubjectDataTable;
use App\Http\Requests\SubjectRequest;
use App\Types\Entities\SubjectEntity;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class SubjectController extends Controller
{
    private $service;

    public function __construct(SubjectService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SubjectDataTable $dataTables)
    {
        $this->authorize('list_subject');
        $data['nav_title'] = 'Master | Subjects';
        $data['title'] = 'Data Mata Pelajaran';
        $data['button_add'] = 'Tambah Data Mata Pelajaran';
        return $dataTables->render('pages.master.subject.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create_subject');
        $data['action'] = route('subjects.store');
        $data['type'] = 'create';
        $data['title'] = 'Tambah Data Mata Pelajaran';
        return view('pages.master.subject.form', ['data' => $data, 'subject' => new Subject()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubjectRequest $request)
    {
        $this->authorize('create_subject');
        $code = substr(Uuid::uuid4()->toString(), 0, 8);
        $validated = $request->validated();
        $validated['code'] = strtoupper($code);

        $subject = new SubjectEntity();
        $subject->formRequest($validated);

        $this->service->insertSubject($subject);
        return response()->json([
            'status' => 'success',
            'message' => 'Data mata pelajaran berhasil disimpan.'
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
        $this->authorize('read_subject');
        $data['title'] = 'Lihat Data Mata Pelajaran';
        $subject = $this->service->getSubjectByID($id);
        return view('pages.master.subject.show', ['data' => $data, 'subject' => $subject]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update_subject');
        $data['action'] = route('subjects.update', $id);
        $data['type'] = 'edit';
        $data['title'] = 'Ubah Data Mata Pelajaran';
        $subject = $this->service->getSubjectByID($id);
        return view('pages.master.subject.form', ['data' => $data, 'subject' => $subject]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubjectRequest $request, $id)
    {
        $validated = $request->validated();

        $subject = new SubjectEntity();
        $subject->updateRequest($validated);

        $this->service->updateSubject($subject, $id);
        return response()->json([
            'status' => 'success',
            'message' => 'Data mata pelajaran berhasil diperbarui.'
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
        $this->authorize('delete_subject');
        $this->service->deleteSubject($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data mata pelajaran berhasil dihapus.'
        ], 200);
    }
}
