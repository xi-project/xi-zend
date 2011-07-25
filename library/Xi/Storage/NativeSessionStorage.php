<?php
namespace Xi\Storage;

/**
 * Directly accesses the $_SESSION superglobal instead of going through
 * Zend_Session_Namespace as with Xi\Storage\Zend\SessionStorage
 * 
 * @category    Xi
 * @package     Xi_Storage
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class NativeSessionStorage implements Storage
{
    /**
     * @var string
     */
    protected $_namespace;
    
    /**
     * @var string
     */
    protected $_member;
    
    /**
     * @param string $namespace
     * @param string $member
     */
    public function __construct($namespace, $member)
    {
        $this->_namespace = $namespace;
        $this->_member = $member;
    }
    
    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->_namespace;
    }
    
    /**
     * @return string
     */
    public function getMember()
    {
        return $this->_member;
    }
    
    /**
     * Check whether the storage is empty
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return !isset($_SESSION[$this->getNamespace()][$this->getMember()]);
    }
    
    /**
     * Read contents of storage
     *
     * @return mixed
     */
    public function read()
    {
        if ($this->isEmpty()) {
            return null;
        }
        return $_SESSION[$this->getNamespace()][$this->getMember()];
    }
    
    /**
     * Write contents to storage
     *
     * @param mixed $contents
     */
    public function write($contents)
    {
        $_SESSION[$this->getNamespace()][$this->getMember()] = $contents;
    }
    
    /**
     * Clear storage contents
     *
     * @return void
     */
    public function clear()
    {
        unset($_SESSION[$this->getNamespace()][$this->getMember()]);
    }

}
