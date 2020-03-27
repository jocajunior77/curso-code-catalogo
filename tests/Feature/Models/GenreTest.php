<?php

namespace Tests\Feature\Models;

use App\Models\Genre;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GenreTest extends TestCase
{

    use DatabaseMigrations;

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

    public function testList()
    {
        factory(Genre::class)->create();
        $genre = Genre::all();
        $this->assertCount(1, $genre);
        $categoryKeys = array_keys($genre->first()->getAttributes());

        $categoryFields = [
            'id',
            'name',
            'is_active',
            'created_at',
            'updated_at',
            'deleted_at'
        ];
        $this->assertEqualsCanonicalizing($categoryFields, $categoryKeys);
    }
}
