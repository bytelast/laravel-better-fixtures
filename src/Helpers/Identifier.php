<?php

namespace Yaodong\Fixtures\Helpers;

use Yaodong\Fixtures\Contracts\Relation;
use Yaodong\Fixtures\Fixtures;

class Identifier
{
    /**
     * Integer identifiers are values less than 2^30.
     */
    const MAX_ID = 1073741823;

    public static function apply(array &$data, Fixtures $fixtures)
    {
        array_walk($data, function (array &$rows, $table) use ($fixtures) {
            $schema = $fixtures->getSchema($table);

            array_walk($rows, function (array &$row, $label) use ($schema) {

                # only table with incrementing
                if ($schema->getIncrementing()) {
                    $row = array_merge([$schema->getKeyName() => static::identify($label)], $row);
                }

                foreach ($row as $key => $value) {
                    $relation = $schema->getRelation($key);
                    if ($relation instanceof Relation) {
                        $row[$key] = static::identify($value);
                        self::arrayReplaceKey($row, $key, $relation->getForeignKey());
                    }
                }
            });
        });
    }

    private static function identify($label)
    {
        return sprintf('%u', crc32($label)) % self::MAX_ID;
    }

    private static function arrayReplaceKey(&$array, $key_from, $key_to)
    {
        $keys  = array_keys($array);
        $index = array_search($key_from, $keys);

        if ($index !== false) {
            $keys[$index] = $key_to;
            $array = array_combine($keys, $array);
        }
    }
}
