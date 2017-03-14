<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 11.03.17
 * Time: 16:31
 */

namespace Fg\Frame\Response;

/**
 * Class JavaScriptResponse
 * @package Fg\Frame\Response
 */
class JavaScriptResponse extends Response
{

    public function __construct($content, $code, $headers)
    {
        parent::__construct($content, $code);

        $this->addHeader('Content-Type', 'application/javascript');

        $this->addArrHeaders($headers);

        $this->send();

    }

    /**
     * show content
     */
    public function sendContent()
    {
        echo '<script>' . $this->body . '</script>';
    }


    /**
     * @param $arr
     * add enhanced params
     */
    public function addArrHeaders($arr)
    {

        foreach ($arr as $key => $value) {
            $this->addHeader($key, $value);
        }

    }

}