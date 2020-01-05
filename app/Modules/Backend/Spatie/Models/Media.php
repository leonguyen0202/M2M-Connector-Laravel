<?php

namespace App\Modules\Backend\Spatie\Models;

use Spatie\MediaLibrary\Models\Media as BaseMedia;
use Webpatser\Uuid\Uuid;

class Media extends BaseMedia
{
    public $incrementing = false;

    /**
     * @see https://github.com/spatie/laravel-medialibrary/issues/1112#issuecomment-531477078
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            do {
                $model->id = (string) Uuid::generate(4);
            } while ($model->where($model->getKeyName(), $model->id)->first() != null);
        });
    }
}