<?php

namespace App\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class InfoUserResource extends JsonResource {

    
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
    public function toArray($user)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'mother_last_name' => $this->mother_last_name,
            'birth_date' => $this->birth_date,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'user_code' => $this->user_code,
            'role_id' => $this->role_id,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d'),
            'current_swimming_level' => $this->current_swimming_level,
            'role' => $this->role,
            'active_schedules' => $this->activeSchedules,
            'swimming_levels' => $this->swimmingLevels
        ];
    }
}