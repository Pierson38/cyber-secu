<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $approval_at
 * @property int|null $approval_by
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereApprovalAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereApprovalBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @property string $role
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @property string|null $picture
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePicture($value)
 * @mixin \Eloquent
 */
class User extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'approval_at',
        'approval_by',
        'picture'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'approval_at' => 'datetime',
        ];
    }

    public function getTextPresentation()
    {
        return sprintf("%s (%s) [Role = %s]", $this->name, $this->email, $this->role);
    }

    protected function password(): Attribute
    {
        // Secure way to store password : we use APP_NAME to have integrity of our password in database with salted algo
        return Attribute::make(
            get: fn(string $value) => $value,
            set: fn(string $value) => sprintf('%s_%s_%s',
                config('app.name'), md5($value), config('app.name'))
        );
    }

    public function checkIfPasswordIsCorrect(string $givenPassword)
    {
        $encrypted_password_from_db = preg_split('/_/', str_replace(
            config('app.name'), '',
            $this->password), -1, PREG_SPLIT_NO_EMPTY);

        if (empty($encrypted_password_from_db)) {
            return false;
        }

        return md5($givenPassword) === $encrypted_password_from_db[0];
    }

    public function isUserApproved()
    {
        if (is_null($this->approval_by)) {
            return false;
        }
        return true;
    }

    public function getUserWhoGivesApproval()
    {
        return User::where('id', "=", $this->approval_by)->first();
    }
}
