<?php

namespace Yaodong\Fixtures;

use Symfony\Component\Yaml\Yaml;

class Fixtures
{
    /**
     * @var Fixture[]
     */
    private $fixtures = [];

    /**
     * Integer identifiers are values less than 2^30.
     */
    const MAX_ID = 1073741823; // 2 ** 30 - 1

    /**
     * @param string|array $paths
     * @param callable     $schema_loader
     */
    public function __construct($paths, callable $schema_loader)
    {
        is_array($paths) || $paths = [$paths];
        $fixtures = $this->import($paths);
        foreach ($fixtures as $table => $rows) {
            $this->fixtures[$table] = new Fixture($table, $rows, call_user_func($schema_loader, $table));
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
