<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CastMember;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;

class CastMemberControllerTest extends TestCase
{

    use DatabaseMigrations, TestValidations, TestSaves;

    protected $castMember;

    protected function setUp(): void
    {
        parent::setUp();
        $this->castMember = factory (CastMember::class)->create([
            'type' => CastMember::TYPE_DIRECTOR
        ]);
    }


    public function testIndex()
    {
        $this->castMember->refresh();
        $response = $this->get(route('cast_members.index'));
        $response->assertStatus(200)
                 ->assertJson([$this->castMember->toArray()]);
    }


    public function testShow()
    {
        $this->castMember->refresh();
        $response = $this->get(route('cast_members.show', [ 'cast_member' => $this->castMember->id] ));
        $response->assertStatus(200)
                 ->assertJson($this->castMember->toArray());
    }


    public function testInvalidData()
    {

        $data = [ 'name' => '' , 'type' => '' ];
        $this->assertassertInvalidationInSaveAction($data, 'required');

        $data = [ 'type' => 3 ];
        $this->assertassertInvalidationInSaveAction($data, 'in');

    }

    public function testDelete()
    {
        $this->castMember->refresh();
        $response = $this->get(route('cast_members.destroy', [ 'cast_member' => $this->castMember->id] ));
        $response->assertStatus(200);
    }


    public function testStore()
    {

        $data = [ 'name' => 'Teste_' . uniqid(), 'type' => CastMember::TYPE_ACTOR ];
        $this->assertStore($data, $data + [ 'deleted_at' => null ]);

        $data = [ 'name' => 'Teste_' . uniqid(), 'type' => CastMember::TYPE_DIRECTOR ];
        $this->assertStore($data, $data + [ 'deleted_at' => null ]);

    }


    public function testUpdate()
    {

        $data = [ 'name' => 'Teste_' . uniqid(), 'type' => CastMember::TYPE_ACTOR ];
        $this->assertUpdate($data, $data);
    }


    protected function routeStore()
    {
        return route('cast_members.store');
    }

    protected function routeUpdate()
    {
        return route('cast_members.update' , [ 'cast_member' => $this->castMember->id ]);
    }

    protected function model()
    {
        return CastMember::class;
    }


}
