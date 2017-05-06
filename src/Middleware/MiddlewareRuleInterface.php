<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 22.04.17
 * Time: 14:39
 */

namespace Fg\Frame\Middleware;

/**
 * Interface MiddlewareRuleInterface
 * @package Fg\Frame\Middleware
 */
interface MiddlewareRuleInterface
{
    public function handle(array $request, \Closure $next);
}