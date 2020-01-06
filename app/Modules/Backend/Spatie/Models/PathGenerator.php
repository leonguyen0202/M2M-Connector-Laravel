<?php

namespace App\Modules\Backend\Spatie\Models;

use App\Modules\Backend\Blogs\Models\Blog;
use App\Modules\Backend\Settings\Models\Developer;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\PathGenerator\PathGenerator as BaseGenerator;

class PathGenerator implements BaseGenerator
{
    public function getPath(Media $media): string
    {
        if ($media instanceof \App\Modules\Backend\Blogs\Models\Blog) {
            return 'posts/' . md5($media->id) . '/';
        }

        if ($media instanceof \App\Modules\Backend\Events\Models\Event) {
            return 'events/' . md5($media->id) . '/';
        }
        return md5($media->id) . '/';
    }
    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . 'conversions/';
    }
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . '/responsive/';
    }

    protected function imageUploadPath()
    {
        $settings = Developer::query()->where([
            ['type', '=', 'upload'],
        ])->first();

        return $settings->details['default']['path'][0];
    }
}
