<?php

namespace Yaodong\Fixtures;

use Exception;
use Symfony\Component\Yaml\Yaml;
use Yaodong\Fixtures\Contracts\Schema;

class Fixtures
{
    /**
     * @var Fixture[]
     */
    private $fixtures = [];

    /**
     * @var callable
     */
    protected static $schema_loader;

    /**
     * Integer identifiers are values less than 2^30.
     */
    const MAX_ID = 1073741823; // 2 ** 30 - 1

    /**
     * @param string|array $paths
     */
    public function __construct($paths)
    {
        is_array($paths) || $paths = [$paths];
        $fixtures = $this->import($paths);
        foreach ($fixtures as $table => $rows) {
            $this->fixtures[$table] = new Fixture($this, $table, $rows);
        }
    }

    public function toArray()
    {
        $fixtures = [];
        foreach ($this->fixtures as $table => $fixture) {
            $fixtures[$table] = $fixture->toArray();
        }

        return $fixtures;
    }

    public static function identify($label)
    {
        return sprintf('%u', crc32($label)) % self::MAX_ID;
    }

    /**
     * @param string $table
     *
     * @return Schema
     * @throws Exception
     */
    public function getSchema($table)
    {
        if (empty(static::$schema_loader)) {
            throw new \Exception('Schema loader is not set.');
        }

        return call_user_func(static::$schema_loader, $table);
    }

    /**
     * @param callable $loader
     */
    public static function setSchemaLoader(callable $loader)
    {
        static::$schema_loader = $loader;
    }

    /**
     * @param array $paths
     *
     * @return array
     */
    protected function import(array $paths)
    {
        $fixtures = [];
        foreach ($paths as $path) {
            foreach (glob("$path/*.yml") as $file) {
                $table = static::parseTableName($file);
                $rows  = static::readTableRows($file);
                if (isset($fixtures[$table])) {
                    $fixtures[$table] = array_merge($fixtures[$table], $rows);
                } else {
                    $fixtures[$table] = $rows;
                }
            }
        }

        return $fixtures;
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
