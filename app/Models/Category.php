<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid;

class Category extends BaseModel
{

    use SoftDeletes, Uuid;

    protected $fillable = [
        'name', 'description', 'is_active',
    ];

    protected $dates = ['deleted_at'];

    protected  $casts = [
        'id'        => 'string',
        'is_active' => 'boolean'
    ];

    public $incrementing = false;

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

}
