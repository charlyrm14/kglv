<?php

namespace App\Http\Controllers;

use App\Http\Requests\{
    UserAssistanceReportingRequest
};
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserAttendanceExport;
use App\Models\UserAttendance;
use App\Services\HelperService;

class ReportingController extends Controller
{    
    /**
     * Handles the generation and download of a user's assistance report.
     *
     * This method retrieves a user by ID, checks for their assistance records
     * for a given month and year, and returns an Excel file with the report.
     * It handles not-found and empty data cases with JSON responses.
     *
     * @param \App\Http\Requests\UserAssistanceReportingRequest $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
     */
    public function userAttendance(UserAssistanceReportingRequest $request)
    {
        try {

            $user = User::byId($request->user_id)->first();

            if(!$user) return response()->json(['message' => 'Usuario no encontrado'], 404);

            $assistances = UserAttendance::getHistoryAttendanceByUserAndDate($user->id, $request->date);

            if($assistances->isEmpty()) {
                return response()->json([
                    "message" => "Usuario no cuenta con historial de asistencias"
                ], 404);
            }

            return Excel::download(new UserAttendanceExport($user, $assistances), HelperService::reportingFileName($user));

        } catch (\Exception $e) {

            return response()->json(["error" => $e->getMessage()], 500);
        }  
    }
}
