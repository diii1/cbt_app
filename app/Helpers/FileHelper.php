<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use App\Types\FileMetadata;
use Exception;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

class FileHelper
{
    public static function storeFile(mixed $file, string $storePath, string $disk = "public"): FileMetadata | Exception
    {
        if (!$file) return new Exception("file not found", 400);

        // $fileID = (string) Str::uuid();
        $uuid = substr(Uuid::uuid4()->toString(), 0, 8);
        $currentDate = Carbon::now()->format('Y-m-d');
        $fileID = $uuid . '-' . $currentDate;
        $filename = $uuid . '-' . $currentDate . '.' . $file->extension();

        $metadata = new FileMetadata();
        $metadata->id = $fileID;
        $metadata->path = $file->storeAs($storePath, $filename, $disk);
        $metadata->name = $filename;
        $metadata->extension = $file->extension();

        return $metadata;
    }

    public static function deleteFile(string $disk, string $filename): bool
    {
        if (Storage::disk($disk)->exists($filename))
            return Storage::disk($disk)->delete($filename);

        return false;
    }
}
