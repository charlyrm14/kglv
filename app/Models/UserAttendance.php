<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class UserAttendance extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'present'
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
    public function scopeAttendanceById(Builder $query, int $user_id): void
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
    public static function getAttendanceCurrentMonth(int $user_id)
    {
        return static::select(
            'user_id',
            'present',
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
    public static function getAttendanceCurrentDayByUser(int $user_id, int $type_assistance)
    {
        return static::where([
            ['user_id', $user_id],
            ['present', $type_assistance]
        ])->whereDate('created_at', Carbon::today())->first();
    }

    /**
     * Retrieves the assistance history records for a specific user and a given month/year.
     *
     * Parses the given date and returns all UserAssistance records that match the user ID
     * and fall within the same month and year as the provided date.
     *
     * @param int $user_id The ID of the user whose assistance records are being retrieved.
     * @param string $date A date string in a parseable format (e.g., "2025-06") used to filter records by month and year.
     * @return \Illuminate\Support\Collection A collection of UserAssistance records matching the criteria.
     */
    public static function getHistoryAttendanceByUserAndDate(int $user_id, string $date): Collection
    {
        $date = Carbon::parse($date);

        return static::where([
            ['user_id', $user_id]
        ])->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->get();
    }
}
