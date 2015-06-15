<?php

namespace Application\Api\Controllers;

use Application\Models\Entities\Advertisement as AdvEntity;

class TestController extends ControllerBase
{

	public function trojmiastoAction()
	{
		$this->_response['message'] = 'Trojmiasto action';

		$count = 0;
		
		$filename = "http://ogloszenia.trojmiasto.pl/nieruchomosci-sprzedam/?searchFormSended=1&id_kat=101&katlist=1&id_kat_list%5B101%5D=101&cena_min=&cena_max=&rodzaj_nieruchomosci=100&cenam2_min=&cenam2_max=&powierzchnia_min=&powierzchnia_max=&adres_ulica_i_nr=&districtListWhatSelected=wybranych%3A+4&f1i=&e1i=32%7C1%7C139%7C7&l_pokoi_min=2&l_pokoi_max=&pietro_min=&pietro_max=&l_pieter_min=&l_pieter_max=&rok_budowy_min=&rok_budowy_max=&powierzchnia_dzialki_min=&powierzchnia_dzialki_max=&typ_ogrzewania=&slowa_option=all_phrases&slowa=&obList=&data_wprow=all&order=data_wazne_SMS+DESC%2C+data_wprow+DESC&limit=100";
		$advertisements = new \Application\Models\Services\Trojmiasto\ResultsList($filename);
		
		
		
		$results = [
			'advertisements' => [],
			'errorneus' => []
		];
		
		foreach($advertisements->urls as $key => $url) {
			
			$ent = AdvEntity::findFirst('source_name = "' . $advertisements->source_name . '" AND source_id = "' . $key . '"');
			if(!$ent) {
				$advertisement = new \Application\Models\Services\Trojmiasto\Advertisement($url);
				$ent = $advertisement->getEntity();
				
//				if(!$ent->save()) {
//					$error = [
//						'advertisement' => $ent->toArray(),
//						'errors' => []
//					];
//					foreach($ent->getMessages() as $message) {
//						$error['errors'][] = $message->getMessage();
//					}
//					$results['errorneus'][] = $error;
//				}

			}
			
			if(++$count > 2) break;
			
			$results['advertisements'][] = $ent->toArray();
		}
		
		return $results;
	}

}
