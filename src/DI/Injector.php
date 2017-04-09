<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 08.04.17
 * Time: 18:31
 */

namespace Fg\Frame\DI;

use Fg\Frame\Exceptions\DIErrorException;
use Fg\Frame\Validation\Validation;

/**
 * Class Injector
 * @package Fg\Frame\DI
 */
class Injector
{
    public static $mapping = [];

    public static function setConfig(string $cfgFile)
    {
        self::$mapping = Validation::checkConfigFile($cfgFile);
    }

    /**
     * @param $className
     * @return object
     * @throws DIErrorException
     */
    public static function build(string $className)
    {
        if (isset(self::$mapping[$className])) {
            $className = self::$mapping[$className];
        }

        $ref = new \ReflectionClass($className);
        $construct = $ref->getConstructor();
        $params = $construct->getParameters();
        $configs = [];
        $paramsNameArr = [];
        $paramsArr = [];

        for ($i = 0; $i < count($params); $i++) {
            $paramsNameArr[] = $params[$i]->name;
        }

        if (count($paramsNameArr) > 0) {
            $pathArr = explode('\\', $className);
            $cfgFile = ROOTDIR . '/config/serv/' . strtolower(array_pop($pathArr)) . '.php';
            $configs = Validation::checkConfigFile($cfgFile);

            for ($i = 0; $i < count($paramsNameArr); $i++) {
                if (!isset($configs[$paramsNameArr[$i]])) {
                    throw new DIErrorException('Parameter "' . $paramsNameArr[$i] . '" not find in file: ' . $cfgFile);
                }
                $paramsArr[] = $configs[$paramsNameArr[$i]];
            }
        }

        $instance = $ref->newInstanceArgs($paramsArr);
        return $instance;
    }

}