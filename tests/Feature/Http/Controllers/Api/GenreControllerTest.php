<?php

namespace Tests\Feature\Http\Controllers\Api;


use App\Models\Genre;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;

class GenreControllerTest extends TestCase
{

    use DatabaseMigrations, TestValidations, TestSaves;

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

        $data = [ 'name' => 'Teste_' . uniqid() ];
        $this->assertStore($data, $data + [ 'is_active' => true ]);

        $data = [
            'name'          => 'Teste_' . uniqid() ,
            'is_active'     => rand(1,10) % 2 == 0 ? true : false,
        ];
        $this->assertStore($data, $data);

    }

    public function testUpdate()
    {

        $data = [
            'name'          => 'Teste_' . uniqid() ,
            'is_active'     => rand(1,10) % 2 == 0 ? true : false,
        ];
        $this->assertUpdate($data, $data);

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
