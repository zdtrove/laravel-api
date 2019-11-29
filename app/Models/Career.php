<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $profile_id
 * @property int $occupation_id
 * @property int $company_id
 * @property string $description
 * @property string $work_from
 * @property string $work_to
 * @property string $created_at
 * @property string $updated_at
 * @property Company $company
 * @property Occupation $occupation
 * @property Profile $profile
 */
class Career extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'profile_id',
        'occupation_id',
        'company_id',
        'description',
        'work_from',
        'work_to',
        'created_at',
        'updated_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function occupation()
    {
        return $this->belongsTo('App\Models\Occupation');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo('App\Models\Profile');
    }
}
