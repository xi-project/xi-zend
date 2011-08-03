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

namespace Xi\Zend\Auth\Adapter;

use Zend_Auth_Result;

/**
 * Abstract authentication adapter
 *
 * @category   Xi
 * @package    Zend
 * @subpackage Auth
 * @author     Mikko Hirvonen <mikko.petteri.hirvonen@gmail.com>
 */
abstract class AbstractAdapter
{
    /**
     * Create a Zend_Auth_Result for an authentication request.
     *
     * @param  integer          $code
     * @param  string           $result
     * @param  array            $messages
     * @return Zend_Auth_Result
     */
    protected function createResult($code, $result, array $messages = array())
    {
        return new Zend_Auth_Result($code, $result, $messages);
    }
}
