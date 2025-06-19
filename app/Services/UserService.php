<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class UserService {

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
        return $user->first_name . ' ' . $user->last_name . ' ' . $user->mother_last_name;
    }

    /**
     * Retrieve users who have birthdays today, adding additional attributes.
     *
     * This method fetches users filtered by their birthdate (assumed by usersBirthdate()).
     * For each user, it adds:
     * - `is_birthdate`: a boolean indicating if today is their birthday.
     * - `age`: the calculated age based on their birthdate.
     *
     * @return \Illuminate\Support\Collection A collection of users with added attributes.
     */
    public static function getUsersWithBirthdayToday(): ?Collection
    {
        $users = User::usersBirthdate();

        return $users->map(function ($user) {
            $user->is_birthdate = DateService::isBirthdateUser($user->birth_date);
            $user->age = DateService::userAge($user->birth_date);
            return $user;
        });
    }
}