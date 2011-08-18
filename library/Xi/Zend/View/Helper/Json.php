<?php
namespace Xi\Zend\View\Helper;

use Zend_Json;

/**
 * @author     Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Json
{
	/**
	 * Creates a JSON string representation using Zend_Json 
	 * 
	 * @param array|object $data
	 * @return string
	 */
	public function json($data)
	{
		return '(' . Zend_Json::encode($data) . ')';
	}
}