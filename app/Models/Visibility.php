<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $profile_id
 * @property boolean $sex
 * @property boolean $birth
 * @property boolean $tel
 * @property boolean $email
 * @property Profile $profile
 */
class Visibility extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['profile_id', 'group_id', 'object_id', 'object_type'];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo('App\Models\Profile');
    }
}
