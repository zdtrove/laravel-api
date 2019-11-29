<?php

namespace App\Models;

use App\ModelTraits\HasRoleTrait;
use App\Notifications\AdminResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticate;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticate implements JWTSubject
{
    use Notifiable, BaseModel, SoftDeletes, HasRoleTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'uuid',
        'email',
        'image',
        'password',
        'status',
        'is_manager', // deprecated
        'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Append more attribute
     *
     * @var array
     */
    protected $appends = ['image_url', 'role', 'is_first_logged_in'];

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPassword($token));
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'guard' => ADMIN_GUARD
        ];
    }

    /**
     * Role of Admin
     *
     * @return string
     */
    public function getRoleAttribute()
    {
        return ADMIN;
    }

    /**
     * Return is first logged in flag
     *
     * @return string
     */
    public function getIsFirstLoggedInAttribute()
    {
        return !empty($this->uuid);
    }

    public function getIsManagerRoleAttribute()
    {
        return $this->adminRoles->contains('role', ADMIN_ROLE_MANAGER) ? 1 : 0;
    }

    public function adminRoles()
    {
        return $this->hasMany(AdminRole::class, 'admin_id', 'id');
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['is_manager'] = $this->isManagerRole;
        $array['admin_roles'] = $this->adminRoles->pluck('role')->all();
        return $array;
    }
}
