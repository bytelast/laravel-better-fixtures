<?php

namespace Yaodong\Fixtures;

class Fixture
{
    /**
     * @var Fixtures
     */
    protected $fixtures;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $rows;

    /**
     * @param Fixtures $fixtures
     * @param string   $table
     * @param array    $rows
     */
    public function __construct(Fixtures $fixtures, $table, array $rows)
    {
        $this->fixtures = $fixtures;
        $this->table = $table;

        foreach ($rows as $label => $row) {
            $this->rows[$label] = $this->parse($label, $row);
        }
    }

    /**
     * @param string $label
     * @param array  $row
     *
     * @return array
     */
    protected function parse($label, array $row)
    {
        $data = [];
        $schema = $this->fixtures->getSchema($this->table);

        // generate a primary key if necessary
        if ($schema->getIncrementing() && !array_key_exists($pk = $schema->getPrimaryKeyName(), $data)) {
            $data[$pk] = $this->fixtures->identify($label);
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
