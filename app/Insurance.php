<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Insurance extends Model
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

    public function replies()
    {
        return $this->hasMany('App\InsuranceReply');
    }

    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }

    public function attachments()
    {
        return $this->morphMany('App\Attachment', 'attach');
    }
}
