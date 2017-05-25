<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 14.04.17
 * Time: 16:25
 */

namespace Fg\Frame\DataBase;

/**
 * Class MysqlDataBaseDriver
 * @package Fg\Frame\DataBase
 */
class MysqlDataBaseDriver extends SqlAbstractDataBaseDriver
{
    public function connect()
    {
        $this->connecting = new \PDO("mysql:host=" . $this->config['host'] . ";dbname=" . $this->config['db'], $this->config['user'], $this->config['pass']);
    }

}