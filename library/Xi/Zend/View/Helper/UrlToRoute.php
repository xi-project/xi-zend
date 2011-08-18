<?php
namespace Xi\Zend\View\Helper;

use Zend_View_Helper_Abstract;

/**
 * A shortcut for creating a URL based on only the route name.
 */
class UrlToRoute extends Zend_View_Helper_Abstract
{
    /**
     * @param string $route
     * @return string
     */
    public function urlToRoute($route)
    {
        return $this->view->url(array(), $route, true);
    }
}