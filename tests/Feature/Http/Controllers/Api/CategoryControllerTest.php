<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CategoryControllerTest extends TestCase
{

    use DatabaseMigrations;

    public function testIndex()
    {
        $category = factory(Category::class)->create();
        $category->refresh();
        $response = $this->get(route('categories.index'));
        $response->assertStatus(200)
                 ->assertJson([$category->toArray()]);
    }


    public function testShow()
    {
        $category = factory(Category::class)->create();
        $category->refresh();
        $response = $this->get(route('categories.show', [ 'category' => $category->id] ));
        $response->assertStatus(200)
                 ->assertJson($category->toArray());
    }


    public function testInvalidData()
    {
        $response = $this->json('POST', route('categories.store', []));
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name'])
                 ->assertJsonMissingValidationErrors(['is_active']);
                 /*->assertJsonFragment([
                    \Lang::get('valitation.required', ['atribute' => 'name'])
                 ]);*/

        $response = $this->json('POST', route('categories.store', [
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

        $category = factory(Category::class)->create();
        $category->refresh();
        $response = $this->json('PUT', route('categories.update', ['category' => $category->id ]), []);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);


        $category = factory(Category::class)->create();
        $category->refresh();
        $response = $this->json('PUT', route('categories.update', ['category' => $category->id ]), [
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
        $category = factory(Category::class)->create();
        $category->refresh();
        $response = $this->get(route('categories.destroy', [ 'category' => $category->id] ));
        $response->assertStatus(200);
    }


    public function testStore()
    {

        $response = $this->json('POST', route('categories.store', [
            'name'      => 'Teste_' . uniqid()
        ]));

        $id = $response->json('id');
        $category = Category::find($id);

        $response->assertStatus(201)
                 ->assertJson($category->toArray());
        $this->assertTrue($response->json('is_active'));
        $this->assertNull($response->json('description'));



        $response = $this->json('POST', route('categories.store', [
            'name'          => 'Teste_' . uniqid() ,
            'description'   => 'description',
            'is_active'     => rand(1,10) % 2 == 0 ? true : false,
        ]));

        $id = $response->json('id');
        $category = Category::find($id);

        $response->assertStatus(201)
                 ->assertJson($category->toArray());

    }


    public function testUpdate()
    {

        $category = factory(Category::class)->create();
        $category->refresh();
        $response = $this->json('PUT', route('categories.update', ['category' => $category->id ]), [
            'name'      => str_repeat('a', 254),
            'is_active' => true
        ]);

        $response->assertStatus(200);
        $this->assertTrue($response->json('is_active'));

    }



}
