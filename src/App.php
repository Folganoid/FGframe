<?php

namespace Fg\Frame;

use Fg\Frame\DI\DIInjector;
use Fg\Frame\Exceptions\DIErrorException;
use Fg\Frame\Exceptions\InvalidRouteMethodException;
use Fg\Frame\Exceptions\InvalidHttpMethodException;
use Fg\Frame\Exceptions\InvalidRouteControllerException;
use Fg\Frame\Exceptions\InvalidUrlException;
use Fg\Frame\Response\RedirectResponse;
use Fg\Frame\Router\Router;



/**
 * Class App
 * @package Fg\Frame
 */
class App
{
    private $config;

    /**
     * App constructor.
     * @param array $config
     */
    public function __construct()
    {
        // $this->config = Validation::checkConfigFile(ROOTDIR . '/config/router.php');
    }

    /**
     * starting the application
     *
     */
    public function start()
    {
        //$request = new Request();
        //$router = new Router($this->config['config']);

        DIInjector::setConfig(ROOTDIR . '/config/services.php');

        try {

            DIInjector::get('middleware'); //check middleware conditions
            $request = DIInjector::get('request');
            $router = DIInjector::get('router');
        } catch (DIErrorException $e) {
            echo $e->getMessage();
        }

        try {

            $routerResult = $router->getRoute($request);
            $router->valid($routerResult->controller, $routerResult->method, $routerResult->params, $routerResult->enhanceParams);

        } catch (InvalidHttpMethodException $e) {
            echo $e->getMessage();
        } catch (InvalidUrlException $e) {
            new RedirectResponse(Router::getLink('error', [], ['code' => 404, 'message' => $e->getMessage()]), 404);
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