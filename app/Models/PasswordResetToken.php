<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'token',
    ];

    protected $table = 'password_reset_tokens';

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (PasswordResetToken $password_token) {
            $password_token->created_at = now();
        });
    }

    /**
     * Retrieve a valid password reset token record.
     *
     * @param string $token
     * @return PasswordResetToken|null
     */
    public static function verifyToken(string $token): ?PasswordResetToken
    {   
        return static::where([
            ['token', $token],
            ['created_at', '>=', now()->subMinutes(15)]
        ])->first();
    }

    /**
     * Deletes all existing password reset tokens for the given email.
     *
     * This method ensures that only one valid reset token is stored per user,
     * preventing multiple active tokens at the same time.
     * It is typically called before generating and saving a new token.
     *
     * @param string $email The email address whose tokens should be deleted.
     *
     * @return void
     */
    public static function deleteOlderTokens(string $email): void
    {
        static::where('email', $email)->delete();
    }
}
