<?php

namespace Yaodong\Fixtures\Frameworks\Eloquent;

use Yaodong\Fixtures\Attribute;
use Yaodong\Fixtures\Fixtures;

class Association extends Attribute
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function parseValue($value)
    {
        return Fixtures::identify($value);
    }
}
