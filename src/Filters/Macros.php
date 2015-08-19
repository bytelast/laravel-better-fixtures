<?php

namespace Yaodong\Fixtures\Filters;

use Yaodong\Fixtures\Contracts\Filter;
use Yaodong\Fixtures\Fixtures;

class Macros implements Filter
{
    public function apply(array &$data, Fixtures $fixtures)
    {
        return $data;
    }
}
