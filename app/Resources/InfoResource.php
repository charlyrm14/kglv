<?php

namespace App\Resources;

use App\Models\Content;
use App\Models\User;
use App\Services\DateService;
use App\Services\UserService;
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
        

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'mother_last_name' => $this->mother_last_name,
            'birth_date' => $this->birth_date,
            'is_birthdate' => DateService::isBirthdateUser($this->birth_date),
            'age' => DateService::userAge($this->birth_date),
            'featured_users' => User::getStudents()->get(),
            'users_birthdate' => UserService::getUsersWithBirthdayToday(),
            'last_notice' => Content::getLastContentType(1),
            'last_event' => Content::getLastContentType(2)
        ];
    }
}