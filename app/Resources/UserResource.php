<?php

namespace App\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource 
{
    
    /**
     * The toArray function in PHP converts a user object into an associative array with specific
     * attributes and nested role information.
     * 
     * @param user The `toArray` function you provided seems to be a method in a class that converts a
     * user object into an array. It includes user details like id, name, last name, mother's name,
     * birth date, email, user code, role id, and role details.
     * 
     * @return An array is being returned with the following keys and values:
     * - 'id' => ->id
     * - 'name' => ->name
     * - 'last_name' => ->last_name
     * - 'mothers_name' => ->mothers_name
     * - 'birth_date' => ->birth_date
     * - 'email' => ->email
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
            'role' => [
                'id' => $this->role->id,
                'name' => $this->role->name,
                'created_at' => $this->role->created_at,
                'updated_at' => $this->role->updated_at
            ]
        ];
    }
}