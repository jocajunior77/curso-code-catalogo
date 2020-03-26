<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{

    public function index()
    {
        return Genre::all();
    }


    public function store(Request $request)
    {
        return Genre::create($request->all());
    }


    public function show(Genre $genre)
    {
        return $genre;
    }


    public function update(Request $request, Genre $genre)
    {
        return $genre->update($request->all());
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();
        return response()->noContent();
    }
}
