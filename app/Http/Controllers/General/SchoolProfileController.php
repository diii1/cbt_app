<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolProfile;
use App\Services\General\SchoolProfileService;
use App\Types\Entities\SchoolProfileEntity;
use Exception;
use Symfony\Component\Console\Output\ConsoleOutput;

class SchoolProfileController extends Controller
{
    private $service;

    public function __construct(SchoolProfileService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $profile = $this->service->getSchoolProfile();

        if($profile) {
            return redirect()->route('login');
        }else{
            return view('pages.general.school_profile');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->all();
        $schoolLogo = null;

        if ($request->hasFile('logo')) {
            $schoolLogo = $this->service->storeLogo($request->logo);
        }

        $profile = new SchoolProfileEntity();
        $profile->formRequest($validated, $schoolLogo->path);

        $inserted = $this->service->insertSchoolProfile($profile);
        if($inserted instanceof Exception) {
            $output = new ConsoleOutput();
            $output->writeln($inserted->getMessage());

            return back()->with('error', 'Gagal menambahkan profil sekolah.')->withInput();
        }

        return redirect()->route('login')->with('success', 'Berhasil menambahkan profil sekolah.');
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
