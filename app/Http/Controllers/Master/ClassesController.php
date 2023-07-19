<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Master\ClassService;
use App\DataTables\ClassDataTable;
use App\Http\Requests\ClassRequest;
use App\Models\Classes;
use App\Types\Entities\ClassEntity;

class ClassesController extends Controller
{
    private $service;

    public function __construct(ClassService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ClassDataTable $dataTables)
    {
        $this->authorize('list_class');
        $data['nav_title'] = 'Master | Classes';
        $data['title'] = 'Data Kelas';
        $data['button_add'] = 'Tambah Data Kelas';
        return $dataTables->render('pages.master.classes.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create_class');
        $data['action'] = route('classes.store');
        $data['type'] = 'create';
        $data['title'] = 'Tambah Data Kelas';
        return view('pages.master.classes.form', ['data' => $data, 'class' => new Classes()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClassRequest $request)
    {
        $this->authorize('create_class');
        $validated = $request->validated();

        $class = new ClassEntity();
        $class->formRequest($validated);

        $this->service->insertClass($class);
        return response()->json([
            'status' => 'success',
            'message' => 'Data kelas berhasil disimpan.'
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
        $this->authorize('read_class');
        $data['title'] = 'Detail Data Kelas';
        $class = $this->service->getClassByID($id);
        return view('pages.master.classes.show', ['data' => $data, 'class' => $class]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update_class');
        $data['action'] = route('classes.update', $id);
        $data['type'] = 'edit';
        $data['title'] = 'Ubah Data Kelas';
        $class = $this->service->getClassByID($id);
        return view('pages.master.classes.form', ['data' => $data, 'class' => $class]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClassRequest $request, $id)
    {
        $validated = $request->validated();

        $class = new ClassEntity();
        $class->formRequest($validated);

        $this->service->updateClass($class, $id);
        return response()->json([
            'status' => 'success',
            'message' => 'Data kelas berhasil diperbarui.'
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
        $this->authorize('delete_class');
        $this->service->deleteClass($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data kelas berhasil dihapus.'
        ], 200);
    }
}
