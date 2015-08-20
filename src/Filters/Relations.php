<?php

namespace Yaodong\Fixtures\Filters;

use Yaodong\Fixtures\Contracts\Filter;
use Yaodong\Fixtures\Contracts\Relation;
use Yaodong\Fixtures\Fixtures;

class Relations implements Filter
{
    public function apply(array &$data, Fixtures $fixtures)
    {
        foreach ($data as $table => $rows) {
            $schema = $fixtures->getSchema($table);
            foreach ($rows as $label => $row) {
                foreach ($row as $key => $value) {
                    $relation = $schema->getRelation($key);
                    if ($relation instanceof Relation) {
                        $target       = &$data[$table][$label];
                        $target[$key] = $relation->getForeignId($data, $value);
                        static::arrayReplaceKey($target, $key, $relation->getForeignKey());
                    }
                }
            }
        }
    }

    protected static function arrayReplaceKey(&$array, $key_from, $key_to)
    {
        $keys  = array_keys($array);
        $index = array_search($key_from, $keys);

        if ($index !== false) {
            $keys[$index] = $key_to;
            $array = array_combine($keys, $array);
        }
    }
}
