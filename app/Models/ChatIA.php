<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ChatIA extends Model
{
    protected $table = 'chat_ia';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'sender',
        'message'
    ];

    /**
     * This PHP function filters a query by a specific user ID to retrieve conversation history.
     * 
     * @param Builder query The `` parameter is an instance of the Laravel query builder class
     * `Illuminate\Database\Eloquent\Builder`. It is used to build and execute database queries in an
     * object-oriented way within Laravel applications.
     * @param int user_id The `user_id` parameter is an integer value that represents the unique
     * identifier of a user. In the provided code snippet, the `scopeConversationHistoryByUser`
     * function is a query scope that filters the query based on the `user_id` column matching the
     * specified user ID.
     */
    public function scopeConversationHistoryByUser(Builder $query, int $user_id): void
    {
        $query->where('user_id', $user_id);
    }

    /**
     * The function `saveLog` saves a log entry with the sender type, message, and optional user ID.
     * 
     * @param string sender_type The `sender_type` parameter in the `saveLog` function is a string that
     * represents the type of the sender of the log message. It could be a user type, system type, or
     * any other identifier that helps categorize the sender of the log message.
     * @param string message The `saveLog` function takes three parameters:
     * @param user_id The `user_id` parameter in the `saveLog` function is an optional parameter with a
     * default value of 0. This means that if a value for `user_id` is not provided when calling the
     * function, it will default to 0.
     * 
     * @return The `saveLog` function is returning the result of creating a new log entry in the
     * database with the provided `user_id`, `sender`, and `message` values.
     */
    public static function saveLog(string $sender_type, string $message, int $user_id)
    {
        static::create(['user_id' => $user_id, 'sender' => $sender_type, 'message' => $message]);
    }    
}
