<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $profile_id
 * @property string $award_title
 * @property string $description
 * @property string $award_date
 * @property string $created_at
 * @property string $updated_at
 * @property Portfolio $portfolio
 */
class Award extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['profile_id', 'title', 'description', 'award_date', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo('App\Models\Profile');
    }
}
