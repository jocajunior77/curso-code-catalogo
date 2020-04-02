<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends BasicCrudController
{

    private $rules = [
        'name'          => 'required|max:255',
        'is_active'     => 'boolean',
        'categories_id' => 'array|exists:categories,id',
    ];

    public function store(Request $request)
    {

        Genre::beginTransaction();
        $validateData = $this->validate($request, $this->rulesStore());
        $obj = $this->model()::create($validateData);
        $obj->categories()->sync($request->get('categories_id'));
        Genre::commit();
        return $obj->refresh();
    }

    public function update(Request $request, $id)
    {
        Genre::beginTransaction();
        $obj = $this->findOrFail($id);
        $validateData = $this->validate($request, $this->rulesUpdate());
        $obj->update($validateData);
        $obj->categories()->sync($request->get('categories_id'));
        Genre::commit();
        return $obj->refresh();
    }

    public function model()
    {
        return Genre::class;
    }

    protected function rulesStore()
    {
        return $this->rules;
    }

    protected function rulesUpdate()
    {
        return $this->rules;
    }
}
