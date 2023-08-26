<?php

namespace App\Services\General;

use App\Models\SchoolProfile;
use App\Types\Entities\SchoolProfileEntity;
use Exception;
use App\Types\FileMetadata;
use App\Helpers\FileHelper;

class SchoolProfileService
{
    public function storeLogo(mixed $image): FileMetadata | Exception
    {
        return FileHelper::storeFile($image, "uploads/logo", "public");
    }

    public function deleteLogo(mixed $image): bool | Exception
    {
        return FileHelper::deleteFile("public", $filename);
    }

    public function getSchoolProfile(): object | null
    {
        $schoolProfile = SchoolProfile::first();
        if (!$schoolProfile) {
            // throw new Exception('School profile not found');
            return null;
        }
        return $schoolProfile;
    }

    public function insertSchoolProfile(SchoolProfileEntity $profile): string | Exception
    {
        $isSaved = SchoolProfile::create([
            'name' => $profile->name,
            'contact' => $profile->contact,
            'email' => $profile->email,
            'address' => $profile->address,
            'district' => $profile->district,
            'regency' => $profile->regency,
            'province' => $profile->province,
            'acreditation' => $profile->acreditation,
            'logo' => $profile->logo,
        ]);

        if (!$isSaved) {
            throw new Exception('Failed to insert school profile');
        }
        return $isSaved->id;
    }

    public function updateSchoolProfile(SchoolProfileEntity $profile, int $id): bool | Exception
    {
        $schoolProfile = SchoolProfile::find($id);
        if (!$schoolProfile) {
            throw new Exception('School profile not found');
        }

        if ($profile->logo) {
            $this->deleteLogo($schoolProfile->logo);
        }

        $isUpdated = $schoolProfile->update([
            'name' => $profile->name,
            'contact' => $profile->contact,
            'email' => $profile->email,
            'address' => $profile->address,
            'district' => $profile->district,
            'regency' => $profile->regency,
            'province' => $profile->province,
            'acreditation' => $profile->acreditation,
            'logo' => $profile->logo,
        ]);

        if (!$isUpdated) {
            throw new Exception('Failed to update school profile');
        }
        return $isUpdated;
    }
}
