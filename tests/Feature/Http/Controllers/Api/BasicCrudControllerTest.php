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

class BasicCrudControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        CategoryStub::dropTable();
        CategoryStub::createTable();
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
        $controller = new CategoryControllerStub();
        $result = $controller->index()->toArray();
        $this->assertEquals([$category->toArray()], $result);

    }
}
