<?php

namespace App\Models;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

trait BaseModel
{
    /**
     * Add or update the owner
     */
    public static function boot()
    {
        parent::boot();
        static::saving(function ($item) {
            $currentUser = auth()->user() ? auth()->user()->email : 'system';
            $table = $item->getTable();
            if (Schema::hasColumn($table, 'created_by') && Schema::hasColumn($table, 'updated_by')) {
                if (empty($item->id)) {
                    $item->created_by = $currentUser;
                }
                $item->updated_by = $currentUser;
            }
        });
    }

    /**
     * Scope a query to only include status
     *
     * @param $query
     * @param int $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatus($query, $status = 1)
    {
        return $query->where('status', $status);
    }

    /**
     * Full path of image
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return !empty($this->image) ? asset(Storage::url(
            UPLOAD_PATH . SLASH . str_singular($this->getTable()) . SLASH . $this->id . SLASH . $this->image
        )) : null;
    }

    /**
     * Full path of thumbnail
     *
     * @return string
     */
    public function getThumbnailUrlAttribute()
    {
        return !empty($this->thumbnail) ? asset(Storage::url(
            UPLOAD_PATH . SLASH . str_singular($this->getTable()) . SLASH . $this->id . SLASH . $this->thumbnail
        )) : null;
    }

    /**
     * Full path of refer file
     *
     * @return string
     */
    public function getPdfUrlAttribute()
    {
        return !empty($this->pdf) ? asset(Storage::url(
            UPLOAD_PATH . SLASH . str_singular($this->getTable()) . SLASH . $this->id . SLASH . $this->pdf
        )) : null;
    }

    /**
     * Full path of pdf
     *
     * @return string
     */
    public function getReferFileUrlAttribute()
    {
        return !empty($this->refer_file) ? asset(Storage::url(
            UPLOAD_PATH . SLASH . str_singular($this->getTable()) . SLASH . $this->id . SLASH . $this->refer_file
        )) : null;
    }
}
