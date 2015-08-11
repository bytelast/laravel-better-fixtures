<?php

namespace Yaodong\Fixtures\Test\Frameworks\Eloquent;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function posts()
    {
        return $this->hasMany('Yaodong\Fixtures\Test\Frameworks\Eloquent\Post');
    }
}
