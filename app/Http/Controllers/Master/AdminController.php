<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\AdminDataTable;
use Spatie\Permission\Models\Role;
use App\Http\Requests\AdminRequest;
use App\Services\Master\AdminService;
use App\Types\Entities\AdminEntity;
use App\Models\Admin;
use App\Models\User;
use Symfony\Component\Console\Output\ConsoleOutput;

class AdminController extends Controller
{
    private $service;

    public function __construct(AdminService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AdminDataTable $dataTables)
    {
        $this->authorize('list_admin');
        $data['nav_title'] = 'Master | Admins';
        $data['title'] = 'Data Pengguna Administrator';
        $data['button_add'] = 'Tambah Data Administrator';
        return $dataTables->render('pages.master.admin.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create_admin');
        $data['action'] = route('admins.store');
        $data['type'] = 'create';
        $data['title'] = 'Tambah Data Administrator';
        return view('pages.master.admin.form', ['data' => $data, 'admin' => new Admin()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminRequest $request)
    {
        $this->authorize('create_admin');

        $validated = $request->validated();
        $profile = null;

        if ($request->hasFile('profile')) {
            $profile = $this->service->storeProfile($request->profile);
        }

        $admin = new AdminEntity();
        $admin->formRequest($validated, $profile->path);

        $this->service->insertAdmin($admin);
        return response()->json([
            'status' => 'success',
            'message' => 'Data administrator berhasil disimpan.'
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
        $data['title'] = 'Detail Data Administrator';
        $admin = $this->service->getAdminByID($id);
        return view('pages.master.admin.show', ['data' => $data, 'admin' => $admin]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update_admin');
        $data['action'] = route('admins.update', $id);
        $data['type'] = 'edit';
        $data['title'] = 'Edit Data Administrator';
        $admin = $this->service->getAdminByID($id);
        return view('pages.master.admin.form', ['data' => $data, 'admin' => $admin]);
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

        $admin = new AdminEntity();
        $admin->updateRequest($validated, $profile->path);

        $this->service->updateAdmin($admin, $id);
        return response()->json([
            'status' => 'success',
            'message' => 'Data administrator berhasil diperbarui.'
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
        $this->authorize('delete_admin');
        $this->service->deleteAdmin($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data administrator berhasil dihapus.'
        ], 200);
    }
}
