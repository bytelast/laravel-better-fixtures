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

    public function __construct($path, callable $schema_loader)
    {
        foreach (glob("$path/*.yml") as $file) {
            $table = static::parseTableName($file);
            $rows = static::readTableRows($file);
            $this->fixtures[$table] = new Fixture($table, $rows, call_user_func($schema_loader, $table));
        }
    }

    public function toArray()
    {
        return $this->export('toArray');
    }

    public function toYaml()
    {
        return $this->export('toYaml');
    }

    public static function identify($label)
    {
        return sprintf('%u', crc32($label)) % self::MAX_ID;
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

    private function export($method)
    {
        $fixtures = [];

        foreach ($this->fixtures as $table => $fixture) {
            $fixtures[$table] = $fixture->$method();
        }

        return $fixtures;
    }
}
