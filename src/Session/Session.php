<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 27.04.17
 * Time: 10:46
 */

namespace Fg\Frame\Session;

/**
 * Class Session
 * @package Fg\Frame\Session
 */
class Session
{
    /**
     * Session constructor.
     */
    public function __construct()
    {
        session_start();
    }

    /**
     * get session var
     * @param $name
     * @return string
     */
    public function get($name)
    {
        return $_SESSION[$name] ?? null;
    }

    /**
     * set session var
     * @param $name
     * @param $value
     */
    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * unset one session var
     * @param $name
     */
    public function clearOne($name)
    {
        unset($_SESSION[$name]);
    }

    /**
     * frees all session variables
     */
    public function clearAll()
    {
        session_unset();
    }
}