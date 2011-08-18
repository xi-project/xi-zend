<?php
namespace Xi\Zend\View\Helper;

use Zend_View_Helper_Abstract;

class LinkTo extends Zend_View_Helper_Abstract
{
    /**
     * @param string $url
     * @param string $content
     * @param array $attributes
     * @return string
     */
    public function linkTo($url, $content, $attributes = array())
    {
        return $this->view->element(
            'a',
            $this->view->translate($content),
            array('href' => $url) + $attributes
        );
    }
}