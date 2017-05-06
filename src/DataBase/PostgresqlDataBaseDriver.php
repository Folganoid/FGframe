<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 14.04.17
 * Time: 16:25
 */

namespace Fg\Frame\DataBase;


class PostgresqlDataBaseDriver implements DBInterface
{
    public $connecting;
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
        $this->connect();
    }

    public function connect()
    {
        try {
            $this->connecting = new \PDO("pgsql:host=" . $this->config['host'] . ";dbname=" . $this->config['db'], $this->config['user'], $this->config['pass']);
        } catch (\PDOException $e) {
            exit('PostgreSQL DataBase connection error: ' . $e->getMessage());
        }
    }
}
