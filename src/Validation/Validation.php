<?php

namespace Fg\Frame\Validation;

/**
 * Class Validation
 * @package Fg\Frame\Validation
 */
class Validation
{

    public static $rules = [];
    public static $vars = [];
    public static $rule = false;
    public static $errors = [];

    /**
     * validator $str
     *
     * @param $str
     * @param bool $rule
     * @param array $vars
     * @return bool
     */
    public static function Check(string $str, $rule = false, array $vars = []): bool
    {
        self::$rules = include((ROOTDIR . '/config/validation.php'));
        self::$vars = $vars;
        self::$rule = $rule;
        self::$errors = []; // reset

        if (isset(self::$rules[$rule])) {

            $pattern = self::$rules[$rule];

            if (count(self::$vars) > 0) {
                foreach (self::$vars as $key => $val) {
                    $pattern = str_replace($key, $val, $pattern);
                }
            }

            self::$rule = preg_match($pattern, $str, $matches);

            if (!$matches) {
                static::setError('RegExp pattern does not match');
            }

        } else {
            static::setError('Unknown validation rule');
        }

        return self::$rule;
    }

    /**
     *set errors in arr
     *
     * @param $message
     */
    public static function setError(string $message)
    {
        self::$errors[] = $message;
    }

    /**
     * show errors
     */
    public static function getError()
    {
        for ($i = 0; $i < count(self::$errors); $i++) {
            echo '<dd>' . self::$errors[$i] . '</dd>';
        }
    }

    /**
     * entry secure
     *
     * @param $str
     * @return string
     */
    public static function EntrySecure(string $str): string
    {
        return nl2br(htmlspecialchars(trim($str), ENT_QUOTES), false);
    }

}