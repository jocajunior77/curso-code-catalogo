<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CategoryTest extends TestCase
{


    public function testIfUseTrait()
    {
        $traits = [
            SoftDeletes::class, Uuid::class
        ];
        $classTraits = array_keys(class_uses(Category::class));
        $this->assertEquals($traits, $classTraits);
    }

    public function testFillableAttribute()
    {
        $category = new Category;
        $this->assertEquals(
            ['name', 'description', 'is_active'],
            $category->getFillable()
        );
    }

    public function testCastsAttribute()
    {
        $category = new Category;
        $casts = [ 'id' => 'string' , 'is_active' => 'boolean'];
        $this->assertEquals(
            $casts,
            $category->getCasts()
        );
    }

}
