<?php

namespace App\Services\Master;

use App\Models\Teacher;
use App\Models\User;
use App\Types\Entities\TeacherEntity;
use App\Types\Entities\UserEntity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\Service;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class TeacherService extends Service
{
    public function getTeachers(): Collection
    {
        try {
            return DB::table('teachers')
            ->join('users', 'teachers.user_id', '=', 'users.id')
            ->join('subjects', 'teachers.subject_id', '=', 'subjects.id')
            ->select(
                'teachers.user_id as id',
                'users.name as name',
                'users.email as email',
                'subjects.name as subject',
                'teachers.nip as nip',
                'teachers.phone as phone',
                'teachers.address as address'
            )
            ->get();
        } catch (\Throwable $th) {
            $this->writeLog("TeacherService::getTeachers", $th);
            return new Collection();
        }
    }

    public function getTeacherByID(int $id): Teacher
    {
        try {
            return Teacher::where('user_id', $id)->with('user', 'subject')->first();
        } catch (\Throwable $th) {
            $this->writeLog("TeacherService::getTeacherByID", $th);
            return new Collection();
        }
    }

    public function getTeacherBySubjectID(int $id): Collection
    {
        try {
            return DB::table('teachers')
                ->join('users', 'teachers.user_id', '=', 'users.id')
                ->join('subjects', 'teachers.subject_id', '=', 'subjects.id')
                ->select(
                    'teachers.user_id as id',
                    'users.name as name'
                )
                ->where('teachers.subject_id', $id)
                ->get();
        } catch (\Throwable $th) {
            $this->writeLog("TeacherService::getTeacherBySubjectID", $th);
            return new Collection();
        }
    }

    public function insertTeacher(TeacherEntity $request): bool | Collection
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);
            $user->email_verified_at = now();
            $user->remember_token = Str::random(10);
            $user->assignRole('teacher');
            $user->save();

            $teacher = DB::table('teachers')->insert([
                'user_id' => $user->id,
                'subject_id' => $request->subject_id,
                'nip' => $request->nip,
                'address' => $request->address,
                'phone' => $request->phone,
            ]);

            if($user && $teacher) return true;
        } catch (\Throwable $th) {
            $this->writeLog("TeacherService::insertTeacher", $th);
            return new Collection();
        }
    }

    public function updateTeacher(TeacherEntity $request, int $id): bool | Collection
    {
        try {
            $result = false;
            $teacher = Teacher::find($id);
            $user = User::find($id);

            if($teacher && $user) {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->save();

                $teacher->subject_id = $request->subject_id;
                $teacher->nip = $request->nip;
                $teacher->address = $request->address;
                $teacher->phone = $request->phone;
                $teacher->save();

                if($user && $teacher) $result = true;
            }

            return $result;
        } catch (\Throwable $th) {
            $this->writeLog("TeacherService::updateTeacher", $th);
            return new Collection();
        }
    }

    public function deleteTeacher(int $id): bool | Collection
    {
        try {
            $result = false;
            $teacher = Teacher::find($id);
            $user = User::find($id);

            if($teacher && $user) {
                $teacher->delete();
                $user->delete();

                $result = true;
            }

            return $result;
        } catch (\Throwable $th) {
            $this->writeLog("TeacherService::deleteTeacher", $th);
            return new Collection();
        }
    }
}
