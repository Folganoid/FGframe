<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 21.05.17
 * Time: 17:34
 */

namespace Fg\Frame\DataBase;

/**
 * Class PostgresqlDataBaseDriver
 * @package Fg\Frame\DataBase
 */
abstract class SqlAbstractDataBaseDriver implements DBInterface
{
    public $connecting;
    protected $config;

    /**
     * PostgresqlDataBaseDriver constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        try {
            $this->connect();
        } catch (\PDOException $e) {
            exit('DataBase connection error: ' . $e->getMessage());
        }
    }

    /**
     * return Exception by default
     */
    public function connect()
    {
        throw new \PDOException('Data Base driver is not connected');
    }
}