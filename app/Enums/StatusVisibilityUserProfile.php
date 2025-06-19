<?php

namespace App\Enums;

/**
 * Enum StatusVisibilityUserProfile
 *
 * Represents visibility levels for user profile information categories.
 * Each case defines who can see the specific profile section.
 *
 * Example usage:
 * StatusVisibilityUserProfile::Public->label();         // returns "Public"
 * StatusVisibilityUserProfile::from('students')->label(); // returns "Students"
 *
 * @method string label() Returns the human-readable label for the enum case.
 */
enum StatusVisibilityUserProfile: string
{
    case Public = 'public';
    case Students = 'students';
    case Staff = 'staff';
    case Private = 'private';

    /**
     * Get the human-readable label for the current profile category.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::Public => 'Public',
            self::Students => 'Students',
            self::Staff => 'Staff',
            self::Private => 'Private'
        };
    }
}