<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
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

            // create default user as admin
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);

            // create default admin detail
            Admin::create([
                'nip' => '1234567890',
                'address' => 'Jl. Admin',
                'phone' => '081234567890',
                'user_id' => $user->id,
            ]);

            $role = Role::create(['name' => 'admin']);

            // user assign role to admin
            $user->assignRole('admin');

            // permission for prefix school profile
            $role->givePermissionTo('school_profile');

            // permission for prefix master menu
            $role->givePermissionTo('master');

            // permission for prefix pengguna menu
            $role->givePermissionTo('pengguna');

            // permission for general change_password user
            $role->givePermissionTo('change_password');

            // permission for prefix pengguna/admin menu
            $role->givePermissionTo('list_admin');
            $role->givePermissionTo('create_admin');
            $role->givePermissionTo('read_admin');
            $role->givePermissionTo('update_admin');
            $role->givePermissionTo('delete_admin');

            // permission for prefix master/subject menu
            $role->givePermissionTo('list_subject');
            $role->givePermissionTo('create_subject');
            $role->givePermissionTo('read_subject');
            $role->givePermissionTo('update_subject');
            $role->givePermissionTo('delete_subject');

            // permission for prefix pengguna/teacher menu
            $role->givePermissionTo('list_teacher');
            $role->givePermissionTo('create_teacher');
            $role->givePermissionTo('read_teacher');
            $role->givePermissionTo('update_teacher');
            $role->givePermissionTo('delete_teacher');
            $role->givePermissionTo('import_teacher');
            $role->givePermissionTo('export_teacher');

            // permission for prefix master/class menu
            $role->givePermissionTo('list_class');
            $role->givePermissionTo('create_class');
            $role->givePermissionTo('read_class');
            $role->givePermissionTo('update_class');
            $role->givePermissionTo('delete_class');

            // permission for prefix pengguna/student menu
            $role->givePermissionTo('list_student');
            $role->givePermissionTo('create_student');
            $role->givePermissionTo('read_student');
            $role->givePermissionTo('update_student');
            $role->givePermissionTo('delete_student');
            $role->givePermissionTo('import_student');
            $role->givePermissionTo('export_student');

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
