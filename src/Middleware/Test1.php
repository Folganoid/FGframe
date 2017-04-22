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
 * Class Test1
 * @package Fg\Frame\Middleware
 */
class Test1 implements MiddlewareRuleInterface
{
    public function handle(array $request, \Closure $next)
    {
        if ($request['test1'] < 0) {
            new RedirectResponse(Router::getLink('error', [], ['code' => 500, 'message' => 'test1 - failed']), 500);
        }
        return $next($request);
    }
}