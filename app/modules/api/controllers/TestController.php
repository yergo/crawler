<?php

namespace Application\Api\Controllers;

class TestController extends ControllerBase
{

	public function indexAction()
	{
		$this->_response['message'] = 'Available actions';
		return [
			'trojmiasto'
		];
	}

	public function trojmiastoAction()
	{
		$this->_response['message'] = 'Trojmiasto action';
		
		return [];
	}

}