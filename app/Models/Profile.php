<?php

namespace App\Models;

use App\ModelTraits\HasRoleTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticate;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name_kanji
 * @property string $name_furigana
 * @property boolean $sex
 * @property string $birth
 * @property string $address
 * @property string $tel
 * @property string $email
 * @property string $appeal
 * @property string $image
 * @property string $facebook
 * @property string $twitter
 * @property string $instagram
 * @property string $site_url
 * @property string $pdf
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Career[] $careers
 * @property Portfolio[] $portfolios
 * @property ProfileApplication[] $profileApplications
 * @property ProfileSkill[] $profileSkills
 * @property Visibility[] $visibilities
 */
class Profile extends Authenticate implements JWTSubject
{
    use Notifiable, BaseModel, SoftDeletes, HasRoleTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'uuid',
        'full_name',
        'sex',
        'birth',
        'address',
        'tel',
        'email',
        'password',
        'appeal',
        'job_title',
        'image',
        'facebook',
        'google',
        'twitter',
        'instagram',
        'site_url',
        'pdf',
        'status',
        'token_confirm',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'token_confirm'
    ];

    /**
     * Append more attribute
     *
     * @var array
     */
    protected $appends = ['image_url', 'pdf_url',  'role'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function careers()
    {
        return $this->hasMany('App\Models\Career');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function portfolios()
    {
        return $this->hasMany('App\Models\Portfolio');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profileApplications()
    {
        return $this->hasMany('App\Models\ProfileApplication');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profileSkills()
    {
        return $this->hasMany('App\Models\ProfileSkill');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function visibilities()
    {
        return $this->hasMany('App\Models\Visibility');
    }

    public function skills()
    {
        return $this->hasManyThrough(
            'App\Models\Skill',
            'App\Models\ProfileSkill',
            'profile_id',
            'id',
            'id',
            'skill_id'
        );
    }

    public function applications()
    {
        return $this->hasManyThrough(
            'App\Models\Application',
            'App\Models\ProfileApplication',
            'profile_id',
            'id',
            'id',
            'application_id'
        );
    }

    public function occupations()
    {
        return $this->hasManyThrough(
            'App\Models\Occupation',
            'App\Models\Career',
            'profile_id',
            'id',
            'id',
            'occupation_id'
        );
    }

    public function scopeOccupation($query)
    {
        return $query->leftJoin(
            'careers',
            'careers.profile_id',
            '=',
            'profiles.id'
        )->leftJoin(
            'occupations',
            'occupations.id',
            '=',
            'careers.occupation_id'
        )->orderBy(DB::raw('careers.work_to IS NULL'), 'DESC')
            ->orderBy('careers.work_to', 'DESC')
            ->groupBy('profiles.id');
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
            'guard' => PROFILE_GUARD
        ];
    }

    /**
     * Role of User
     *
     * @return string
     */
    public function getRoleAttribute()
    {
        return PROFILE;
    }

    /**
     * Visibility of Profile
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasOne|object
     */
    public function getVisibilitiesAttribute()
    {
        return 'bla bla bla';
        return $this->visibilities()->first();
    }
}
