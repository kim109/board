<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Implant extends Model
{
    use SoftDeletes;

    protected $table = 'freeboard';

    protected $casts = [
        'pin' => 'boolean',
        'open' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }
}
