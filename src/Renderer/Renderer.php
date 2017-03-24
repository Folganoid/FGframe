<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 24.03.17
 * Time: 10:12
 */

namespace Fg\Frame\Renderer;


use Fg\Frame\Exceptions\FileNotFoundException;

/**
 * Class Renderer
 * @package Fg\Frame\Renderer
 */
class Renderer
{
    public $content;

    /**
     * Renderer constructor.
     * @param $path
     * @throws FileNotFoundException
     */
    public function __construct($path)
    {
        if (!file_exists($path)) throw new FileNotFoundException('file "' . $path . '" not found');
        $this->content = file_get_contents($path);
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}