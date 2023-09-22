<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolProfile;
use App\Services\General\SchoolProfileService;
use App\Types\Entities\SchoolProfileEntity;
use Exception;
use Symfony\Component\Console\Output\ConsoleOutput;
use RealRashid\SweetAlert\Facades\Alert;

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
        $profile = $this->service->getSchoolProfile();

        if($profile) {
            return redirect()->route('login');
        }else{
            return view('pages.general.school_profile');
        }
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

        if ($request->hasFile('bg_app')) {
            $schoolBackground = $this->service->storeBackground($request->bg_app);
        }

        $profile = new SchoolProfileEntity();
        $profile->formRequest($validated, $schoolLogo->path, $schoolBackground->path);

        $inserted = $this->service->insertSchoolProfile($profile);
        if($inserted instanceof Exception) {
            $output = new ConsoleOutput();
            $output->writeln($inserted->getMessage());

            return redirect()->back()->with('error', 'Gagal menambahkan profil sekolah.')->withInput();
        }
        return redirect()->route('login')->with('success', 'Berhasil menambahkan profil sekolah.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $this->authorize('update_school_profile');
        $profile = $this->service->getSchoolProfile();
        $data['nav_title'] = 'School Profile';
        $data['title'] = 'Profil Sekolah';
        return view('pages.general.detail_school_profile', ['data' => $data, 'profile' => $profile]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update_school_profile');
        $profile = $this->service->getSchoolProfile();
        $data['nav_title'] = 'School Profile';
        $data['title'] = 'Perbarui Profil Sekolah';
        $data['action'] = route('school_profile.update', $profile->id);
        return view('pages.general.edit_school_profile', ['data' => $data, 'profile' => $profile]);
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
        $schoolLogo = null;

        if ($request->hasFile('logo')) {
            $profile = $this->service->getSchoolProfile();
            $this->service->deleteLogo($profile->logo);
            $schoolLogo = $this->service->storeLogo($request->logo);
        }

        if ($request->hasFile('bg_app')) {
            $profile = $this->service->getSchoolProfile();
            $this->service->deleteBackground($profile->background);
            $schoolBackground = $this->service->storeBackground($request->bg_app);
        }

        $profile = new SchoolProfileEntity();
        $profile->formRequest($validated, $schoolLogo->path, $schoolBackground->path);

        $updated = $this->service->updateSchoolProfile($profile, $id);

        if($updated instanceof Exception) {
            $output = new ConsoleOutput();
            $output->writeln($updated->getMessage());

            return redirect()->back()->with('error', 'Gagal memperbarui profil sekolah.')->withInput();
        }

        return redirect()->route('school_profile.show')->with('success', 'Berhasil memperbarui profil sekolah.');
    }
}
