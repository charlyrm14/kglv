<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Resources\InfoResource;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class InfoController extends Controller
{
    /**
     * The function retrieves user information and returns it as a JSON response, handling errors
     * appropriately.
     * 
     * @param int user_id The `appInfo` function takes a user_id as a parameter, which is an integer
     * representing the ID of the user for whom you want to retrieve information. The function then
     * attempts to fetch the user details based on the provided user_id. If the user is found, it
     * returns a JSON response with
     * 
     * @return JsonResponse The `appInfo` function is returning a JSON response. If the user with the
     * specified user ID is found, it will return a JSON response with the user's information in the
     * 'data' key using the `InfoResource` resource class. If the user is not found, it will return a
     * JSON response with a 'Usuario no encontrado' message and a status code of 404. If an
     */
    public function appInfo(): JsonResponse
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'data' => new InfoResource($user)
        ], 200);
    }
}
