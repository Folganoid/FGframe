<?php

namespace Fg\Frame;

use Fg\Frame\DI\Injector;
use Fg\Frame\DI\Service;
use Fg\Frame\Exceptions\DIErrorException;
use Fg\Frame\Exceptions\InvalidRouteMethodException;
use Fg\Frame\Exceptions\InvalidHttpMethodException;
use Fg\Frame\Exceptions\InvalidRouteControllerException;
use Fg\Frame\Exceptions\InvalidUrlException;
use Fg\Frame\Request\Request;
use Fg\Frame\Response\RedirectResponse;
use Fg\Frame\Router\Router;
use Fg\Frame\Validation\Validation;


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

        Injector::setConfig(ROOTDIR . '/config/services.php');

        try {

            $request = Service::get('request');
            $router = Service::get('router');
            $validation = Service::get('validation');

           // echo $validation->check('123123', 'min_max_length', ['min' => 2, 'max' => 10]);
           // $validation->getError();

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