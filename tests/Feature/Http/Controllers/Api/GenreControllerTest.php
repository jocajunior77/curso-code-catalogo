<?php

namespace Tests\Feature\Http\Controllers\Api;


use App\Models\Genre;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GenreControllerTest extends TestCase
{

    use DatabaseMigrations;

    public function testIndex()
    {
        $genre = factory(Genre::class)->create();
        $genre->refresh();
        $response = $this->get(route('genres.index'));
        $response->assertStatus(200)
                 ->assertJson([$genre->toArray()]);
    }


    public function testShow()
    {
        $genre = factory(Genre::class)->create();
        $genre->refresh();
        $response = $this->get(route('genres.show', [ 'genre' => $genre->id] ));
        $response->assertStatus(200)
                 ->assertJson($genre->toArray());
    }

    public function testInvalidData()
    {
        $response = $this->json('POST', route('genres.store', []));
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name'])
                 ->assertJsonMissingValidationErrors(['is_active']);
                 /*->assertJsonFragment([
                    \Lang::get('valitation.required', ['atribute' => 'name'])
                 ]);*/

        $response = $this->json('POST', route('genres.store', [
            'name'      => str_repeat('a', 256),
            'is_active' => 'a'
        ]));

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                    'name',
                    'is_active'
                 ]);
                /*->assertJsonFragment([
                \Lang::get('valitation.max.string', ['atribute' => 'name', 'max' => 255])
                ])->assertJsonFragment([
                \Lang::get('valitation.max.string', ['atribute' => 'is active'])
                ]);*/

        $genre = factory(Genre::class)->create();
        $genre->refresh();
        $response = $this->json('PUT', route('genres.update', ['genre' => $genre->id ]), []);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);


        $genre = factory(Genre::class)->create();
        $genre->refresh();
        $response = $this->json('PUT', route('genres.update', ['genre' => $genre->id ]), [
            'name'      => str_repeat('a', 256),
            'is_active' => 'a'
        ]);

        //dd($response->content());
        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                    'name',
                    'is_active'
                 ]);
    }

    public function testDelete()
    {
        $genre = factory(Genre::class)->create();
        $genre->refresh();
        $response = $this->get(route('genres.destroy', [ 'genre' => $genre->id] ));
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

        $genre = factory(Genre::class)->create();
        $genre->refresh();
        $response = $this->json('PUT', route('genres.update', ['genre' => $genre->id ]), [
            'name'      => str_repeat('a', 254),
            'is_active' => true
        ]);

        $response->assertStatus(200);
        $this->assertTrue($response->json('is_active'));

    }
}
