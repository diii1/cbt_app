<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\Exam\ExamParticipantService;
use App\Services\Master\StudentService;
use App\Models\Student;
use App\Models\Exam;
use App\Models\Teacher;

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
            $data['title'] = 'Dashboard';
            $data['teacher_id'] = $user->id;
            return view('pages.general.dashboard.teacher', ['data' => $data]);
        }

        if($user->hasRole('student')){
            $data['nav_title'] = 'Dashboard | Student';
            $data['title'] = 'Dashboard';
            $data['date'] = Carbon::parse(Carbon::now())->locale('id')->settings(['formatFunction' => 'translatedFormat']);
            $data['date2'] = Carbon::parse(Carbon::now());

            $user_id = Auth::user()->id;
            $data['student'] = $this->studentService->getStudentByID($user_id);
            $data['student']->birth_date = Carbon::parse($data['student']->birth_date)->locale('id')->settings(['formatFunction' => 'translatedFormat']);
            $data['exams'] = $this->participantService->getExamParticipantByStudentID($user_id);
            return view('pages.general.dashboard.student', ['data' => $data]);
        }

        $data['nav_title'] = 'Dashboard | Admin';
        $data['title'] = 'Dashboard';
        $data['teacher'] = Teacher::count();
        $data['student'] = Student::count();
        $data['exam'] = Exam::count();
        return view('pages.general.dashboard.index', ['data' => $data]);
    }

    public function examTable($id)
    {
        dd($id);
    }
}
