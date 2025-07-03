<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
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
        'first_name',
        'last_name',
        'mother_last_name',
        'birth_date',
        'email',
        'phone_number',
        'profile_image',
        'user_code',
        'password',
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
     * Add custom attributes to the model's array and JSON representations.
     *
     * This ensures that the 'current_swimming_level' accessor is automatically
     * included when the model is serialized.
     *
     * @var array<int, string>
     */
    protected $appends = ['current_swimming_level'];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (User $user) {
            $user->user_code = (string) date('YmdHis');
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
     * The `biography` function returns a `HasMany` relationship with the `UserBiography` model.
     * 
     * @return HasMany An instance of the `HasMany` relationship with the `UserBiography` model is being
     * returned.
     */
    public function profile() : HasMany
    {
        return $this->hasMany(UserProfile::class)->where('visible_to', 'public');
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
        $query->select('id', 'first_name', 'last_name', 'mother_last_name', 'email', 'role_id')->where('email', $email);
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
            'first_name',
            'last_name',
            'mother_last_name',
            'birth_date'
        )
        ->whereRaw('MONTH(birth_date) = ? AND DAY(birth_date) = ?', [$today->month, $today->day])
        ->get();
    }

    /**
     * Get all users whose birthday is today (matching current day and month).
     *
     * This method performs a query to select users whose birth_date 
     * matches today's day and month. It also eagerly loads the 'profile' 
     * relationship to minimize database queries.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function todayBirthdayUsers()
    {
        $today = now();

        return static::with('profile')->select(
            'id',
            'first_name',
            'last_name',
            'mother_last_name',
            'birth_date',
            'role_id'
        )
        ->whereDay('birth_date', $today->day)
        ->whereMonth('birth_date', $today->month)
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
            'first_name',
            'last_name',
            'mother_last_name',
            'birth_date',
            'email',
            'phone_number',
            'user_code',
            'role_id'
        )->orderByDesc('id');
    }

    /**
     * Scope a query to only include users with the "student" role (role_id = 3).
     *
     * This query selects specific user fields and filters the results 
     * to only include users whose role ID matches the student role.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function scopeGetStudents(Builder $query): void
    {
        $query->select(
            'id', 
            'first_name',
            'last_name',
            'mother_last_name',
            'birth_date',
            'email',
            'phone_number',
            'user_code',
            'role_id'
        )->where('role_id', 3);
    }

    /**
     * Get the user's class schedules that are currently active.
     *
     * This relationship returns only schedules where the 'status' is set to 1,
     * meaning they are considered active.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activeSchedules(): HasMany
    {
        return $this->hasMany(UserSchedule::class)->where('status', 1);
    }

    /**
     * Get all swimming level records assigned to the user.
     *
     * This includes a relation to the corresponding 'swimmingLevel' model,
     * which contains the level details (e.g., name, image, etc.).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function swimmingLevels(): HasMany
    {
        return $this->hasMany(UserSwimmingLevel::class)->with('swimmingLevel');
    }

    /**
     * Accessor: Get the user's current (highest) swimming level.
     *
     * This accessor returns the `SwimmingLevel` model associated with the user's
     * highest `swimming_level_id` from the `swimmingLevels` relationship.
     *
     * Requirements:
     * - The `swimmingLevels` relation must be loaded.
     * - At least one `UserSwimmingLevel` record must exist for the user.
     *
     * If these conditions are not met, it returns null.
     *
     * @return \App\Models\SwimmingLevel|null
     */
    public function getCurrentSwimmingLevelAttribute()
    {
        /**
         * Valida que se haya cargado la relacion de swimmingLevels y que esta tenga registros
         */
        if ($this->relationLoaded('swimmingLevels') && $this->swimmingLevels->isNotEmpty()) {
            /**
             * Si hay registros obtiene el id mas alto para mostrarlo como
             * el nivel actual
             */
            $current = $this->swimmingLevels->sortByDesc('swimming_level_id')->first();
            return $current->swimmingLevel ?? NULL;
        }

        return NULL;
    }
}
