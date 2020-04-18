<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid;
use App\Models\Traits\UploadFiles;

class Video extends BaseModel
{

    use SoftDeletes, Uuid, UploadFiles;

    const RATING_LIST = ['L','10','12','14','16','18'];

    protected $fillable = [
        'title',
        'description',
        'year_launched',
        'opened',
        'rating',
        'duraction',
        'video_file',
        'thumb_file',
        'banner_file',
        'trailler_file'
    ];

    protected $dates = ['deleted_at'];

    protected  $casts = [
        'id'            => 'string',
        'opened'        => 'boolean',
        'year_launched' => 'integer',
        'duraction'     => 'integer',
    ];

    public $incrementing = false;
    public static $fileFields = ['video_file', 'thumb_file', 'banner_file', 'trailer_file'];

    public static function create(array $attributes = [])
    {
        $files = self::extractFiles($attributes);
        try {
            Video::beginTransaction();
            $obj = static::query()->create($attributes);
            Video::handleRelations($obj, $attributes);
            $obj->uploadFiles($files);
            Video::commit();
            return $obj;
        } catch (\Exception $e) {
            if(isset($obj)) {
                $obj->deleteFiles($files);
            }
            Video::rollBack();
            throw $e;
        }
    }


    public function update(array $attributes = [], array $options = [])
    {
        $files = self::extractFiles($attributes);
        try {
            Video::beginTransaction();
            $saved = parent::update($attributes, $options);
            Video::handleRelations($this, $attributes);
            if($saved) {
                $this->uploadFiles($files);
            }
            Video::commit();
            if($saved && count($files)) {
                $this->deleteOldFiles();
            }
            return $saved;
        } catch (\Exception $e) {
            $this->deleteFiles($files);
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

    protected function uploadDir()
    {
        return $this->id;
    }
}