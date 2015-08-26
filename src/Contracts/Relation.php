<?php

namespace Yaodong\Fixtures\Contracts;

interface Relation
{
    /**
     * @return bool
     */
    public function getForeignKey();
}
