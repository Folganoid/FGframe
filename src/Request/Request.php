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
     * Get current URI
     *
     * @return string
     */
    public function getUri(): string
    {
        if ((substr(self::$URI, -1) == "/") AND strlen(self::$URI) > 1) {
            $result = substr(self::$URI, 0, -1);
        } else $result = self::$URI;

        if (strpos($result, "?")) {
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
        $tempArr = preg_split("`\?|\&`", self::$URI);
        unset($tempArr[0]);
        sort($tempArr);

        $result = [];

        for ($i = 0; $i < count($tempArr); $i++) {
            $result[(explode('=', $tempArr[$i]))[0]] = (explode('=', $tempArr[$i]))[1];
        }

        return $result;

    }

}