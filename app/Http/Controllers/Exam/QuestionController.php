<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\ListExamDataTable;
use App\DataTables\QuestionDataTable;
use App\Services\Exam\QuestionService;
use App\Services\Exam\ExamService;
use App\Types\Entities\QuestionEntity;
use App\Http\Requests\QuestionRequest;
use App\Models\Question;

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
        $question = new Question();
        return view('pages.exam.question.form', ['data' => $data, 'question' => $question]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request)
    {
        $this->authorize('create_question');
        $exam = $this->examService->getExamByID($request->exam_id);

        $validated = $request->validated();
        $validated['exam_title'] = $exam->title;
        $validated['subject_name'] = $exam->subject->name;
        $validated['created_by'] = auth()->user()->id;
        switch ($request->answer) {
            case 'A':
                $validated['answer'] = json_encode([
                    "option" => 'a',
                    "value" => $validated['option_a']
                ]);
                break;

            case 'B':
                $validated['answer'] = json_encode([
                    "option" => 'b',
                    "value" => $validated['option_b']
                ]);
                break;

            case 'C':
                $validated['answer'] = json_encode([
                    "option" => 'c',
                    "value" => $validated['option_c']
                ]);
                break;

            case 'D':
                $validated['answer'] = json_encode([
                    "option" => 'd',
                    "value" => $validated['option_d']
                ]);
                break;

            case 'E':
                $validated['answer'] = json_encode([
                    "option" => 'e',
                    "value" => $validated['option_e']
                ]);
                break;
        }

        $question = new QuestionEntity();
        $question->formRequest($validated);

        $inserted = $this->service->insertQuestion($question);

        if($inserted != []) return redirect()->route('questions.list', $exam->id)->with('success', 'Data Soal Berhasil Ditambahkan');

        return redirect()->route('questions.list', $exam->id)->with('error', 'Data Soal Gagal Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('read_question');
        $question = $this->service->getQuestionByID($id);
        $data['title'] = 'Detail Data Soal';
        $question->answer = json_decode($question->answer);

        return view('pages.exam.question.show', ['data' => $data, 'question' => $question]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update_question');
        $data['nav_title'] = 'Edit Questions';
        $data['action'] = route('questions.update', $id);
        $data['type'] = 'edit';
        $data['title'] = 'Edit Data Soal';
        $question = $this->service->getQuestionByID($id);
        $question->answer = json_decode($question->answer);

        return view('pages.exam.question.form', ['data' => $data, 'question' => $question]);
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
        $dataQuestion = $this->service->getQuestionByID($id);
        $validated = $request->all();
        switch ($request->answer) {
            case 'A':
                $validated['answer'] = json_encode([
                    "option" => 'a',
                    "value" => $validated['option_a']
                ]);
                break;

            case 'B':
                $validated['answer'] = json_encode([
                    "option" => 'b',
                    "value" => $validated['option_b']
                ]);
                break;

            case 'C':
                $validated['answer'] = json_encode([
                    "option" => 'c',
                    "value" => $validated['option_c']
                ]);
                break;

            case 'D':
                $validated['answer'] = json_encode([
                    "option" => 'd',
                    "value" => $validated['option_d']
                ]);
                break;

            case 'E':
                $validated['answer'] = json_encode([
                    "option" => 'e',
                    "value" => $validated['option_e']
                ]);
                break;
        }

        $question = new QuestionEntity();
        $question->updateRequest($validated);

        $updated = $this->service->updateQuestion($question, $id);
        if($updated != []) return redirect()->route('questions.list', $dataQuestion->exam_id)->with('success', 'Data Soal Berhasil Diperbarui');

        return redirect()->route('questions.list', $dataQuestion->exam_id)->with('error', 'Data Soal Gagal Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete_question');
        $deleted = $this->service->deleteQuestion($id);
        if($deleted != []){
            return response()->json([
                'status' => 'success',
                'message' => 'Data Soal berhasil dihapus.'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data Soal gagal dihapus.'
        ], 500);
    }
}
