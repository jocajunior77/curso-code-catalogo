<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Stubs\Models\CategoryStub;
use Tests\Stubs\Controllers\CategoryControllerStub;
use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BasicCrudController;

class BasicCrudControllerTest extends TestCase
{

    private $controller;

    protected function setUp(): void
    {
        parent::setUp();
        CategoryStub::dropTable();
        CategoryStub::createTable();
        $this->controller = new CategoryControllerStub();
    }

    protected function tearDown(): void
    {
        CategoryStub::dropTable();
        parent::tearDown();
    }

    public function testIndex()
    {
        $category = CategoryStub::create(['name' => 'name', 'description' => 'description']);
        $category->refresh();
        $result = $this->controller->index()->toArray();
        $this->assertEquals([$category->toArray()], $result);

    }

    public function testInvalidationDataInStore()
    {

        $this->expectException('Illuminate\Validation\ValidationException');
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')
                ->once()
                ->andReturn(['name'=>'']);

        $this->controller->store($request);
    }

    public  function testStore()
    {
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')
                ->once()
                ->andReturn([
                    'name'=>'name',
                    'description'=>'description',
                ]);

        $obj = $this->controller->store($request);
        $this->assertEquals(CategoryStub::find(1)->toArray(), $obj->toArray());
    }

    public function testIfFindOrFailFetchModel()
    {
        $category = CategoryStub::create(['name' => 'name', 'description' => 'description']);

        $reflectionClass = new \ReflectionClass(BasicCrudController::class);
        $reflectionMethod = $reflectionClass->getMethod('findOrFail');
        $reflectionMethod->setAccessible(true);

        $result = $reflectionMethod->invokeArgs($this->controller, [$category->id]);
        $this->assertInstanceOf(CategoryStub::class, $result);
    }


    public function testIfFindOrFailThrowExceptionWhenIdInvalid()
    {
        $this->expectException('Illuminate\Database\Eloquent\ModelNotFoundException');
        $reflectionClass = new \ReflectionClass(BasicCrudController::class);
        $reflectionMethod = $reflectionClass->getMethod('findOrFail');
        $reflectionMethod->setAccessible(true);

        $result = $reflectionMethod->invokeArgs($this->controller, [0]);
        $this->assertInstanceOf(CategoryStub::class, $result);
    }


    public function testShow()
    {
        $category = CategoryStub::create(['name' => 'name', 'description' => 'description']);
        $result = $this->controller->show($category->id);
        $this->assertEquals($result->toArray(), CategoryStub::find(1)->toArray());
    }


    public function testUpdate()
    {
        $category = CategoryStub::create(['name' => 'name', 'description' => 'description']);
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')
                ->once()
                ->andReturn(['name'=>'new_name', 'description' => 'new_description']);
        $result = $this->controller->update($request, $category->id);
        $this->assertEquals($result->toArray(), CategoryStub::find(1)->toArray());
    }


    public function testDestroy()
    {
        $category = CategoryStub::create(['name' => 'name', 'description' => 'description']);
        $response = $this->controller->destroy($category->id);
        $this->createTestResponse($response)
             ->assertStatus(204);
        $this->assertCount(0,CategoryStub::all());
    }

}

