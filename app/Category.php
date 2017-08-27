<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $casts = [
        'open' => 'boolean'
    ];

    public function articles()
    {
        return $this->hasMany('App\Article');
    }
}
