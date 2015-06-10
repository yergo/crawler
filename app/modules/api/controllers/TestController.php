<?php

namespace Application\Api\Controllers;

class TestController extends ControllerBase
{

	public function trojmiastoAction()
	{
		$this->_response['message'] = 'Trojmiasto action';

		$filename = "http://ogloszenia.trojmiasto.pl/nieruchomosci-sprzedam/mieszkanie-gdansk-wrzeszcz-gorny-475000-zl-ogl10010125.html";

		$advertisement = new \Application\Models\Services\Trojmiasto\Advertisement($filename);

		return $advertisement;
	}

}
