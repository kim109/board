<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    public function attach()
    {
        return $this->morphTo();
    }
}
