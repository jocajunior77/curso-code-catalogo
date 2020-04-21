<?php

namespace Tests\Feature\Http\Controllers\Api\VideoController;

use App\Models\Video;
use App\Models\Category;
use App\Models\Genre;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;
use Tests\Traits\TestUploads;
use App\Http\Controllers\Api\VideoController;

class VideoControllerUploadsTest extends BaseVideoControllerTestCase
{

    use TestValidations, TestSaves, TestUploads;

    public function testInvalidationThumbField()
    {
        $this->assertInvalidationFile(
            'thumb_file',
            'jpg',
            Video::THUMB_FILE_MAX_SIZE,
            'image'
        );
    }


    public function testInvalidationBannerField()
    {
        $this->assertInvalidationFile(
            'banner_file',
            'jpg',
            Video::BANNER_FILE_MAX_SIZE,
            'image'
        );
    }


    public function testInvalidationTrailerField()
    {
        $this->assertInvalidationFile(
            'trailer_file',
            'mp4',
            Video::TRAILER_FILE_MAX_SIZE,
            'mimetypes',
            ['values' => 'video/mp4']
        );
    }

    public function testInvalidationFile()
    {
        $this->assertInvalidationFile(
            'video_file',
            'mp4',
            Video::VIDEO_FILE_MAX_SIZE,
            'mimetypes',
            ['values' => 'video/mp4']
        );
    }
}
