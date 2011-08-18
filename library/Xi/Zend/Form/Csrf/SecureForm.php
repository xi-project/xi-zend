<?php
namespace Xi\Zend\Form\Csrf;

use Xi\Zend\Form\AbstractForm,
    Zend_Form_Decorator_ViewScript,
    Zend_Form_Decorator_Callback,
    Zend_Form_Decorator_Abstract,
    Zend_Form_Decorator_Form;

/**
 * Prevents Cross-Site Request Forgery (CSRF) using Zend_Form_Element_Hash.
 * Tries to be as unobtrusive as possible with adding the element.
 */
class SecureForm extends AbstractForm
{
    /**
     * Security element configuration
     * 
     * @var ArrayObject
     */
    protected $_securityConfig;

    /**
     * Whether to use the security element
     * 
     * @var boolean
     */
    protected $_useCsrfSecurityElement = true;

    /**
     * The name of the form element
     * 
     * @var string
     */
    protected $_csrfSecurityElementName = 'csrf_hash';

    /**
     * The class of the form element
     * 
     * @var string
     */
    protected $_csrfSecurityElementClass = 'csrf-hash';

    /**
     * The class added to a secured form
     * 
     * @var string
     */
    protected $_csrfSecurityFormClass = 'secure';

    /**
     * An identity token that identifies this form uniquely among forms on a
     * page and persists between requests
     *
     * @return string
     */
    protected function _getFormIdentityToken()
    {
        return get_class($this);
    }

    /**
     * Adds a CSRF security element if required. Note that addition may have
     * been triggered by eg. setting a view script.
     */
    public function postInit()
    {
        if ($this->_useCsrfSecurityElement && !$this->_hasCsrfSecurityElement()) {
            $this->_addCsrfSecurityElement();
        }
    }

    /**
     * Check if the form has security errors
     * 
     * @return boolean
     */
    public function hasSecurityErrors()
    {
        return $this->_hasCsrfSecurityElement()
            && $this->_getCsrfSecurityElement()->hasErrors();
    }

    /**
     * Add the security form element
     *
     * @return SecureForm
     */
    protected function _addCsrfSecurityElement()
    {
        return $this->addClass($this->_csrfSecurityFormClass)
                    ->addElement($this->_createCsrfSecurityElement($this->_csrfSecurityElementName));
    }

    /**
     * Check if the security element exists
     * 
     * @return boolean
     */
    protected function _hasCsrfSecurityElement()
    {
        return (boolean) $this->getElement($this->_csrfSecurityElementName);
    }

    /**
     * Get the security element
     * 
     * @return HashElement
     * @throws FeatureDisabledException
     */
    protected function _getCsrfSecurityElement()
    {
        if (!$this->_useCsrfSecurityElement) {
            throw new FeatureDisabledException("Trying to access CSRF security element when not enabled");
        }
        
        if (!$this->_hasCsrfSecurityElement()) {
            $this->_addCsrfSecurityElement();
        }

        return $this->getElement($this->_csrfSecurityElementName) ?: null;
    }

    /**
     * Set form to be rendered by a specific view script using a ViewScript
     * form decorator. Makes sure the CSRF security element is rendered if so
     * required.
     *
     * @param string $script
     * @return SecureForm
     */
    public function setViewScript($script)
    {      
        $this->clearDecorators();
        $this->addDecorator($this->_getViewScriptDecorator($script));
        if ($this->_useCsrfSecurityElement) {
            $this->addDecorator($this->_getCsrfSecurityElementDecorator());
        }
        $this->addDecorator($this->_getFormDecorator());
        return $this;
    }

    /**
     * Generate a value acceptable to addDecorator()
     *
     * @param string $script
     * @return Zend_Form_Decorator_ViewScript
     */
    protected function _getViewScriptDecorator($script)
    {
        return new Zend_Form_Decorator_ViewScript(array(
            'viewScript' => $script
        ));
    }

    /**
     * Generate a value acceptable to addDecorator()
     *
     * @return Zend_Form_Decorator_Callback
     */
    protected function _getCsrfSecurityElementDecorator()
    {
        $element = $this->_getCsrfSecurityElement();
        return new Zend_Form_Decorator_Callback(array(
            'placement' => Zend_Form_Decorator_Abstract::PREPEND,
            'callback' => function() use($element) {
                return $element->render();
            }
        ));
    }

    /**
     * Generate a value acceptable to addDecorator()
     *
     * @return Zend_Form_Decorator_Form
     */
    protected function _getFormDecorator()
    {
        return new Zend_Form_Decorator_Form();
    }

    /**
     * Set useCsrfSecurityElement
     * 
     * @param boolean $flag
     * @return SecureForm
     */
    public function setUseCsrfSecurityElement($flag)
    {
        $this->_useCsrfSecurityElement = $flag;
        return $this;
    }

    /**
     * Create the form element
     * 
     * @param string $name
     * @return Zend_Form_Element_Hash
     */
    protected function _createCsrfSecurityElement($name)
    {
        return $this->_createHashElement($name, $this->_getCsrfSecurityElementOptions())
                    ->setAttrib('class', $this->_csrfSecurityElementClass);
    }

    /**
     * Get the options for the security element
     * 
     * @return array
     */
    protected function _getCsrfSecurityElementOptions()
    {
        return array(
            'salt'    => $this->_getCsrfSecurityElementSalt(),
            'timeout' => $this->_getCsrfSecurityElementTimeout()
        );
    }

    /**
     * Uses _getFormIdentityToken() to generate a salt.
     *
     * @return string
     */
    protected function _getCsrfSecurityElementSalt()
    {
        return sha1($this->_getFormIdentityToken());
    }

    /**
     * Get the timeout for the security element
     * 
     * @return int  Timeout in seconds
     */
    protected function _getCsrfSecurityElementTimeout()
    {
        return 60 * (int) $this->_getSecurityConfigParameter('formTimeout');
    }

    /**
     * Get a security configuration parameter by key
     * 
     * @param string $key
     * @return mixed
     * @throws ConfigurationNotFoundException
     */
    protected function _getSecurityConfigParameter($key)
    {
        $config = $this->_getSecurityConfig();
        if (!isset($config->{$key})) {
            throw new ConfigurationNotFoundException("Missing security configuration parameter '{$key}'");
        }
        return $config->{$key};
    }

    /**
     * Get the security configuration
     * 
     * @return ArrayObject
     * @throws ConfigurationNotFoundException
     */
    protected function _getSecurityConfig()
    {
        if (null === $this->_securityConfig) {
            throw new ConfigurationNotFoundException("Missing security configuration");
        }
        return $this->_securityConfig;
    }
    
    /**
     * @param ArrayObject $config
     * @return SecureForm
     */
    public function setSecurityConfig($config)
    {
        $this->_securityConfig = $config;
        return $this;
    }
}
