<?php

namespace Yaodong\Fixtures\Contracts;

interface Schema
{
    /**
     * @return string
     */
    public function getTable();

    /**
     * @return bool
     */
    public function getIncrementing();

    /**
     * @return string
     */
    public function getKeyName();

    /**
     * @param string $key
     *
     * @return Relation|false
     */
    public function getRelation($key);
}
