<?php
namespace Xi\Storage\Zend;
use Xi\Storage\StorageException;

/**
 * @category    Xi
 * @package     Xi_Storage
 * @subpackage  Xi_Storage_Zend
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class NamespaceException extends \InvalidArgumentException implements StorageException
{
    /**
     * @param mixed $name
     * @return NamespaceException
     */
    public static function invalidPropertyName($name)
    {
        $type = is_object($name) ? get_class($name) : gettype($name);
        $error = sprintf("Expecting a non-null scalar with a positive string length, received parameter of type '%s'", $type);
        return new self($error);
    }
}