<?php

namespace Application\Api\Controllers;

/**
 * Quick util to build up headers wrom array for purpose of contexts
 * @param array $headers
 * @return string
 */
function headers($headers) {
	
	$result = '';
	foreach($headers as $key => $value) {
		$result .= sprintf("%s: %s\r\n", $key, $value);
	}
	
	return $result;
}

class TestController extends ControllerBase
{

	public function trojmiastoAction()
	{
		$this->_response['message'] = 'Trojmiasto action';
		
		
		
		return [];
	}

}