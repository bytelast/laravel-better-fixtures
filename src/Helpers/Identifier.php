<?php

namespace Yaodong\Fixtures\Helpers;

use Exception;
use Yaodong\Fixtures\Contracts\Relation;
use Yaodong\Fixtures\Fixtures;

class Identifier
{
    /**
     * @var Fixtures
     */
    private $fixtures;

    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    private $counters = [];

    /**
     * @param Fixtures $fixtures
     * @param array    $data
     */
    public function __construct(Fixtures $fixtures, array $data)
    {
        $this->fixtures = $fixtures;
        $this->data     = $data;

        $this->identifyIncrementingFields();
        $this->identifyRelationFields();
    }

    public function getData()
    {
        return $this->data;
    }

    private function identifyIncrementingFields()
    {
        // each table
        foreach ($this->data as $table => $rows) {
            $schema   = $this->fixtures->getSchema($table);

            // each row if incrementing
            if ($schema->getIncrementing()) {
                $key_name = $schema->getKeyName();

                foreach ($rows as $label => $row) {
                    $this->data[$table][$label][$key_name] = $this->count($table);
                }
            }
        }
    }

    private function identifyRelationFields()
    {
        // each table
        foreach ($this->data as $table => $rows) {
            $schema = $this->fixtures->getSchema($table);

            // each row
            foreach ($rows as $label => $row) {

                // each pair of key-value
                foreach ($row as $key => $value) {

                    if ($value === null) {
                        continue;
                    }

                    $relation = $schema->getRelation($key);
                    if (!$relation instanceof Relation) {
                        continue;
                    }

                    unset($this->data[$table][$label][$key]);

                    $foreign_key = $relation->getForeignKey();
                    $this->data[$table][$label][$foreign_key] = $this->fetchForeignId($relation, $value);
                }
            }
        }
    }

    private function fetchForeignId(Relation $relation, $label)
    {
        $other_table  = $relation->getOtherTable();
        $other_key    = $relation->getOtherKey();
        $other_schema = $this->fixtures->getSchema($other_table);
        $other_value  = array_get($this->data[$other_table][$label], $other_key, false);

        if ($other_value !== false) {
            return $other_value;
        }

        if (substr($other_key, -3) === '_id') {
            $relation_key   = substr($other_key, 0, -3);
            $other_relation = $other_schema->getRelation($relation_key);

            if ($other_relation instanceof Relation) {
                $other_value = array_get($this->data[$other_table][$label], $relation_key, false);
                return $this->fetchForeignId($other_relation, $other_value);
            }
        }

        throw new Exception('Can\'t identify ' . $other_table . '.' . $other_key . ' with value ' . $other_value);
    }

    /**
     * @param string $table
     *
     * @return int
     */
    private function count($table)
    {
        if (!array_key_exists($table, $this->counters)) {
            $this->counters[$table] = 0;
        }

        return ++$this->counters[$table];
    }
}
