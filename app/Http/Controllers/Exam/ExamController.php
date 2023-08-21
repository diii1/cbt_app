<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\DataTables\ExamDataTable;
use App\Http\Requests\ExamRequest;
use App\Services\Exam\ExamService;
use App\Services\Exam\SessionService;
use App\Services\Exam\QuestionService;
use App\Services\Master\ClassService;
use App\Services\Master\SubjectService;
use App\Services\Master\TeacherService;
use App\Types\Entities\ExamEntity;
use App\Models\Exam;
use App\Models\Session;
use App\Models\Student;
use App\Models\Answer;
use Carbon\Carbon;

class ExamController extends Controller
{
    private $service;
    private $sessionService;
    private $classService;
    private $subjectService;
    private $teacherService;
    private $questionService;

    public function __construct ()
    {
        $this->service = new ExamService();
        $this->sessionService = new SessionService();
        $this->classService = new ClassService();
        $this->subjectService = new SubjectService();
        $this->teacherService = new TeacherService();
        $this->questionService = new QuestionService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ExamDataTable $dataTables)
    {
        $this->authorize('list_exam');
        $data['nav_title'] = 'Exams';
        $data['title'] = 'Data Ujian';
        $data['button_add'] = 'Tambah Data Ujian';
        return $dataTables->render('pages.exam.home.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create_exam');
        $data['action'] = route('exams.store');
        $data['type'] = 'create';
        $data['title'] = 'Tambah Data Ujian';
        $data['sessions'] = $this->sessionService->getSessions();
        $data['classes'] = $this->classService->getClasses();
        $data['subjects'] = $this->subjectService->getSubjects();
        return view('pages.exam.home.form', ['data' => $data, 'exam' => new Exam()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExamRequest $request)
    {
        $this->authorize('create_exam');
        $token = random_int(0, 999999);
        $token = str_pad($token, 6, 0, STR_PAD_LEFT);
        $session_start_time = $this->sessionService->getSessionByID($request->session_id);
        $time = explode(':', $session_start_time->time_start);
        $req_date = explode('-', $request->date);
        $expired_token = Carbon::create($req_date[2], $req_date[1], $req_date[0], $time[0], $time[1], $time[2], 'Asia/Jakarta');
        $expired_token->addMinutes(30);

        $validated = $request->validated();
        $validated['subject_name'] = $this->subjectService->getSubjectByID($validated['subject_id'])->name;
        $validated['teacher_name'] = $this->teacherService->getTeacherByID($validated['teacher_id'])->user->name;
        $validated['class_name'] = $this->classService->getClassByID($validated['class_id'])->name;
        $validated['token'] = $token;
        $validated['expired_token'] = $expired_token;
        $validated['code'] = "U-".floor(time()-999999999);

        $exam = new ExamEntity();
        $exam->formRequest($validated);

        $inserted = $this->service->insertExam($exam);

        if($inserted != []){
            return response()->json([
                'status' => 'success',
                'message' => 'Data Ujian berhasil disimpan.'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data Ujian gagal disimpan.'
        ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('read_exam');
        $data['title'] = 'Detail Data Siswa';
        $exam = $this->service->getExamByID($id);
        $exam->expired_token = Carbon::parse($exam->expired_token)->locale('id')->settings(['formatFunction' => 'translatedFormat']);
        $exam->date = Carbon::parse($exam->date)->locale('id')->settings(['formatFunction' => 'translatedFormat']);

        $session = Session::find($exam->session_id);
        $time_start = Carbon::createFromTimeString($session->time_start, 'Asia/Jakarta');
        $time_end = Carbon::createFromTimeString($session->time_end, 'Asia/Jakarta');

        $exam->session = $session->name .', '. $time_start->format('H:i') . ' - ' . $time_end->format('H:i').' WIB';
        return view('pages.exam.home.show', ['data' => $data, 'exam' => $exam]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update_exam');
        $data['action'] = route('exams.update', $id);
        $data['type'] = 'edit';
        $data['title'] = 'Edit Data Ujian';
        $data['sessions'] = $this->sessionService->getSessions();
        $data['classes'] = $this->classService->getClasses();
        $data['subjects'] = $this->subjectService->getSubjects();
        $exam = $this->service->getExamByID($id);
        $exam->date = Carbon::parse($exam->date);
        $data['teachers'] = $this->teacherService->getTeacherBySubjectID($exam->subject_id);
        return view('pages.exam.home.form', ['data' => $data, 'exam' => $exam]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExamRequest $request, $id)
    {
        $this->authorize('update_exam');
        $token = random_int(0, 999999);
        $token = str_pad($token, 6, 0, STR_PAD_LEFT);
        $session_start_time = $this->sessionService->getSessionByID($request->session_id);
        $time = explode(':', $session_start_time->time_start);
        $req_date = explode('-', $request->date);
        $expired_token = Carbon::create($req_date[2], $req_date[1], $req_date[0], $time[0], $time[1], $time[2], 'Asia/Jakarta');
        $expired_token->addMinutes(30);

        $validated = $request->validated();
        $validated['subject_name'] = $this->subjectService->getSubjectByID($validated['subject_id'])->name;
        $validated['teacher_name'] = $this->teacherService->getTeacherByID($validated['teacher_id'])->user->name;
        $validated['class_name'] = $this->classService->getClassByID($validated['class_id'])->name;
        $validated['token'] = $token;
        $validated['expired_token'] = $expired_token;
        $validated['code'] = "U-".floor(time()-999999999);

        $exam = new ExamEntity();
        $exam->formRequest($validated);

        $updated = $this->service->updateExam($exam, $id);
        if($updated != []){
            return response()->json([
                'status' => 'success',
                'message' => 'Data Ujian berhasil diperbarui.'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data Ujian gagal diperbarui.'
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete_exam');
        $deleted = $this->service->deleteExam($id);
        if($deleted != []){
            return response()->json([
                'status' => 'success',
                'message' => 'Data Ujian berhasil dihapus.'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data Ujian gagal dihapus.'
        ], 500);
    }

    public function update_exam($id)
    {
        $updated = $this->service->updateStatusExam($id);
        if($updated != []){
            return response()->json([
                'status' => 'success',
                'message' => 'Data status ujian berhasil diperbarui.'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data status ujian gagal diperbarui.'
        ], 500);
    }

    public function validate_token($code)
    {
        $data['title'] = "Validasi Token Ujian";
        $data['exam'] = $this->service->getExamByCode($code);
        return view('pages.exam.student.validate', ["data" => $data]);
    }

    public function validate_exam(Request $request)
    {
        $exam = $this->service->getExamByCode($request->code);
        // if($exam->token != $request->token){
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Token tidak valid.'
        //     ]);
        // }
        // if(Carbon::now()->greaterThan($exam->expired_token)){
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Token sudah kadaluarsa.'
        //     ]);
        // }
        // if($exam->token == $request->token && Carbon::now()->lessThan($exam->expired_token)){
        //     return response()->json([
        //         'status' => 'success',
        //         'code' => $request->code,
        //         'message' => 'Validasi token berhasil.'
        //     ]);
        // }

        if($exam->token == $request->token){
            return response()->json([
                'status' => 'success',
                'code' => $request->code,
                'message' => 'Validasi token berhasil.'
            ]);
        }
    }

    public function start($code)
    {
        $data['exam'] = $this->service->getExamByCode($code);
        $data['nav_title'] = 'Exam Start';
        $data['time_start'] = Carbon::createFromTimeString($data['exam']->session->time_start, 'Asia/Jakarta');
        $data['time_end'] = Carbon::createFromTimeString($data['exam']->session->time_end, 'Asia/Jakarta');
        $startTime = Carbon::parse($data['exam']->session->time_start);
        $endTime = Carbon::parse($data['exam']->session->time_end);

        $data['student'] = Student::where('user_id', auth()->user()->id)->with('user')->first();

        $data['duration'] = $startTime->diffInMinutes($endTime);

        return view('pages.exam.student.start', ['data' => $data]);
    }

    public function start_exam(Request $request)
    {
        $student_id = Auth::user()->id;
        $exam_id = $request->exam_id;
        $exam = $this->service->getExamByID($exam_id);

        $questions = $this->questionService->getMappedQuestionByExamID($exam_id);
        $shuffledQuestions = $this->questionService->getShuffledQuestions($questions)->take($exam->total_question);

        $answers = Answer::where('exam_id', $exam->id)->where('student_id', $student_id)->get();

        if(count($answers) > 0){
            return response()->json([
                'status' => 'success',
                'code' => $exam->code,
                'message' => 'Data soal berhasil didapatkan.'
            ], 200);
        }

        if($shuffledQuestions) {
            DB::beginTransaction();

            foreach ($shuffledQuestions as $key => $question) {
                Answer::create([
                    'question_id' => $question->id,
                    'student_id' => $student_id,
                    'exam_id' => $exam_id,
                    'number' => ++$key,
                    'answer' => null,
                    'doubtful_answer' => false,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'code' => $exam->code,
                'message' => 'Data soal berhasil didapatkan.'
            ], 200);
        }else{
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Data soal tidak ditemukan.'
            ], 500);
        }
    }

    public function get_question($code, $index)
    {
        $exam = $this->service->getExamByCode($code);
        $data['exam'] = $exam;
        $data['sessionEnd'] = Carbon::parse($exam->session->time_end)->format('H:i');
        $question_id = Answer::where('number', $index)->first()->question_id;
        $data['nav_title'] = 'Student | Question Exam';
        $data['question'] = $this->questionService->getMappedQuestionByID($question_id);
        $data['question']->options = $this->questionService->shuffleOption($data['question']->options);
        // $data['question_numbers'] = Answer::where('exam_id', $exam->id)->pluck('number')->chunk(4);
        $data['answers'] = Answer::where('exam_id', $exam->id)->where('student_id', Auth::user()->id)->get()->chunk(4);
        $data['answer'] = Answer::where('exam_id', $exam->id)
            ->where('number', $index)
            ->where('student_id', Auth::user()->id)
            ->first();

        $sessionStep = session('step_exam');
        $score = $this->getCurrentScore($exam->id, $sessionStep);
        if($index > $sessionStep) {
            if($score > $exam->min_score){
                $sessionStep += $exam->total_question_step;
                session(['step_exam' => $sessionStep]);
            }else{
                return redirect()->back()->with('error', 'Maaf, Nilai anda masih belum mencukupi.');
            }
        }

        return view('pages.exam.student.question', ['data' => $data, 'index' => $index]);
    }

    public function getCurrentScore($exam_id, $step)
    {
        $exam = $this->service->getExamByID($exam_id);
        $student_id = Auth::user()->id;
        $answers = Answer::where('exam_id', $exam_id)->where('student_id', $student_id)->orderBy('number')->limit($step)->get();
        $score = 0;
        foreach ($answers as $answer) {
            $key_answer = json_decode($answer->question->answer);
            if($answer->answer){
                if($key_answer->value == $answer->answer){
                    $score++;
                }
            }
        }

        if($step != 0) $result = ($score / $step) * 100;
        else $result = 0;

        return $result;
    }

    public function set_answer(Request $request)
    {
        $student_id = Auth::user()->id;
        $answer = Answer::where('exam_id', $request->exam)
            ->where('number', $request->index)
            ->where('student_id', $student_id)
            ->first();

        $answer->answer = $request->answer;

        if($answer->save()){
            return response()->json([
                'status' => 'success',
                'message' => 'Jawaban berhasil disimpan.'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Jawaban gagal disimpan.'
        ], 500);
    }

    public function set_doubtful_answer(Request $request)
    {
        $student_id = Auth::user()->id;
        $answer = Answer::where('exam_id', $request->exam)
            ->where('number', $request->index)
            ->where('student_id', $student_id)
            ->first();

        $answer->doubtful_answer = $request->doubtful[0] == 'true' ? true : false;

        if($answer->save()){
            return response()->json([
                'status' => 'success',
                'message' => 'Jawaban berhasil disimpan.'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Jawaban gagal disimpan.'
        ], 500);
    }
}
