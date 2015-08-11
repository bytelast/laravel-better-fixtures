<?php

namespace Yaodong\Fixtures\Contracts;

interface Schema
{
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
     * @return Attribute
     */
    public function getAttribute($key);
}
