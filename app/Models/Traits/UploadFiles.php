<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;


trait UploadFiles
{

    public $oldFiles = [];

    public abstract function uploadDir();

    public static function bootUploadFiles()
    {
        static::updating(function(Model $model) {
            $fieldUpdated = array_keys($model->getDirty());
            $filesUpdated = array_intersect($fieldUpdated, self::$fileFields);
            $filesFiltered = Arr::where($filesUpdated, function($fileField) use($model) {
                return $model->getOriginal($fileField);
            });
            $model->oldFiles = array_map(function($fileField) use ($model) {
                return $model->getOriginal($fileField);
            }, $filesFiltered);
        });
    }

    public function uploadFiles(array $files)
    {
        foreach ($files as $file) {
            $this->uploadFile($file);
        }
    }

    public function uploadFile(UploadedFile $file)
    {
        $file->store($this->uploadDir());
    }

    public function deleteFiles(array $files)
    {
        foreach ($files as $file) {
            $this->deleteFile($file);
        }
    }


    public function deleteOldFiles()
    {
        $this->deleteFiles($this->oldFiles);
    }


    /**
     * @param string|UploadedFile $file
     */

    public function deleteFile($file)
    {
        $filename = $file instanceof UploadedFile ? $file->hashName() : $file;
        \Storage::delete("{$this->uploadDir()}/{$filename}");
    }

    // &$attributes -> desse jeito os attributes sao alterados automaticamente
    //  aonde chamaram o metodo
    public static function extractFiles(array &$attributes = [])
    {
        $files = [];
        foreach (self::$fileFields as $file) {
            if(isset($attributes[$file]) && $attributes[$file] instanceof UploadedFile) {
                $files[] = $attributes[$file];
                $attributes[$file] = $attributes[$file]->hashName();
            }
        }
        return $files;
    }

    public function relativeFilePath($value) {

        return "{$this->uploadDir()}/{$value}";
    }


    public function getFileUrl($file)
    {
        return \Storage::url($this->relativeFilePath($file));
    }
}