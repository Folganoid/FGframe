<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 22.04.17
 * Time: 10:01
 */

namespace Fg\Frame\Middleware;

use Fg\Frame\Exceptions\MiddlewareErrorException;
use Fg\Frame\Response\RedirectResponse;
use Fg\Frame\Router\Router;

/**
 * Class UserMode
 * @package Fg\Frame\Middleware
 */
class UserMode implements MiddlewareRuleInterface
{
    public function handle(array $request, \Closure $next)
    {
        if ($request['usermode'] < 0) {
            throw new MiddlewareErrorException('usermod is failed');
            //new RedirectResponse(Router::getLink('error', [], ['code' => 401, 'message' => 'Access denied']), 401);
        }
        return $next($request);
    }

}