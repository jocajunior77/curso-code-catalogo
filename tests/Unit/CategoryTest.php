<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $casts = ['id' => 'string'];
        $this->assertEquals(
            $casts,
            $category->getCasts()
        );
    }

    public function testIncrementingAttribute()
    {
        $category = new Category;
        $this->assertFalse($category->incrementing);
    }

    public function testDatesAttribute()
    {
        $category = new Category;
        $dates    = ['deleted_at', 'created_at', 'updated_at'];
        foreach ($dates as $date) {
            $this->assertContains($date, $category->getDates());
        }
        $this->assertCount(count($dates), $category->getDates());
    }
}
