<?php
namespace Yaodong\Fixtures;

use Yaodong\Fixtures\Contracts\Attribute as Base;

class Attribute implements Base
{
    /**
     * @var string
     */
    private $name;

    /**
     * The registered string macros.
     *
     * @var array
     */
    protected static $macros = [];

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name  = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function parseValue($value)
    {
        return $value;
    }

    /**
     * Register a custom macro.
     *
     * @param  string    $name
     * @param  callable  $macro
     * @return void
     */
    public static function macro($name, callable $macro)
    {
        static::$macros[$name] = $macro;
    }

    /**
     * Checks if macro is registered.
     *
     * @param  string  $name
     * @return bool
     */
    public static function hasMacro($name)
    {
        return isset(static::$macros[$name]);
    }

}
