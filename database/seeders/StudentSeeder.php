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
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            (object)[
                'name' => 'Student 1',
                'email' => 'student1@gmail.com',
                'password' => 'password',
                'address' => 'Jl. Student 1',
                'birth_date' => Carbon::parse('02/03/2003'),
                'gender' => 'L'
            ],
            (object)[
                'name' => 'Student 2',
                'email' => 'student2@gmail.com',
                'password' => 'password',
                'address' => 'Jl. Student 2',
                'birth_date' => Carbon::parse('02/03/2003'),
                'gender' => 'L'
            ],
            (object)[
                'name' => 'Student 3',
                'email' => 'student3@gmail.com',
                'password' => 'password',
                'address' => 'Jl. Student 3',
                'birth_date' => Carbon::parse('02/03/2003'),
                'gender' => 'L'
            ],
            (object)[
                'name' => 'Student 4',
                'email' => 'student4@gmail.com',
                'password' => 'password',
                'address' => 'Jl. Student 4',
                'birth_date' => Carbon::parse('02/03/2003'),
                'gender' => 'L'
            ],
            (object)[
                'name' => 'Student 5',
                'email' => 'student5@gmail.com',
                'password' => 'password',
                'address' => 'Jl. Student 5',
                'birth_date' => Carbon::parse('02/03/2003'),
                'gender' => 'L'
            ],
            (object)[
                'name' => 'Student 6',
                'email' => 'student6@gmail.com',
                'password' => 'password',
                'address' => 'Jl. Student 6',
                'birth_date' => Carbon::parse('02/03/2003'),
                'gender' => 'L'
            ]
        ];

        try {
            DB::beginTransaction();

            // create default class
            $class = CLasses::create([
                'name' => 'Kelas 7 - A',
                'description' => null
            ]);

            // create default user as student
            foreach ($data as $item) {
                $user = User::create([
                    'name' => $item->name,
                    'email' => $item->email,
                    'password' => Hash::make($item->password),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]);

                // create default student detail
                Student::create([
                    'nis' => random_int(100000, 999999),
                    'nisn' => random_int(100000, 999999),
                    'address' => $item->address,
                    'birth_date' => $item->birth_date,
                    'gender' => $item->gender,
                    'password' => Crypt::encryptString($item->password),
                    'user_id' => $user->id,
                    'class_id' => $class->id
                ]);

                // user assign role to student
                $user->assignRole('student');
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }
}
