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
        $this->send();

    }

    /**
     * send json
     */
    public function sendContent()
    {
        echo json_encode($this->body);
    }
}

