<?php
namespace Fg\Frame\Router;
/**
 * Class RouteResult
 * @package Fg\Framework\Router
 */
class RouterResult
{
    public $name = '';
    public $controller = '';
    public $method = '';
    public $params = [];
    public $enhanceParams = [];

    /**
     * RouterResult constructor.
     * @param string $name
     * @param string $controller
     * @param string $method
     * @param array $var
     * @param array $enhVar
     */
    public function __construct(string $name, string $controller, string $method, array $var = [], array $enhVar = [])
    {
        $this->name = $name;
        $this->controller = $controller;
        $this->method = $method;
        $this->params = $var;
        $this->enhanceParams = $enhVar;
    }


}