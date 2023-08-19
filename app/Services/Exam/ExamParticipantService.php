<?php

namespace App\Services\Exam;

use App\Models\ExamParticipant;
use App\Models\Exam;
use App\Types\Entities\ExamParticipantEntity;
use Illuminate\Support\Facades\DB;
use App\Services\Service;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ExamParticipantService extends Service
{
    public function getExamParticipantByExamID(int $exam_id): Collection
    {
        try {
            return DB::table('exam_participants')
                ->join('students', 'exam_participants.student_id', '=', 'students.user_id')
                ->join('users', 'students.user_id', '=', 'users.id')
                ->join('exams', 'exam_participants.exam_id', '=', 'exams.id')
                ->select(
                    'exam_participants.id as id',
                    'exam_participants.exam_id as exam_id',
                    'exam_participants.student_id as student_id',
                    'users.name as student_name',
                    'students.nis as student_nis',
                    'students.nisn as student_nisn',
                    'exams.class_name as class_name',
                )
                ->where('exam_id', $exam_id)
                ->get();
        } catch (\Throwable $th) {
            $this->writeLog("ExamParticipantService::getExamParticipantByExamID", $th);
            return new Collection();
        }
    }

    public function getExamParticipantByStudentID(int $student_id): Collection
    {
        $todayDate = Carbon::now()->format('Y-m-d');
        try {
            return ExamParticipant::where('student_id', $student_id)
                ->where('is_submitted', 0)
                ->whereHas('exam', function ($query) use ($todayDate) {
                    $query->whereRaw("DATE_FORMAT(date, '%Y-%m-%d') >= ?", [$todayDate]);
                })
                ->with('exam')
                ->get();
        } catch (\Throwable $th) {
            $this->writeLog("ExamParticipantService::getExamParticipantByStudentID", $th);
            return new Collection();
        }
    }

    public function getParticipantCards($exam_id): Collection
    {
        try {
            return DB::table('exam_participants')
                ->join('students', 'exam_participants.student_id', '=', 'students.user_id')
                ->join('users', 'students.user_id', '=', 'users.id')
                ->join('exams', 'exam_participants.exam_id', '=', 'exams.id')
                ->select(
                    'users.name as name',
                    'users.email as email',
                    'students.nis as nis',
                    'students.password as password',
                    'exams.class_name as class_name',
                )
                ->where('exam_id', $exam_id)
                ->get()->chunk(2);
        } catch (\Throwable $th) {
            $this->writeLog("ExamParticipantService::getExamParticipants", $th);
            return new Collection();
        }
    }

    public function insertExamParticipant(array $data): bool | Collection
    {
        try {
            return DB::table('exam_participants')->insert($data);
        } catch (\Throwable $th) {
            $this->writeLog("ExamParticipantService::insertExamParticipant", $th);
            return new Collection();
        }
    }

    public function deleteExamParticipant(int $id): bool | Collection
    {
        try {
            return ExamParticipant::destroy($id);
        } catch (\Throwable $th) {
            $this->writeLog("ExamParticipantService::deleteExamParticipant", $th);
            return new Collection();
        }
    }
}
