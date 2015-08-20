<?php

namespace Yaodong\Fixtures\Filters;

use Yaodong\Fixtures\Contracts\Filter as FilterInterface;
use Yaodong\Fixtures\Fixtures;

class Identifier implements FilterInterface
{
    /**
     * @var bool
     */
    protected $sequential = false;

    /**
     * Integer identifiers are values less than 2^30.
     */
    const MAX_ID = 1073741823;

    public function __construct($sequential = false)
    {
        $this->sequential = $sequential;
    }

    public function apply(array &$data, Fixtures $fixtures)
    {
        array_walk($data, function (array &$rows, $table) use ($fixtures) {
            $schema = $fixtures->getSchema($table);
            $pk     = $schema->getKeyName();

            # only table with incrementing
            if ($schema->getIncrementing()) {
                array_walk($rows, function (array &$row, $label) use ($table, $pk) {
                    $id  = $this->sequential ? static::sequential($table) : static::checksum($label);
                    $row = array_merge([$pk => $id], $row);
                });
            }
        });
    }

    protected static function checksum($label)
    {
        return sprintf('%u', crc32($label)) % self::MAX_ID;
    }

    protected static function sequential($table)
    {
        static $counters = [];

        if (!isset($counters[$table])) {
            $counters[$table] = 1;
        } else {
            $counters[$table] ++;
        }

        return $counters[$table];
    }
}
