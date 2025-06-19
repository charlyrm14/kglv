<?php

namespace App\Services;

use Carbon\Carbon;

class DateService {

    /**
     * Check if the given birthdate matches today's date (ignoring the year).
     *
     * @param string $birthdate The birthdate to check, formatted as 'Y-m-d'. Default is '1990-01-01'.
     * @return bool Returns true if the birthdate's month and day match today's date; otherwise, false.
     */
    public static function isBirthdateUser(string $birthdate = '1990-01-01'): bool
    {
        return Carbon::parse($birthdate)->isBirthday(Carbon::today());
    }

    /**
     * The function calculates the age of a user based on their birthdate and the current date.
     * 
     * @param string birthdate The `userAge` function calculates the age of a user based on their
     * birthdate. The function takes a string parameter `` which represents the birthdate of
     * the user in the format 'YYYY-MM-DD'.
     * 
     * @return The function `userAge` takes a string representing a birthdate as input, calculates the
     * age of the user based on the given birthdate and the current date (2025-05-20), and returns the
     * age as an integer.
     */
    public static function userAge(string $birthdate = '1990-01-01'): int
    {
        $today = Carbon::today();
        $format_birthdate = Carbon::parse($birthdate);

        return (int) $format_birthdate->diffInYears($today);
    }

    /**
     * Get the current day of the week in Spanish and uppercase.
     *
     * This method uses Carbon to retrieve the current day name
     * localized in Spanish (e.g., "LUNES", "MARTES"). The result
     * is returned in uppercase with proper UTF-8 handling for accents.
     *
     * @return string The uppercase Spanish name of the current weekday.
     */
    public static function getCurrentDay(): string 
    {
        Carbon::setLocale('es');

        return mb_strtoupper(Carbon::now()->translatedFormat('l'), 'UTF-8');
    }

    /**
     * Returns the day of the week in uppercase Spanish for a given date.
     *
     * This method parses the given date string, sets the locale to Spanish,
     * and returns the translated day of the week in uppercase (e.g., "LUNES").
     *
     * @param string $date A date string in 'Y-m-d' format. Defaults to '1990-01-01'.
     * @return string The day of the week in uppercase Spanish (e.g., "MIÉRCOLES").
     */
    public static function getDayByDate(string $date = '1990-01-01'): string
    {
        Carbon::setLocale('es');

        return mb_strtoupper(Carbon::parse($date)->translatedFormat('l'), 'UTF-8');
    }
}