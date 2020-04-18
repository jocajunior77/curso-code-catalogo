<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Rules\GenresHasCategoriesRule;

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
            'categories_id' => 'required|array|exists:categories,id,deleted_at,NULL',
            'genres_id'     => [
                'required',
                'array',
                'exists:genres,id,deleted_at,NULL',
            ],
            'video_file' => 'mimetypes:video/mp4|max:51200000',
            'thumb_file' => 'mimes:jpeg,bmp,png|max:5120',
            'banner_file' => 'mimes:jpeg,bmp,png|max:10240',
            'trailer_file' => 'mimes:jpeg,bmp,png|max:1024000',
        ];
    }

    public function store(Request $request)
    {

        $this->addRuleIfGenreHasCategoriesRule($request);
        $validateData = $this->validate($request, $this->rulesStore());
        $obj = $this->model()::create($validateData);
        return $obj->refresh();
    }

    public function update(Request $request, $id)
    {
        $obj = $this->findOrFail($id);
        $this->addRuleIfGenreHasCategoriesRule($request);
        $validateData = $this->validate($request, $this->rulesUpdate());
        $obj->update($validateData);
        return $obj->refresh();
    }

    protected function addRuleIfGenreHasCategoriesRule(Request $request)
    {
        $categoriesId = $request->get('categories_id');
        $categoriesId = is_array($categoriesId)? $categoriesId: [];
        $this->rules['genres_id'][] = new GenresHasCategoriesRule($categoriesId);
    }


    // protected function handleRelations($video, Request $request) {
    //     $video->categories()->sync($request->get('categories_id'));
    //     $video->genres()->sync($request->get('genres_id'));
    // }


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
