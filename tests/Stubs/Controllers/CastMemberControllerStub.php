<?php

namespace Tests\Stubs\Controllers;

use App\Http\Controllers\Api\BasicCrudController;
use Tests\Stubs\Models\CastMemberStub;
use Illuminate\Http\Request;

class CastMemberControllerStub extends BasicCrudController
{


    private $rules = [
        'name'          => 'required|max:255',
        'type'          => 'in:1,2'
    ];

    protected function model()
    {
        return CastMemberStub::class;
    }

    public function rulesStore()
    {
        return $this->rules;
    }

    public function rulesUpdate()
    {
        return $this->rules;
    }


}
