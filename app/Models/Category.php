<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'is_active',
    ];
}
