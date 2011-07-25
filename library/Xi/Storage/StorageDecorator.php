<?php
namespace Xi\Storage;

/**
 * Abstract base class for decorating other Storage instances
 * 
 * @category    Xi
 * @package     Xi_Storage
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class StorageDecorator implements Storage
{
    /**
     * @var Storage
     */
    private $storage;
    
    /**
     * @param Storage $storage
     */
    public function __construct($storage)
    {
        $this->storage = $storage;
    }
    
    public function clear()
    {
        return $this->storage->clear();
    }
    
    public function isEmpty()
    {
        return $this->storage->isEmpty();
    }
    
    public function read()
    {
        return $this->storage->read();
    }
    
    public function write($contents)
    {
        return $this->storage->write($contents);
    }
}