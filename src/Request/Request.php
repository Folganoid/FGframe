<?php

namespace Fg\Frame\Request;
/**
 * Class Request - singleton
 * @package Fg\Frame\Request
 */
class Request
{
    private static $request = null;
    public static $URI;
    public $params;

    /**
     * Request constructor.
     */
    private function __construct()
    {
        self::$URI = $_SERVER["REQUEST_URI"];
    }

    /**
     * Returns request
     *
     * @return Request
     */
    public static function getRequest(): self
    {
        if (!self::$request) {
            self::$request = new self();
        }

        return self::$request;
    }

    private function __clone()
    {
    }

    private function __sleep()
    {
    }

    private function __wakeup()
    {
    }


    /**
     * Get current URI without GET params
     * trim '/' from end, if exist
     *
     * @return string
     */
    public function getUri(): string
    {
        if ((substr(self::$URI, -1) == "/") AND strlen(self::$URI) > 1) {
            $result = substr(self::$URI, 0, -1);
        } else $result = self::$URI;

        if (strpos($result, "?")) {
            $this->params = substr($result, strpos($result, "?") + 1);
            return substr($result, 0, strpos($result, "?"));
        } else return $result;
    }

    /**
     * Get current request method
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    /**
     * Get enhanced params from URI
     *
     * @return array
     */
    public function getUriParams(): array
    {
        $res = [];
        parse_str($this->params, $res);
        return $res;
    }

}