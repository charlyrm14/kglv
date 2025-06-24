<?php

namespace App\Services;

use App\Models\SwimmingLevel;

class SwimmingLevelService 
{
    /**
     * Calculates the user's progress percentage based on the number of completed levels.
     *
     * @param int $user_level_count Number of swimming levels completed by the user.
     * @param int $total_levels Total number of swimming levels available.
     * @return int Progress percentage as an integer between 0 and 100.
     */
    public static function progressPercentage(int $user_level_count, int $total_levels): int
    {
        if ($total_levels === 0) {
            return 0;
        }

        return (int) round(($user_level_count / $total_levels) * 100);
    }

    /**
     * Calculates the number of remaining levels the user needs to complete.
     *
     * @param int $user_current_level Number of swimming levels currently completed by the user.
     * @param int $total_levels Total number of swimming levels available.
     * @return int Number of remaining levels to complete.
     */
    public static function remainingLevels(int $user_current_level, int $total_levels): int
    {
        if ($total_levels === 0) {
            return 0;
        }

        return (int) $total_levels - $user_current_level;
    }
}