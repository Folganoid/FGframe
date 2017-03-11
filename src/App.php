<?php

namespace Fg\Frame;

use Fg\Frame\Exceptions\IncorrectRouteMethodException;
use Fg\Frame\Exceptions\InvalidUrlException;
use Fg\Frame\Request\Request;
use Fg\Frame\Router\Router;

/**
 * Class Application
 * @package Mindk\Framework
 */
class App
{
    private $config;

    public function __construct($config=[])
    {
        $this->config = $config['routeConfig'];
    }

    public function start()
    {
        $request = Request::getRequest();
        $router = new Router($this->config);

        try {

            $routerResult = $router->getRoute($request);

                echo $routerResult->name.'<br>';
                echo $routerResult->controller.'<br>';
                echo $routerResult->method.'<br>';
                var_dump($routerResult->params);
                echo '<br>';
                var_dump($routerResult->enhanceParams);


        } catch (IncorrectRouteMethodException $e) {
            echo $e->getMessage();
        } catch (InvalidUrlException $e) {
            echo $e->getMessage();
        }
    }
}
