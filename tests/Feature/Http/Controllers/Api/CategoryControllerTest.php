<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;

class CategoryControllerTest extends TestCase
{

    use DatabaseMigrations, TestValidations, TestSaves;

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


        $data = [ 'name' => 'Teste_' . uniqid() ];
        $this->assertStore($data, $data + [ 'description' => null, 'is_active' => true ]);

        $data = [
            'name'          => 'Teste_' . uniqid(),
            'description'   => 'description'
        ];
        $this->assertStore($data, $data + [ 'is_active' => true ]);


        $data = [
            'name'          => 'Teste_' . uniqid() ,
            'description'   => 'description',
            'is_active'     => rand(1,10) % 2 == 0 ? true : false,
        ];
        $this->assertStore($data, $data);

    }


    public function testUpdate()
    {

        $data = [
            'name'          => 'Teste_' . uniqid() ,
            'description'   => 'description',
            'is_active'     => rand(1,10) % 2 == 0 ? true : false,
        ];
        $this->assertUpdate($data, $data);


    }


    protected function routeStore()
    {
        return route('categories.store');
    }

    protected function routeUpdate()
    {
        return route('categories.update' , [ 'category' => $this->category->id ]);
    }

    protected function model()
    {
        return Category::class;
    }


}
