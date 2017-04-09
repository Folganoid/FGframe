<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 12.03.17
 * Time: 19:03
 */

namespace Fg\Frame\Controller;

use Fg\Frame\Exceptions\FileNotFoundException;
use Fg\Frame\Renderer\Renderer;
use Fg\Frame\Response\JSONResponse;
use Fg\Frame\Response\Response;
use Fg\Frame\Validation\Validation;
use Fg\Frame\Router\Router;

/**
 * Class Controller
 * @package Fg\Frame\Controller
 */
class Controller
{
    protected $configDir;
    private $viewFile;

    /**
     * set view template file
     * @param $path
     */
    public function setViewFile($path)
    {
        $this->viewFile = $path;
    }

    /**
     * @return string
     */
    public function getViewFile($path = ''): string
    {
        if ($path != '') {
            $this->setViewFile($path);
        }
        return $this->viewFile;
    }

    /**
     * Controller constructor.
     * @param $layersDir
     */
    public function __construct()
    {
        $this->configDir = Validation::checkConfigFile(ROOTDIR . '/config/lrsdir.php');
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
    public function render(string $view_path, array $params = [], array $enhanceParams = [], bool $with_layout = true): Response
    {
        try {
            $render = new Renderer($view_path);
            $content = $render->getContent();
        } catch (FileNotFoundException $e) {
            exit($e->getMessage());
        }

        if ($this->isJSON($content)) {
            return new JSONResponse($content);
        }

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

        /**
         * transit getLink() in twig
         */
        $function1 = new \Twig_Function('twigLink', function (string $str, array $params = [], array $enhanceParams = []) {
            return Router::getLink($str, $params, $enhanceParams);
        });

        $twig->addFunction($function1);
        $result = '';

        foreach ($pages as $key => $val) {
            $result .= $twig->render($key, $allParams);
        }

        return new Response($result);
    }

    /**
     * check JSON type
     *
     * @param $str
     * @return bool
     */
    public function isJSON(string $str): bool
    {
        return ((is_string($str) && (is_object(json_decode($str)) || is_array(json_decode($str))))) ? true : false;
    }


}