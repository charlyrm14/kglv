<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreNoticeRequest;
use App\Events\NotificationNewContent;
use App\Http\Requests\UpdateNoticeRequest;
use App\Models\Content;
use App\Services\ContentService;
use App\Services\FileService;
use Illuminate\Http\Exceptions\HttpResponseException;

class NoticeController extends Controller
{
    /**
     * The function creates a new event using validated data from a request, dispatches a notification
     * event, and returns a JSON response with success message and event data.
     * 
     * @param StoreNoticeRequest request The `create` function you provided seems to be a controller
     * method for creating a new notice. It takes a `StoreNoticeRequest` object as a parameter, which
     * likely contains the validated data for creating the event.
     * 
     * @return JsonResponse A JSON response is being returned. If the creation of the notice is
     * successful, a JSON response with a success message and the event data is returned with a status
     * code of 201 (Created). If an exception occurs during the process, a JSON response with an error
     * message is returned with a status code of 500 (Internal Server Error).
     */
    public function create(StoreNoticeRequest $request): JsonResponse
    {
        try {

            $data = $request->validated() + ['content_type_id' => 1];
            $notice = Content::create($data);

            if($notice->active == 1) {
                NotificationNewContent::dispatch($notice);
            }

            $notice->load('contentType');

            return response()->json([
                'message' => 'Aviso creado con éxito',
                'data' => $notice
            ], 201);

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }
    }

    /**
     * The function updates a notice with the provided request data and handles exceptions
     * appropriately.
     * 
     * @param UpdateNoticeRequest request The `update` function in the code snippet is responsible for
     * updating a notice based on the provided request data and slug. Let me explain the parameters
     * involved:
     * @param string slug The `slug` parameter in the `update` function is a string that represents a
     * unique identifier for the content you want to update. It is used to fetch the specific content
     * from the database that matches the provided slug. This slug is typically a part of the URL and
     * helps in identifying the resource.
     * 
     * @return The `update` function is returning a JSON response with a success message and the
     * updated notice data if the update is successful. If the notice is not found, it returns a 404
     * response with a message indicating that the resource was not found. If there is an error during
     * the update process, it returns a 500 response with an error message indicating a server error.
     */
    public function update(UpdateNoticeRequest $request, string $slug): JsonResponse
    {
        $notice = Content::getContentBySlug($slug);
        
        try {
            
            if(!$notice) {
                return response()->json(['message' => 'Recurso no encontrado'], 404);
            }

            ContentService::checkContentType($notice->content_type_id, 1);
            
            if($notice->cover_image !== $request->cover_image) {
                FileService::deleteFile($notice->cover_image);
            }

            $notice->update($request->validated());

            return response()->json([
                'message' => 'Aviso actualizado con éxito',
                'data' => $notice
            ], 200);

        } catch (HttpResponseException $e) {
            // Re-lanzamos para que Laravel maneje correctamente el JSON que ya viene en la excepción
            throw $e;

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }
    }
}
