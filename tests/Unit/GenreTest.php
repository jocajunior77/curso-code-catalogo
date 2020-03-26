<?php

namespace Tests\Unit;

use App\Models\Genre;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $casts = ['id' => 'string'];
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

    public function testDatabaseCreate()
    {
        $UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        $genre = factory(Genre::class)->create();
        $this->assertEquals(preg_match($UUIDv4, $genre->id),1);
    }

    public function testDatabaseDelete()
    {
        $genre = factory(Genre::class)->create();
        $this->assertTrue($genre->delete());
    }

}
