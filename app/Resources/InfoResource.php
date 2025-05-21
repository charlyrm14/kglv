<?php

namespace App\Resources;

use App\Models\Content;
use App\Models\User;
use App\Services\DateService;
use Illuminate\Http\Resources\Json\JsonResource;

class InfoResource extends JsonResource {

    
    /**
     * The toArray function retrieves user information, calculates age and birthdate status, and
     * includes featured users, users with birthdates, and last content types.
     * 
     * @param info The `toArray` function takes an `` parameter, but it seems like the parameter
     * is not being used within the function. If you intended to use the `` parameter within the
     * function, you can incorporate it into the logic to customize the output based on the provided
     * information.
     * 
     * @return An array is being returned with the following keys and values:
     */
    public function toArray($info)
    {
        $users_birthdate = User::usersBirthdate();

        if(!$users_birthdate->isEmpty()) {
            $users_birthdate->each(function($user) {
                $user->is_birthdate = DateService::isBirthdateUser($user->birth_date);
                $user->age = DateService::userAge($user->birth_date);
            });
        } else {
            $users_birthdate = NULL;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'mothers_name' => $this->mothers_name,
            'birth_date' => $this->birth_date,
            'is_birthdate' => DateService::isBirthdateUser($this->birth_date),
            'age' => DateService::userAge($this->birth_date),
            'featured_users' => [
                [
                    'name' => 'Valentina Exemany',
                    'last_name' => 'Hérnandez',
                    'mothers_name' => 'Ramos',
                    'achievement' => '1° lugar en maraton circuito acuático categoría adolescente'
                ],
                [
                    'name' => 'Valany Eliet',
                    'last_name' => 'Hérnandez',
                    'mothers_name' => 'Ramos',
                    'achievement' => '1° lugar en maraton circuito acuático categoría infantil'
                ]
            ],
            'users_birthdate' => $users_birthdate,
            'important_notice' => Content::getLastContentType(1),
            'last_event' => Content::getLastContentType(2)
        ];
    }
}