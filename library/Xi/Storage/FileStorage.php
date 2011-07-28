<?php
namespace Xi\Storage;
use \SplFileInfo;

/**
 * @category    Xi
 * @package     Xi_Storage
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class FileStorage implements Storage
{
    /**
     * @var SplFileInfo
     */
    protected $_file;
    
    /**
     * Provide either a file name or an SplFileInfo object referring to a file
     * 
     * @param SplFileInfo|string $file
     * @throws FileException
     */
    public function __construct($file)
    {
        if (!$file instanceof SplFileInfo) {
            $file = new SplFileInfo($file);
        }
        if ($file->isDir()) {
            throw FileException::shouldNotBeADirectory((string) $file);
        }
        $this->_file = $file;
    }
    
    /**
     * @return SplFileInfo
     */
    public function getFile()
    {
        return $this->_file;
    }
    
    /**
     * Check whether file can be read
     * 
     * @return boolean
     */
    public function isEmpty()
    {
        return !$this->getFile()->isReadable();
    }
    
    /**
     * Read contents of file
     *
     * @return mixed
     * @throws FileException
     */
    public function read()
    {
        $file = $this->getFile();
        if (!$file->isReadable()) {
            throw FileException::shouldBeReadable((string) $file);
        }
        return file_get_contents((string) $file);
    }
    
    /**
     * Write contents to file
     * 
     * @param mixed $contents
     * @throws FileException
     */
    public function write($contents)
    {
        $file = $this->getFile();
        if ($file->isFile() && !$file->isWritable()) {
            throw FileException::shouldBeWriteable((string) $file);
        }
        file_put_contents((string) $file, $contents);
    }
    
    /**
     * Delete file
     *
     * @return void
     */
    public function clear()
    {
        unlink((string) $this->getFile());
    }
}
