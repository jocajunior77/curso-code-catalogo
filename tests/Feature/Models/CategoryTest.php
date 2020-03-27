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

    public function testCreate()
    {
        $UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        $category = factory(Category::class)->create();
        $category->refresh();
        $this->assertEquals(preg_match($UUIDv4, $category->id),1);
    }

    public function testDelete()
    {
        $category = factory(Category::class)->create();
        $category->refresh();
        $this->assertTrue($category->delete());
    }

    public function testList()
    {
        factory(Category::class)->create();
        $categories = Category::all();
        $this->assertCount(1, $categories);
        $categoryKeys = array_keys($categories->first()->getAttributes());

        $categoryFields = [
            'id',
            'name',
            'description',
            'is_active',
            'created_at',
            'updated_at',
            'deleted_at'
        ];
        $this->assertEqualsCanonicalizing($categoryFields, $categoryKeys);
    }

    public function testUpdate()
    {
        $category = factory(Category::class)->create([
            'description' => 'teste_create',
            'is_active'   => false
        ]);

        $data = [
           'name'        => 'teste_update',
           'description' => 'teste_update_description',
           'is_active'   => true
        ];

        $category->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $category->{$key} );
        }


    }
}
