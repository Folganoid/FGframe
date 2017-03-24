<?php

namespace Fg\Frame;

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
        $this->config = Validation::checkConfigFile(ROOTDIR . '/config/routing.php');
    }

    /**
     * starting the application
     *
     */
    public function start()
    {
        $request = Request::getRequest();

        $router = new Router($this->config);

        try {

            $routerResult = $router->getRoute($request);
            Router::valid($routerResult->controller, $routerResult->method, $routerResult->params, $routerResult->enhanceParams);

        } catch (InvalidHttpMethodException $e) {
            echo $e->getMessage();
        } catch (InvalidUrlException $e) {
            new RedirectResponse('/error?code=404&message=' . $e->getMessage(), 404);
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