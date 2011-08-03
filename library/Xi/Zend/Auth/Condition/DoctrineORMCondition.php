<?php


/**
 * Xi
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category Xi
 * @package  Zend
 * @license  http://www.opensource.org/licenses/BSD-3-Clause New BSD License
 */

namespace Xi\Zend\Auth\Condition;

/**
 * Authentication adapter condition for Doctrine 2+ ORM
 *
 * @category   Xi
 * @package    Zend
 * @subpackage Auth
 * @author     Mikko Hirvonen <mikko.petteri.hirvonen@gmail.com>
 */
class DoctrineORMCondition
{
    /**
     * @var string
     */
    private $condition;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @param  string               $condition
     * @param  array                $parameters
     * @return DoctrineORMCondition
     */
    public function __construct($condition, array $parameters)
    {
        $this->condition  = $condition;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
