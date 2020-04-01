<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid;

class Video extends BaseModel
{

    use SoftDeletes, Uuid;

    const RATING_LIST = ['L','10','12','14','16','18'];

    protected $fillable = [
        'title',
        'description',
        'year_launched',
        'opened',
        'rating',
        'duraction'
    ];

    protected $dates = ['deleted_at'];

    protected  $casts = [
        'id'            => 'string',
        'opened'        => 'boolean',
        'year_launched' => 'integer',
        'duraction'     => 'integer',
    ];

    public $incrementing = false;

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }
}