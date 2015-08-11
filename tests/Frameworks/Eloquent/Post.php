<?php

namespace Yaodong\Fixtures\Test\Frameworks\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user()
    {
        return $this->belongsTo('Yaodong\Fixtures\Test\Frameworks\Eloquent\User');
    }
}
