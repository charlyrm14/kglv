<?php

namespace App\Http\Controllers;

use App\Enums\StatusContent;
use App\Http\Requests\UpdateContentStatusRequest;
use App\Models\Content;
use App\Resources\ContentResource;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class ContentController extends Controller
{
    private $default_cover_image = 'uploads/swimming-categories/swimmer.png';

    /**
     * Retrieves a list of content items based on the authenticated user's role.
     *
     * This method attempts to authenticate the user using a JWT token.
     * - If the user is not found or authentication fails, it returns a 404 error.
     * - If the authenticated user has a role ID different from 1 (non-admin),
     *   only active content items (where 'active' = 1) are returned.
     * - Admin users (role ID = 1) receive all content items regardless of their active status.
     * 
     * The content items are loaded along with their associated 'contentCategory' relationship.
     * 
     * In case of any unexpected exceptions during execution, a 500 server error response is returned.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse 
    {
        try {
            
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            $query = Content::query();

            if( (int) $user->role_id !== 1) {
                $query->where('active', 1);
            } 

            $contents = $query->with('contentCategory')->get();

            if($contents->isEmpty()) return response()->json(['message' => 'No se encontraron resultados'], 404);

        } catch (\Exception $e) {
            
            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'data' => $contents
        ], 200);
    }

    /**
     * The function retrieves and displays event details based on a given slug in PHP, handling errors
     * appropriately.
     * 
     * @param string slug The `show` function you provided is a controller method that retrieves an
     * event based on the provided slug and returns a JSON response. The `slug` parameter is a string
     * that is used to identify the specific event to be shown.
     * 
     * @return JsonResponse If the event with the provided slug is found, a JSON response with a
     * success message and the data of the event in the EventResource format will be returned with a
     * status code of 200. If the event is not found, a JSON response with a message indicating the
     * resource was not found and a status code of 404 will be returned. If an exception occurs during
     * the process, a JSON response
     */
    public function show(string $slug): JsonResponse
    {
        try {

            $content = Content::getContBySlug($slug);
            
            if(!$content) return response()->json(['message' => 'Recurso no encontrado'], 404);

        } catch (\Exception $e) {
            
            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'message' => 'success',
            'data' => new ContentResource($content)
        ], 200);
    }

    /**
     * Update the activation status of a content item by its slug.
     *
     * This endpoint receives a validated request containing the new status (active/inactive)
     * and updates the corresponding content record. If the content is not found,
     * it returns a 404 error. On success, it returns a JSON response with the updated
     * status and a human-readable label.
     *
     * @param  UpdateContentStatusRequest  $request  The validated request containing the new status value.
     * @param  string  $slug  The unique slug identifying the content item to update.
     * @return JsonResponse
     *
     *
     * @throws \Exception if an unexpected error occurs during the operation.
     */
    public function updateStatus(UpdateContentStatusRequest $request, string $slug): JsonResponse
    {
        try {

            $content = Content::getContBySlug($slug);
            
            if(!$content) return response()->json(['message' => 'Recurso no encontrado'], 404);

            $content->active = $request->active;
            $content->save();

            $statusLabel = StatusContent::from($request->active)->label();

            return response()->json([
                'message' => "Se ha actualizado el estatus del contenido a {$statusLabel}",
                'data' => [
                    'title' => $content->title,
                    'status' => $content->active,
                    'status_label' => $statusLabel
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json(["error" => 'Error del servidor'], 500);
        }
    }

    /**
     * Deletes a content item by its slug.
     *
     * This method attempts to find a content record using the provided slug.
     * - If the content is not found, it returns a 404 Not Found response.
     * - If found, it deletes the content from the database.
     * 
     * In case of an unexpected error during the operation, it returns a 500 Internal Server Error response.
     * 
     * @param string $slug The unique slug identifier of the content item.
     * 
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the result of the operation.
     * - 200 OK with success message on successful deletion.
     * - 404 Not Found if the content item does not exist.
     * - 500 Internal Server Error in case of an exception.
     */
    public function delete(string $slug): JsonResponse
    {
        try {

            $content = Content::getContBySlug($slug);
            
            if(!$content) return response()->json(['message' => 'Recurso no encontrado'], 404);

            if($content->cover_image !== $this->default_cover_image) {
                FileService::deleteFile($content->cover_image);
            }
            
            $content->delete();

        } catch (\Exception $e) {
            
            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'message' => 'Contenido eliminado con éxito'
        ], 200); 
    }
}
