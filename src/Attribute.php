<?php

namespace Yaodong\Fixtures;

use Yaodong\Fixtures\Contracts\Attribute as Base;

class Attribute implements Base
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $is_relation;

    /**
     * @param string $name
     * @param bool   $is_relation
     */
    public function __construct($name, $is_relation = false)
    {
        $this->name        = $name;
        $this->is_relation = $is_relation;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function parseValue($value)
    {
        if ($this->is_relation) {
            return Fixtures::identify($value);
        } else {
            return $value;
        }
    }
}
