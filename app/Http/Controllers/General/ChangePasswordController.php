<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ChangePasswordRequest;
use App\Services\General\ChangePasswordService;
use App\Types\Entities\PasswordEntity;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Crypt;
use App\Models\Student;
use App\Models\User;

class ChangePasswordController extends Controller
{
    private $service;

    public function __construct(ChangePasswordService $service)
    {
        $this->service = $service;
    }

    public function edit(int $id)
    {
        $data['title'] = 'Perbarui Password Pengguna';
        $data['action'] = route('api.user.change_password.update', $id);
        return view('pages.general.change-password.form', ['data' => $data]);
    }

    public function update(ChangePasswordRequest $request, $id)
    {
        $user = User::find($id);
        $role = $user->getRoleNames()[0];
        $validated = $request->validated();
        $password = new PasswordEntity();
        $password->formRequest($validated);
        if ($role == 'student'){
            $student = Student::findOrFail($id);
            $student->password = Crypt::encryptString($validated['password']);
            if(!$student->save()){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data password detail pengguna gagal diperbarui.'
                ], 500);
            }
        }

        $updated = $this->service->updatePassword($password, $id);
        if($updated != []) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data password pengguna berhasil diperbarui.'
            ], 200);
        }
    }
}
