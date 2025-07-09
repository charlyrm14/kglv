<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserProfileInformationRequest;
use App\Http\Requests\UpdateUserProfileInformationRequest;
use App\Models\User;
use App\Models\UserProfile;
use App\Resources\UserProfileInfoResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserProfileController extends Controller
{
    /**
     * This PHP function retrieves and returns the profile information of a user by their ID.
     * 
     * @param int id The `id` parameter in the `userProfileInfo` function is used to specify the user
     * for which you want to retrieve the profile information. This function takes an integer `` as
     * input, which is the unique identifier of the user whose profile information you want to fetch.
     * 
     * @return A JSON response containing the user's profile information is being returned. If the user
     * is not found, a JSON response with a message "Usuario no encontrado" and a status code of 404 is
     * returned.
     */
    public function userProfileInfo(int $id): JsonResponse
    {
        $user = User::with('profile')->byId($id)->first();

        try {

            if(!$user) {
                return response()->json(['message' => 'Usuario no encontrado'], 404);
            }

            return response()->json([
                'data' => new UserProfileInfoResource($user)
            ], 200);

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }
    }

    /**
     * The function `assignProfileInfo` assigns user profile information and handles exceptions by
     * returning appropriate JSON responses.
     * 
     * @param StoreUserProfileInformationRequest request It looks like you are trying to assign profile
     * information based on a request object in a Laravel application. The `assignProfileInfo` function
     * takes a `StoreUserProfileInformationRequest` object as a parameter.
     * 
     * @return JsonResponse A JSON response is being returned with a success message "Información
     * asignada con éxito" and the data that was created or updated. The HTTP status code is 201
     * (Created) for a successful creation operation. If an error occurs, a JSON response with an error
     * message "Error del servidor" and a status code of 500 (Internal Server Error) is returned.
     */
    public function assignProfileInfo(StoreUserProfileInformationRequest $request): JsonResponse
    {
        try {

            $user_profile = UserProfile::byUserId($request->user_id)->get();
        
            UserService::validateProfileTypeLimit($user_profile, $request->type);

            $data = UserProfile::create($request->validated());

            return response()->json([
                'message' => 'Información asignada con éxito',
                'data' => $data
            ], 201);

        } catch (HttpResponseException $e) {
            // Re-lanzamos para que Laravel maneje correctamente el JSON que ya viene en la excepción
            throw $e;

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }
    }

    /**
     * This PHP function updates user profile information based on the provided request and user ID,
     * handling error cases appropriately.
     * 
     * @param UpdateUserProfileInformationRequest request The `updateProfileInfo` function takes two
     * parameters:
     * @param int id The `id` parameter in the `updateProfileInfo` function is used to specify the ID
     * of the user profile that needs to be updated. This ID is used to retrieve the specific user
     * profile from the database so that it can be updated with the new information provided in the
     * request.
     * 
     * @return The `updateProfileInfo` function returns a JSON response with a success message and the
     * updated user profile data if the update is successful. If the user profile is not found, it
     * returns a 404 status code with a message indicating that the resource was not found. If the user
     * ID in the request does not match the user ID associated with the user profile, it returns a 422
     * status code with
     */
    public function updateProfileInfo(UpdateUserProfileInformationRequest $request, int $id)
    {
        $data_user_profile = UserProfile::byId($id)->first();

        try {
            
            if(!$data_user_profile) {
                return response()->json(['message' => 'Recurso no encontrado'], 404);
            }
            
            if ($data_user_profile->user_id !== $request->user_id) {
                return response()->json(['message' => 'El tipo de información proporcionado no pertenece al usuario seleccionado'], 422);
            }

            $data_user_profile->update([
                'content' => $request->validated()['content']
            ]);

            return response()->json([
                'message' => 'Información actualizada con éxito',
                'data' => $data_user_profile
            ], 200);

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }
    }
}
