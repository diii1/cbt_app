<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\ExamDataTable;
use App\Http\Requests\ExamRequest;
use App\Services\Exam\ExamService;
use App\Services\Exam\SessionService;
use App\Services\Master\ClassService;
use App\Services\Master\SubjectService;
use App\Services\Master\TeacherService;
use App\Types\Entities\ExamEntity;
use App\Models\Exam;
use App\Models\Session;
use Carbon\Carbon;

class ExamController extends Controller
{
    private $service;
    private $sessionService;
    private $classService;
    private $subjectService;
    private $teacherService;

    public function __construct(ExamService $service, SessionService $sessionService, ClassService $classService, SubjectService $subjectService, TeacherService $teacherService)
    {
        $this->service = $service;
        $this->sessionService = $sessionService;
        $this->classService = $classService;
        $this->subjectService = $subjectService;
        $this->teacherService = $teacherService;
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
        if($exam->token != $request->token){
            return response()->json([
                'status' => 'error',
                'message' => 'Token tidak valid.'
            ]);
        }
        if(Carbon::now()->greaterThan($exam->expired_token)){
            return response()->json([
                'status' => 'error',
                'message' => 'Token sudah kadaluarsa.'
            ]);
        }
        if($exam->token == $request->token && Carbon::now()->lessThan($exam->expired_token)){
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

        return view('pages.exam.student.start', ['data' => $data]);
    }
}
