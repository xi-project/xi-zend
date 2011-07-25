<?php
namespace Xi\Storage\Zend;
use Xi\Storage\StorageDecorator;

/**
 * Provides opt-in type compatibility with Zend_Auth_Storage_Interface, based on
 * which the Storage interface is modelled.
 * 
 * FIXME: Generated exceptions are not guaranteed to be type-compatible.
 * 
 * @category    Xi
 * @package     Xi_Storage
 * @subpackage  Xi_Storage_Zend
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class AuthStorageAdapter extends StorageDecorator implements Zend_Auth_Storage_Interface
{
}