<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid;

class Genre extends BaseModel
{

    use SoftDeletes, Uuid;

    protected $fillable = [
        'name', 'is_active',
    ];

    protected $dates = ['deleted_at'];

    protected  $casts = [
        'id'        => 'string',
        'is_active' => 'boolean'
    ];

    public $incrementing = false;


}
