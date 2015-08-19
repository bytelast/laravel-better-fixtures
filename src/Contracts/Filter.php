<?php

namespace Yaodong\Fixtures\Contracts;

use Yaodong\Fixtures\Fixtures;

interface Filter
{
    public function apply(array &$data, Fixtures $fixtures);
}
