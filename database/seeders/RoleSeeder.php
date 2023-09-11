<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            /** Start Create Role and set Permission for Admin */
            $admin = Role::create(['name' => 'admin']);

            // permission for prefix school profile
            $admin->givePermissionTo('school_profile');
            $admin->givePermissionTo('update_school_profile');

            // permission for prefix master menu
            $admin->givePermissionTo('master');

            // permission for prefix pengguna menu
            $admin->givePermissionTo('user');
            $admin->givePermissionTo('exam');

            // permission for general change_password user
            $admin->givePermissionTo('change_password');

            // permission for prefix pengguna/admin menu
            $admin->givePermissionTo('list_admin');
            $admin->givePermissionTo('create_admin');
            $admin->givePermissionTo('read_admin');
            $admin->givePermissionTo('update_admin');
            $admin->givePermissionTo('delete_admin');

            // permission for prefix master/subject menu
            $admin->givePermissionTo('list_subject');
            $admin->givePermissionTo('create_subject');
            $admin->givePermissionTo('read_subject');
            $admin->givePermissionTo('update_subject');
            $admin->givePermissionTo('delete_subject');

            // permission for prefix pengguna/teacher menu
            $admin->givePermissionTo('list_teacher');
            $admin->givePermissionTo('create_teacher');
            $admin->givePermissionTo('read_teacher');
            $admin->givePermissionTo('update_teacher');
            $admin->givePermissionTo('delete_teacher');
            $admin->givePermissionTo('import_teacher');
            $admin->givePermissionTo('export_teacher');

            // permission for prefix master/class menu
            $admin->givePermissionTo('list_class');
            $admin->givePermissionTo('create_class');
            $admin->givePermissionTo('read_class');
            $admin->givePermissionTo('update_class');
            $admin->givePermissionTo('delete_class');

            // permission for prefix pengguna/student menu
            $admin->givePermissionTo('list_student');
            $admin->givePermissionTo('create_student');
            $admin->givePermissionTo('read_student');
            $admin->givePermissionTo('update_student');
            $admin->givePermissionTo('delete_student');
            $admin->givePermissionTo('import_student');
            $admin->givePermissionTo('export_student');

            // permission for prefix exam/session menu
            $admin->givePermissionTo('list_session');
            $admin->givePermissionTo('create_session');
            $admin->givePermissionTo('read_session');
            $admin->givePermissionTo('update_session');
            $admin->givePermissionTo('delete_session');

            // permission for prefix exam menu
            $admin->givePermissionTo('list_exam');
            $admin->givePermissionTo('create_exam');
            $admin->givePermissionTo('read_exam');
            $admin->givePermissionTo('update_exam');
            $admin->givePermissionTo('delete_exam');

            // permission for prefix exam participant menu
            $admin->givePermissionTo('list_participant');
            $admin->givePermissionTo('create_participant');
            $admin->givePermissionTo('read_participant');
            $admin->givePermissionTo('update_participant');
            $admin->givePermissionTo('delete_participant');
            $admin->givePermissionTo('card_participant');

            // permission for prefix exam question  menu
            $admin->givePermissionTo('list_question');

            // permission for result menu
            $admin->givePermissionTo('list_result');
            $admin->givePermissionTo('export_result');
            /** End Create Role and set Permission for Admin */

            /** Start Create Role and set Permission for Teacher */
            $teacher = Role::create(['name' => 'teacher']);

            // permission for prefix pengguna menu
            $teacher->givePermissionTo('exam');

            // permission for prefix exam menu
            $teacher->givePermissionTo('list_exam');

            // permission for result menu
            $teacher->givePermissionTo('list_result');
            $teacher->givePermissionTo('export_result');

            // pemission for prefix exam question menu
            $teacher->givePermissionTo('list_question');
            $teacher->givePermissionTo('create_question');
            $teacher->givePermissionTo('read_question');
            $teacher->givePermissionTo('update_question');
            $teacher->givePermissionTo('delete_question');
            /** End Create Role and set Permission for Teacher */

            /** Start Create Role and set Permission for Student */
            $student = Role::create(['name' => 'student']);
            /** End Create Role and set Permission for Student */
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
