<?php

namespace App\Enums;

/**
 * Enum StatusUserProfile
 *
 * Represents different types of user profile information categories.
 * Each case corresponds to a specific profile section.
 *
 * Example usage:
 * StatusUserProfile::Biography->label();       // returns "Biography"
 * StatusUserProfile::from('experience')->label(); // returns "Experience"
 *
 * @method string label() Returns the human-readable label for the enum case.
 */
enum StatusUserProfile: string
{
    case Biography = 'biography';
    case Achievements = 'achievements';
    case Experience = 'experience';
    case Education = 'education';
    case Certifications = 'certifications';

    /**
     * Get the human-readable label for the current profile category.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::Biography => 'Biography',
            self::Achievements => 'Achievements',
            self::Experience => 'Experience',
            self::Education => 'Education',
            self::Certifications => 'Certifications',
        };
    }
}
