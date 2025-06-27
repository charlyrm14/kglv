<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContentType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug'
    ];

    /**
     * Get the post that owns the comment.
     */
    public function content(): HasMany
    {
        return $this->hasMany(Content::class);
    }
}
