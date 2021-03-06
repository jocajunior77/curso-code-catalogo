<?php

namespace Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

class VideoStub extends Model
{

    protected $table = 'video_stubs';
    protected $fillable = ['name', 'description'];

    public static function createTable()
    {
        \Schema::create('video_stubs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description');
            $table->smallInteger('year_launched');
            $table->boolean('opened')->default(false);
            $table->string('rating',3);
            $table->smallInteger('duraction');
            $table->string('video_file',);
            $table->string('thumb_file',);
            $table->string('banner_file',);
            $table->string('trailler_file');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public static function dropTable()
    {
        \Schema::dropIfExists('video_stubs');
    }

}