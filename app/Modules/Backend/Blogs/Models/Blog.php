<?php

namespace App\Modules\Backend\Blogs\Models;

// use App\Traits\CleanUpProjectTrait;
use App\User;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Webpatser\Uuid\Uuid;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;

class Blog extends Model implements HasMedia
{
    use HasMediaTrait, Sluggable;

    public $incrementing = false;

    protected $primaryKey = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blogs';

    /**
     * Define Collection Name
     */
    protected $media_collection_name = 'blog-images';

    /**
     * Defining new media collection
     *
     * @var null
     */
    public function registerMediaCollections()
    {
        $this->addMediaCollection($this->media_collection_name)
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('card')
                    ->width(350)
                    ->height(250);

                $this->addMediaConversion('slider')
                    ->width(1440)
                    ->height(960);

                $this->addMediaConversion('thumb')
                    ->width(50)
                    ->height(50);
            });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function getFillable()
    {
        $excluding_columns = array();

        $db = DB::table('localization')->get();

        for ($i = 0; $i < count($db); $i++) {
            array_push($excluding_columns, ($db[$i])->locale_code . '_slug');
        }

        foreach ($this->hidden as $key => $value) {
            array_push($excluding_columns, $value);
        }

        array_push($excluding_columns, $this->primaryKey);

        return array_diff($this->getColumns(), $excluding_columns);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();

        // $this->fillable = $this->getFillableColumns();

        self::creating(function ($model) {
            do {
                $model->id = (string) Uuid::generate(4);
            } while ($model->where($model->getKeyName(), $model->id)->first() != null);
        });

        self::saved(function ($model)
        {
            if (Auth::check() && Auth::id() == $model->author_id) {
                $blogs = Auth::user()->has_blogs;

                if (Cache::has('_'. Auth::id() . '_blog_data')) {
                    Cache::forget('_'. Auth::id() . '_blog_data');
                }

                Cache::store('database')->put('_'. Auth::id() . '_blog_data', $blogs, Config::get('cache.lifetime'));
            }
        });
    }

    protected function getColumns()
    {
        return Schema::getColumnListing($this->table);
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'categories' => 'array',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        // $languages = array();

        // $db = DB::table('localization')->get();

        // for ($i = 0; $i < count($db); $i++) {
        //     $languages[($db[$i])->locale_code . '_slug'] = ['source' => ($db[$i])->locale_code . '_title'];
        // }

        return $languages;
    }

    public function getMetaAttribute($value)
    {
        return json_decode($value, true);
    }

    public function scopeExclude($query, $value = array())
    {
        return $query->select(array_diff($this->getColumns(), (array) $value));
    }

    public function scopeCategory($query, $category)
    {
        $post_id = array();

        $query->chunk(100, function ($posts) use ($category, $post_id) {
            foreach ($posts as $key => $post) {
                $tags = json_decode($post->categories);

                for ($i = 0; $i < count($tags); $i++) {
                    if ($tags[$i]->categories_id == $category->id) {
                        array_push($post_id, $post->id);
                    }
                }
            }
        });

        return $query->whereIn($this->primaryKey, $post_id);
    }

    public function get_disk_for_media()
    {
        /**
         * See filesystem.php for more disks
         */
        return 'upload';
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function media_url($conversions)
    {
        return $this->getFirstMediaUrl($this->media_collection_name, $conversions);
    }

    public function add_media_from_disk($name, $fileName)
    {
        return $this->addMediaFromDisk($fileName, $this->get_disk_for_media())->usingName($name)->usingFileName($fileName)->toMediaCollection($this->media_collection_name);
    }

    /**
     * $table->uuid('model_id');
            *$table->string('model_type');
            *$table->index(['model_id', 'model_type']);
     */
}
