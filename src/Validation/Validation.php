<?php

namespace Fg\Frame\Validation;
use Fg\Frame\Exceptions\BadConfigFileException;

/**
 * Class Validation
 * @package Fg\Frame\Validation
 */
class Validation
{

    public $rules = [];
    public $vars = [];
    public $rule = false;
    public $errors = [];

    public function __construct($rules)
    {
        $this->rules = $rules;
    }

    /**
     * validator $str
     *
     * @param $str
     * @param bool $rule
     * @param array $vars
     * @return bool
     */
    public function check(string $str, $rule = false, array $vars = []): bool
    {
        $this->vars = $vars;
        $this->rule = $rule;

        if (isset($this->rules[$rule])) {

            $pattern = $this->rules[$rule];

            if (count($this->vars) > 0) {
                foreach ($this->vars as $key => $val) {
                    if (strrpos($pattern, '@' . $key . '@') == false) {
                        $this->setError('Parameter "' . $key . '" not found in rule "' . $rule . '"');
                    }
                    $pattern = str_replace('@' . $key . '@', $val, $pattern);
                }
            }

            $this->rule = preg_match('/^' . $pattern . '$/', $str, $matches);

            if (!$matches) {
                $this->setError('RegExp pattern does not match');
            }

        } else {
            $this->setError('Unknown validation rule');
        }

        return $this->rule;
    }

    /**
     *set errors in arr
     *
     * @param $message
     */
    public function setError(string $message)
    {
        $this->errors[] = $message;
    }

    /**
     * show errors
     */
    public function getError()
    {
        for ($i = 0; $i < count($this->errors); $i++) {
            echo '<dd>' . $this->errors[$i] . '</dd>';
        }
    }

    /**
     * entry secure
     *
     * @param $str
     * @return string
     */
    public static function entrySecure(string $str): string
    {
        return nl2br(htmlspecialchars(trim($str), ENT_QUOTES), false);
    }

    /**
     * validator for configfile
     *
     * @param string $file
     * @return array
     */
    public static function checkConfigFile(string $file): array
    {
        try {

            if (file_exists($file)) {
                $res = include($file);
            }
            else {
                throw new BadConfigFileException('Can`t find config file '.$file);
            }

            if (!is_array($res)) {
                throw new BadConfigFileException('Bad parameters in config file '.$file);
            }

        } catch (BadConfigFileException $e) {
            exit($e->getMessage());
        }
        return $res;
    }
}