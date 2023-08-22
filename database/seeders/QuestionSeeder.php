<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teacher = Teacher::first();
        $exam = Exam::first();
        $subject = Subject::first();

        try {
            DB::beginTransaction();

            for ($i = 1; $i <= 100; $i++) {
                Question::create([
                    "exam_id" => $exam->id,
                    "subject_id" => $subject->id,
                    "exam_title" => $exam->title,
                    "subject_name" => $subject->name,
                    "question" => "<p>Berapakah hasil dari $i + 5?</p>",
                    "option_a" => "<p>" . ($i + 5 - 2) . "</p>",
                    "option_b" => "<p>" . ($i + 5 + 1) . "</p>",
                    "option_c" => "<p>" . ($i + 5) . "</p>",
                    "option_d" => "<p>" . ($i + 5 - 1) . "</p>",
                    "option_e" => "<p>" . ($i + 5 + 2) . "</p>",
                    "answer" => json_encode([
                        "option" => "c",
                        "value" => "<p>" . ($i + 5) . "</p>"
                    ]),
                    "created_by" => $teacher->user_id,
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
