<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property int $profile_id
 * @property string $title
 * @property string $position
 * @property string $ref_url
 * @property string $description
 * @property string $thumbnail
 * @property string $work_from
 * @property string $work_to
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Profile $profile
 * @property Award[] $awards
 */
class Portfolio extends Model
{
    use Notifiable, BaseModel, SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'profile_id',
        'title',
        'position',
        'ref_url',
        'description',
        'thumbnail',
        'work_from',
        'work_to',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Append more attribute
     *
     * @var array
     */
    protected $appends = ['thumbnail_url'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo('App\Models\Profile');
    }
}
