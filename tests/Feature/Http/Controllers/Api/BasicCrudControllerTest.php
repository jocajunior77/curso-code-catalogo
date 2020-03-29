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

    /**
     * @expectedException Illuminate\Validation\ValidationException
     */
    public function testInvalidationDataInStore()
    {
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')
                ->once()
                ->andReturn(['name'=>'']);

        $this->controller->store($request);
    }
}
