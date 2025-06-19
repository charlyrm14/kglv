<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSwimmingLevel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'swimming_level_id',
        'user_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * The scopeCategoriesByUser function filters query results by user_id.
     * 
     * @param Builder query The `` parameter is an instance of the Laravel query builder class
     * `Illuminate\Database\Eloquent\Builder`. It is used to build and execute database queries in an
     * object-oriented way within Laravel applications.
     * @param int user_id The `user_id` parameter is an integer value that represents the unique
     * identifier of a user. This parameter is used to filter the query results based on the specified
     * user ID.
     */
    public function scopeCategoriesByUser(Builder $query, int $user_id): void
    {
        $query->where('user_id', $user_id);
    }

    /**
     * The function `scopeCategoryIdAndUserId` filters a query by swimming category ID and user ID.
     * 
     * @param Builder query The `scopeCategoryIdAndUserId` function is a custom query scope in Laravel
     * Eloquent. It is used to filter the query based on the provided `swimming_category_id` and
     * `user_id` values.
     * @param int swimming_category_id The `swimming_category_id` parameter is used to filter the query
     * results based on a specific swimming category ID. This means that only records related to the
     * specified swimming category ID will be returned in the query results.
     * @param int user_id The `user_id` parameter is an integer value that represents the unique
     * identifier of a user in the system. It is used to filter the query results based on the
     * specified user's ID.
     */
    public function scopeCategoryByUser(Builder $query, int $swimming_level_id, int $user_id): void
    {
        $query->where('swimming_level_id', $swimming_level_id)->where('user_id', $user_id);
    }

    /**
     * Get the user that owns the schedule.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that owns the swimming level.
     */
    public function swimmingLevel()
    {
        return $this->belongsTo(SwimmingLevel::class);
    }
}
