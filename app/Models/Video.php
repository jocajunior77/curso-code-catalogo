<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid;

class Video extends BaseModel
{

    use SoftDeletes, Uuid;

    const RATING_LIST = ['L','10','12','14','16','18'];

    protected $fillable = [
        'title',
        'description',
        'year_launched',
        'opened',
        'rating',
        'duraction'
    ];

    protected $dates = ['deleted_at'];

    protected  $casts = [
        'id'            => 'string',
        'opened'        => 'boolean',
        'year_launched' => 'integer',
        'duraction'     => 'integer',
    ];

    public $incrementing = false;


    public static function create(array $attributes = [])
    {
        try {
            Video::beginTransaction();
            $obj = static::query()->create($attributes);
            Video::handleRelations($obj, $attributes);
            //uploads aqui
            Video::commit();
            return $obj;
        } catch (\Exception $e) {
            if(isset($obj)) {
                //excluir os arquivos de uploads
            }
            Video::rollBack();
            throw $e;
        }
    }


    public function update(array $attributes = [], array $options = [])
    {
        try {
            Video::beginTransaction();
            $saved = parent::update($attributes, $options);
            Video::handleRelations($this, $attributes);
            if($saved) {
                //uploads aqui
                //excluir os arquivos antigos
            }
            Video::commit();
            return $saved;
        } catch (\Exception $e) {
            //excluir os arquivos de uploads
            Video::rollBack();
            throw $e;
        }
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTrashed();
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class)->withTrashed();
    }

    public static function handleRelations(Video $video, array $attributes) {

        if(isset($attributes['categories_id'])) {
            $video->categories()->sync($attributes['categories_id']);
        }
        if(isset($attributes['genres_id'])) {
            $video->genres()->sync($attributes['genres_id']);
        }
    }
}