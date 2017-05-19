<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 04.05.17
 * Time: 19:12
 */

namespace Fg\Frame\Secure;

use Fg\Frame\DI\DIInjector;
use Fg\Frame\Exceptions\AccessDeniedException;

/**
 * Class Secure
 * @package Fg\Frame\Secure
 */
class Secure
{
    protected $session;
    protected static $user;
    protected $mapping = [];
    protected $variables = [];

    /**
     * Secure constructor.
     * @param array $mapping
     */
    public function __construct(array $mapping = [], array $variables = [])
    {
        $this->mapping = $mapping;
        $this->variables = $variables;
        $this->session = DIInjector::get('session');
    }

    /**
     * get user
     *
     * @param array $user
     * @return array
     */
    public function getUser(array $user): array
    {
        if (empty(self::$user)) {
            self::$user = $user;

            foreach (self::$user as $k => $v) {
                $this->session->set($this->variables[$k], $v);
            }
        }
        return self::$user;
    }

    /**
     * check user access level
     *
     * @param $name
     * @return bool
     */
    public function checkAllow(string $name): bool
    {
        if ($this->mapping[$name] <= $_SESSION[$this->variables['rank']]) {
            return true;
        }
        throw new AccessDeniedException('Access denied! Your access level is low.');
    }

    /**
     * check owner
     *
     * @param int $userId
     * @return bool
     */
    public function checkOwner(int $userId): bool
    {
        if ($userId == $_SESSION[$this->variables['id']] OR $this->isAdmin()) {
            return true;
        }
        throw new AccessDeniedException('Access denied! You are not owner of this account');
    }

    /**
     * check admin rank
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return ($_SESSION[$this->variables['rank']] >= $this->mapping['admin']) ? true : false;
    }

}