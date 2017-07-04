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
class TechWorks implements MiddlewareRuleInterface
{
    public function handle(array $request, \Closure $next)
    {
        if ($request['technical_works'] != 0) {
            throw new MiddlewareErrorException('Application temporarily unavailable due to technical issues. Please try again later.');
        }
        return $next($request);
    }

}