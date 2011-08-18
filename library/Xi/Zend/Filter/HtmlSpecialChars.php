<?php
namespace Xi\Zend\Filter;

/**
 * Converts special characters to HTML entities
 */
class HtmlSpecialChars implements Zend_Filter_Interface
{
    /**
     * Charset
     *
     * @var string
     */
    protected $charset = 'UTF-8';

    /**
     * Quote style
     *
     * @var integer
     */
    protected $quoteStyle = ENT_NOQUOTES;

    /**
     * Double encode
     *
     * @var boolean
     */
    protected $doubleEncode = true;


    /**
     * Constructor
     *
     * @param  array $options
     * @return void
     */
    public function __construct(array $options = array())
    {
        if (!empty($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * Set options
     *
     * @param  array                               $options
     * @return HtmlSpecialChars
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        return $this;
    }

    /**
     * Get charset
     *
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Set charset
     *
     * @param  string                              $charset
     * @return HtmlSpecialChars
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * Get quote style
     *
     * @return integer
     */
    public function getQuoteStyle()
    {
        return $this->quoteStyle;
    }

    /**
     * Set quote style
     *
     * @param  integer                             $quoteStyle
     * @return HtmlSpecialChars
     */
    public function setQuoteStyle($quoteStyle)
    {
        $this->quoteStyle = $quoteStyle;

        return $this;
    }

    /**
     * Get double encode
     *
     * @return boolean
     */
    public function getDoubleEncode()
    {
        return $this->doubleEncode;
    }

    /**
     * Set double encode
     *
     * @param  boolean                             $doubleEncode
     * @return HtmlSpecialChars
     */
    public function setDoubleEncode($doubleEncode)
    {
        $this->doubleEncode = $doubleEncode;

        return $this;
    }

    /**
     * Defined by Zend_Filter_Interface
     *
     * Convert special characters to HTML entities
     *
     * @param  string $value
     * @return string
     */
    public function filter($value)
    {
        return htmlspecialchars((string) $value, $this->quoteStyle, $this->charset, $this->doubleEncode);
    }
}
