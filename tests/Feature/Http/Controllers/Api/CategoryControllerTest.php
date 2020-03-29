<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\TestValidations;

class CategoryControllerTest extends TestCase
{

    use DatabaseMigrations, TestValidations;

    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = factory (Category::class)->create();
    }


    public function testIndex()
    {
        $this->category->refresh();
        $response = $this->get(route('categories.index'));
        $response->assertStatus(200)
                 ->assertJson([$this->category->toArray()]);
    }


    public function testShow()
    {
        $this->category->refresh();
        $response = $this->get(route('categories.show', [ 'category' => $this->category->id] ));
        $response->assertStatus(200)
                 ->assertJson($this->category->toArray());
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
        $this->category->refresh();
        $response = $this->get(route('categories.destroy', [ 'category' => $this->category->id] ));
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

        $this->category->refresh();
        $response = $this->json('PUT', route('categories.update', ['category' => $this->category->id ]), [
            'name'      => str_repeat('a', 254),
            'is_active' => true
        ]);

        $response->assertStatus(200);
        $this->assertTrue($response->json('is_active'));

    }


    protected function routeStore()
    {
        return route('categories.store');
    }

    protected function routeUpdate()
    {
        return route('categories.update' , [ 'category' => $this->category->id ]);
    }



}
