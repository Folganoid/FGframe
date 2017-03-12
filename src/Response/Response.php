<?php

namespace Fg\Frame\Response;

/**
 * Class Response
 * @package Fg\Frame\Response
 */
class Response
{
    public $code = 200;

    const STATUS_MSGS = [
        '200' => 'Ok',
        '301' => 'Moved',
        '404' => 'Not found',
        '500' => 'Server error'
    ];

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
        $this->body = $content;
        $this->code = $code;
        $this->addHeader('Content-Type','text/html');
        $this->send();
    }

    /**
     * @param $key
     * @param $value
     */
    public function addHeader($key, $value){
        $this->headers[$key] = $value;
    }

    /**
     * Send response
     */
    public function send(){
        $this->sendHeaders();
        $this->sendContent();
    }

    /**
     * add headers
     */
    public function sendHeaders(){
        header($_SERVER['SERVER_PROTOCOL'] . " " . $this->code . " " . self::STATUS_MSGS[$this->code]);
        if(!empty($this->headers)){
            foreach($this->headers as $key => $value){
                header($key.": ". $value);
            }
        }
    }

    /**
     * show content
     */
    public function sendContent(){
        echo $this->body;
    }
}