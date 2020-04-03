<?php

namespace Tests\Feature\Http\Controllers\Api;


use App\Models\Genre;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;
use App\Models\Category;
use App\Http\Controllers\Api\GenreController;

class GenreControllerTest extends TestCase
{

    use DatabaseMigrations, TestValidations, TestSaves;

    protected $genre;
    protected $sendData;
    protected $appendData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->genre = factory(Genre::class)->create();
        $this->sendData = [
            'name' => 'Teste_' . uniqid() ,
            'is_active' => false
        ];

        $this->appendData = [
            'categories_id' => [ factory(Category::class)->create()->id ]
        ];
    }


    public function testIndex()
    {
        $this->genre->refresh();
        $response = $this->get(route('genres.index'));
        $response->assertStatus(200)
                 ->assertJson([$this->genre->toArray()]);
    }


    public function testShow()
    {
        $this->genre->refresh();
        $response = $this->get(route('genres.show', [ 'genre' => $this->genre->id] ));
        $response->assertStatus(200)
                 ->assertJson($this->genre->toArray());
    }

    public function testInvalidData()
    {

        $data = [
            'name' => '',
            'categories_id' => ''
        ];
        $this->assertassertInvalidationInSaveAction($data, 'required');

        $data = [ 'name' => str_repeat('a', 256) ];
        $this->assertassertInvalidationInSaveAction($data, 'max.string', ['max' => 255 ]);

        $data = [ 'is_active' => 'a' ];
        $this->assertassertInvalidationInSaveAction($data, 'boolean');

        $data = [ 'categories_id' => 'a'];
        $this->assertassertInvalidationInSaveAction($data, 'array');

        $data = [ 'categories_id' => [100]];
        $this->assertassertInvalidationInSaveAction($data, 'exists');

    }

    public function testDelete()
    {
        $this->genre->refresh();
        $response = $this->get(route('genres.destroy', [ 'genre' => $this->genre->id] ));
        $response->assertStatus(200);
    }

    public function testStore()
    {
       $this->assertStore($this->sendData + $this->appendData, $this->sendData);
    }

    public function testUpdate()
    {
        $this->assertUpdate($this->sendData + $this->appendData, $this->sendData);
    }

    public function testRollbackStore()
    {
        $this->expectExceptionMessage('0');
        $request = \Mockery::mock(Request::class);

        $controller = \Mockery::mock(GenreController::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $controller->shouldReceive('validate')
           ->withAnyArgs()
           ->andReturn($this->sendData + $this->appendData);

        $controller->shouldReceive('rulesStore')
           ->withAnyArgs()
           ->andReturn([]);

        $controller->shouldReceive('handleRelations')
                   ->once()
                   ->andThrow(new \Exception(\DB::transactionLevel()));

        $controller->store($request);
    }

    protected function routeStore()
    {
        return route('genres.store');
    }

    protected function routeUpdate()
    {
        return route('genres.update' , [ 'genre' => $this->genre->id ]);
    }

    protected function model()
    {
        return Genre::class;
    }
}
