<?php

namespace Yaodong\Fixtures\Contracts;

interface Relation
{
    /**
     * @return string
     */
    public function getOtherTable();

    /**
     * @return string
     */
    public function getOtherKey();

    /**
     * @return bool
     */
    public function getForeignKey();
}
