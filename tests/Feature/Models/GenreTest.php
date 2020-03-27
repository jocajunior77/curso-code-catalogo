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

    public function testCreate()
    {
        $UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        $genre = factory(Genre::class)->create();
        $this->assertEquals(preg_match($UUIDv4, $genre->id),1);
    }

    public function testDelete()
    {
        $genre = factory(Genre::class)->create();
        $this->assertTrue($genre->delete());
    }

    public function testList()
    {
        factory(Genre::class)->create();
        $genre = Genre::all();
        $this->assertCount(1, $genre);
        $genreKeys = array_keys($genre->first()->getAttributes());

        $genreFields = [
            'id',
            'name',
            'is_active',
            'created_at',
            'updated_at',
            'deleted_at'
        ];
        $this->assertEqualsCanonicalizing($genreFields, $genreKeys);
    }

    public function testUpdate()
    {
        $genre = factory(Genre::class)->create([
            'is_active'   => false
        ]);

        $data = [
           'name'        => 'teste_update',
           'is_active'   => true
        ];

        $genre->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $genre->{$key} );
        }


    }
}
