<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class FileService 
{
    /**
     * Store an uploaded file in the public/uploads directory using a hashed name.
     *
     * The file is saved in a date-based subdirectory (e.g., uploads/2025/05/28/filename.jpg).
     * Returns the relative file path on success, or null if an error occurs.
     *
     * @param \Illuminate\Http\UploadedFile $file The uploaded file instance.
     * @return string|null The relative path of the saved file, or null on failure.
     *
     * Example return value:
     * uploads/2025/05/28/abc123def456.jpg
     */
    public static function storeFile(UploadedFile $file): ?string
    {
        try {

            $now = Carbon::now();
            $folder = 'uploads/' . $now->year . '/' . $now->format('m') . '/' . $now->day . '/';

            $file_name = $file->hashName();

            $file->move(public_path($folder), $file_name);

        } catch (\Exception $e) {

            return null;
        }   

        return $folder . $file_name;
    }

    /**
     * Handle file deletion request.
     *
     * This method receives a validated file path and delegates the file deletion
     * to the FileService. It returns a JSON response indicating whether the operation
     * was successful or if an error occurred.
     *
     * @param DeleteFileRequest $request The validated request containing the file path to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public static function deleteFile(string $file_path): bool
    {        
        if (!File::exists($file_path)) return false;

        File::delete($file_path);

        return true;
    }
}