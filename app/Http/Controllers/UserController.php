<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Resources\UserResource;
use App\Services\PasswordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegisteredMail;
use App\Resources\InfoUserResource;
use Tymon\JWTAuth\Facades\JWTAuth;

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
    public function index(): JsonResponse
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
    public function create(StoreUserRequest $request): JsonResponse
    {
        try {

            $password = PasswordService::generateRandomPassword(10);
            $data = $request->validated() + ['password' => $password];

            $user = User::create($data);

            $user->load('role'); // carga la relación del rol del usuario creado para agregarla en la respuesta
            
            Mail::to($request->email)
                ->send(new UserRegisteredMail($user, $password));

        } catch (\Exception $e) {

            return response()->json(["error" => $e->getMessage()], 500);
        }

        return response()->json([
            'message' => 'Hemos enviado un email con los datos de acceso al usuario',
            'data' => new UserResource($user)
        ], 201);
    }

    /**
     * Display the specified user along with active schedules and swimming levels.
     *
     * This method retrieves a user by their ID and includes related data:
     * - Active class schedules (status = 1)
     * - All swimming levels the user has been assigned (from the pivot table)
     * - Automatically appends the user's current_swimming_level based on the highest swimming_level_id
     *
     * @param int $user_id The ID of the user to retrieve
     * @return \Illuminate\Http\JsonResponse JSON response containing the user and related data
     *
     * @throws \Exception If there is an error while retrieving the data
     */
    public function show(int $user_id): JsonResponse
    {
        try {

            $user = User::ById($user_id)->with([
                'role',
                'activeSchedules', 
                'swimmingLevels.swimmingLevel'
            ])->first();

            if(!$user) return response()->json(['message' => 'Usuario no encontrado'], 404);

        } catch (\Exception $e) {

            return response()->json(["error" => $e->getMessage()], 500);
        }

        return response()->json([
            'data' => new InfoUserResource($user)
        ], 200);
    }

    /**
     * This PHP function retrieves users with a specific email and returns them as JSON response.
     * 
     * @return JsonResponse The `searchByEmail` function is returning a JSON response. If the ``
     * collection is not empty, it will return a JSON response with the data containing the users. If
     * the `` collection is empty, it will return a JSON response with a message indicating that
     * no results were found. If an exception occurs during the process, it will return a JSON response
     * with an error message.
     */
    public function searchByEmail(string $email): JsonResponse
    {
        try {

            $user = User::byEmail($email)->with(['role' => function($query){
                $query->select('id', 'name');
            }])->first();

            if(!$user) return response()->json(['message' => 'Usuario no encontrado'], 404);

        } catch (\Exception $e) {

            return response()->json(["error" => $e->getMessage()], 500);
        }

        return response()->json([
            'data' => $user
        ], 200);
    }

    /**
     * The function delete a user based on the specific user id  and returns a JSON response
     * with a success message or an error message.
     * 
     * @param StoreUserRequest request The `delete` function you provided is a controller method that
     * handles the delete of a user based on the id provided
     * 
     * @return JsonResponse A JSON response is being returned. If the user delete is successful, a
     * JSON response with a success message is returned with status code 200. If an exception
     * occurs during the process, a JSON response with an error message containing the exception
     * message is returned with status code 500 (Internal Server Error).
     */
    public function delete(int $user_id): JsonResponse
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            if($user->role_id !== 1) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
            
            $user_to_delete = User::byId($user_id)->first();

            if(!$user_to_delete) return response()->json(['message' => 'Usuario no encontrado'], 404);

            if($user->id === $user_id || in_array($user_to_delete->id, [1, 2])) {
                return response()->json(['message' => 'No se puede procesar esta solicitud'], 401);
            }

            $user_to_delete->delete();

        } catch (\Exception $e) {

            return response()->json(["error" => $e->getMessage()], 500);
        }

        return response()->json([
            'message' => 'Usuario eliminado con éxito'
        ], 200);
    }
}
