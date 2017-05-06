<?php
namespace Fg\Frame\Router;

use Fg\Frame\Exceptions\InvalidHttpMethodException;
use Fg\Frame\Exceptions\InvalidRouteControllerException;
use Fg\Frame\Exceptions\InvalidRouteMethodException;
use Fg\Frame\Exceptions\InvalidUrlException;
use Fg\Frame\Request\Request;
use Fg\Frame\Response\Response;

/**
 * Class Router
 * @package Fg\Frame\Router
 */
class Router
{
    const DEFAULT_VAR_REGEXP = "[^\/]+";
    private static $routes = [];

    /**
     * Router constructor.
     */
    public function __construct(array $config)
    {
        foreach ($config as $key => $value) {
            $existed_variables = $this->getExistedVariables($value);
            self::$routes[$key] = [
                "origin" => $value["pattern"],
                "regexp" => $this->getRegexpFromRoute($value, $existed_variables),
                "method" => isset($value["method"]) ? $value["method"] : "GET",
                "controller_name" => $this->getControllerName($value),
                "controller_method" => $this->getControllerMethod($value),
                "variables" => $existed_variables
            ];
        }
        //var_dump($this->routes);
    }

    /**
     * Return RouteResult(controllerName, controllerMethod, variables)
     *
     * @param Request $request
     * @return RouterResult
     * @throws InvalidHttpMethodException
     * @throws InvalidUrlException
     */
    public function getRoute(Request $request): RouterResult
    {
        $uri = $request->getUri();          // from $_SERVER
        $method = $request->getMethod();    // from $_SERVER
        $enhanceParams = $request->getUriParams();

        foreach (self::$routes as $key => $value) {
            if ((preg_match('`' . $value['regexp'] . '`', $uri, $matches)) AND ($method == $value['method'])) {
                $variablesArr = [];
                for ($i = 0; $i < count($value['variables']); $i++) {
                    $variablesArr[$value['variables'][$i]] = $matches[$i + 1];
                }
                return new RouterResult($key, $value['controller_name'], $value['controller_method'], $variablesArr, $enhanceParams);
            } else if ((preg_match('`' . $value['regexp'] . '`', $uri)) AND ($method != $value['method'])) {
                throw new InvalidHttpMethodException("Method '" . $method . "' is not allow");
            }
        };
        throw new InvalidUrlException("Incorrect link - " . $uri);
    }

    /**
     * Returns name of controller
     *
     * @param array $config_route
     * @return string
     */
    private function getControllerName(array $config_route): string
    {
        return $config_route["controller"];
    }

    /**
     * Return name of controller method
     *
     * @param array $config_route
     * @return string
     */
    private function getControllerMethod(array $config_route): string
    {
        return $config_route["action"];
    }

    /**
     * Returns regexp by config
     *
     * @param array $config_route
     * @return string
     */
    private function getRegexpFromRoute(array $config_route, array $existed_variables): string
    {
        $pattern = $config_route["pattern"];
        $result = str_replace("/", "\/", $pattern);
        $variables_names = $existed_variables;
        for ($i = 0; $i < count($variables_names); $i++) {
            $var_reg = "(" .
                (array_key_exists($variables_names[$i], $config_route["variables"])
                    ? $config_route["variables"][$variables_names[$i]]
                    : self::DEFAULT_VAR_REGEXP
                )
                . ")";
            $result = str_replace("{" . $variables_names[$i] . "}", $var_reg, $result);
        }
        return "^" . $result . "$";
    }

    /**
     * Returns all variables that exist in pattern
     *
     * @param $config
     * @return array
     */
    private function getExistedVariables($config)
    {
        preg_match_all("/{.+}/U", $config["pattern"], $variables);
        return array_map(function ($value) {
            return substr($value, 1, strlen($value) - 2);
        }, $variables[0]);
    }

    /**
     * Build link
     *
     * @param $route_name
     * @param array $params
     */
    public static function getLink($route_name, $params = [], $enhancedParams = [])
    {
        $pattern = self::$routes[$route_name]['origin'];

        foreach ($params as $key => $value) {
            $pattern = str_replace("{" . $key . "}", $value, $pattern);
        }

        if (count($enhancedParams) > 0) {
            $pattern .= '?';
            foreach ($enhancedParams as $key => $value) {
                $pattern .= $key . "=" . $value . "&";
            }
            $pattern = substr($pattern, 0, -1);
        }
        return $pattern;
    }

    public static function test($str)
    {
        return $str;
    }

    /**
     * Route validator
     *
     * @param $routeController
     * @param $routeMethod
     * @param $routeParams
     * @throws InvalidRouteControllerException
     * @throws InvalidRouteMethodException
     */
    public function valid($routeController, $routeMethod, $routeParams = [], $routeEnhanceParams = [])
    {
        if (class_exists($routeController)) {
            $refClass = new \ReflectionClass($routeController);
            if ($refClass->hasMethod($routeMethod)) {
                $aaa = new $routeController;
                $aaa->$routeMethod($routeParams, $routeEnhanceParams);
            } else throw new InvalidRouteMethodException($routeMethod . ' - Invalid Route Method');
        } else throw new InvalidRouteControllerException($routeController . ' - Invalid Route Controller');
    }
}