<?php

namespace App\Http\Controllers;

use App\Models\SwimmingCategory;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AssignCategoryToUserRequest;
use App\Models\SwimmingCategoryUser;

class SwimmingCategoryController extends Controller
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
    public function index() : JsonResponse
    {
        try {
            
            $categories = SwimmingCategory::all();

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
    public function byUser(int $user_id) : JsonResponse
    {
        try {

            $user_categories = SwimmingCategoryUser::categoriesByUser($user_id)->get();

            if($user_categories->isEmpty()) return response()->json(["message" => 'No se encontraron resultados'], 404);

            $user_categories->each(function($value) {
                $value->category = SwimmingCategory::categoryById($value->swimming_category_id)->first()?->title;
            });

            $data_current_category = SwimmingCategory::categoryById($user_categories->max('swimming_category_id'))->first();

            $current_category = [
                'category' => optional($data_current_category)->title,
                'message_category' => optional($data_current_category)->message
            ];

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'data' => [
                'current_category' => $current_category,
                'categories' => $user_categories
            ]
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
            
            $total_categories_user = SwimmingCategoryUser::categoriesByUser($request->user_id)->count();

            if($total_categories_user >= SwimmingCategory::count()) {
                return response()->json(['message' => 'El estudiante ha alcanzado el nivel máximo de las categorías'], 400);
            }

            $exist_category_user = SwimmingCategoryUser::categoryByUser(
                $request->swimming_category_id,
                $request->user_id
            )->first();

            if($exist_category_user) return response()->json(['message' => 'El estudiante ya tiene esta categoría asignada'], 400);

            SwimmingCategoryUser::create($request->validated());

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'message' => 'Categoría asignada a usuario con éxito'
        ], 201);
    }
}
