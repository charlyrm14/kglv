<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Resources\UserResource;
use App\Services\PasswordService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{

    /**
     * This PHP function retrieves users with a specific role and returns them as JSON response.
     * 
     * @return JsonResponse The `index` function is returning a JSON response. If the ``
     * collection is not empty, it will return a JSON response with the data containing the users. If
     * the `` collection is empty, it will return a JSON response with a message indicating that
     * no results were found. If an exception occurs during the process, it will return a JSON response
     * with an error message.
     */
    public function index() : JsonResponse
    {
        try {
            
            $users = User::getUsers()->with('role')->get();

            if($users->isEmpty()) response()->json(["message" => 'No se encontraron resultados'], 500);

        } catch (\Exception $e) {

            return response()->json(["error" => $e->getMessage()], 500);
        }

        return response()->json([
            'data' => $users
        ], 200);
    }

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

            $password = PasswordService::generateRandomPassword(10);
            $data = $request->validated() + ['password' => $password];

            $user = User::create($data);

            $user->load('role'); // carga la relación del rol del usuario creado para agregarla en la respuesta

        } catch (\Exception $e) {

            return response()->json(["error" => $e->getMessage()], 500);
        }

        return response()->json([
            'message' => 'Hemos enviado un email con los datos de acceso al usuario',
            'data' => new UserResource($user)
        ], 201);
    }

    public function delete(int $user_id) : JsonResponse
    {
        try {
            
            $user = User::ById($user_id)->first();

            if(!$user) return response()->json(['message' => 'Usuario no encontrado'], 404);

            $user->delete();

        } catch (\Exception $e) {

            return response()->json(["error" => $e->getMessage()], 500);
        }

        return response()->json([
            'message' => 'Usuario eliminado con éxito'
        ], 200);
    }
}
