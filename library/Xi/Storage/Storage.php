<?php
namespace Xi\Storage;

/**
 * Storage interface modelled directly after Zend_Auth_Storage_Interface. For
 * type compatibility with the original interface, wrap a concrete Storage
 * instance into a Xi\Storage\Zend\AuthStorageAdapter.
 * 
 * @category    Xi
 * @package     Xi_Storage
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
interface Storage
{
    /**
     * Returns true if and only if storage is empty
     *
     * @throws StorageException If it is impossible to determine whether storage is empty
     * @return boolean
     */
    public function isEmpty();

    /**
     * Returns the contents of storage
     *
     * Behavior is undefined when storage is empty.
     *
     * @throws StorageException If reading contents from storage is impossible
     * @return mixed
     */
    public function read();

    /**
     * Writes $contents to storage
     *
     * @param  mixed $contents
     * @throws StorageException If writing $contents to storage is impossible
     * @return void
     */
    public function write($contents);

    /**
     * Clears contents from storage
     *
     * @throws StorageException If clearing contents from storage is impossible
     * @return void
     */
    public function clear();
}
