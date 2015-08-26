<?php

namespace Yaodong\Fixtures\Helpers;

use Exception;
use Yaodong\Fixtures\Contracts\Relation;
use Yaodong\Fixtures\Fixtures;

class Identifier
{
    /**
     * Integer identifiers are values less than 2^30.
     */
    const MAX_ID = 1073741823;

    /**
     * @var Fixtures
     */
    private $fixtures;

    /**
     * @param Fixtures $fixtures
     */
    public function __construct(Fixtures $fixtures)
    {
        $this->fixtures = $fixtures;
    }

    public function apply(array &$data)
    {
        $this->fillIncrementing($data);

        foreach ($data as $table => $rows) {
            $schema = $this->fixtures->getSchema($table);
            foreach ($rows as $label => $row) {
                foreach ($row as $key => $value) {
                    $relation = $schema->getRelation($key);
                    if ($relation instanceof Relation) {
                        $data[$table][$label][$key] = $this->identify($data, $relation, $value);
                        self::arrayReplaceKey($data[$table][$label], $key, $relation->getForeignKey());
                    }
                }

            }
        }
    }

    private function identify(&$data, Relation $relation, $label)
    {
        $other_table  = $relation->getOtherTable();
        $other_key    = $relation->getOtherKey();
        $other_schema = $this->fixtures->getSchema($other_table);
        $other_value  = array_get($data[$other_table][$label], $other_key, false);

        if ($other_value !== false) {
            return $other_value;
        } elseif (substr($other_key, -3) === '_id') {
            $other_relation = $other_schema->getRelation(substr($other_key, 0, -3));
            if ($other_relation instanceof Relation) {
                return $this->identify($data, $other_relation, $other_value);
            }
        }

        throw new Exception('Can\'t identify ' . $other_table . '.' . $other_key . ' with value ' . $other_value);
    }

    private function fillIncrementing(array &$data)
    {
        foreach ($data as $table => $rows) {
            $schema = $this->fixtures->getSchema($table);

            foreach ($rows as $label => $row) {
                if ($schema->getIncrementing()) {
                    $data[$table][$label] = array_merge([$schema->getKeyName() =>sprintf('%u', crc32($label)) % self::MAX_ID], $row);
                }
            }
        }
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
