<?php

namespace Tests\Unit\Models;

use App\Models\Genre;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GenreTest extends TestCase
{


    public function testIfUseTrait()
    {
        $traits = [
            SoftDeletes::class, Uuid::class
        ];
        $classTraits = array_keys(class_uses(Genre::class));
        $this->assertEquals($traits, $classTraits);
    }

    public function testFillableAttribute()
    {
        $genre = new Genre;
        $this->assertEquals(
            ['name', 'is_active'],
            $genre->getFillable()
        );
    }


    public function testCastsAttribute()
    {
        $genre = new Genre;
        $casts = [ 'id' => 'string' , 'is_active' => 'boolean'];
        $this->assertEquals(
            $casts,
            $genre->getCasts()
        );
    }

    public function testIncrementingAttribute()
    {
        $genre = new Genre;
        $this->assertFalse($genre->incrementing);
    }


    public function testDatesAttribute()
    {
        $genre = new Genre;
        $dates = ['deleted_at', 'created_at', 'updated_at'];
        foreach ($dates as $date) {
            $this->assertContains($date, $genre->getDates());
        }
        $this->assertCount(count($dates), $genre->getDates());
    }

}
