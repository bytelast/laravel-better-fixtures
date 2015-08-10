<?php
namespace Yaodong\Fixtures\Contracts;

interface Attribute
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return mixed
     */
    public function getValue();
}
