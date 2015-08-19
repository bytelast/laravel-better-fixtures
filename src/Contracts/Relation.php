<?php

namespace Yaodong\Fixtures\Contracts;

interface Relation
{
    /**
     * @return bool
     */
    public function getForeignKey();

    /**
     * @param array  $data
     * @param string $label
     *
     * @return mixed
     */
    public function getForeignId(array $data, $label);
}
