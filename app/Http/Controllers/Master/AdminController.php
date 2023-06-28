<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\AdminDataTable;
use Spatie\Permission\Models\Role;
use App\Http\Requests\AdminRequest;
use App\Services\Master\AdminService;
use App\Types\Entities\AdminEntity;
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
        $roles = Role::all()->pluck('name');
        return view('pages.master.admin.form', ['data' => $data, 'roles' => $roles]);
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

        $admin = new AdminEntity();
        $admin->formRequest($validated);

        $inserted = $this->service->insertAdmin($admin);
        if($inserted instanceof Exception) {
            $output = new ConsoleOutput();
            $output->writeln($inserted->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data administrator.'
            ], 500);
        }

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
