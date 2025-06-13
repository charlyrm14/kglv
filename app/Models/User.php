<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'mothers_name',
        'birth_date',
        'email',
        'phone_number',
        'user_code',
        'password',
        'token',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (User $user) {
            $user->user_code = (string) date('YmdHis');
            $user->token = (string) Str::uuid();
        });
    }

    /**
     * The function setPasswordAttribute in PHP hashes the input password before setting it as an
     * attribute.
     * 
     * @param password The `setPasswordAttribute` function is a mutator in Laravel Eloquent that
     * automatically hashes the password before saving it to the database. This helps to ensure that
     * the password is securely stored.
     */
    public function setPasswordAttribute($password){
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * The getJWTIdentifier function returns the key of the current object for JWT identification.
     * 
     * @return The `getJWTIdentifier` function is returning the key of the current object.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * The function getJWTCustomClaims() in PHP returns an empty array.
     * 
     * @return An empty array is being returned.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get the post that owns the comment.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * The `biography` function returns a `HasOne` relationship with the `UserBiography` model.
     * 
     * @return HasOne An instance of the `HasOne` relationship with the `UserBiography` model is being
     * returned.
     */
    public function biography() : HasOne
    {
        return $this->hasOne(UserBiography::class);
    }

    public function featured() : HasOne
    {
        return $this->hasOne(FeaturedUser::class);
    }

    /**
     * The scopeById function filters a query by a specific user ID.
     * 
     * @param Builder query The `` parameter is an instance of the
     * `Illuminate\Database\Eloquent\Builder` class, which is used for building database queries in
     * Laravel's Eloquent ORM.
     * @param int user_id The `user_id` parameter is an integer value that represents the unique
     * identifier of a user. In the provided code snippet, this parameter is used to filter the query
     * results based on the `id` column matching the specified `user_id`.
     */
    public function scopeById(Builder $query, int $user_id): void
    {
        $query->where('id', $user_id);
    }

    /**
     * The scopeByEmail function filters a query by a specific user ID.
     * 
     * @param Builder query The `` parameter is an instance of the
     * `Illuminate\Database\Eloquent\Builder` class, which is used for building database queries in
     * Laravel's Eloquent ORM.
     * @param int email The `email` parameter is an integer value that represents the unique
     * identifier of a user. In the provided code snippet, this parameter is used to filter the query
     * results based on the `id` column matching the specified `email`.
     */
    public function scopeByEmail(Builder $query, string $email): void
    {
        $query->select('id', 'name', 'last_name', 'mothers_name', 'email', 'role_id')->where('email', $email);
    }

    /**
     * This PHP function retrieves users' information based on their birthdate matching the current
     * month and day.
     * 
     * @return The `usersBirthdate` function is returning a collection of users who have their birthday
     * on the current day. The function selects the `id`, `name`, `last_name`, and `mothers_name`
     * columns from the database table. It then filters the results based on the month and day of the
     * `birth_date` column matching the current month and day. Finally, it retrieves the filtered
     * results
     */
    public static function usersBirthdate()
    {
        $today = now();

        return static::select(
            'id',
            'name',
            'last_name',
            'mothers_name',
            'birth_date'
        )
        ->whereRaw('MONTH(birth_date) = ? AND DAY(birth_date) = ?', [$today->month, $today->day])
        ->get();
    }

    /**
     * The function `scopeUsersWithRole` selects specific columns from the users table and eager loads
     * the associated role relationship.
     * 
     * @param Builder query The `scopeUsersWithRole` function is a query scope in Laravel Eloquent that
     * can be used to retrieve users along with their associated role. The function takes a `Builder`
     * instance `` as a parameter, which represents the query being built.
     */
    public function scopeGetUsers(Builder $query): void
    {
        $query->select(
            'id', 
            'name',
            'last_name',
            'mothers_name',
            'birth_date',
            'email',
            'phone_number',
            'user_code',
            'role_id'
        );
    }

    /**
     * Get the classes that owns the user.
     */
    public function classes(): HasMany
    {
        return $this->hasMany(UserClass::class);
    }
}
