<?php

namespace Tests\Stubs\Controllers;

use App\Http\Controllers\Api\BasicCrudController;
use Tests\Stubs\Models\GenreStub;
use Illuminate\Http\Request;

class GenreControllerStub extends BasicCrudController
{


    private $rules = [
        'name'          => 'required|max:255',
        'is_active'     => 'boolean'
    ];

    protected function model()
    {
        return GenreStub::class;
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
