<?php 

namespace App\Services;

use App\Models\User;

class HelperService {

    /**
     * Returns the full name of the given user.
     *
     * Concatenates the user's first name, last name, and mother's last name
     * into a single string separated by spaces.
     *
     * @param \App\Models\User $user The user instance.
     * @return string The full name in the format: "name last_name mothers_name".
     */
    public static function getFullName(User $user): string
    {
        return $user->name . ' ' . $user->last_name . ' ' . $user->mothers_name;
    }

    /**
     * Generates a unique filename for a user's assistance report export.
     *
     * The filename includes the user's name in lowercase, their unique ID,
     * and the current timestamp in the format YmdHis. The result is appended
     * with the `.xlsx` extension.
     *
     * Example output: "reporte-asistencias-john-5-20250612123456.xlsx"
     *
     * @param \App\Models\User $user The user for whom the report is being generated.
     * @return string The generated filename for the user's assistance report.
     */
    public static function userFileName(User $user): string
    {
        return 'reporte-asistencias-' . strtolower($user->name) . '-' . $user->id . '-' . date('YmdHis') . '.xlsx';
    }
}