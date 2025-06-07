<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UserAssistance extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'assistance'
    ];

    /**
     * Scope a query to only include today's assistance record for a specific user.
     *
     * This scope filters the query results to find a record matching the given user ID
     * and with an assistance_date equal to today.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query   The Eloquent query builder instance.
     * @param  int  $user_id  The ID of the user to filter by.
     * @return void
     */
    public function scopeAssistanceById(Builder $query, int $user_id): void
    {
        $query->where('user_id', $user_id)->whereDate('created_at', Carbon::today());
    }

    /**
     * Retrieve all assistance records for the current month for a specific user.
     *
     * This static method fetches all entries in the `UserAssistance` model
     * where the `user_id` matches the provided ID and the `assistance_date`
     * is within the current month.
     *
     * @param  int  $user_id  The ID of the user whose assistance records are being retrieved.
     * @return \Illuminate\Database\Eloquent\Collection  A collection of UserAssistance records.
     */
    public static function getAssistanceCurrentMonth(int $user_id)
    {
        return static::select(
            'user_id',
            'assistance',
            'created_at'
        )->where('user_id', $user_id)->whereMonth('created_at', Carbon::now()->month)->get();
    }

    /**
     * Retrieve today's assistance record for a specific user and assistance type.
     *
     * @param  int  $user_id  The ID of the user
     * @param  int  $type_assistance  The type of assistance (e.g., 0 = absent, 1 = present)
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public static function getAssistanceCurrentDayByUser(int $user_id, int $type_assistance)
    {
        return static::where([
            ['user_id', $user_id],
            ['assistance', $type_assistance]
        ])->whereDate('created_at', Carbon::today())->first();
    }
}
