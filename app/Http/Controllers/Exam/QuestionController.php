<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\ListExamDataTable;
use App\DataTables\QuestionDataTable;
use App\Services\Exam\QuestionService;
use App\Services\Exam\ExamService;

class QuestionController extends Controller
{
    private $service;
    private $examService;

    public function __construct(QuestionService $service, ExamService $examService)
    {
        $this->service = $service;
        $this->examService = $examService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListExamDataTable $dataTables)
    {
        $this->authorize('list_question');
        $data['nav_title'] = 'Question | List Exam';
        $data['title'] = 'Data Ujian';
        return $dataTables->render('pages.exam.question.index', ['data' => $data]);
    }

    public function question_list(QuestionDataTable $dataTables, $id)
    {
        $this->authorize('list_question');
        $exam = $this->examService->getExamByID($id);
        // $questions = $this->service->getQuestionByExamID($id);
        $data['nav_title'] = 'Questions';
        $data['title'] = 'Data Soal Ujian';
        $data['button_add'] = 'Tambah Data Soal';
        $data['exam'] = $exam;
        return $dataTables->render('pages.exam.question.list', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $this->authorize('create_question');
        $exam = $this->examService->getExamByID($id);
        $data['nav_title'] = 'Create Questions';
        $data['action'] = route('questions.store');
        $data['type'] = 'create';
        $data['title'] = 'Tambah Data Soal';
        $data['exam'] = $exam;
        return view('pages.exam.question.form', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
