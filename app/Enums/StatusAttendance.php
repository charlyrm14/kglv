<?php

namespace App\Enums;

/**
 * Enum StatusAssistance
 *
 * Represents the activation status of a content item.
 * This enum provides integer values for status states and a method
 * to retrieve their human-readable labels.
 *
 *
 * ### Example usage:
 * StatusAssistance::NoAsistio->label(); // returns "No asistio"
 * StatusAssistance::from(0)->label();  // returns "No Asistio"
 *
 * @method string label() Returns a human-readable label for the enum case.
 */
enum StatusAttendance: int
{
    case NoAsistio = 0;
    case Asistio = 1;
    case SinClaseAsignada = 2;

    /**
     * Get the label for the current status.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::NoAsistio => 'No Asistio',
            self::Asistio => 'Asistio',
            self::SinClaseAsignada => 'Sin clase asignada',
        };
    }
}
