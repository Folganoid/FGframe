<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 04.05.17
 * Time: 19:12
 */

namespace Fg\Frame\Secure;

use Fg\Frame\DI\DIInjector;

/**
  * Class Secure
 * @package Fg\Frame\Secure
 */
class Secure
{
    protected $session;

    protected static $user;

    protected $mapping = [];

    /**
     * Secure constructor.
     * @param array $mapping
     */
    public function __construct(array $mapping = [])
    {

        $this->mapping = $mapping;
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
                $this->session->set('SESSION_' . $k, $v);
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

        if ($this->mapping[$name] <= $_SESSION['SESSION_rank']) {
            return true;
        }
        return false;
    }

    /**
     * check owner
     *
     * @param int $userId
     * @return bool
     */

    public function checkOwner(int $userId): bool
    {
        if ($userId == $_SESSION['SESSION_id']) {
            return true;
        }
        return false;
    }

}