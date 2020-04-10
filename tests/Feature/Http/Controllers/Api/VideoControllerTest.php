<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Video;
use App\Models\Category;
use App\Models\Genre;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;
use App\Http\Controllers\Api\VideoController;

class VideoControllerTest extends TestCase
{

    use DatabaseMigrations, TestValidations, TestSaves;

    protected $video;

    protected function setUp(): void
    {
        parent::setUp();
        $this->video = factory (Video::class)->create();
    }

    protected function sendData($type = null)
    {

        $data =  [
            'title'         =>  'Teste_' . uniqid(),
            'description'   => 'description',
            'year_launched' => rand(2001,2020),
            'rating'        => Video::RATING_LIST[array_rand(Video::RATING_LIST)],
            'opened'        => true,
            'duraction'     => rand(40,120)
        ];

        switch ($type) {
            case '1':
                return ['title' => '','description' => '','year_launched' => '','duraction' => '','categories_id' => '','genres_id' => ''];
                break;
            case '2':
                $data['opened'] = false;
                return $data;
                break;
        }

        return $data;

    }

    protected function appendSendData()
    {

        $category = factory (Category::class)->create();
        $genre    = factory (Genre::class)->create();

        $genre->categories()->attach($category->id);

        return [
            'categories_id' => [ $category->id ],
            'genres_id' => [ $genre->id ],
        ];
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

        $this->assertassertInvalidationInSaveAction($this->sendData(1), 'required');

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

        $data = $this->sendData();
        $appendData = $this->appendSendData();
        $this->assertStore($data + $appendData, $data + [ 'opened' => true, 'deleted_at' => null ]);
        $data = $this->sendData(2);
        $this->assertStore($data + $appendData, $data + ['deleted_at' => null]);

    }


    public function testUpdate()
    {
        $data = $this->sendData();
        $this->assertUpdate($data + $this->appendSendData(), $data);
    }


    // public function testRollbackStore()
    // {
    //     $this->expectExceptionMessage('0');
    //     $data = $this->sendData();// + $this->appendSendData();

    //     $request = \Mockery::mock(Request::class);

    //     $request->shouldReceive('get')
    //             ->withAnyArgs()
    //             ->andReturnNull();

    //     $controller = \Mockery::mock(VideoController::class)
    //         ->makePartial()
    //         ->shouldAllowMockingProtectedMethods();

    //     $controller->shouldReceive('validate')
    //        ->withAnyArgs()
    //        ->andReturn($data);

    //     $controller->shouldReceive('rulesStore')
    //        ->withAnyArgs()
    //        ->andReturn([]);

    //     $controller->shouldReceive('handleRelations')
    //                ->once()
    //                ->andThrow(new \Exception(\DB::transactionLevel()));

    //     $controller->store($request);
    // }

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
