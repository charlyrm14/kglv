<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedUser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'achievement',
        'achievement_date',
        'user_id'
    ];
}
