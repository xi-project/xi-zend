<?php
namespace Xi\Zend\Form\Csrf;

use Zend_Session_Namespace;

/**
 * CSRF element for forms. Uses a single common hash for all forms in a page.
 * 
 * @author Petri Mahanen <petri.mahanen@brainalliance.com>
 */
class HashElement extends \Zend_Form_Element_Hash
{
    /**
     * A single hash for all elements
     *
     * @var string
     */
    protected static $_singleHash;
    
    /**
     * Single session to rule them all, and in darkness manage them.
     *
     * @var Zend_Session_Abstract
     */
    protected static $_singleSession;
    
    /**
     * Session name uses only the class name and the security element name.
     * 
     * This way all DS Secure forms share one hash.
     *
     * @return string 
     */
    public function getSessionName()
    {
        return __CLASS__ . '_' . $this->getName();
    }
    
    /**
     * Use a single session namespace for all elements
     *
     * @return Zend_Session_Abstract
     */
    public function getSession()
    {
        if (!self::$_singleSession) {
            self::$_singleSession = new Zend_Session_Namespace($this->getSessionName(), true);
        }
        return self::$_singleSession;
    }
    
    /**
     * Setting the session from outside is disabled
     *
     * @param Zend_Session_Abstract $session
     * @throws FeatureDisabledException            always
     */
    public function setSession($session)
    {
        throw new FeatureDisabledException('Cannot set CSRF session!');
    }
    
    /**
     * Get the common hash for all elements
     *
     * @return string
     */
    public function getHash()
    {
        if (!self::$_singleHash) {
            $this->_generateHash();
            self::$_singleHash = $this->_hash;
        } else {
            // _generateHash() sets the value.
            // if the value has already been generated, we must set the value here,
            // or elements in forms 2..n do not have the value
            $this->setValue(self::$_singleHash);
        }
        
        return self::$_singleHash;
    }
}
