<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * The function creates a new user based on the validated request data and returns a JSON response
     * with a success message or an error message.
     * 
     * @param StoreUserRequest request The `create` function you provided is a controller method that
     * handles the creation of a new user based on the data provided in the `StoreUserRequest` request.
     * Here's a breakdown of the function:
     * 
     * @return JsonResponse A JSON response is being returned. If the user creation is successful, a
     * JSON response with a success message is returned with status code 201 (Created). If an exception
     * occurs during the process, a JSON response with an error message containing the exception
     * message is returned with status code 500 (Internal Server Error).
     */
    public function create(StoreUserRequest $request) : JsonResponse
    {
        try {

            User::create($request->validated());

            return response()->json([
                'message' => 'Usuario credo con éxito, revisa tu email para confirmar tu cuenta'
            ], 201);

        } catch (\Exception $e) {

            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}
