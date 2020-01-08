<?php

namespace App\Modules\Backend\Events\Models;

// use App\Traits\CleanUpProjectTrait;
use App\User;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Event extends Model implements HasMedia
{
    use HasMediaTrait, Sluggable;

    public $incrementing = false;

    protected $primaryKey = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'events';

    /**
     * Define Collection Name
     */
    protected $media_collection_name = 'event-images';

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

        for ($i=0; $i < count($db) ; $i++) { 
            array_push($excluding_columns, ($db[$i])->locale_code.'_slug');
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

        self::creating(function ($model) {
            do {
                $model->id = (string) Uuid::generate(4);
            } while ($model->where($model->getKeyName(), $model->id)->first() != null);
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
        'event_date' => 'datetime',
        'categories' => 'array'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        $languages = array();
        
        $db = DB::table('localization')->get();

        for ($i=0; $i < count($db) ; $i++) { 
            $languages[($db[$i])->locale_code.'_slug'] = [ 'source'=>($db[$i])->locale_code.'_title' ];
        }

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

    // public function getCustomAttribute() {
    //     return $this->title . ' ' . Carbon::now()->toDateTimeString();
    // }

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
}
