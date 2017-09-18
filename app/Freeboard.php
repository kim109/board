<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Freeboard extends Model
{
    use SoftDeletes;

    protected $table = 'freeboard';

    protected $casts = [
        'attachs' => 'array',
        'pin' => 'boolean',
        'open' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comments()
    {
        return $this->hasMany('App\FreeBoardComment');
    }

    public function attachments()
    {
        return $this->morphMany('App\Attachment', 'attach');
    }
}
