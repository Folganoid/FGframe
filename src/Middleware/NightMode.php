<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 22.04.17
 * Time: 10:02
 */

namespace Fg\Frame\Middleware;

use Fg\Frame\Exceptions\MiddlewareErrorException;
use Fg\Frame\Exceptions\MiddlewareErrorHighLevelException;

/**
 * Class Test1
 * @package Fg\Frame\Middleware
 */
class NightMode implements MiddlewareRuleInterface
{
    /**
     * @param array $request
     * @param \Closure $next
     * @return mixed
     * @throws MiddlewareErrorException
     */
    public function handle(array $request, \Closure $next)
    {
        if ($request['night_mode'] != 0) {
            $d = getdate();
            if ($d['hours'] < 7 || $d['hours'] > 21) {
                throw new MiddlewareErrorHighLevelException('Sorry, we does not work at night.');
            }
        }
        return $next($request);
    }
}
