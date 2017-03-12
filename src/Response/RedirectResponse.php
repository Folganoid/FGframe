<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 11.03.17
 * Time: 16:27
 */

namespace Fg\Frame\Response;

/**
 * header('location @url')
 *
 * Class RedirectResponse
 * @package Fg\Frame\Response
 */
class RedirectResponse extends Response
{
    /**
     * RedirectResponse constructor.
     * @param string $url
     * @param int $code
     */
   public function __construct($url, $code = 301)
   {
       $this->code = $code;
       $this->addHeader('Location', $url);
       $this->sendHeaders();

   }

}
