<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data['nav_title'] = 'Dashboard';
        $data['title'] = 'Dashboard';
        return view('pages.general.dashboard.index', ['data' => $data]);
    }
}
