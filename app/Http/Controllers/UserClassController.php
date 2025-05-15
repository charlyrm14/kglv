<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignClassesToUserRequest;
use App\Models\User;
use App\Models\UserClass;
use Illuminate\Http\JsonResponse;

class UserClassController extends Controller
{
    /**
     * This PHP function retrieves classes assigned to a user by their user ID and returns a JSON
     * response with the data.
     * 
     * @param int user_id The `classesByUser` function takes a user_id as a parameter, which is an
     * integer representing the ID of the user for whom you want to retrieve classes.
     * 
     * @return JsonResponse a JSON response. If the user classes are found, it will return a JSON
     * response with the data containing the user classes. If the user classes are empty, it will
     * return a JSON response with a message indicating that the user has no classes assigned. If an
     * exception occurs during the process, it will return a JSON response with an error message
     * indicating a server error.
     */
    public function classesByUser(int $user_id) : JsonResponse
    {
        try {
            
            $user_classes = UserClass::byUserId($user_id)->get();

            if($user_classes->isEmpty()) return response()->json(['message' => 'Usuario sin clases asignadas'], 400);

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'data' => $user_classes
        ], 200);
    }

    /**
     * The function assigns classes to a user based on the request data provided, handling various
     * validation checks and returning appropriate JSON responses.
     * 
     * @param AssignClassesToUserRequest request The `assignClassesToUser` function takes an
     * `AssignClassesToUserRequest` object as a parameter. This request object likely contains the
     * necessary data for assigning classes to a user, such as the user ID and the days on which the
     * classes should be assigned.
     * 
     * @return JsonResponse A JSON response is being returned. If the operation is successful, a
     * response with a success message is returned with status code 201. If there are any errors during
     * the process, an error message is returned with status code 500 or specific error messages with
     * status code 400 or 404 based on the validation checks.
     */
    public function assignClassesToUser(AssignClassesToUserRequest $request) : JsonResponse
    {
        try {

            $user = User::ById($request->user_id)->first();

            if(!$user) return response()->json(['message' => 'Usuario invalido'], 404);
            
            if(count($request->days) > 7) return response()->json(['message' => 'Días de clases por semana excedidos'], 400);

            $duplicate_days = count($request->days) !== count(array_unique($request->days));

            if($duplicate_days) return response()->json(['message' => 'Hay días duplicados'], 400);

            $user_classes = UserClass::byUserId($user->id)->get();

            if(!$user_classes->isEmpty()) return response()->json(['message' => 'Usuario ya cuenta con clases aignadas'], 400);

            foreach($request->days as $value) {
                $data = $request->except('days');
                $data['day'] = $value;
                UserClass::create($data);
            }

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'message' => "Se han asignado con éxito las clases al usuario $user->name"
        ], 201);
    }
}
