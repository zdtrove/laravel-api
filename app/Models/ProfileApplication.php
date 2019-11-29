<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $profile_id
 * @property int $application_id
 * @property float $level
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property Application $application
 * @property Profile $profile
 */
class ProfileApplication extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['profile_id', 'application_id', 'level', 'description', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function application()
    {
        return $this->belongsTo('App\Models\Application');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo('App\Models\Profile');
    }
}
