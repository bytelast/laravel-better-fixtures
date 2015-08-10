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
     * @param mixed  $value
     *
     * @return Attribute
     */
    public function getAttribute($key, $value);
}
