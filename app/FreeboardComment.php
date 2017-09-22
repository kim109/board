<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FreeboardComment extends Model
{
    use SoftDeletes;

    protected $hidden = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function article()
    {
        return $this->belongsTo('App\FreeBoard');
    }

    public function childs()
    {
        return $this->hasMany('App\FreeboardComment', 'parent_id', 'id');
    }
}
