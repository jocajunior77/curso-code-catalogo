<?php

namespace Tests\Stubs\Controllers;

use App\Http\Controllers\Api\BasicCrudController;
use Tests\Stubs\Models\CategoryStub;
use Illuminate\Http\Request;

class CategoryControllerStub extends BasicCrudController
{


    private $rules = [
        'name'          => 'required|max:255',
        'description'   => 'nullable'
    ];

    protected function model()
    {
        return CategoryStub::class;
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
