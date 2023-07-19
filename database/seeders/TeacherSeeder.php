<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Ramsey\Uuid\Uuid;

class TeacherSeeder extends Seeder
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

            // create default user as teacher
            $user = User::create([
                'name' => 'Teacher',
                'email' => 'teacher@gmail.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);

            // create default subject
            $code = substr(Uuid::uuid4()->toString(), 0, 8);
            $subject = Subject::create([
                'name' => 'Matematika',
                'code' => strtoupper($code),
                'description' => null,
            ]);

            // create default teacher detail
            Teacher::create([
                'nip' => '1234567890',
                'address' => 'Jl. Teacher',
                'phone' => '081234567890',
                'user_id' => $user->id,
                'subject_id' => $subject->id,
            ]);

            $role = Role::create(['name' => 'teacher']);

            // user assign role to teacher
            $user->assignRole('teacher');

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
