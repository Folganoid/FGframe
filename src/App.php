<?php

namespace Fg\Frame;

use Fg\Frame\Exceptions\InvalidRouteMethodException;
use Fg\Frame\Exceptions\InvalidHttpMethodException;
use Fg\Frame\Exceptions\InvalidRouteControllerException;
use Fg\Frame\Exceptions\InvalidUrlException;
use Fg\Frame\Request\Request;
use Fg\Frame\Response\Response;
use Fg\Frame\Router\Router;


/**
 * Class App
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

            /*
                echo $routerResult->name.'<br>';
                echo $routerResult->controller.'<br>';
                echo $routerResult->method.'<br>';
                var_dump($routerResult->params);
                echo '<br>';
                var_dump($routerResult->enhanceParams);
                echo '<br>';
            */


            Router::valid($routerResult->controller, $routerResult->method, $routerResult->params);


        }  catch (InvalidHttpMethodException $e) {
            echo $e->getMessage();
        } catch (InvalidUrlException $e) {
            echo $e->getMessage();
        } catch (InvalidRouteMethodException $e) {
            echo $e->getMessage();
        } catch (InvalidRouteControllerException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * destructor
     */
    public function __destruct()
    {

    }
}

