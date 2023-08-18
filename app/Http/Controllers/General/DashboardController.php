<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\Exam\ExamParticipantService;
use App\Services\Master\StudentService;

class DashboardController extends Controller
{
    private $studentService;
    private $participantService;

    public function __construct()
    {
        $this->studentService = new StudentService();
        $this->participantService = new ExamParticipantService();
    }

    public function index()
    {
        $user = Auth::user();

        if($user->hasRole('teacher')){
            $data['nav_title'] = 'Dashboard | Teacher';
            $data['title'] = 'Dashboard Guru';
            return view('pages.general.dashboard.index', ['data' => $data]);
        }

        if($user->hasRole('student')){
            $data['nav_title'] = 'Dashboard | Student';
            $data['title'] = 'Dashboard Siswa';
            $data['date'] = Carbon::parse(Carbon::now())->locale('id')->settings(['formatFunction' => 'translatedFormat']);
            $data['date2'] = Carbon::parse(Carbon::now());

            $user_id = Auth::user()->id;
            $data['student'] = $this->studentService->getStudentByID($user_id);
            $data['student']->birth_date = Carbon::parse($data['student']->birth_date)->locale('id')->settings(['formatFunction' => 'translatedFormat']);
            $data['exams'] = $this->participantService->getExamParticipantByStudentID($user_id);
            // $date = Carbon::parse(Carbon::now())->locale('id');
            // $date->settings(['formatFunction' => 'translatedFormat']);

            // $user_id = Auth::user()->id;
            // $student = Student::with('class')->where('user_id', $user_id)->first();
            // $student_day_of_birth = Carbon::parse(Carbon::now())->locale('id')->settings(['formatFunction' => 'translatedFormat']);

            // $exams = DB::table('exam_participants')
            //     ->join('exams', 'exam_participants.exam_id', '=', 'exams.id')
            //     ->join('exam_sessions', 'exams.session_id', '=', 'exam_sessions.id')
            //     ->join('subjects', 'exams.subject_id', '=', 'subjects.id')
            //     ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            //     ->join('classes', 'exams.class_id', '=', 'classes.id')
            //     ->select([
            //         'exam_participants.id as id',
            //         'exam_participants.is_submitted as is_submitted',
            //         'exams.title as exam_title',
            //         'exams.code as exam_code',
            //         'exams.exam_date as exam_date',
            //         'exams.exam_type as exam_type',
            //         'exams.amount_question as amount_question',
            //         'exam_sessions.name as session_name',
            //         'exam_sessions.start_time as session_start_time',
            //         'exam_sessions.end_time as session_end_time',
            //         'subjects.name as subject_name',
            //         'teachers.name as teacher_name',
            //         'classes.name as class_name',
            //     ])
            //     ->where('student_id', $user_id)->get();
            // dd($exams);
            return view('pages.general.dashboard.student', ['data' => $data]);
        }

        $data['nav_title'] = 'Dashboard';
        $data['title'] = 'Dashboard';
        return view('pages.general.dashboard.index', ['data' => $data]);
    }
}
