<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignClassesToUserRequest;
use App\Models\User;
use App\Models\UserClass;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserClassController extends Controller
{
    /**
     * Retrieve all classes assigned to the currently authenticated user.
     *
     * This method authenticates the user via a JWT token, fetches their assigned classes,
     * and returns the data in a JSON response. If the user is not found or has no assigned
     * classes, it returns the appropriate error response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function classesByUser() : JsonResponse
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }
            
            $user_classes = UserClass::activeClassesByUserId($user->id)->get();

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
            
            if($user->role_id !== 3) return response()->json(['message' => 'No se puede asignar clases a este tipo de usuario, solo estudiantes'], 400);
            
            if(count($request->days) > 7) return response()->json(['message' => 'Días de clases por semana excedidos'], 400);

            $duplicate_days = count($request->days) !== count(array_unique($request->days));

            if($duplicate_days) return response()->json(['message' => 'Hay días duplicados'], 400);

            $user_classes = UserClass::byUserId($user->id)->get();

            if(!$user_classes->isEmpty()) {
                foreach($user_classes as $user_class) {
                    $user_class->status = 0;
                    $user_class->save();
                }
            }

            foreach($request->days as $value) {
                $data = $request->except('days');
                $data['day'] = $value;
                $data['status'] = 1;
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
