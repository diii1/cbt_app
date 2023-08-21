<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\ListExamDataTable;

class ResultController extends Controller
{
    public function index(ListExamDataTable $dataTables)
    {
        $this->authorize('list_result');
        $data['title'] = 'List Ujian';
        $data['nav_title'] = 'Exam | List Ujian';
        return $dataTables->render('pages.exam.result.index', ['data' => $data]);
    }

    public function list($id)
    {
        dd($id);
    }
}
