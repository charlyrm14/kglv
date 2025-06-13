<?php

namespace App\Enums;

/**
 * Enum StatusContent
 *
 * Represents the activation status of a content item.
 * This enum provides integer values for status states and a method
 * to retrieve their human-readable labels.
 *
 * ### Enum Cases:
 * - Desactivado (0): The content is deactivated.
 * - Activado (1): The content is activated.
 *
 * ### Example usage:
 * StatusContent::Activado->label(); // returns "Activado"
 * StatusContent::from(0)->label();  // returns "Desactivado"
 *
 * @method string label() Returns a human-readable label for the enum case.
 */
enum StatusContent: int
{
    case Desactivado = 0;
    case Activado = 1;

    /**
     * Get the label for the current status.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::Desactivado => 'Desactivado',
            self::Activado => 'Activado',
        };
    }
}
