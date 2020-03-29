<?php

namespace Tests\Stubs\Controllers;

use App\Http\Controllers\Api\BasicCrudController;
use Tests\Stubs\Models\CategoryStub;
use Illuminate\Http\Request;

class CategoryControllerStub extends BasicCrudController
{

    protected function model()
    {
        return CategoryStub::class;
    }

    public function rulesStore()
    {
        return [
            'name'          => 'required|max:255',
            'description'   => 'nullable',
        ];
    }


}
