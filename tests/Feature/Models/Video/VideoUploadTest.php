<?php

namespace Tests\Feature\Models\Video;

use App\Models\Video;
use Tests\TestCase;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Http\UploadedFile;
use Storage;


class VideoUploadTest extends BaseVideoTestCase
{

    public function testCreateWithFiles()
    {
        Storage::fake();
        $video = Video::create(
            $this->data + [
                'thumb_file' => UploadedFile::fake()->image('thumb.jpg'),
                'video_file' => UploadedFile::fake()->create('video.mp4'),
                'banner_file' => UploadedFile::fake()->image('thumb.jpg'),
                'trailer_file' => UploadedFile::fake()->create('traile.mp4')
            ]
        );

        Storage::assertExists("{$video->id}/{$video->thumb_file}");
        Storage::assertExists("{$video->id}/{$video->video_file}");
        Storage::assertExists("{$video->id}/{$video->banner_file}");
        Storage::assertExists("{$video->id}/{$video->trailer_file}");
    }

    public function testCreateIfRollbackFiles()
    {
        Storage::fake();
        \Event::listen(TransactionCommitted::class, function(){
            throw new \Exception("Error Processing Request", 1);
        });

        $hasError = false;
        try {
            $video = Video::create(
                $this->data + [
                    'thumb_file' => UploadedFile::fake()->image('thumb.jpg'),
                    'video_file' => UploadedFile::fake()->create('video.mp4'),
                    'banner_file' => UploadedFile::fake()->image('thumb.jpg'),
                    'trailer_file' => UploadedFile::fake()->create('traile.mp4')
                ]
            );
        } catch (\Exception $exception) {
            $this->assertCount(0, Storage::allFiles());
            $hasError = true;
        }

        $this->assertTrue($hasError);

    }

    public function testUpdateWithFiles()
    {
        Storage::fake();
        $video = factory(Video::class)->create();
        $thumbFile = UploadedFile::fake()->image('thumb.jpg');
        $videoFile = UploadedFile::fake()->create('video.mp4');

        $video->update($this->data + [
                'thumb_file' => $thumbFile,
                'video_file' => $videoFile
            ]);

        Storage::assertExists("{$video->id}/{$video->thumb_file}");
        Storage::assertExists("{$video->id}/{$video->video_file}");

        $newVideoFile = UploadedFile::fake()->create('video.mp4');
        $video->update($this->data + [
                'video_file' => $newVideoFile
            ]);

        Storage::assertExists("{$video->id}/{$thumbFile->hashName()}");
        Storage::assertExists("{$video->id}/{$newVideoFile->hashName()}");
        Storage::assertMissing("{$video->id}/{$videoFile->hashName()}");
    }

    public function testUpdateIfRollbackFiles()
    {
        Storage::fake();
        $video = factory(Video::class)->create();
        \Event::listen(TransactionCommitted::class, function(){
            throw new \Exception("Error Processing Request", 1);
        });

        $hasError = false;
        try {
            $video->update(
                $this->data + [
                    'thumb_file' => UploadedFile::fake()->image('thumb.jpg'),
                    'video_file' => UploadedFile::fake()->create('video.mp4')
                ]
            );
        } catch (\Exception $exception) {
            $this->assertCount(0, Storage::allFiles());
            $hasError = true;
        }

        $this->assertTrue($hasError);

    }

    public function testUrlWithLocalDriver()
    {

        \Config::set('filesystems.default','video_local');

        $video = factory(Video::class)->create($this->fileFieldsData);
        $storagePath = config('filesystems.disks.video_local.url');

        foreach (Video::$fileFields as $field){
            $fullPath = "{$storagePath}/{$video->id}/{$video->{$field}}";
            $modelUrl =  "{$video->{"{$field}_url"}}";
            $this->assertEquals($fullPath, $modelUrl);
        }

    }

    public function testUrlWithGCSDriver()
    {
        $this->markTestSkipped();
        \Config::set('filesystems.default','gcs');

        $video = factory(Video::class)->create($this->fileFieldsData);
        $storagePath = config('filesystems.disks.gcs.storage_api_uri');

        foreach (Video::$fileFields as $field){
            $fullPath = "{$storagePath}/{$video->id}/{$video->{$field}}";
            $modelUrl =  "{$video->{"{$field}_url"}}";
            $this->assertEquals($fullPath, $modelUrl);
        }

    }

    public function testUrlWithNullFile()
    {
        \Config::set('filesystems.default','video_local');

        $video = factory(Video::class)->create();
        $storagePath = config('filesystems.disks.video_local.url');

        foreach (Video::$fileFields as $field){
            $this->assertNull($video->{"{$field}_url"});
        }
    }

}