<?php

namespace App\Http\Controllers\Exam;

use App\Models\Session;
use App\Http\Controllers\Controller;
use App\Services\Exam\SessionService;
use App\DataTables\SessionDataTable;
use App\Http\Requests\SessionRequest;
use App\Types\Entities\SessionEntity;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SessionController extends Controller
{
    private $service;

    public function __construct(SessionService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SessionDataTable $dataTable)
    {
        $this->authorize('list_session');
        $data['nav_title'] = 'Exam | Sessions';
        $data['title'] = 'Data Sesi Ujian';
        $data['button_add'] = 'Tambah Data';
        return $dataTable->render('pages.exam.session.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create_session');
        $data['action'] = route('sessions.store');
        $data['type'] = 'create';
        $data['title'] = 'Tambah Data Sesi Ujian';
        return view('pages.exam.session.form', ['data' => $data, 'session' => new Session()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SessionRequest $request)
    {
        $this->authorize('create_session');
        $validated = $request->validated();
        $session = new SessionEntity();
        $session->formRequest($validated);

        $inserted = $this->service->insertSession($session);
        if($inserted != []){
            return response()->json([
                'status' => 'success',
                'message' => 'Data Sesi Ujian berhasil disimpan',
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data Sesi Ujian gagal disimpan',
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
        $this->authorize('read_session');
        $data['title'] = 'Detail Data Sesi Ujian';
        $session = $this->service->getSessionByID($id);
        $session->time_start = Carbon::createFromTimeString($session->time_start, 'Asia/Jakarta')->format('g:i A');
        $session->time_end = Carbon::createFromTimeString($session->time_end, 'Asia/Jakarta')->format('g:i A');
        return view('pages.exam.session.show', ['data' => $data, 'session' => $session]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update_session');
        $data['action'] = route('sessions.update', $id);
        $data['type'] = 'edit';
        $data['title'] = 'Ubah Data Sesi Ujian';
        $session = $this->service->getSessionByID($id);
        return view('pages.exam.session.form', ['data' => $data, 'session' => $session]);
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
        // $validated = $request->validated();
        $session = new SessionEntity();
        $session->formRequest($request->all());

        $updated = $this->service->updateSession($session, $id);
        if($updated != []){
            return response()->json([
                'status' => 'success',
                'message' => 'Data Sesi Ujian berhasil diperbarui',
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data Sesi Ujian gagal diperbarui',
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
        $this->authorize('delete_session');
        $deleted = $this->service->deleteSession($id);

        if($deleted){
            return response()->json([
                'status' => 'success',
                'message' => 'Data Sesi Ujian berhasil dihapus',
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data Sesi Ujian gagal dihapus',
        ], 500);
    }
}
