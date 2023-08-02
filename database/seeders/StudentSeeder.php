<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Classes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();

            // create default user as student
            $user = User::create([
                'name' => 'Student',
                'email' => 'student@gmail.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);

            // create default class
            $class = CLasses::create([
                'name' => 'Kelas 7 - A',
                'description' => null
            ]);

            // create default student detail
            Student::create([
                'nis' => '0002211',
                'nisn' => '00201221',
                'address' => 'Jl. Student',
                'birth_date' => Carbon::parse('02/03/2003'),
                'password' => Crypt::encryptString('password'),
                'user_id' => $user->id,
                'class_id' => $class->id
            ]);

            $role = Role::create(['name' => 'student']);

            // user assign role to student
            $user->assignRole('student');

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }
}
