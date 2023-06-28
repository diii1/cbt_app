<?php

namespace App\Services\Master;

use App\Models\Admin;
use App\Models\User;
use App\Types\Entities\AdminEntity;
use App\Types\Entities\UserEntity;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminService
{
    public function insertAdmin(AdminEntity $request): int | Exception
    {
        $isSaved = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);

            if (!$user) {
                throw new Exception('Failed to insert user');
            }

            $user->assignRole('admin');

            $admin = Admin::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'address' => $request->address,
                'phone' => $request->phone,
            ]);

            if (!$admin) {
                throw new Exception('Failed to insert admin');
            }

            return true;
        });

        if (!$isSaved) {
            throw new Exception('Failed to insert admin');
        }

        return $isSaved;
    }

    public function deleteAdmin(int $id): int | Exception
    {
        $isDeleted = DB::transaction(function () use ($id) {
            $admin = Admin::find($id);

            if (!$admin) {
                throw new Exception('Admin not found');
            }

            $user = User::find($admin->user_id);

            if (!$user) {
                throw new Exception('User not found');
            }

            $admin->delete();
            $user->delete();

            return true;
        });

        if (!$isDeleted) {
            throw new Exception('Failed to delete admin');
        }

        return $isDeleted;
    }
}
