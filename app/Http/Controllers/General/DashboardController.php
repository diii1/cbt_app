<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if($user->hasRole('teacher')){
            $data['nav_title'] = 'Dashboard | Teacher';
            $data['title'] = 'Dashboard Guru';
            return view('pages.general.dashboard.index', ['data' => $data]);
        }

        if($user->hasRole('student')){
            $data['nav_title'] = 'Dashboard | Teacher';
            $data['title'] = 'Dashboard Guru';
            return view('pages.general.dashboard.index', ['data' => $data]);
        }

        $data['nav_title'] = 'Dashboard';
        $data['title'] = 'Dashboard';
        return view('pages.general.dashboard.index', ['data' => $data]);
    }
}
