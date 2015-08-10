<?php
namespace Yaodong\Fixtures\Contracts;

interface Attribute
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function parseValue($value);
}
