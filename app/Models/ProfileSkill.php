<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $profile_id
 * @property int $skill_id
 * @property float $level
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property Skill $skill
 * @property Profile $profile
 */
class ProfileSkill extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['profile_id', 'skill_id', 'level', 'description', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function skill()
    {
        return $this->belongsTo('App\Models\Skill');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo('App\Models\Profile');
    }
}
