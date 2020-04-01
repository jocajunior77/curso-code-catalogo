<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends BasicCrudController
{

    /**
     * rollback em caso de erro esta em Exceptions/Handler
     */
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
            'genres_id'     => 'required|array|exists:genres,id',
        ];
    }

    public function store(Request $request)
    {

        Video::beginTransaction();
        $validateData = $this->validate($request, $this->rulesStore());
        $obj = $this->model()::create($validateData);
        $this->handleRelations($obj, $request);
        Video::commit();
        return $obj->refresh();
    }

    public function update(Request $request, $id)
    {
        Video::beginTransaction();
        $obj = $this->findOrFail($id);
        $validateData = $this->validate($request, $this->rulesUpdate());
        $obj->update($validateData);
        $this->handleRelations($obj, $request);
        Video::commit();
        return $obj->refresh();
    }


    protected function handleRelations($video, Request $request) {
        $video->categories()->sync($request->get('categories_id')); // Remove e cadastra novamente
        $video->genres()->sync($request->get('genres_id')); // Remove e cadastra novamente
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
