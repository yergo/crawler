<?php

namespace Application\Api\Controllers;

class IndexController extends ControllerBase
{

	public function indexAction()
	{
		$this->_response['message'] = 'Available actions';
		return [];
	}
	
}