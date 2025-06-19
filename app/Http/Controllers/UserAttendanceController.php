<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignUserAttendanceRequest;
use App\Models\User;
use App\Models\UserAttendance;
use App\Models\UserSchedule;
use App\Services\DateService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserAttendanceController extends Controller
{
    /**
     * Retrieve the authenticated user's assistance records for the current month.
     *
     * This method authenticates the user using a JWT token. If the user is found, it retrieves
     * all assistance records for the current month. If no records are found, it returns a 404
     * response. Each assistance record is formatted with a translated day name (e.g., "Monday 03")
     * and a date in "Y-m-d" format.
     *
     * @return \Illuminate\Http\JsonResponse
     *     - 200: Returns a list of assistance records for the current month with additional formatted dates.
     *     - 404: If the user is not found or has no assistance records for the current month.
     *     - 500: On server error.
     */
    public function getUserAttendance(): JsonResponse
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            $user_assistances = UserAttendance::getAttendanceCurrentMonth($user->id);

            if($user_assistances->isEmpty()) {
                return response()->json(['message' => 'Usuario no cuenta con asistencias del mes en curso'], 404);
            }

            foreach ($user_assistances as $user_assistance) {
                $date = Carbon::parse($user_assistance->created_at);
                $user_assistance->translated_format = ucfirst($date->translatedFormat('l d'));
                $user_assistance->formatted_date = Carbon::parse($user_assistance->created_at)->format('Y-m-d');
            }

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'data' => $user_assistances
        ], 200);
    }

    /**
     * Registers a user's attendance for the current day.
     *
     * This method validates the request, ensures the user exists,
     * checks for existing attendance on the same day, and creates a new attendance record.
     *
     * @param  AssignUserAssistanceRequest  $request  The validated request containing user_id and other data.
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception If an unexpected error occurs.
     *
     * Response codes:
     * - 201: Attendance successfully assigned
     * - 404: User not found
     * - 422: Attendance already exists for today
     * - 500: Server error
     */
    public function assignUserAttendance(AssignUserAttendanceRequest $request): JsonResponse
    {
        try {

            $user = User::ById($request->user_id)->first();

            if(!$user) return response()->json(['message' => 'Usuario no encontrado'], 404);

            if($user->role_id !== 3) return response()->json(['message' => 'No se puede asignar asistencia a este tipo de usuario, solo estudiantes'], 400);

            $today = DateService::getCurrentDay();
            
            $user_class = UserSchedule::getCurrentDaysClass($user->id, $today);

            if(!$user_class) return response()->json(['message' => 'El usuario no tiene asiganado el día de hoy como clase'], 400);

            $verify_assistance = UserAttendance::attendanceById($user->id)->first();

            if($verify_assistance) return response()->json(['message' => 'Usuario ya cuenta con asistencia del día de hoy'], 422);

            $data = $request->validated() + ['present' => 1];

            UserAttendance::create($data);

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'message' => "Asistencia asignada con éxito a {$user->name}"
        ], 201);
    }
}
