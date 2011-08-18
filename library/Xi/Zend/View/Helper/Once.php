<?php
namespace Xi\Zend\View\Helper;

use Zend_View_Helper_Abstract,
    Zend_Registry,
    ArrayObject;

/**
 * @author     Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Once extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    protected $_logRegistryKey = __CLASS__;
    
	/**
	 * Executes a given named callback only once regardless of how many times
	 * this function is called with the same name
	 * 
	 * @param string $name
	 * @param callback(Zend_View_Abstract) $callback
	 * @return void|mixed
	 */
	public function once($name, $callback)
	{
	    if ($this->isExecuted($name)) {
	        return;
	    }
	    
	    $this->setExecuted($name, true);
	    return $callback($this->view);
	}
	
	/**
	 * @return boolean
	 */
	public function isExecuted($name)
	{
	    return !empty($this->getExecutionLog()->$name);
	}
	
	/**
	 * @param string $name
	 * @param boolean $status
	 * @return Once
	 */
	public function setExecuted($name, $status)
	{
	    $this->getExecutionLog()->$name = (boolean) $status;
	    return $this;
	}
	
	/**
	 * @return ArrayObject
	 */
	public function getExecutionLog()
	{
	    if (!Zend_Registry::isRegistered($this->_logRegistryKey)) {
	        Zend_Registry::set($this->_logRegistryKey, new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS));
	    }
	    return Zend_Registry::get($this->_logRegistryKey);
	}
}
