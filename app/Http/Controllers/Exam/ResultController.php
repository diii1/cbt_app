<?php

namespace App\Http\Controllers\Exam;

use App\Models\ExamResult;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\ListExamDataTable;
use App\DataTables\ExamResultDataTable;

class ResultController extends Controller
{
    public function index(ListExamDataTable $dataTables)
    {
        $this->authorize('list_result');
        $data['title'] = 'List Ujian';
        $data['nav_title'] = 'Exam | List Ujian';
        return $dataTables->render('pages.exam.result.index', ['data' => $data]);
    }

    public function list(ExamResultDataTable $dataTables, $id)
    {
        $this->authorize('list_result');
        $data['exam_id'] = ExamResult::find($id)->exam_id;
        $data['title'] = 'List Hasil Ujian';
        $data['nav_title'] = 'Exam | List Hasil Ujian';
        return $dataTables->render('pages.exam.result.list', ['data' => $data]);
    }
}
