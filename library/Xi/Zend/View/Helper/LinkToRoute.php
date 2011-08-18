<?php
namespace Xi\Zend\View\Helper;

use Zend_View_Helper_Abstract;

class LinkToRoute extends Zend_View_Helper_Abstract
{
    /**
     * @param string $route
     * @param string $content optional, defaults to $route
     * @param array $attributes
     * @return string
     */
    public function linkToRoute($route, $content = null, $attributes = array())
    {
        if (null === $content) {
            $content = $route;
        }
        return $this->view->linkTo(
            $this->view->urlToRoute($route),
            $content,
            $attributes
        );
    }
}