<?php
namespace Xi\Zend\View\Helper;

use Zend_View_Helper_Abstract;

/**
 * Creates ingresses of specified lengths from text. Tries to preserve
 * sentences and words, if possible.
 */
class Ingress extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    protected $_ellipsis = '...';
    
    /**
     * @var string
     */
    protected $_endSentence = '. ';
    
    /**
     * @var string
     */
    protected $_endWord = ' ';
    
    /**
     * Cut the given text to between $min and $max (multibyte) characters.
     * 
     * Tries to preserve sentences and words, if possible.
     *
     * @param string $text
     * @param int $min
     * @param int $max
     * @return string
     * @throws InvalidArgumentException
     */
    public function ingress($text, $min, $max)
    {
        if ($max < 0) {
            throw new InvalidArgumentException('Maximum string length cannot be less than zero');
        }
        
        if (mb_strlen($text) <= $max) {
            return $text;
        }
        
        // try to limit text to the end of a sentence (period and space)
        $newText = $this->_cut($text, $max, $this->_endSentence);
        
        if (!$newText || mb_strlen($newText) < $min) {
            
            // try to limit text to the end of a word (space)
            $newText = $this->_cut($text, $max, $this->_endWord);
            
            if (!$newText || mb_strlen($newText) < $min) {
                
                // brutally cut text
                $newText = mb_substr($text, 0, $max);
            }
        }
        
        $newText .= $this->_ellipsis;
        
        return $newText;
    }
    
    /**
     * Cut $string to a maximum length of $maxLength,
     * and cut to the first occurrence of $cutString on the right.
     *
     * @param string $string
     * @param int $maxLength
     * @param string $cutString
     * @return string
     */
    protected function _cut($string, $maxLength, $cutString)
    {
    	$string = mb_substr($string, 0, $maxLength);
    	$pos = mb_strrpos($string, $cutString);
        if ($pos === false) {
            return null;
        }

        return mb_substr($string, 0, $pos);
    }
}
