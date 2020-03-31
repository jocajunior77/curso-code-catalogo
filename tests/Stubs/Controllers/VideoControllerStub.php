<?php

namespace Tests\Stubs\Controllers;

use App\Http\Controllers\Api\BasicCrudController;
use Tests\Stubs\Models\VideoStub;
use Illuminate\Http\Request;

class VideoControllerStub extends BasicCrudController
{


    private $rules = [
        'title'         => 'required|max:255',
        'description'   => 'required|max:255'
    ];

    protected function model()
    {
        return VideoStub::class;
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
