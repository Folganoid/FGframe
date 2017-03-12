<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 12.03.17
 * Time: 19:04
 */

namespace Fg\Frame\Renderer;


class Renderer
{

    public static function render(string $path_to_view): string
    {
        $file = file_get_contents($path_to_view);
        return $file;
    }
}