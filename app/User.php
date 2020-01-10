<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Webpatser\Uuid\Uuid;
use Spatie\Permission\Traits\HasRoles;
use App\Modules\Backend\Subscribes\Models\Subscribe;
use App\Modules\Backend\Events\Models\Event;
use App\Modules\Backend\Blogs\Models\Blog;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    protected $table = 'users';

    protected $primaryKey = 'id';

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            do {
                $model->id = (string) Uuid::generate(4);
            } while ($model->where($model->getKeyName(), $model->id)->first() != null);
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'about', 'is_active', 'verifyToken'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function selectable_field()
    {
        $languages = array();

        $db = DB::table('localization')->get();

        for ($i=0; $i < count($db); $i++) { 
            array_push($languages, ($db[$i])->locale_code . '_slug');
            array_push($languages, ($db[$i])->locale_code . '_title');
            array_push($languages, ($db[$i])->locale_code . '_description');
        }

        array_push($languages, 'background_image');

        array_push($languages, 'categories');

        array_push($languages, 'visits');

        array_push($languages, 'id');

        return $languages;
    }

    public function scopeExclude($query, $value = array())
    {
        return $query->select(array_diff($this->getColumns(), (array) $value));
    }

    public function subscribe_with_id()
    {
        return $this->hasOne(Subscribe::class, 'user_id');
    }

    public function has_subscribe()
    {
        return $this->hasOne(Subscribe::class, 'email', 'email');
    }

    public function has_blogs()
    {
        return $this->hasMany(Blog::class, 'author_id', 'id');
    }

    public function has_events()
    {
        return $this->hasMany(Event::class, 'author_id', 'id');
    }
}
