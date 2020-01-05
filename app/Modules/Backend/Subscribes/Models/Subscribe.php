<?php

namespace App\Modules\Backend\Subscribes\Models;

// use App\Traits\CleanUpProjectTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\DB;

class Subscribe extends Model
{
    public $incrementing = false;

    protected $primaryKey = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subscribes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'email', 'categories', 'users', 'follow_by', 'blogs'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['user_id', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    public function getCasts()
    {
        $a = array();

        foreach ($this->getColumns() as $key => $value) {
            $type_name = DB::connection()->getDoctrineColumn($this->table, $value)->getType()->getName();

            if ($type_name == 'json') {
                array_push($a, [$value => 'array']);
            }
        }
        return array_merge(...array_values($a));
    }

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

    public function getMetaAttribute($value)
    {
        return json_decode($value, true);
    }

    public function scopeExclude($query, $value = array())
    {
        return $query->select(array_diff($this->getColumns(), (array) $value));
    }
}
