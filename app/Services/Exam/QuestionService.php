<?php

namespace App\Services\Exam;

use App\Models\Question;
use App\Types\Entities\QuestionEntity;
use Illuminate\Support\Facades\DB;
use App\Services\Service;
use Illuminate\Support\Collection;

class QuestionService extends Service
{
    public function getQuestions(): Collection
    {
        try {
            return DB::table('questions')->get();
        } catch (\Throwable $th) {
            $this->writeLog("QuestionService::getQuestions", $th);
            return new Collection();
        }
    }

    public function getQuestionByExamID(int $id): Collection
    {
        try {
            return DB::table('questions')->where('exam_id', $id)->get();
        } catch (\Throwable $th) {
            $this->writeLog("QuestionService::getQuestionByExamID", $th);
            return new Collection();
        }
    }

    public function getQuestionByID(int $id): Question | Collection
    {
        try {
            return Question::where('id', $id)->with('exam', 'subject')->first();
        } catch (\Throwable $th) {
            $this->writeLog("QuestionService::getQuestionByID", $th);
            return new Collection();
        }
    }

    public function insertQuestion(QuestionEntity $request):bool | Collection
    {
        try {
            return DB::table('questions')->insert([
                'exam_id' => $request->exam_id,
                'subject_id' => $request->subject_id,
                'exam_title' => $request->exam_title,
                'subject_name' => $request->subject_name,
                'number' => $request->number,
                'question' => $request->question,
                'options' => $request->options,
                'answer' => $request->answer,
                'created_by' => $request->created_by,
            ]);
        } catch (\Throwable $th) {
            $this->writeLog("QuestionService::insertQuestion", $th);
            return new Collection();
        }
    }

    public function updateQuestion(QuestionEntity $request, int $id):bool | Collection
    {
        try {
            return DB::table('questions')->where('id', $id)->update([
                'exam_id' => $request->exam_id,
                'subject_id' => $request->subject_id,
                'exam_title' => $request->exam_title,
                'subject_name' => $request->subject_name,
                'number' => $request->number,
                'question' => $request->question,
                'options' => $request->options,
                'answer' => $request->answer,
                'created_by' => $request->created_by,
            ]);
        } catch (\Throwable $th) {
            $this->writeLog("QuestionService::updateQuestion", $th);
            return new Collection();
        }
    }

    public function deleteQuestion(int $id):bool | Collection
    {
        try {
            return DB::table('questions')->where('id', $id)->delete();
        } catch (\Throwable $th) {
            $this->writeLog("QuestionService::deleteQuestion", $th);
            return new Collection();
        }
    }
}
