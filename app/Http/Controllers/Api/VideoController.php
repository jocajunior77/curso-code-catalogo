<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends BasicCrudController
{

    private $rules;

    public function __construct()
    {
        $this->rules = [
            'title'         => 'required|max:255',
            'description'   => 'required',
            'year_launched' => 'required|date_format:Y',
            'duraction'     => 'required|integer',
            'rating'        => 'required|in:'.implode(',', Video::RATING_LIST),
            'opened'        => 'boolean',
            'categories_id' => 'required|array|exists:categories,id',
        ];
    }

    public function store(Request $request)
    {
        Video::beginTransaction();
        $validateData = $this->validate($request, $this->rulesStore());
        $obj = $this->model()::create($validateData);
        $obj->categories()->sync($request->get('categories_id')); // Remove e cadastra novamente
        Video::commit();
        return $obj->refresh();
    }

    public function update(Request $request, $id)
    {
        Video::beginTransaction();
        $obj = $this->findOrFail($id);
        $validateData = $this->validate($request, $this->rulesUpdate());
        $obj->update($validateData);
        $obj->categories()->sync($request->get('categories_id')); // Remove e cadastra novamente
        Video::commit();
        return $obj->refresh();
    }

    public function model()
    {
        return Video::class;
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
