<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'short_description',
        'content',
        'slug',
        'start_date',
        'end_date'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {

        /**
         * Genera slug en base al titulo
         */
        static::created(function (Event $event) {

            $slug = Str::slug($event->title);
            $exists = Event::where('slug', $slug)->exists();

            if (!$exists) {
                $event->slug = $slug;
            } else {
                $event->slug = "{$slug}-{$event->id}";
            }

            $event->save();
        });
    }
}
