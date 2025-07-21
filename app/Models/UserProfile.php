<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class UserProfile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'content',
        'visible_to'
    ];

    /**
     * Get the post that owns the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The scopeById function filters a query by the specified id value.
     * 
     * @param Builder query The `` parameter is an instance of the
     * `Illuminate\Database\Eloquent\Builder` class, which is used for building database queries in
     * Laravel's Eloquent ORM.
     * @param int id The `id` parameter is an integer value that is used to filter the query results
     * based on the specified ID.
     */
    public function scopeById(Builder $query, int $id): void
    {
        $query->where('id', $id);
    }

    /**
     * This PHP function filters a query by a specific user ID.
     * 
     * @param Builder query The `` parameter is an instance of the Laravel query builder class
     * `Illuminate\Database\Eloquent\Builder`. It is used to build and execute database queries in an
     * object-oriented way within Laravel applications.
     * @param int user_id The `user_id` parameter is an integer value that is used to filter the query
     * results based on the user ID. The `scopeByUserId` function is a query scope in Laravel that can
     * be used to apply this filter when querying the database.
     */
    public function scopeByUserId(Builder $query, int $user_id): void
    {
        $query->where('user_id', $user_id);
    }

    
    /**
     * This PHP function retrieves users who are part of a team along with their profiles filtered by
     * hobbies.
     *
     * @return Collection A collection of users who are part of a team, with their profiles filtered to
     * only include hobbies.
     */
    public static function usersTeam(): Collection
    {
        return static::with(['user.profile' => function ($query) {
            $query->where('type', 'hobbies');
        }])->where('type', 'team')->get();
    }
}
