<?php

namespace App\Services\General;

use App\Services\Service;
use App\Types\Entities\PasswordEntity;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class ChangePasswordService extends Service
{
    public function updatePassword(PasswordEntity $request, int $id): bool | Collection
    {
        try {
            $user = User::find($id);
            $user->password = $request->password;

            if($user->save()) return true;
        } catch (\Throwable $th) {
            $this->writeLog("ChangePasswordService::updatePassword", $th);
            return new Collection();
        }
    }
}
