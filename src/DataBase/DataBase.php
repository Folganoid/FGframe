<?php

namespace Fg\Frame\DataBase;

/**
 * Created by PhpStorm.
 * User: fg
 * Date: 14.04.17
 * Time: 12:03
 */

class DataBase
{
    private $config = [];
    private $driverDB;
    public $methods;

    public function __construct(array $config, string $driver)
    {
        $this->config = $config;
        $this->driverDB = $driver;

        $driverFileName = 'Fg\\Frame\\DataBase\\' . $this->driverDB . 'DataBaseDriver';

        $this->methods = new $driverFileName($this->config);

    }

}