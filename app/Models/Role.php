<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Get the comments for the blog post.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function scopeTeachersAndStudents(Builder $query, string $rol): void
    {
        $query->select(
            'id', 
            'name'
        )->where('name', $rol);
    }
}
