<?php

namespace App\Services\Master;

use App\Models\Classes;
use App\Services\Service;
use App\Types\Entities\ClassEntity;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ClassService extends Service
{
    public function getClasses(): Collection
    {
        try {
            return DB::table('classes')->get();
        } catch (\Throwable $th) {
            $this->writeLog("ClassService::getClasses", $th);
            return new Collection();
        }
    }

    public function getClassByID(int $id): Classes | Collection
    {
        try {
            $class = Classes::where('id', $id)->first();
            return $class;
        } catch (\Throwable $th) {
            $this->writeLog("ClassService::getClassByID", $th);
            return new Collection();
        }
    }

    public function insertClass(ClassEntity $request): bool | Collection
    {
        try {
            return DB::table('classes')->insert([
                'name' => $request->name,
                'description' => $request->description,
            ]);
        } catch (\Throwable $th) {
            $this->writeLog("ClassService::insertClass", $th);
            return new Collection();
        }
    }

    public function updateClass(ClassEntity $request, int $id): bool | Collection
    {
        try {
            return DB::table('classes')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);
        } catch (\Throwable $th) {
            $this->writeLog("ClassService::updateClass", $th);
            return new Collection();
        }
    }

    public function deleteClass(int $id): bool | Collection
    {
        try {
            return DB::table('classes')
                ->where('id', $id)
                ->delete();
        } catch (\Throwable $th) {
            $this->writeLog("ClassService::deleteClass", $th);
            return new Collection();
        }
    }
}
