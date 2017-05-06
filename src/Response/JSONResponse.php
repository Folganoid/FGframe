<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 11.03.17
 * Time: 16:29
 */

namespace Fg\Frame\Response;

/**
 * Class JSONResponse
 * @package Fg\Frame\Response
 */
class JSONResponse extends Response
{
    /**
     * JSONResponse constructor.
     * @param string $content
     * @param int $code
     */
    public function __construct($content, $code = 200)
    {
        parent::__construct($content, $code);
        $this->addHeader('Content-Type', 'application/json');
    }

    /**
     * send json
     */
    public function sendContent()
    {
        echo $this->body; //already in JSON format
    }
}

