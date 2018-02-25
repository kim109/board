<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Column extends Model
{
    use SoftDeletes;

    protected $dates = [
        'craeted_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'pin' => 'boolean',
        'open' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }

    public function thumbnail()
    {
        return $this->hasOne('App\Attachment', 'id', 'thumbnail_id');
    }

    public function attachments()
    {
        return $this->morphMany('App\Attachment', 'attach');
    }
}
