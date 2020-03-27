<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CategoryTest extends TestCase
{

    use DatabaseMigrations;

    public function testDatabaseCreate()
    {
        $UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        $category = factory(Category::class)->create();
        $this->assertEquals(preg_match($UUIDv4, $category->id),1);
    }

    public function testDatabaseDelete()
    {
        $category = factory(Category::class)->create();
        $this->assertTrue($category->delete());
    }
}
