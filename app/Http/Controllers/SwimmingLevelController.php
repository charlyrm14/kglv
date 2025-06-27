<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\AssignCategoryToUserRequest;
use App\Models\SwimmingLevel;
use App\Models\User;
use App\Models\UserSwimmingLevel;
use App\Resources\UserSwimmingProgressResource;
use App\Services\SwimmingLevelService;
use Tymon\JWTAuth\Facades\JWTAuth;

class SwimmingLevelController extends Controller
{
    /**
     * The function retrieves all swimming categories and returns them as JSON response, handling
     * potential errors along the way.
     * 
     * @return JsonResponse A JSON response is being returned. If the `` collection is
     * empty, a 404 status code with a message indicating no results are found is returned. If an
     * exception occurs during the process, a 500 status code with an error message is returned.
     * Otherwise, a 201 status code with the data from the `` collection is returned.
     */
    public function index(): JsonResponse
    {
        try {
            
            $categories = SwimmingLevel::orderByDesc('id')->get();

            if($categories->isEmpty()) return response()->json(["message" => 'No se encontraron resultados'], 404);

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'data' => $categories
        ], 200); 
    }

    /**
     * This PHP function retrieves swimming categories associated with a specific user and returns them
     * as JSON response.
     * 
     * @param int user_id The `byUser` function takes a user_id as a parameter, which is an integer
     * representing the ID of a user. This function retrieves swimming categories associated with the
     * user specified by the user_id. If no categories are found for the user, it returns a JSON
     * response with a message indicating that no
     * 
     * @return JsonResponse A JSON response is being returned. If the operation is successful, it will
     * return a JSON response with the data containing the user categories. If there is an error, it
     * will return a JSON response with an error message.
     */
    public function byUser(): JsonResponse
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            $user_levels = UserSwimmingLevel::categoriesByUser($user->id)->with('swimmingLevel')->get();

            if($user_levels->isEmpty()) {
                return response()->json(["message" => 'No se encontraron resultados'], 404);
            }

            $completed_levels = (int)$user_levels->count();
            $total_levels = (int)SwimmingLevel::totalLevels();

            // Obtiene el nivel más alto y hace la relación sobre swimmingLevel, de lo contrario retorna NULL
            $current_level = $user_levels->sortByDesc('swimming_level_id')->first()->swimmingLevel ?? NULL;

            $progress_percentage = SwimmingLevelService::progressPercentage($completed_levels, $total_levels);
            $remaining_levels = SwimmingLevelService::remainingLevels($completed_levels, $total_levels);
            $next_level = SwimmingLevel::nextLevel($completed_levels, $total_levels);

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'data' => new UserSwimmingProgressResource(
                $current_level,
                $progress_percentage,
                $user_levels,
                $completed_levels,
                $total_levels,
                $remaining_levels,
                $next_level
            )
        ], 200); 
    }

    /**
     * The function assigns a swimming category to a user while checking for existing assignments and
     * maximum category limits.
     * 
     * @param AssignCategoryToUserRequest request The `assignToUser` function takes an
     * `AssignCategoryToUserRequest` object as a parameter. This request object likely contains the
     * necessary data for assigning a swimming category to a user, such as the user ID and the swimming
     * category ID.
     * 
     * @return JsonResponse The function `assignToUser` is returning a JSON response. If the operation
     * is successful, it returns a JSON response with a success message "Categoría asignada a usuario
     * con éxito" and a status code of 201. If there are specific error conditions, it returns a JSON
     * response with an appropriate error message and status code (400 for client errors, 500 for
     * server errors).
     */
    public function assignToUser(AssignCategoryToUserRequest $request) : JsonResponse
    {
        try {

            $user_categories = UserSwimmingLevel::categoriesByUser($request->user_id)->get();
            
            if($user_categories->count() >= SwimmingLevel::totalLevels()) {
                return response()->json(['message' => 'El estudiante ha alcanzado el nivel máximo de las categorías'], 400);
            }

            $category_already_assigned = UserSwimmingLevel::categoryByUser(
                $request->swimming_level_id,
                $request->user_id
            )->first();

            if($category_already_assigned){ 
                return response()->json(['message' => 'El estudiante ya tiene esta categoría asignada'], 400);
            }
            
            $current_level = $user_categories->sortByDesc('swimming_level_id')->first()->swimming_level_id ?? NULL;

            $next_level = !is_null($current_level) ? SwimmingLevel::nextLevel($current_level)->id : SwimmingLevel::nextLevel(0)->id;

            if($next_level !== $request->swimming_level_id) {
                return response()->json(['message' => 'No se puede asignar un nivel mas alto al nivel actual'], 400);
            }
            
            UserSwimmingLevel::create($request->validated());

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'message' => 'Categoría asignada a usuario con éxito'
        ], 201);
    }
}
