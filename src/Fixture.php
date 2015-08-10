<?php
namespace Yaodong\Fixtures;

use Yaodong\Fixtures\Contracts\Schema;

class Fixture
{
    /**
     * @var string
     */
    private $table;

    /**
     * @var array
     */
    private $rows;

    /**
     * @var Schema
     */
    private $schema;

    /**
     * @param string $table
     * @param array  $rows
     * @param Schema $schema
     */
    public function __construct($table, array $rows, Schema $schema)
    {
        $this->table  = $table;
        $this->schema = $schema;

        foreach ($rows as $label => $row) {
            $this->rows[$label] = $this->parse($label, $row);
        }
    }

    /**
     * @param string $label
     * @param array  $row
     * @return array
     */
    protected function parse($label, array $row)
    {
        $data   = [];
        $schema = $this->schema;

        // generate a primary key if necessary
        if ($schema->getIncrementing() && !array_key_exists($pk = $schema->getPrimaryKeyName(), $data)) {
            $data[$pk] = Fixtures::identify($label);
        }

        foreach ($row as $key => $value) {
            $attr = $schema->getAttribute($key);
            $data[$attr->getName()] = $attr->parseValue($value);
        }

        return $data;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->rows;
    }

}
