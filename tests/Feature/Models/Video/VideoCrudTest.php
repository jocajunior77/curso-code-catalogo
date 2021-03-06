<?php

namespace Tests\Feature\Models\Video;

use App\Models\Video;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Database\QueryException;


class VideoCrudTest extends BaseVideoTestCase
{

    public function testCreate()
    {
        $UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        $video = factory(Video::class)->create();
        $video->refresh();
        $this->assertEquals(preg_match($UUIDv4, $video->id),1);
    }

    public function testDelete()
    {
        $video = factory(Video::class)->create();
        $video->refresh();
        $this->assertTrue($video->delete());
    }

    public function testList()
    {
        factory(Video::class)->create();
        $categories = Video::all();
        $this->assertCount(1, $categories);
        $videoKeys = array_keys($categories->first()->getAttributes());

        $videoFields = [
            'id',
            'title',
            'description',
            'opened',
            'duraction',
            'rating',
            'year_launched',
            'thumb_file',
            'banner_file',
            'video_file',
            'trailer_file',
            'created_at',
            'updated_at',
            'deleted_at'
        ];
        $this->assertEqualsCanonicalizing($videoFields, $videoKeys);
    }

    public function testUpdate()
    {
        $video = factory(Video::class)->create([
            'description' => 'teste_create',
            'opened'   => false
        ]);

        $data = [
           'title'      => 'teste_update',
           'description' => 'teste_update_description',
           'opened'      => true
        ];

        $video->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $video->{$key} );
        }


    }

    public function testRollBackCreate()
    {
        $hasError = false;
        try {
            Video::create($this->data + [ 'categories_id' => [0,1,2]]);

        } catch (QueryException $exception) {
            $hasError = true;
            $this->assertCount(0, Video::all());
        }
        $this->assertTrue($hasError);

    }

    public function testRollBackUpdate()
    {

        $video = factory(Video::class)->create();
        $oldVideo = $video->title;

        $hasError = false;
        try {
            $video->update($this->data + [ 'categories_id' => [0,1,2]]);
        } catch (QueryException $exception) {
            $hasError = true;
            $this->assertDatabaseHas('videos', [
                'title' => $oldVideo
            ]);
        }
        $this->assertTrue($hasError);

    }

}