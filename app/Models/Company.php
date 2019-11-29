<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property int $count
 * @property string $created_at
 * @property string $updated_at
 * @property Career[] $careers
 */
class Company extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['title', 'count', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function careers()
    {
        return $this->hasMany('App\Models\Career');
    }
}
