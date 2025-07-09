<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileInfoResource extends JsonResource {

    /**
     * The toArray function in PHP returns an array with user details and grouped profile information.
     * 
     * @param user The `toArray` function you provided seems to be a method within a class. It takes a
     * `` parameter, but it seems like the function is not using the `` parameter directly
     * within the method. Instead, it is accessing properties like `->id`, `->first_name
     * 
     * @return The `toArray` function returns an array with the following structure:
     */
    public function toArray($user)
    {
        $groupedProfile = $this->profile->groupBy('type');

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'mother_last_name' => $this->mother_last_name,
            'profile' => [
                'biography' => optional($groupedProfile->get('biography'))->first(),
                'hobbies' => $groupedProfile->get('hobbies')?->values() ?? null,
                'achievements' => $groupedProfile->get('achievements')?->values() ?? null
            ]
        ];
    }
}
