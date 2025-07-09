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

    /**
     * The function `validateProfileTypeLimit` checks if a user has reached the maximum limit for a
     * specific profile type and throws an exception if the limit is exceeded.
     * 
     * @param Collection user_profiles The `user_profiles` parameter is expected to be a Collection of
     * user profile records. It seems like this function is designed to validate the limit of a
     * specific type of profile for a user. The function checks the number of existing profiles of a
     * certain type for a user and compares it against the predefined limit
     * @param string profile_type The `validateProfileTypeLimit` function you provided is used to check
     * if a user has reached the limit for a specific profile type. The function takes a collection of
     * user profiles and a profile type as parameters.
     */
    public static function validateProfileTypeLimit(Collection $user_profiles, string $profile_type): void
    {
        $profile_type_limits = [
            'biography' => [
                'max_records' => 1,
                'message' => 'Ya existe una biografía asignada al usuario'
            ],
            'achievements' => [
                'max_records' => 3,
                'message' => 'El usuario alcanzo el máximo de logros asignados'
            ],
            'hobbies' => [
                'max_records' => 3,
                'message' => 'El usuario alcanzo el máximo de hobbies asignados'
            ]
        ];

        if (!array_key_exists($profile_type, $profile_type_limits)) {
            throw new HttpResponseException(response()->json([
                'message' => "Tipo de información no reconocido: {$profile_type}"
            ], 422));
        }

        $limit = $profile_type_limits[$profile_type]['max_records'];
        $type_count = $user_profiles->where('type', $profile_type)->count();

        if(!$user_profiles->isEmpty() && $type_count >= $limit) {
            throw new HttpResponseException(response()->json([
                'message' => $profile_type_limits[$profile_type]['message']
            ], 422));
        }
    }
}