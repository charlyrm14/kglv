<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SwimmingLevel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'image',
        'skill_1',
        'skill_2',
        'skill_3',
        'description'
    ];

    /**
     * The scopeCategoryById function filters a query by a specific category ID.
     * 
     * @param Builder query The `` parameter is an instance of the Laravel query builder class
     * `Illuminate\Database\Eloquent\Builder`. It is used to build and execute database queries in an
     * object-oriented way within Laravel applications.
     * @param int category_id The `category_id` parameter is an integer value that represents the
     * unique identifier of a category. This parameter is used in the `scopeCategoryById` function to
     * filter the query results based on the category ID provided.
     */
    public function scopeCategoryById(Builder $query, int $category_id): void
    {
        $query->where('id', $category_id);
    }

    /**
     * Get the total number of swimming levels available in the system.
     *
     * @return int The total count of swimming levels.
     */
    public static function totalLevels(): int
    {
        return static::count();
    }

    /**
     * Retrieve the next swimming level based on the user's current level.
     *
     * If the user has already completed all available levels,
     * the function will return null.
     *
     * @param int $user_current_level The number representing the user's current level count.
     * @return SwimmingLevel|null The next SwimmingLevel instance or null if the user has reached the maximum level.
     */
    public static function nextLevel(int $user_current_level): ?SwimmingLevel
    {        
        $total_levels = self::count();

        if ($user_current_level >= $total_levels) {
            return null;
        }

        return static::where('id', $user_current_level + 1)->first();
    }
}
