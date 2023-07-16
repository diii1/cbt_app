<?php

namespace App\Services\Master;

use App\Models\Admin;
use App\Models\User;
use App\Types\Entities\AdminEntity;
use App\Types\Entities\UserEntity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\Service;
use Illuminate\Support\Collection;

class AdminService extends Service
{
    public function getAdminByID(int $id): Admin | Collection
    {
        try {
            $admin = Admin::where('user_id', $id)->with('user')->first();
            return $admin;
        } catch (\Throwable $th) {
            $this->writeLog("AdminService::getAdminByID", $th);
            return new Collection();
        }
    }

    public function insertAdmin(AdminEntity $request): bool | Collection
    {
        try {
            DB::transaction(function () use ($request) {
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
            });
            return true;
        } catch (\Throwable $th) {
            $this->writeLog("AdminService::insertAdmin", $th);
            return new Collection();
        }
    }

    public function updateAdmin(AdminEntity $request, int $id): bool | Collection
    {
        try {
            DB::transaction(function () use ($request, $id) {
                $admin = Admin::find($id);

                if (!$admin) {
                    throw new Exception('Admin not found');
                }

                $user = User::find($admin->user_id);

                if (!$user) {
                    throw new Exception('User not found');
                }

                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                ]);

                $admin->update([
                    'nip' => $request->nip,
                    'address' => $request->address,
                    'phone' => $request->phone,
                ]);
            });
            return true;
        } catch (\Throwable $th) {
            $this->writeLog("AdminService::updateAdmin", $th);
            return new Collection();
        }
    }

    public function deleteAdmin(int $id): bool | Collection
    {
        try {
            DB::transaction(function () use ($id) {
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

            });
            return true;
        } catch (\Throwable $th) {
            $this->writeLog("AdminService::getAdminByID", $th);
            return new Collection();
        }
    }
}
