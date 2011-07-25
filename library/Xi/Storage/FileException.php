<?php
namespace Xi\Storage;

/**
 * @category    Xi
 * @package     Xi_Storage
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class FileException extends \DomainException implements StorageException
{
    /**
     * @param string $path
     * @return FileException
     */
    public static function shouldNotBeADirectory($path)
    {
        $error = sprintf("Path '%s' should not point to a directory", (string) $path);
        return new self($error);
    }
    
    /**
     * @param string $path
     * @return FileException
     */
    public static function shouldBeReadable($path)
    {
        $error = sprintf("File '%s' should be readable", (string) $path);
        return new self($error);
    }
    
    /**
     * @param string $path
     * @return FileException
     */
    public static function shouldBeWriteable($path)
    {
        $error = sprintf("File '%s' should be writeable", (string) $path);
        return new self($error);
    }
}
