<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
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
            // User default as admin user
            $admin = User::create(array_merge([
                'email' => 'admin@gmail.com',
                'name' => 'Admin'
            ], $defaultValue));

            $role_admin = Role::create(['name' => 'admin']);
            $role_teacher= Role::create(['name' => 'teacher']);
            $role_student = Role::create(['name' => 'student']);

            // create permission for master admin
            $permission = Permission::create(['name' => 'read admin']);
            $permission = Permission::create(['name' => 'create admin']);
            $permission = Permission::create(['name' => 'update admin']);
            $permission = Permission::create(['name' => 'delete admin']);

            // give permission to role admin for master admin
            $role_admin->givePermissionTo('read admin');
            $role_admin->givePermissionTo('create admin');
            $role_admin->givePermissionTo('update admin');
            $role_admin->givePermissionTo('delete admin');

            // Set role for default admin user
            $admin->assignRole('admin');

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }
}
