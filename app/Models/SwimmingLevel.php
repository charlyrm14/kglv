<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SwimmingLevel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'image',
        'description'
    ];

    /**
     * The scopeCategoryById function filters a query by a specific category ID.
     * 
     * @param Builder query The `` parameter is an instance of the Laravel query builder class
     * `Illuminate\Database\Eloquent\Builder`. It is used to build and execute database queries in an
     * object-oriented way within Laravel applications.
     * @param int category_id The `category_id` parameter is an integer value that represents the
     * unique identifier of a category. This parameter is used in the `scopeCategoryById` function to
     * filter the query results based on the category ID provided.
     */
    public function scopeCategoryById(Builder $query, int $category_id): void
    {
        $query->where('id', $category_id);
    }
}
