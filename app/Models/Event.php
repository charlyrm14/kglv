<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

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

    
    /**
     * The scopeId function filters a query by the specified id value.
     * 
     * @param Builder query The `` parameter is an instance of the
     * `Illuminate\Database\Eloquent\Builder` class, which is used for building database queries in
     * Laravel's Eloquent ORM.
     * @param int id The `id` parameter is an integer value that is used to filter the query results
     * based on the specified ID.
     */
    public function scopeId(Builder $query, int $id): void
    {
        $query->where('id', $id);
    }

    /**
     * The scopeSlug function filters a query based on a specified slug value.
     * 
     * @param Builder query The `` parameter is an instance of the
     * `Illuminate\Database\Eloquent\Builder` class, which represents a query builder for a specific
     * Eloquent model. It allows you to construct and execute queries against the model's database
     * table.
     * @param string slug The `slug` parameter is a string that is used to filter the query results
     * based on the value of the `slug` column in the database table. The `scopeSlug` function is a
     * query scope that can be used to add a condition to the query to filter records where the `slug`
     */
    public function scopeSlug(Builder $query, string $slug): void
    {
        $query->where('slug', $slug);
    }
}
