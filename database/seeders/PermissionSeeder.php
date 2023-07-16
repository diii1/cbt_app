<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // permission for school profile feature
        $permission = Permission::create(['name' => 'school_profile']);

        // permission for master menu
        $permission = Permission::create(['name' => 'master']);

        // permission for admin feature
        $permission = Permission::create(['name' => 'list_admin']);
        $permission = Permission::create(['name' => 'create_admin']);
        $permission = Permission::create(['name' => 'read_admin']);
        $permission = Permission::create(['name' => 'update_admin']);
        $permission = Permission::create(['name' => 'delete_admin']);

        // permission for teacher feature
        $permission = Permission::create(['name' => 'list_teacher']);
        $permission = Permission::create(['name' => 'create_teacher']);
        $permission = Permission::create(['name' => 'read_teacher']);
        $permission = Permission::create(['name' => 'update_teacher']);
        $permission = Permission::create(['name' => 'delete_teacher']);

        // permission for student feature
        $permission = Permission::create(['name' => 'list_student']);
        $permission = Permission::create(['name' => 'create_student']);
        $permission = Permission::create(['name' => 'read_student']);
        $permission = Permission::create(['name' => 'update_student']);
        $permission = Permission::create(['name' => 'delete_student']);
    }
}
