<?php

namespace App\Services;

use Carbon\Carbon;


class DateService {

    /**
     * The function `isBirthdateUser` checks if a given birthdate corresponds to today's date.
     * 
     * @param string birthdate The `isBirthdateUser` function takes a string parameter ``,
     * which represents the birthdate of a user. The function uses the Carbon library to work with
     * dates and determines if the given birthdate corresponds to today's date.
     * 
     * @return The function is checking if the provided birthdate is the same as today's date. It
     * returns a boolean value indicating whether the provided birthdate matches today's date in terms
     * of the day and month.
     */
    public static function isBirthdateUser(string $birthdate = '1990-01-01')
    {
        $today = Carbon::today();
        $format_birthdate = Carbon::parse($birthdate);

        return $format_birthdate->isBirthday($today);
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
    public static function userAge(string $birthdate = '1990-01-01')
    {
        $today = Carbon::today();
        $format_birthdate = Carbon::parse($birthdate);

        return (int) $format_birthdate->diffInYears($today);
    }
}