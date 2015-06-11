<?php

namespace Application\Api\Controllers;

class TestController extends ControllerBase
{

	public function trojmiastoAction()
	{
		$this->_response['message'] = 'Trojmiasto action';

		$count = 0;
		
		$filename = "http://ogloszenia.trojmiasto.pl/nieruchomosci-sprzedam/?formSended=1&formSendedFrom=advSearchLeft&searchFormSended=1&id_kat=101&katlist=1&id_kat_list%5B101%5D=101&cena_min=&cena_max=&rodzaj_nieruchomosci=100&cenam2_min=&cenam2_max=&powierzchnia_min=35&powierzchnia_max=&adres_ulica_i_nr=&districtListWhatSelected=Gda%F1sk+Wrzeszcz+G%F3rny&f1i%5B0%5D=&e1i=139&f1i=&l_pokoi_min=&l_pokoi_max=&pietro_min=&pietro_max=&l_pieter_min=&l_pieter_max=&rok_budowy_min=&rok_budowy_max=&powierzchnia_dzialki_min=&powierzchnia_dzialki_max=&typ_ogrzewania=&slowa_option=all_phrases&slowa=&obList=&data_wprow=all&order=data_wazne_SMS+DESC%2C+data_wprow+DESC&limit=100&id_kat=&limit=100&id_kat=101";
		$advertisements = new \Application\Models\Services\Trojmiasto\ResultsList($filename);
		
		$results = [];
		
//		var_dump($advertisements);
		
		foreach($advertisements->urls as $url) {
			$advertisement = new \Application\Models\Services\Trojmiasto\Advertisement($url);
			$results[$url] = $advertisement->toArray();
			
			if(++$count > 10) break;
		}
		
		return $results;
	}

}
