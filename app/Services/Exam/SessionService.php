<?php

namespace App\Services\Exam;

use App\Models\Session;
use App\Services\Service;
use App\Types\Entities\SessionEntity;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SessionService
{
    public function getSessions(): Collection
    {
        try {
            return DB::table('sessions')->get();
        } catch (\Throwable $th) {
            $this->writeLog("SessionService::getSessions", $th);
            return new Collection();
        }
    }

    public function getSessionByID($id): Session | Collection
    {
        try {
            return Session::where('id', $id)->first();
        } catch (\Throwable $th) {
            $this->writeLog("SessionService::getSessionByID", $th);
            return new Collection();
        }
    }

    public function insertSession(SessionEntity $request): bool | Collection
    {
        try {
            return DB::table('sessions')->insert([
                'name' => $request->name,
                'time_start' => $request->time_start,
                'time_end' => $request->time_end,
            ]);
        } catch (\Throwable $th) {
            $this->writeLog("SessionService::insertSession", $th);
            return new Collection();
        }
    }

    public function updateSession(SessionEntity $request, int $id): bool | Collection
    {
        try {
            return DB::table('sessions')
                ->where('id', $id)
                ->update([
                    'name' => $request->name,
                    'time_start' => $request->time_start,
                    'time_end' => $request->time_end,
                ]);
        } catch (\Throwable $th) {
            $this->writeLog("SessionService::updateSession", $th);
            return new Collection();
        }
    }

    public function deleteSession(int $id): bool | Collection
    {
        try {
            return DB::table('sessions')
                ->where('id', $id)
                ->delete();
        } catch (\Throwable $th) {
            $this->writeLog("SessionService::deleteSession", $th);
            return new Collection();
        }
    }
}
