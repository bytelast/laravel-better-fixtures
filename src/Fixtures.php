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
    protected $fixture = [];

    /**
     * @var callable
     */
    protected static $schema_loader;

    /**
     * @var callable
     */
    protected static $identifier;

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
            $this->fixture[$table] = $this->instanceFixture($table, $rows);
        }
    }

    /**
     * @param string $label
     *
     * @return int
     */
    public static function identify($label)
    {
        if (!static::$identifier) {
            return sprintf('%u', crc32($label)) % self::MAX_ID;
        } else {
            return call_user_func(static::$identifier, $label);
        }
    }

    /**
     * @param callable $identifier
     */
    public static function setIdentifier(callable $identifier)
    {
        static::$identifier = $identifier;
    }

    /**
     * @param string $table
     *
     * @throws Exception
     *
     * @return Schema
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
     * @return array
     */
    public function toArray()
    {
        $fixtures = [];
        foreach ($this->fixture as $table => $fixture) {
            $fixtures[$table] = $fixture->toArray();
        }

        return $fixtures;
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
                $rows = static::readTableRows($file);
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

    /**
     * @param string $table
     * @param array  $rows
     *
     * @return Fixture
     */
    protected function instanceFixture($table, $rows)
    {
        return new Fixture($this, $table, $rows);
    }
}
