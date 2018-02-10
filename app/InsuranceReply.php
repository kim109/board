<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuranceReply extends Model
{
    use SoftDeletes;

    protected $dates = [
        'craeted_at',
        'updated_at',
        'deleted_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function insurance()
    {
        return $this->belongsTo('App\Insurance');
    }
}
