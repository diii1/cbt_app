<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ChangePasswordRequest;
use App\Services\General\ChangePasswordService;
use App\Types\Entities\PasswordEntity;

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
        $validated = $request->validated();
        $password = new PasswordEntity();
        $password->formRequest($validated);
        $this->service->updatePassword($password, $id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data password pengguna berhasil diperbarui.'
        ], 200);
    }
}
