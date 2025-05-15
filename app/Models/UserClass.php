<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UserClass extends Model
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
        'departure_time'
    ];

    public function scopeByUserId(Builder $query, int $user_id): void
    {
        $query->where('user_id', $user_id);
    }
}
