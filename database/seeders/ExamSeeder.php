<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Session;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Classes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $session = Session::where('name', 'Sesi 1')->first();
        $subject = Subject::where('name', 'Matematika')->first();
        $teacher = Teacher::with('user')->whereHas('user', function ($query) {
            $query->where('name', 'Teacher');
        })->first();
        $class = Classes::where('name', 'Kelas 7 - A')->first();

        $token = random_int(0, 999999);
        $token = str_pad($token, 6, 0, STR_PAD_LEFT);
        $time = explode(':', $session->time_start);
        $date = Carbon::now();
        $req_date = explode('-', $date->format('d-m-Y'));
        $expired_token = Carbon::create($req_date[2], $req_date[1], $req_date[0], $time[0], $time[1], $time[2], 'Asia/Jakarta');
        $expired_token->addMinutes(30);
        $code = "U-".floor(time()-999999999);

        try {
            DB::beginTransaction();

            Exam::create([
                'session_id' => $session->id,
                'subject_id' => $subject->id,
                'teacher_id' => $teacher->user_id,
                'class_id' => $class->id,
                'subject_name' => $subject->name,
                'teacher_name' => $teacher->user->name,
                'class_name' => $class->name,
                'title' => 'UTS Matematika',
                'code' => $code,
                'total_question' => 25,
                'date' => $date,
                'token' => $token,
                'expired_token' => $expired_token,
                'type' => 'pts',
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
