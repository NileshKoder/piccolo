<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Features\Masters\Users\Constants\UserConstants;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Features\Masters\Users\Domains\Queries\UserScopes;

class User extends Authenticatable implements UserConstants
{
    use Notifiable, UserScopes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function persistCreatUser(array $userData): ?User
    {
        $user = null;
        $user = DB::transaction(function () use ($userData) {
            return User::create($userData);
        });

        return $user;
    }

    public static function persistUpdateUser(User $user, array $userData): ?User
    {
        DB::transaction(function () use ($user, $userData) {
            $user->update($userData);
        });

        return $user;
    }

    public static function persistUpdateState(User $user, string $currentState)
    {
        return DB::transaction(function () use ($user, $currentState) {
            if ($currentState == User::ACTIVE) {
                $user->state = User::INACTIVE;
            } else {
                $user->state = User::ACTIVE;
            }

            $user->update();
        });
    }

    public function isActive(): bool
    {
        return $this->state == self::ACTIVE ? true : false;
    }
}
