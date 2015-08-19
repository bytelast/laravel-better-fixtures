<?php

namespace Yaodong\Fixtures\Test\Frameworks\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user()
    {
        return $this->belongsTo('Yaodong\Fixtures\Test\Frameworks\Eloquent\Models\User');
    }
}
