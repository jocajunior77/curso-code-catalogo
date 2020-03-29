<?php

namespace Tests\Feature\Http\Controllers\Api;


use App\Models\Genre;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\TestValidations;

class GenreControllerTest extends TestCase
{

    use DatabaseMigrations, TestValidations;

    protected $genre;

    protected function setUp(): void
    {
        parent::setUp();
        $this->genre = factory (Genre::class)->create();
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

        $data = [ 'name' => '' ];
        $this->assertassertInvalidationInSaveAction($data, 'required');

        $data = [ 'name' => str_repeat('a', 256) ];
        $this->assertassertInvalidationInSaveAction($data, 'max.string', ['max' => 255 ]);

        $data = [ 'is_active' => 'a' ];
        $this->assertassertInvalidationInSaveAction($data, 'boolean');

    }

    public function testDelete()
    {
        $this->genre->refresh();
        $response = $this->get(route('genres.destroy', [ 'genre' => $this->genre->id] ));
        $response->assertStatus(200);
    }

    public function testStore()
    {

        $response = $this->json('POST', route('genres.store', [
            'name'      => 'Teste_' . uniqid()
        ]));

        $id = $response->json('id');
        $genre = Genre::find($id);

        $response->assertStatus(201)
                 ->assertJson($genre->toArray());
        $this->assertTrue($response->json('is_active'));


        $response = $this->json('POST', route('genres.store', [
            'name'          => 'Teste_' . uniqid() ,
            'is_active'     => rand(1,10) % 2 == 0 ? true : false,
        ]));

        $id = $response->json('id');
        $genre = Genre::find($id);

        $response->assertStatus(201)
                 ->assertJson($genre->toArray());

    }

    public function testUpdate()
    {

        $this->genre->refresh();
        $response = $this->json('PUT', route('genres.update', ['genre' => $this->genre->id ]), [
            'name'      => str_repeat('a', 254),
            'is_active' => true
        ]);

        $response->assertStatus(200);
        $this->assertTrue($response->json('is_active'));

    }

    protected function routeStore()
    {
        return route('genres.store');
    }

    protected function routeUpdate()
    {
        return route('genres.update' , [ 'genre' => $this->genre->id ]);
    }
}
