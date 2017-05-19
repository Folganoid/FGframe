<?php

namespace Fg\Frame;

use Fg\Controller\ErrorController;
use Fg\Frame\DI\DIInjector;
use Fg\Frame\Exceptions\AccessDeniedException;
use Fg\Frame\Exceptions\DataErrorException;
use Fg\Frame\Exceptions\DIErrorException;
use Fg\Frame\Exceptions\IncorrectEntryDataException;
use Fg\Frame\Exceptions\IncorrectLoginPassException;
use Fg\Frame\Exceptions\InvalidRouteMethodException;
use Fg\Frame\Exceptions\InvalidHttpMethodException;
use Fg\Frame\Exceptions\InvalidRouteControllerException;
use Fg\Frame\Exceptions\InvalidUrlException;
use Fg\Frame\Exceptions\MiddlewareErrorHighLevelException;
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
        DIInjector::setConfig(ROOTDIR . '/config/services.php');

        try {

            DIInjector::get('middleware'); //check middleware conditions
            DIInjector::get('session');

            $request = DIInjector::get('request');
            $router = DIInjector::get('router');
        } catch (DIErrorException $e) {
            echo $e->getMessage();
        }
         catch (MiddlewareErrorHighLevelException $e) {
             $res = new ErrorController;
             $res->getError([], ['code' => 406, 'message' => $e->getMessage()]);
             die();
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
        } catch (AccessDeniedException $e) {
            new RedirectResponse(Router::getLink('error', [], ['code' => 403, 'message' => $e->getMessage()]), 403);
        } catch (IncorrectLoginPassException $e) {
            new RedirectResponse(Router::getLink('error', [], ['code' => 401, 'message' => $e->getMessage()]), 401);
        } catch (IncorrectEntryDataException $e) {
            new RedirectResponse(Router::getLink('error', [], ['code' => 400, 'message' => $e->getMessage()]), 400);
        } catch (DataErrorException $e) {
            new RedirectResponse(Router::getLink('error', [], ['code' => 404, 'message' => $e->getMessage()]), 404);
        }
    }

    /**
     * destructor
     */
    public function __destruct()
    {

    }
}