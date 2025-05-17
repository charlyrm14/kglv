<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBiography extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'biography',
        'user_id'
    ];
}
