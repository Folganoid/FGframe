<?php

namespace Fg\Frame\Response;
use Fg\Frame\Validation\Validation;

/**
 * Class Response
 * @package Fg\Frame\Response
 */
class Response
{
    public $code = 200;

    /**
     * @var array
     */
    protected $statusMsg = [];

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var string
     */
    protected $body = '';

    /**
     * Response constructor.
     */
    public function __construct($content = 'default content', $code = 200)
    {

        $this->statusMsg = Validation::checkConfigFile(ROOTDIR . '/config/status_msg.php');

        $this->body = $content;
        $this->code = $code;
        $this->addHeader('Content-Type', 'text/html');
        $this->send();
    }

    /**
     * @param $key
     * @param $value
     */
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    /**
     * Send response
     */
    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
    }

    /**
     * add headers
     */
    public function sendHeaders()
    {
        header($_SERVER['SERVER_PROTOCOL'] . " " . $this->code . " " . $this->statusMsg[$this->code]);
        if (!empty($this->headers)) {
            foreach ($this->headers as $key => $value) {
                header($key . ": " . $value);
            }
        }
    }

    /**
     * show content
     */
    public function sendContent()
    {
        echo $this->body;
    }
}