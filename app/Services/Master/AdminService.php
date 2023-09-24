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
use Carbon\Carbon;
use App\Types\FileMetadata;
use App\Helpers\FileHelper;

class AdminService extends Service
{
    public function storeProfile(mixed $image): FileMetadata | Exception
    {
        return FileHelper::storeFile($image, "uploads/profiles", "public");
    }

    public function deleteProfile(mixed $image): bool | Exception
    {
        return FileHelper::deleteFile("public", $image);
    }

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
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'profile' => $request->profile,
            ]);
            $user->email_verified_at = now();
            $user->remember_token = Str::random(10);
            $user->assignRole('admin');
            $user->save();

            $admin = DB::table('admins')->insert([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'address' => $request->address,
                'phone' => $request->phone,
            ]);

            if($user && $admin) return true;
        } catch (\Throwable $th) {
            $this->writeLog("AdminService::insertAdmin", $th);
            return new Collection();
        }
    }

    public function updateAdmin(AdminEntity $request, int $id): bool | Collection
    {
        try {
            $result = false;
            $admin = DB::table('admins')->where('user_id', $id)->first();
            $user = DB::table('users')->where('id', $id)->first();

            $updateAdmin = DB::table('admins')
                ->where('user_id', $id)
                ->update([
                    'nip' => $request->nip,
                    'address' => $request->address,
                    'phone' => $request->phone,
                ]);

            $updateUser = DB::table('users')
                ->where('id', $admin->user_id)
                ->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'profile' => $request->profile ? $request->profile : $user->profile,
                ]);

            if($updateAdmin == 1 && $updateUser == 1) $result = true;
            return $result;
        } catch (\Throwable $th) {
            $this->writeLog("AdminService::updateAdmin", $th);
            return new Collection();
        }
    }

    public function deleteAdmin(int $id): bool | Collection
    {
        try {
            $admin = Admin::find($id);

            $user = User::find($admin->user_id);

            if($admin->delete() && $user->delete()) return true;
        } catch (\Throwable $th) {
            $this->writeLog("AdminService::getAdminByID", $th);
            return new Collection();
        }
    }
}
