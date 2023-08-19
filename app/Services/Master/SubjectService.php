<?php

namespace App\Services\Master;

use App\Models\Subject;
use App\Services\Service;
use App\Types\Entities\SubjectEntity;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SubjectService extends Service
{
    public function getSubjects(): Collection
    {
        try {
            return DB::table('subjects')->get();
        } catch (\Throwable $th) {
            $this->writeLog("SubjectService::getSubjects", $th);
            return new Collection();
        }
    }

    public function getSubjectByID(int $id): Subject | Collection
    {
        try {
            $subject = Subject::where('id', $id)->first();
            return $subject;
        } catch (\Throwable $th) {
            $this->writeLog("SubjectService::getSubjectByID", $th);
            return new Collection();
        }
    }

    public function insertSubject(SubjectEntity $request): bool | Collection
    {
        try {
            return DB::table('subjects')->insert([
                'name' => $request->name,
                'code' => $request->code,
                'description' => $request->description,
            ]);
        } catch (\Throwable $th) {
            $this->writeLog("SubjectService::insertSubject", $th);
            return new Collection();
        }
    }

    public function updateSubject(SubjectEntity $request, int $id): bool | Collection
    {
        try {
            return DB::table('subjects')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);
        } catch (\Throwable $th) {
            $this->writeLog("SubjectService::updateSubject", $th);
            return new Collection();
        }
    }

    public function deleteSubject(int $id): bool | Collection
    {
        try {
            return DB::table('subjects')->where('id', $id)->delete();
        } catch (\Throwable $th) {
            $this->writeLog("SubjectService::deleteSubject", $th);
            return new Collection();
        }
    }
}
