<?php

namespace Application\Api\Controllers;

class TestController extends ControllerBase
{

	public function trojmiastoAction()
	{
		$this->_response['message'] = 'Trojmiasto action';

		$filename = "http://ogloszenia.trojmiasto.pl/nieruchomosci-sprzedam/klimatyczne-mieszkanie-w-samym-centrum-wrzeszcza-ogl10007065.html";

		$advertisement = new \Application\Models\Services\Trojmiasto\Advertisement($filename);

		return $advertisement;
	}

}
