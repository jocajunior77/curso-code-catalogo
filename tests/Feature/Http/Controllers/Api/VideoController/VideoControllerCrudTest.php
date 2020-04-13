<?php

namespace Tests\Feature\Http\Controllers\Api\VideoController;

use App\Models\Video;
use App\Models\Category;
use App\Models\Genre;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;
use Tests\Traits\TestUploads;
use App\Http\Controllers\Api\VideoController;

class VideoControllerCrudTest extends BaseVideoControllerTestCase
{

    use DatabaseMigrations, TestValidations, TestSaves, TestUploads;

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


    public function testStoreWithFiles()
    {
        \Storage::fake();
        $files = $this->getFiles();

        $sendData = $this->sendData() +
                    $this->appendSendData() +
                    $files;

        $response = $this->json('POST', $this->routeStore(), $sendData);
        $response->assertStatus(201);
        $id = $response->json('id');
        foreach ($files as $file) {
            \Storage::assertExists("{$id}/{$file->hashName()}");
        }

    }

    public function testUpdateWithFiles()
    {
        \Storage::fake();
        $files = $this->getFiles();

        $sendData = $this->sendData() +
                    $this->appendSendData() +
                    $files;

        $response = $this->json('PUT', $this->routeUpdate(), $sendData);
        $response->assertStatus(200);
        $id = $response->json('id');
        foreach ($files as $file) {
           \Storage::assertExists("{$id}/{$file->hashName()}");
        }

    }

    public function testInvalidationFile()
    {
        $this->assertInvalidationFile(
            'video_file',
            'mp4',
            240,
            'mimetypes',
            ['values' => 'video/mp4']
        );
    }

    protected function getFiles()
    {
        return [
            'video_file' => UploadedFile::fake()->create('video_file.mp4')
        ];
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
