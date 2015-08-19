<?php

namespace Yaodong\Fixtures\Contracts;

interface Schema
{
    /**
     * @return string
     */
    public function getTableName();

    /**
     * @return bool
     */
    public function getIncrementing();

    /**
     * @return string
     */
    public function getPrimaryKeyName();

    /**
     * @param string $key
     *
     * @return Relation|false
     */
    public function getRelation($key);
}
