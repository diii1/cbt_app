<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            SchoolProfileSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            AdminSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
            SessionSeeder::class,
            ExamSeeder::class,
            ExamParticipantSeeder::class,
            QuestionSeeder::class,
            // DefaultUserPermissionSeeder::class
        ]);
    }
}
