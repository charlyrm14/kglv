<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Resources\UserResource;
use App\Services\PasswordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegisteredMail;
use App\Models\Role;
use App\Resources\InfoUserResource;
use App\Services\DateService;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Requests\UpdateProfileImageRequest;
use App\Models\UserProfile;

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
     * Retrieve users by role, including their profiles.
     *
     * This method fetches a role using the specified role name and retrieves
     * all users associated with that role, including their related profile data.
     * 
     * - If the role is not found, or if the role ID is `1` (commonly reserved for admins),
     *   it returns a 404 JSON response.
     * - If an exception occurs during the process, it returns a 500 error response.
     *
     * @param string $role The role slug or name to filter users by.
     *
     * @return \Illuminate\Http\JsonResponse JSON response containing users and profiles,
     * or an error message with appropriate status code.
     */
    public function usersByRole(string $role)
    {
        try {
            
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            $roleData = Role::TeachersAndStudents($role)->first();
            
            if(!$roleData || ($roleData && $roleData->id === 1)) {
                return response()->json(['message' => 'Recurso no encontrado'], 404);
            }

            $roleData->load('users.profile');

            return response()->json([
                'data' => $roleData
            ], 200);

        } catch (\Exception $e) {

            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * Retrieve and return a list of users whose birthday is today.
     *
     * This method authenticates the user via JWT, fetches users 
     * whose birth date matches today's day and month, and calculates 
     * their age using the DateService. If no users are found, it returns 
     * a 404 response. Otherwise, it returns the list of users with their 
     * associated profile and computed age.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function birthdayUsers()
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['message' => 'Usuario no encontrado'], 404);
            }

            $users = User::todayBirthdayUsers();

            if($users->isEmpty()) {
                return response()->json(['message' => 'No se encontraron resultados'], 404);
            }

            $users->each(function($userData) {
                $userData->age = DateService::userAge($userData->birth_date);
            });

            return response()->json([
                'data' => $users
            ], 200);

        } catch (\Exception $e) {

            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * The function updates user data, checks for existing email, and returns a JSON response with
     * success message and updated user information.
     * 
     * @param UpdateUserRequest request The `update` function you provided seems to be handling the
     * update of user information based on the `UpdateUserRequest` request. Let's break down the logic:
     * 
     * @return The `update` function is returning a JSON response with the following structure:
     * - If the user is not found, it returns a 404 response with a message 'Usuario no encontrado'.
     * - If there is already a user with the email provided in the request, it returns a 422 response
     * with a message 'Ya existe un usuario con el correo proporcionado'.
     * - If the email in the request is
     */
    public function update(UpdateUserRequest $request)
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['message' => 'Usuario no encontrado'], 404);
            }

            UserService::checkExistsEmail($request->email, $user->email);

            $change_password = $request->email !== $user->email ? true : false;
            $user->update($request->validated());
            
            return response()->json([
                'message' => 'Datos actualizados con éxito',
                'change_email' => $change_password,
                'data' => $user
            ], 200);

        } catch (HttpResponseException $e) {
            // Re-lanzamos para que Laravel maneje correctamente el JSON que ya viene en la excepción
            throw $e;

        } catch (\Exception $e) {

            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * The function `uploadImageProfile` updates the profile image of a user and returns a success
     * message or an error message if an exception occurs.
     * 
     * @param UpdateProfileImageRequest request The `uploadImageProfile` function is responsible for
     * updating the profile image of a user based on the request data. Here's a breakdown of the
     * function and its parameters:
     * 
     * @return If the user is successfully authenticated and the profile image is updated successfully,
     * a JSON response with the message 'Imagen de perfil actualizada con éxito' and status code 200 is
     * returned. If there is an error during the process, a JSON response with the error message from
     * the exception is returned with a status code of 500. If the user is not found, a JSON response
     * with the message '
     */
    public function uploadImageProfile(UpdateProfileImageRequest $request)
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['message' => 'Usuario no encontrado'], 404);
            }

            $user->update($request->validated());

            return response()->json([
                'message' => 'Imagen de perfil actualizada con éxito',
                'data' => [
                    'profile_image' => $user->profile_image
                ]
            ], 200);

        } catch (\Exception $e) {

            return response()->json(["error" => $e->getMessage()], 500);
        }
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

    /**
     * This PHP function retrieves a list of users belonging to a team and returns it as a JSON
     * response, handling potential errors along the way.
     *
     * @return JsonResponse The `usersTeam()` function is returning a JSON response. If the function
     * successfully retrieves user profiles, it will return a JSON response with the user data in the
     * 'data' key and a status code of 200. If no user profiles are found, it will return a JSON
     * response with a message indicating no results and a status code of 404. If an exception occurs
     * during the process, it
     */
    public function usersTeam(): JsonResponse
    {
        try {
            
            $users = UserProfile::usersTeam();

            if($users->isEmpty()) {
                return response()->json(['message' => 'No se encontraron resultados'], 404);
            }

            return response()->json([
                'data' => $users
            ], 200);

        } catch (\Exception $e) {

            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}
