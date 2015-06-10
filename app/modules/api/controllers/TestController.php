<?php

namespace Application\Api\Controllers;

class TestController extends ControllerBase
{

	public function trojmiastoAction()
	{
		$this->_response['message'] = 'Trojmiasto action';

		$filename = "http://ogloszenia.trojmiasto.pl/nieruchomosci-sprzedam/dzialka-na-kaszubach-dzialka-budowlana-nowa-karczma-uzbrojona-prad-woda-warunki-zabudowy-ogl10009856.html";

		$advertisement = new \Application\Models\Services\Trojmiasto\Advertisement($filename);

		return $advertisement;
	}

}
