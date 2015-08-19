<?php

namespace Yaodong\Fixtures\Filters;

use Yaodong\Fixtures\Contracts\Filter as FilterInterface;
use Yaodong\Fixtures\Fixtures;

class Identifier implements FilterInterface
{
    /**
     * @var bool
     */
    protected $incrementing = false;

    /**
     * Integer identifiers are values less than 2^30.
     */
    const MAX_ID = 1073741823;

    public function __construct($incrementing = false)
    {
        $this->incrementing = $incrementing;
    }

    public function apply(array &$data, Fixtures $fixtures)
    {
        foreach ($data as $table_name => $rows) {
            $schema = $fixtures->getSchema($table_name);
            $pk     = $schema->getPrimaryKeyName();
            foreach ($rows as $label => $row) {
                $id = $this->incrementing ? static::incrementing($table_name) : static::checksum($label);
                $data[$table_name][$label] = array_merge([$pk => $id], $data[$table_name][$label]);
            }
        }
    }

    protected static function checksum($label)
    {
        return sprintf('%u', crc32($label)) % self::MAX_ID;
    }

    protected static function incrementing($table_name)
    {
        static $counters = [];

        if (!isset($counters[$table_name])) {
            $counters[$table_name] = 1;
        } else {
            $counters[$table_name] ++;
        }

        return $counters[$table_name];
    }
}
