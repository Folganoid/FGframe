<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 12.03.17
 * Time: 19:03
 */

namespace Fg\Frame\Controller;


use Fg\Frame\Renderer\Renderer;
use Fg\Frame\Response\Response;

class Controller
{

    public function render(string $view_path, array $params=[], $enhanceParams = [], bool $with_layout=true): Response
    {
        //get rendered info
        $content = Renderer::render($view_path);
        //return response

        if (count($params) > 0) {

            foreach ($params as $key => $value) {
                $content = str_replace('{{' .$key. '}}', $value, $content);
            }

        }

        if (count($enhanceParams) > 0) {

            foreach ($enhanceParams as $key => $value) {
                $content = str_replace('{{' .$key. '}}', $value, $content);
            }

        }

        if($with_layout) {
            $head = file_get_contents(__DIR__.'/../layers/head.html.php');
            $footer = file_get_contents(__DIR__.'/../layers/footer.html.php');

            return new Response($head . $content . $footer);
        }

        return new Response($content);

    }
}