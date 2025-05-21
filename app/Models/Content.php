<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Http\Exceptions\HttpResponseException;

class Content extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'content',
        'slug',
        'cover_image',
        'location',
        'start_date',
        'end_date',
        'active',
        'content_category_id'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {

        /**
         * Genera slug en base al titulo
         */
        static::created(function (Content $content) {

            $slug = Str::slug($content->title);
            $exists = Content::where('slug', $slug)->exists();

            if (!$exists) {
                $content->slug = $slug;
            } else {
                $content->slug = "{$slug}-{$content->id}";
            }

            $content->save();
        });
    }

    /**
     * The function `getContentType` retrieves content based on the provided content category ID.
     * 
     * @param int content_category_id The `getContentType` function is a static method that retrieves
     * data from a database table based on the provided `content_category_id`. It uses the Eloquent ORM
     * to query the database table associated with the model class where this method is defined.
     * 
     * @return The `getContentType` function is returning a collection of records from the database
     * where the `content_category_id` matches the provided ``.
     */
    public static function getContentType(int $content_category_id)
    {
        return static::where([
            ['content_category_id', $content_category_id],
            ['active', 1]
        ])->get();
    }

    /**
     * This PHP function retrieves the latest content type based on the provided content category ID.
     * 
     * @param int content_category_id The `content_category_id` parameter is an integer value that
     * represents the category of the content for which you want to retrieve the last content type.
     * This function retrieves the latest content entry of a specific category that is also marked as
     * active.
     * 
     * @return The function `getLastContentType` is returning the latest active content entry for a
     * given content category ID. It queries the database table using Eloquent ORM to find the record
     * with the specified content category ID and active status, then orders the results by the `id`
     * column in descending order (latest first) and returns the first result.
     */
    public static function getLastContentType(int $content_category_id)
    {
        return static::where([
            ['content_category_id', $content_category_id],
            ['active', 1]
        ])->latest('id')->first();
    }

    /**
     * The function `getContBySlug` retrieves a record based on a given slug value.
     * 
     * @param string slug The `slug` parameter is a string that represents a unique identifier for a
     * specific content item. It is used to retrieve a record from the database that matches the
     * provided slug value.
     * 
     * @return The function `getContBySlug` is returning the first record from the database where the
     * `slug` column matches the provided `` parameter.
     */
    public static function getContBySlug(string $slug)
    {
        return static::where('slug', $slug)->first();
    }
}
