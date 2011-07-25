<?php
namespace Xi\Storage\Zend;
use Xi\Storage\Storage;

/**
 * Stores data into a Zend_Cache_Backend_Interface
 * 
 * @category    Xi
 * @package     Xi_Storage
 * @subpackage  Xi_Storage_Zend
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class CacheStorage implements Storage 
{
    /**
     * @var Zend_Cache_Backend_Interface
     */
    protected $_backend;
    
    /**
     * @var string
     */
    protected $_id;
    
    /**
     * @param Zend_Cache_Backend_Interface $backend
     * @param string $id resource identifier
     */
    public function __construct(Zend_Cache_Backend_Interface $backend, $id)
    {
        $this->_backend = $backend;
        $this->_id = $id;
    }
    
    /**
     * Returns true if and only if storage is empty
     *
     * @throws Zend_Auth_Storage_Exception If it is impossible to determine whether storage is empty
     * @return boolean
     */
    public function isEmpty()
    {
        return !$this->_backend->test($this->_id);
    }

    /**
     * Returns the contents of storage
     *
     * Behavior is undefined when storage is empty.
     *
     * @throws Zend_Auth_Storage_Exception If reading contents from storage is impossible
     * @return mixed
     */
    public function read()
    {
        return $this->_backend->load($this->_id);
    }

    /**
     * Writes $contents to storage
     *
     * @param  mixed $contents
     * @throws Zend_Auth_Storage_Exception If writing $contents to storage is impossible
     * @return void
     */
    public function write($contents)
    {
        $this->_backend->save($contents, $this->_id);
    }

    /**
     * Clears contents from storage
     *
     * @throws Zend_Auth_Storage_Exception If clearing contents from storage is impossible
     * @return void
     */
    public function clear()
    {
        $this->_backend->remove($this->_id);
    }
}
