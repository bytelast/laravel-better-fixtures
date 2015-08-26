<?php

namespace Yaodong\Fixtures;

use Closure;
use Symfony\Component\Yaml\Yaml;
use Yaodong\Fixtures\Contracts\Schema;
use Yaodong\Fixtures\Helpers\Identifier;

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

        $this->import();
        $this->identify();
    }

    public function toArray()
    {
        return $this->data;
    }

    public function apply(Closure $function)
    {
        $function($this->data, $this);
    }

    /**
     * @param string $table
     *
     * @return Schema
     */
    abstract public function getSchema($table);

    /**
     * @return array
     */
    protected function import()
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

    /**
     * Apply identifier.
     */
    protected function identify()
    {
        $identifier = new Identifier($this, $this->data);
        $this->data = $identifier->getData();
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
