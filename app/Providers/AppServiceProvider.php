<?php

namespace App\Providers;

use App\Modules\Backend\Blogs\Models\Blog;
use App\Observers\BlogObserver;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Spatie\MediaLibrary\Models\Media;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Webpatser\Uuid\Uuid;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        /* Begin Spatie Laravel Permission : UUID Adjustment */
        Permission::retrieved(function (Permission $permission) {
            $permission->incrementing = false;
        });

        Permission::creating(function (Permission $permission) {
            $permission->incrementing = false;
            $permission->id = Uuid::generate(4)->string;
        });

        Role::retrieved(function (Role $role) {
            $role->incrementing = false;
        });

        Role::creating(function (Role $role) {
            $role->incrementing = false;
            $role->id = Uuid::generate(4)->string;
        });
        /* End Spatie Laravel Permission: UUID Adjustment */

        /* Begin Spatie Media Library: UUID Adjustment */
        Media::retrieved(function (Media $media) {
            $media->incrementing = false;
        });

        Media::creating(function (Media $media) {
            $media->incrementing = false;
            $media->id = Uuid::generate(4)->string;
        });
        /* End Spatie Media Library: UUID Adjustment */

        /**
         * Begin Observe Model
         */
        Blog::observe(BlogObserver::class);
        /**
         * End Observe Model
         */
    }
}
