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

            // user assign role to admin
            $user->assignRole('admin');

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
