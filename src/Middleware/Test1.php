<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 22.04.17
 * Time: 10:02
 */

namespace Fg\Frame\Middleware;

use Fg\Frame\Exceptions\MiddlewareErrorException;

/**
 * Class Test1
 * @package Fg\Frame\Middleware
 */
class Test1 implements MiddlewareRuleInterface
{
    public function handle(array $request, \Closure $next)
    {
        if ($request['test1'] < 0) {
            throw new MiddlewareErrorException('test1 - failed');
        }
        return $next($request);
    }
}