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
        $permission = Permission::create(['name' => 'user']);
        $permission = Permission::create(['name' => 'exam']);

        // permission for general change password user
        $permission = Permission::create(['name' => 'change_password']);

        // permission for admin feature
        $permission = Permission::create(['name' => 'list_admin']);
        $permission = Permission::create(['name' => 'create_admin']);
        $permission = Permission::create(['name' => 'read_admin']);
        $permission = Permission::create(['name' => 'update_admin']);
        $permission = Permission::create(['name' => 'delete_admin']);

        // permission for subject feature
        $permission = Permission::create(['name' => 'list_subject']);
        $permission = Permission::create(['name' => 'create_subject']);
        $permission = Permission::create(['name' => 'read_subject']);
        $permission = Permission::create(['name' => 'update_subject']);
        $permission = Permission::create(['name' => 'delete_subject']);

        // permission for teacher feature
        $permission = Permission::create(['name' => 'list_teacher']);
        $permission = Permission::create(['name' => 'create_teacher']);
        $permission = Permission::create(['name' => 'read_teacher']);
        $permission = Permission::create(['name' => 'update_teacher']);
        $permission = Permission::create(['name' => 'delete_teacher']);
        $permission = Permission::create(['name' => 'import_teacher']);
        $permission = Permission::create(['name' => 'export_teacher']);

        // permission for class feature
        $permission = Permission::create(['name' => 'list_class']);
        $permission = Permission::create(['name' => 'create_class']);
        $permission = Permission::create(['name' => 'read_class']);
        $permission = Permission::create(['name' => 'update_class']);
        $permission = Permission::create(['name' => 'delete_class']);

        // permission for student feature
        $permission = Permission::create(['name' => 'list_student']);
        $permission = Permission::create(['name' => 'create_student']);
        $permission = Permission::create(['name' => 'read_student']);
        $permission = Permission::create(['name' => 'update_student']);
        $permission = Permission::create(['name' => 'delete_student']);
        $permission = Permission::create(['name' => 'import_student']);
        $permission = Permission::create(['name' => 'export_student']);

        // permission for session feature
        $permission = Permission::create(['name' => 'list_session']);
        $permission = Permission::create(['name' => 'create_session']);
        $permission = Permission::create(['name' => 'read_session']);
        $permission = Permission::create(['name' => 'update_session']);
        $permission = Permission::create(['name' => 'delete_session']);
    }
}
