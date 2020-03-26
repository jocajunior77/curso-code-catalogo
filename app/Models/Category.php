<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Category extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'is_active',
    ];

    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();
        static::creating(function($obj) {
            $obj->id = Uuid::uuid4();
        });

    }
}
