<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Student;
use App\Models\ExamParticipant;
use Illuminate\Support\Facades\DB;

class ExamParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = Student::with('user')->get();
        $exam = Exam::first();

        try {
            DB::beginTransaction();

            foreach ($students as $student) {
                ExamParticipant::create([
                    'exam_id' => $exam->id,
                    'student_id' => $student->user_id,
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }

    }
}
