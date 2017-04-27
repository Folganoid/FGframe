<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 22.04.17
 * Time: 10:02
 */

namespace Fg\Frame\Middleware;

use Fg\Frame\Response\RedirectResponse;
use Fg\Frame\Router\Router;

/**
 * Class Test2
 * @package Fg\Frame\Middleware
 */
class Test2 implements MiddlewareRuleInterface
{
    public function handle(array $request, \Closure $next)
    {
        if ($request['test2'] < 0) {
            throw new MiddlewareErrorException('test2 - failed');
        }
        return $next($request);
    }
}