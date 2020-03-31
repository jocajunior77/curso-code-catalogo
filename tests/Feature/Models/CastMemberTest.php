<?php

namespace Tests\Feature\Models;

use App\Models\CastMember;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CastMemberTest extends TestCase
{

    use DatabaseMigrations;

    public function testCreate()
    {
        $UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        $castMember = factory(CastMember::class)->create();
        $this->assertEquals(preg_match($UUIDv4, $castMember->id),1);
    }

    public function testDelete()
    {
        $castMember = factory(CastMember::class)->create();
        $this->assertTrue($castMember->delete());
    }

    public function testList()
    {
        factory(CastMember::class)->create();
        $castMember = CastMember::all();
        $this->assertCount(1, $castMember);
        $castMemberKeys = array_keys($castMember->first()->getAttributes());

        $castMemberFields = [
            'id',
            'name',
            'type',
            'created_at',
            'updated_at',
            'deleted_at'
        ];
        $this->assertEqualsCanonicalizing($castMemberFields, $castMemberKeys);
    }

    public function testUpdate()
    {
        $castMember = factory(CastMember::class)->create([
            'type'   => CastMember::TYPE_DIRECTOR
        ]);

        $data = [
           'name'       => 'teste_update',
           'type'       => CastMember::TYPE_ACTOR
        ];

        $castMember->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $castMember->{$key} );
        }


    }
}
