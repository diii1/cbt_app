<?php

namespace App\Services\Exam;

use App\Models\Exam;
use App\Types\Entities\ExamEntity;
use Illuminate\Support\Facades\DB;
use App\Services\Service;
use Illuminate\Support\Collection;

class ExamService extends Service
{
    public function getExams(): Collection
    {
        try {
            return DB::table('exams')
                ->join('sessions', 'exams.session_id', '=', 'sessions.id')
                ->select(
                    'exams.id as id',
                    'sessions.name as session_name',
                    'sessions.time_start as session_time_start',
                    'sessions.time_end as session_time_end',
                    'exams.subject_name as subject_name',
                    'exams.teacher_name as teacher_name',
                    'exams.class_name as class_name',
                    'exams.title as title',
                    'exams.code as code',
                    'exams.total_question as total_question',
                    'exams.date as date',
                    'exams.token as token',
                    'exams.expired_token as expired_token',
                    'exams.type as type',
                )
                ->get();
        } catch (\Throwable $th) {
            $this->writeLog("ExamService::getExams", $th);
            return new Collection();
        }
    }

    public function getExamByID(int $id): Exam
    {
        try {
            return Exam::where('id', $id)->with('session')->first();
        } catch (\Throwable $th) {
            $this->writeLog("ExamService::getExamByID", $th);
            return new Collection();
        }
    }

    public function insertExam(ExamEntity $request):bool | Collection
    {
        try {
            return DB::table('exams')->insert([
                'session_id' => $request->session_id,
                'subject_id' => $request->subject_id,
                'teacher_id' => $request->teacher_id,
                'class_id' => $request->class_id,
                'subject_name' => $request->subject_name,
                'teacher_name' => $request->teacher_name,
                'class_name' => $request->class_name,
                'title' => $request->title,
                'code' => $request->code,
                'total_question' => $request->total_question,
                'date' => $request->date,
                'token' => $request->token,
                'expired_token' => $request->expired_token,
                'type' => $request->type,
            ]);
        } catch (\Throwable $th) {
            $this->writeLog("ExamService::insertExam", $th);
            return new Collection();
        }
    }

    public function updateExam(ExamEntity $request, int $id):bool | Collection
    {
        try {
            $exam = Exam::find($id);
            $exam->session_id = $request->session_id;
            $exam->subject_id = $request->subject_id;
            $exam->teacher_id = $request->teacher_id;
            $exam->class_id = $request->class_id;
            $exam->subject_name = $request->subject_name;
            $exam->teacher_name = $request->teacher_name;
            $exam->class_name = $request->class_name;
            $exam->title = $request->title;
            $exam->code = $request->code;
            $exam->total_question = $request->total_question;
            $exam->date = $request->date;
            $exam->token = $request->token;
            $exam->expired_token = $request->expired_token;
            $exam->type = $request->type;

            return $exam->save();
        } catch (\Throwable $th) {
            $this->writeLog("ExamService::updateExam", $th);
            return new Collection();
        }
    }

    public function deleteExam(int $id):bool | Collection
    {
        try {
            return Exam::destroy($id);
        } catch (\Throwable $th) {
            $this->writeLog("ExamService::deleteExam", $th);
            return new Collection();
        }
    }

    public function updateStatusExam(int $id):bool | Collection
    {
        try {
            $exam = Exam::find($id);
            $exam->is_active = !$exam->is_active;
            return $exam->save();
        } catch (\Throwable $th) {
            $this->writeLog("ExamService::updateStatusExam", $th);
            return new Collection();
        }
    }
}
