<?php

namespace Fg\Frame\DI;

/**
 * Class Service
 * @package Fg\Frame\DI
 */
class Service
{
    private static $container = [];

    /**
     * @param string $serviceName
     * @return mixed
     */
    public static function get(string $serviceName)
    {
        if(!isset(self::$container[$serviceName]))
        {
            self::set($serviceName, Injector::build($serviceName));
        }

        return self::$container[$serviceName];
    }

    /**
     * @param string $serviceName
     * @param $instance
     */
    public static function set(string $serviceName, $instance)
    {
        self::$container[$serviceName] = $instance;
    }

}