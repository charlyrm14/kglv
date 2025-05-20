<?php

namespace App\Resources;

use App\Models\Event;
use App\Services\DateService;
use Illuminate\Http\Resources\Json\JsonResource;

class InfoResource extends JsonResource {

    /**
     * The function `toArray` returns an array containing user information, featured users, users with
     * upcoming birthdays, an important notice, and the latest event.
     * 
     * @param info The `toArray` function you provided seems to be a method in a class that converts
     * the object's properties into an array. The `` parameter is not being used in the function.
     * If you intended to use the `` parameter, you can incorporate it into the array structure or
     * modify the
     * 
     * @return An array is being returned with various pieces of information structured in key-value
     * pairs. Here is a breakdown of the data being returned:
     */
    public function toArray($info)
    {
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
            'users_birthdate' => [
                [
                    'name' => 'Carlos',
                    'last_name' => 'Ramos',
                    'mothers_name' => 'Flores',
                    'age' => 61,
                ],
                [
                    'name' => 'Kenia Gabriela',
                    'last_name' => 'Ramos',
                    'mothers_name' => 'Morales',
                    'age' => 30
                ]
            ],
            'important_notice' => [
                'title' => 'Mañana no hay clases',
                'short_description' => 'Por motivos de fuerza mayor se informa que el día de mañana no habrá clases.'
            ],
            'last_event' => Event::latest('id')->first()
        ];
    }
}