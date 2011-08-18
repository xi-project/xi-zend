<?php
namespace Xi\Zend\Form;

abstract class AbstractForm extends \Zend_Form
{
    /**
     * Adds preInit() and postInit() template method calls to the standard
     * constructor.
     *
     * @param array $options optional
     */
    public function __construct($options = null)
    {
        $this->preInit();
        parent::__construct($options);
        $this->postInit();
    }

    /**
     * Template method. Runs during __construct() before init().
     */
    public function preInit()
    {
    }

    /**
     * Template method. Runs during __construct() after init().
     */
    public function postInit()
    {
    }
    
    /**
     * Adds a class name if it does not already exist in the class attribute
     *
     * @param string $class
     * @return AbstractForm
     */
    public function addClass($class)
    {
        $classes = explode(" ", trim($this->getAttrib('class')));
        if (in_array($class, $classes)) {
            return $this;
        }
        
        $classes[] = $class;
        return $this->setAttrib("class", implode(" ", $classes));
    }

    /**
     * Remove a class name from class attribute
     *
     * @param  string                    $class
     * @return AbstractForm
     */
    public function removeClass($class)
    {
        $classes = explode(' ', trim($this->getAttrib('class')));

        $classes = array_filter($classes, function($value) use ($class) {
            return $value != $class;
        });

        return $this->setAttrib('class', implode(' ', $classes));
    }
}
