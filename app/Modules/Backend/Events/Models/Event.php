<?php

namespace App\Modules\Backend\Events\Models;

// use App\Traits\CleanUpProjectTrait;
use App\User;
// use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
    // use Sluggable;

    public $incrementing = false;

    protected $primaryKey = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'events';

    // protected $fillable = ['title', 'description', 'qr_code', 'promotion', 'categories', 'author', 'participants', 'event_date'];
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
    // public function sluggable()
    // {
    //     $languages = array();
        
    //     $db = DB::table('localization')->get();

    //     for ($i=0; $i < count($db) ; $i++) { 
    //         $languages[($db[$i])->locale_code.'_slug'] = [ 'source'=>($db[$i])->locale_code.'_title' ];
    //     }

    //     return $languages;
    // }

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
}
