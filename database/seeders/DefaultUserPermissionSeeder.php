<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Admin;
use App\Models\SchoolProfile;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DefaultUserPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultValue = [
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];

        DB::beginTransaction();
        try {
            // school profile default
            // $schoolProfile = SchoolProfile::create([
            //     'name' => 'MTs Faqih Hasyim',
            //     'contact' => '03124511452',
            //     'email' => 'mts.faqih@cbt.com',
            //     'address' => 'Jl. Siwalan Panji No. 1',
            //     'district' => 'Buduran',
            //     'regency' => 'Sidoarjo',
            //     'province' => 'Jawa Timur',
            //     'acreditation' => 'A',
            // ]);

            // User default as admin user
            $admin = User::create(array_merge([
                'email' => 'admin@gmail.com',
                'name' => 'Admin'
            ], $defaultValue));

            // Detail user default as admin user
            $detailAdmin = Admin::create([
                'user_id' => $admin->id,
                'nip' => '1234567890',
                'address' => 'Jl. Admin',
                'phone' => '081234567890'
            ]);

            $role_admin = Role::create(['name' => 'admin']);
            $role_teacher= Role::create(['name' => 'teacher']);
            $role_student = Role::create(['name' => 'student']);

            // permission for general feature
            $permission = Permission::create(['name' => 'dashboard']);
            $permission = Permission::create(['name' => 'change_password']);

            // give permission for general feature
            $role_admin->givePermissionTo('dashboard');
            // $role_teacher->givenPermissionTo('dashboard');
            // $role_student->givenPermissionTo('dashboard');
            $role_admin->givePermissionTo('change_password');

            // permission for list parent dropdown menu
            $permission = Permission::create(['name' => 'master']);

            // give permission for list parent dropdown menu
            $role_admin->givePermissionTo('master');

            // create permission for master admin
            $permission = Permission::create(['name' => 'list_admin']);
            $permission = Permission::create(['name' => 'create_admin']);
            $permission = Permission::create(['name' => 'update_admin']);
            $permission = Permission::create(['name' => 'delete_admin']);

            // give permission to role admin for master admin
            $role_admin->givePermissionTo('list_admin');
            $role_admin->givePermissionTo('create_admin');
            $role_admin->givePermissionTo('update_admin');
            $role_admin->givePermissionTo('delete_admin');

            // create permission for master teacher
            $permission = Permission::create(['name' => 'list_teacher']);
            $permission = Permission::create(['name' => 'create_teacher']);
            $permission = Permission::create(['name' => 'update_teacher']);
            $permission = Permission::create(['name' => 'delete_teacher']);

            // give permission to role teacher for master teacher
            $role_admin->givePermissionTo('list_teacher');
            $role_admin->givePermissionTo('create_teacher');
            $role_admin->givePermissionTo('update_teacher');
            $role_admin->givePermissionTo('delete_teacher');

            // create permission for master student
            $permission = Permission::create(['name' => 'list_student']);
            $permission = Permission::create(['name' => 'create_student']);
            $permission = Permission::create(['name' => 'update_student']);
            $permission = Permission::create(['name' => 'delete_student']);

            // give permission to role student for master student
            $role_admin->givePermissionTo('list_student');
            $role_admin->givePermissionTo('create_student');
            $role_admin->givePermissionTo('update_student');
            $role_admin->givePermissionTo('delete_student');

            // Set role for default admin user
            $admin->assignRole('admin');

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }
}
