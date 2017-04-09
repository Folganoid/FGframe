<?php

namespace Fg\Frame\Request;
/**
 * Class Request
 * @package Fg\Frame\Request
 */
class Request
{
    public static $URI;
    public $params;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        self::$URI = $_SERVER["REQUEST_URI"];
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