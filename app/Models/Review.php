<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property int $admin_id
 * @property int $profile_id
 * @property string $project_name
 * @property string $project_detail
 * @property string $project_start
 * @property string $project_end
 * @property string $rating
 * @property string $comment
 * @property string $refer_url
 * @property string $refer_file
 * @property string $created_at
 * @property string $updated_at
 * @property Admin $admin
 * @property Profile $profile
 */
class Review extends Model
{
    use Notifiable, BaseModel, SoftDeletes;
    /**
     * @var array
     */
    protected $fillable = [
        'admin_id',
        'profile_id',
        'project_name',
        'project_detail',
        'project_start',
        'project_end',
        'rating',
        'comment',
        'refer_url',
        'refer_file'
    ];

    /**
     * Append more attribute
     *
     * @var array
     */
    protected $appends = ['refer_file_url'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }
}
