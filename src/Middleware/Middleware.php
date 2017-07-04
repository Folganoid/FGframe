<?php

namespace Fg\Frame\Middleware;

use Fg\Frame\Exceptions\MiddlewareErrorException;

/**
 * Class Middleware
 * @package Fg\Frame\Middleware
 */
class Middleware
{
    /**
     * @var array - middleware rules (define in config file)
     */
    protected $rules = [];
    /**
     * @var array - check order (define in config file)
     */
    protected $order = [];
    /**
     * @var array incoming params
     */
    protected $request = [];
    /**
     * @var \Closure - next check
     */
    protected $next;
    /**
     * @var int - AI counter
     */
    protected $count;

    /**
     * Middleware constructor.
     * @param array $rules
     * @param array $order
     */
    public function __construct(array $rules, array $order, $params)
    {
        $this->rules = $rules;
        $this->order = $order;
        $this->count = 0;
        $this->request = $params;

        try {
            $this->checkVariables($this->order);
        } catch (MiddlewareErrorException $e) {
            exit($e->getMessage());
        };

        /**
         * recursive func.
         *
         * @param array $request
         * @return bool
         */
        $this->next = function (array $request) {
            $this->count++;

            if (isset($this->order[$this->count])) {
                try {
                    (new $this->rules[$this->order[$this->count]])->handle($request, $this->next);
                } catch (MiddlewareErrorException $e) {
                    exit($e->getMessage());
                }
            } else {
                return true;
            }
        };

        /**
         * first start
         */
        if (count($this->request) > 0) {
            try {
                (new $this->rules[$this->order[$this->count]])->handle($this->request, $this->next);
            } catch (MiddlewareErrorException $e) {
                exit($e->getMessage());
            }

        } else {
            return true;
        }
    }

    /**
     * check isset/match middleware variables by "order"
     *
     * @param array $order
     * @throws MiddlewareErrorException
     */
    protected function checkVariables(array $order)
    {
        for ($i = 0; $i < count($order); $i++) {
            if (!isset($this->rules[$order[$i]]) || !isset($this->request[$order[$i]])) {
                throw new MiddlewareErrorException('Can`t find "' . $order[$i] . '" variable in middleware configuration');
            };
        };
    }
}