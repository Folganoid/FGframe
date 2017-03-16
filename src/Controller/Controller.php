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

/**
 * Class Controller
 * @package Fg\Frame\Controller
 */
class Controller
{
    private $configDir;

    /**
     * Controller constructor.
     * @param array $configDir
     */
    public function __construct($configDir = [])
    {
        $this->configDir = $configDir;
    }

    /**
     * get together page
     *
     * @param string $view_path
     * @param array $params
     * @param array $enhanceParams
     * @param bool $with_layout
     * @return Response
     */
    public function render(string $view_path, array $params = [], $enhanceParams = [], bool $with_layout = true): Response
    {
        $content = Renderer::render($view_path);
        $allParams = array_merge($params, $enhanceParams);

        if ($with_layout) {
            $pages = [
                'head' => file_get_contents($this->configDir['head']),
                'page' => $content,
                'footer' => file_get_contents($this->configDir['footer'])
            ];
        } else {
            $pages = [
                'page' => $content,
            ];
        }

        $loader = new \Twig_Loader_Array($pages);
        $twig = new \Twig_Environment($loader);

        $result = '';

        foreach ($pages as $key => $val) {
            $result .= $twig->render($key, $allParams);
        }

        return new Response($result);

    }
}