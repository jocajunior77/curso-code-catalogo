<?php

namespace Tests\Unit\Models;

use App\Models\CastMember;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CastMemberUnitTest extends TestCase
{


    public function testIfUseTrait()
    {
        $traits = [
            SoftDeletes::class, Uuid::class
        ];
        $classTraits = array_keys(class_uses(CastMember::class));
        $this->assertEquals($traits, $classTraits);
    }

    public function testFillableAttribute()
    {
        $castMember = new CastMember;
        $this->assertEquals(
            ['name', 'type'],
            $castMember->getFillable()
        );
    }


    public function testCastsAttribute()
    {
        $castMember = new CastMember;
        $casts = [ 'id' => 'string'];
        $this->assertEquals(
            $casts,
            $castMember->getCasts()
        );
    }

    public function testIncrementingAttribute()
    {
        $castMember = new CastMember;
        $this->assertFalse($castMember->incrementing);
    }


    public function testDatesAttribute()
    {
        $castMember = new CastMember;
        $dates = ['deleted_at', 'created_at', 'updated_at'];
        foreach ($dates as $date) {
            $this->assertContains($date, $castMember->getDates());
        }
        $this->assertCount(count($dates), $castMember->getDates());
    }

}
