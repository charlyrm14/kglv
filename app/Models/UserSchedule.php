<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSchedule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'day',
        'entry_time',
        'departure_time',
        'status'
    ];

    /**
     * Scope a query to only include records for a specific user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $user_id  The ID of the user to filter by
     * @return void
    */
    public function scopeByUserId(Builder $query, int $user_id): void
    {
        $query->where('user_id', $user_id);
    }

    /**
     * Scope a query to only include records for a specific user with active status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $user_id  The ID of the user to filter by
     * @return void
    */
    public function scopeActiveClassesByUserId(Builder $query, int $user_id): void
    {
        $query->where([
            ['user_id', $user_id],
            ['status', 1]
        ]);
    }

    /**
     * Retrieve the first class assigned to a user for a specific day.
     *
     * @param  int  $user_id  The ID of the user
     * @param  string  $current_day  The current day name (e.g., 'LUNES', 'MARTES')
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public static function getCurrentDaysClass(int $user_id, string $current_day)
    {   
        return static::where([
            ['user_id', $user_id],
            ['day', $current_day],
            ['status', 1]
        ])->first();
    }

    /**
     * Get the user that owns the class.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
