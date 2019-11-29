<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $admin_id
 * @property string $file_name
 * @property Admin $admin
 */
class ImportLog extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['admin_name', 'admin_email', 'status', 'message', 'file_name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }
}
