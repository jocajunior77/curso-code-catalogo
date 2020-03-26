<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name', 'is_active',
    ];
}
