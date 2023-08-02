<?php

namespace App\Services\Master;

use App\Models\Student;
use App\Models\User;
use App\Types\Entities\StudentEntity;
use App\Types\Entities\UserEntity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\Service;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class StudentService extends Service
{
    public function getStudents(): Collection
    {
        try {
            return DB::table('students')
                ->join('users', 'students.user_id', '=', 'users.id')
                ->join('classes', 'students.class_id', '=', 'classes.id')
                ->select(
                    'students.user_id as id',
                    'users.name as name',
                    'users.email as email',
                    'classes.name as class',
                    'students.nis as nis',
                    'students.nisn as nisn',
                    'students.address as address',
                    'students.birth_date as birth_date',
                    'students.password as password'
                )
                ->get();
        } catch (\Throwable $th) {
            $this->writeLog("StudentService::getStudents", $th);
            return new Collection();
        }
    }

    public function getStudentByID(int $id): Student
    {
        try {
            return Student::where('user_id', $id)->with('user', 'class')->first();
        } catch (\Throwable $th) {
            $this->writeLog("StudentService::getStudentByID", $th);
            return new Collection();
        }
    }

    public function insertStudent(StudentEntity $request):bool | Collection
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ]);
            $user->email_verified_at = now();
            $user->remember_token = Str::random(10);
            $user->assignRole('student');
            $user->save();

            $student = DB::table('students')->insert([
                'user_id' => $user->id,
                'class_id' => $request->class_id,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'password' => $request->passwordStudent
            ]);

            if($user && $sudent) return true;
        } catch (\Throwable $th) {
            $this->writeLog("StudentService::insertStudent", $th);
            return new Collection();
        }
    }

    public function updateStudent(StudentEntity $request, int $id):bool | Collection
    {
        try {
            $result = false;
            $student = Student::find($id);
            $user = User::find($id);

            if($student && $user){
                $user->name = $request->name;
                $user->email = $request->email;
                $user->save();

                $student->class_id = $request->class_id;
                $student->nis = $request->nis;
                $student->nisn = $request->nisn;
                $student->address = $request->address;
                $student->birth_date = $request->birth_date;
                $student->save();

                if($user && $student) $result = true;
            }

            return $result;
        } catch (\Throwable $th) {
            $this->writeLog("StudentService::updateStudent", $th);
            return new Collection();
        }
    }

    public function deleteStudent(int $id): bool | Collection
    {
        try {
            $result = false;
            $student = Student::find($id);
            $user = User::find($id);

            if($student && $user) {
                $student->delete();
                $user->delete();

                $result = true;
            }

            return $result;
        } catch (\Throwable $th) {
            $this->writeLog("StudentService::deleteStudent", $th);
            return new Collection();
        }
    }
}
