<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteFileRequest;
use App\Http\Requests\StoreFileRequest;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;


class FileController extends Controller
{
    /**
     * Handle file upload request.
     *
     * Receives a validated file from the client and delegates the storage process to the FileService.
     * Returns a JSON response indicating success or failure.
     *
     * @param StoreFileRequest $request The validated request containing the uploaded file.
     * @return \Illuminate\Http\JsonResponse JSON response with status and path if successful.
     */
    public function uploadFile(StoreFileRequest $request) : JsonResponse
    {
        try {

            $path_file = FileService::storeFile($request->file('file'));

            if(is_null($path_file)) return response()->json(['message' => 'Algo salió mal, inténtalo de nuevo más tarde'], 500);

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        } 

        return response()->json([
            'message' => 'Archivo guardado correctamente',
            'data' => [
                'path' => $path_file
            ]
        ], 201);
    }

    public function deleteFile(DeleteFileRequest $request) : JsonResponse
    {
        try {

            $file = FileService::deleteFile($request->file_path);

            if(!$file) return response()->json(['message' => 'El archivo especificado no éxiste'], 500);

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        } 

        return response()->json([
            'message' => 'Archivo eliminado correctamente'
        ], 200);
    }
}
