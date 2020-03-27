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
}
