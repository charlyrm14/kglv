<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Http\Exceptions\HttpResponseException;

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

    /**
     * The function `checkExistsEmail` compares a request email with a user email and throws an
     * exception if a user with the request email already exists.
     * @param string request_email The `request_email` parameter is the email that is being checked for
     * existence in the system. It is the email that a user is trying to use or update.
     * @param string user_email The `user_email` parameter in the `checkExistsEmail` function
     * represents the email address of a user that is being compared with the `request_email`
     * parameter. The function checks if the `request_email` is different from the `user_email` and if
     * there is already a user with the same
     */
    public static function checkExistsEmail(string $request_email, string $user_email): void
    {
        if($request_email !== $user_email && User::byEmail($request_email)->first()) {
            throw new HttpResponseException(response()->json([
                'message' => 'El correo electrónico ingresado ya está registrado en otro usuario'
            ], 422));
        }
    }
}