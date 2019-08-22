<?php
namespace siesta\domain;

/**
 * Class Singleton
 * @package siesta\domain
 */
abstract class Singleton
{
    private static $_instance;

    private function __construct()
    {
    }

    /**
     * @param string $className
     * @return mixed
     */
    public static function get()
    {
        $className = static::class;

        if (empty(self::$_instance[$className])) {
            self::$_instance[$className] = new $className;
        }

        return self::$_instance[$className];
    }
}