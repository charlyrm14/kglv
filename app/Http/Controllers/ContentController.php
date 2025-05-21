<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Resources\EventResource;
use Illuminate\Http\JsonResponse;

class ContentController extends Controller
{
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
    public function show(string $slug) : JsonResponse
    {
        try {

            $content = Content::getContBySlug($slug);
            
            if(!$content) return response()->json(['message' => 'Recurso no encontrado'], 404);

        } catch (\Exception $e) {
            
            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'message' => 'success',
            'data' => new EventResource($content)
        ], 200);
    }
}
