<?php

namespace Tests\Unit\Models;

use App\Models\Video;
use App\Models\Traits\Uuid;
use App\Models\Traits\UploadFiles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class VideoUnitTest extends TestCase
{


    public function testIfUseTrait()
    {
        $traits = [
            SoftDeletes::class, Uuid::class, UploadFiles::class
        ];
        $classTraits = array_keys(class_uses(Video::class));
        $this->assertEquals($traits, $classTraits);
    }

    public function testFillableAttribute()
    {
        $category = new Video;
        $this->assertEquals([
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
            ], $category->getFillable()
        );
    }

    public function testCastsAttribute()
    {
        $category = new Video;
        $casts = [
            'id' => 'string',
            'opened' => 'boolean',
            'year_launched' => 'integer',
            'duraction' => 'integer'
        ];
        $this->assertEquals(
            $casts,
            $category->getCasts()
        );
    }

}
