<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Video;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;

class VideoControllerTest extends TestCase
{

    use DatabaseMigrations, TestValidations, TestSaves;

    protected $video;

    protected function setUp(): void
    {
        parent::setUp();
        $this->video = factory (Video::class)->create();
    }


    public function testIndex()
    {
        $this->video->refresh();
        $response = $this->get(route('videos.index'));
        $response->assertStatus(200)
                 ->assertJson([$this->video->toArray()]);
    }


    public function testShow()
    {
        $this->video->refresh();
        $response = $this->get(route('videos.show', [ 'video' => $this->video->id] ));
        $response->assertStatus(200)
                 ->assertJson($this->video->toArray());
    }


    public function testInvalidData()
    {

        $data = [ 'title' => '', 'description' => '', 'year_launched' => '', 'duraction' => '' ];
        $this->assertassertInvalidationInSaveAction($data, 'required');

        $data = [ 'title' => str_repeat('a', 256) ];
        $this->assertassertInvalidationInSaveAction($data, 'max.string', ['max' => 255 ]);

    }

    public function testDelete()
    {
        $this->video->refresh();
        $response = $this->get(route('videos.destroy', [ 'video' => $this->video->id] ));
        $response->assertStatus(200);
    }


    public function testStore()
    {


        $data = [
            'title'         =>  'Teste_' . uniqid(),
            'description'   => 'description',
            'year_launched' => rand(2001,2020),
            'rating'        => Video::RATING_LIST[array_rand(Video::RATING_LIST)],
            'opened'        => rand(1,10) % 2 == 0 ? true : false,
            'duraction'     => rand(40,120)
        ];


        $this->assertStore($data, $data + [ 'opened' => true, 'deleted_at' => null ]);


        $data = [
            'title'         => 'Teste_' . uniqid() ,
            'description'   => 'description',
            'year_launched' => rand(2001,2020),
            'rating'        => Video::RATING_LIST[array_rand(Video::RATING_LIST)],
            'opened'        => rand(1,10) % 2 == 0 ? true : false,
            'duraction'     => rand(40,120)
        ];
        $this->assertStore($data, $data + ['deleted_at' => null]);

    }


    public function testUpdate()
    {

        $data = [
            'title'             => 'Teste_' . uniqid() ,
            'description'       => 'description',
            'opened'            => rand(1,10) % 2 == 0 ? true : false,
            'rating'            => Video::RATING_LIST[array_rand(Video::RATING_LIST)],
            'year_launched'     => rand(2001,2020),
            'duraction'         => rand(40,120)
        ];
        $this->assertUpdate($data, $data);


    }


    protected function routeStore()
    {
        return route('videos.store');
    }

    protected function routeUpdate()
    {
        return route('videos.update' , [ 'video' => $this->video->id ]);
    }

    protected function model()
    {
        return Video::class;
    }


}
