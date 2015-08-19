<?php

namespace Yaodong\Fixtures;

use Symfony\Component\Yaml\Yaml;
use Yaodong\Fixtures\Contracts\Filter;
use Yaodong\Fixtures\Contracts\Schema;

abstract class Fixtures
{
    /**
     * @var array
     */
    protected $paths;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param string|array $paths
     */
    public function __construct($paths)
    {
        $this->paths = is_array($paths) ? $paths : [$paths];

        $this->importData();
        $this->applyFilters();
    }

    public function toArray()
    {
        return $this->data;
    }

    /**
     * @param string $table_name
     *
     * @return Schema
     */
    abstract function getSchema($table_name);

    /**
     * @return Filter[] $filters
     */
    abstract function getFilters();

    /**
     * @return array
     */
    protected function importData()
    {
        foreach ($this->paths as $path) {
            foreach (glob("$path/*.yml") as $file) {
                $table = static::parseTableName($file);
                $rows  = static::readTableRows($file);
                if (isset($this->data[$table])) {
                    $this->data[$table] = array_merge($this->data[$table], $rows);
                } else {
                    $this->data[$table] = $rows;
                }
            }
        }
    }

    protected function applyFilters()
    {
        foreach ($this->getFilters() as $filter) {
            $filter->apply($this->data, $this);
        }
    }

    /**
     * @param string $file
     *
     * @return string
     */
    protected static function parseTableName($file)
    {
        return substr(basename($file), 0, -4);
    }

    /**
     * @param string $file
     *
     * @return array
     */
    protected static function readTableRows($file)
    {
        return Yaml::parse(file_get_contents($file));
    }
}
