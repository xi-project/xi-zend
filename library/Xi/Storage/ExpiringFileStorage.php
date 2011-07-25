<?php
namespace Xi\Storage;

/**
 * A file storage with an expiry time
 * 
 * @category    Xi
 * @package     Xi_Storage
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class ExpiringFileStorage extends FileStorage
{
    /**
     * @var int
     */
    protected $_timeToLive;
    
    /**
     * Provide either a file name or an SplFileInfo object referring to a file
     * 
     * @param SplFileInfo|string $file
     * @param int $timeToLive
     * @return void
     */
    public function __construct($file, $timeToLive = 3600)
    {
        parent::__construct($file);
        $this->_timeToLive = $timeToLive;
    }
    
    /**
     * Get time to live in seconds
     * 
     * @return int
     */
    public function getTimeToLive()
    {
        return $this->_timeToLive;
    }
    
    /**
     * Get last modification timestamp
     * 
     * @return int|false
     */
    public function getLastModified()
    {
        return $this->getFile()->getMTime();
    }
    
    /**
     * Check whether file is empty or has timed out
     * 
     * @return boolean
     */
    public function isEmpty()
    {
        return parent::isEmpty() || $this->isTimedOut();
    }
    
    /**
     * Check whether file has timed out
     *
     * @return boolean
     */
    public function isTimedOut()
    {
        return 0 > (time() - $this->getTimeToLive() - $this->getLastModified());
    }
    
    /**
     * Read file contents or return null if file has timed out
     *
     * @return mixed
     */
    public function read()
    {
        if ($this->isTimedOut()) {
            return null;
        }
        return parent::read();
    }
}
