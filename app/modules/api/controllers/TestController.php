<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Api\Controllers;

/**
 * Description of TestController
 *
 * @author bnowakowski
 */
class TestController extends ControllerBase
{
	
	public function olxAction() {
		
		$file = 'http://olx.pl/ajax/gdansk/search/list/';
		
		$list = new \Application\Models\Services\Olx\ResultsList($file);
	
		$result = [];
		foreach($list as $el) {
			
			
			foreach($el->urls as $url) {
				$adv =  new \Application\Models\Services\Olx\Advertisement($url);
				$result[] = $adv->toArray();
			}
			
			break;
		}
		
		return $result;
		
	}
	
	public function trojmiastoAction() {
		
		$file = 'http://ogloszenia.trojmiasto.pl/nieruchomosci-sprzedam/?formSended=1&formSendedFrom=advSearchLeft&searchFormSended=1&id_kat=101&katlist=1&id_kat_list%5B101%5D=101&cena_min=&cena_max=&rodzaj_nieruchomosci=100&cenam2_min=&cenam2_max=&powierzchnia_min=&powierzchnia_max=&adres_ulica_i_nr=&districtListWhatSelected=wybranych%3A+17&e1i%5B81%5D=81&e1i%5B70%5D=70&e1i%5B58%5D=58&e1i%5B68%5D=68&e1i%5B79%5D=79&e1i%5B119%5D=119&e1i%5B3%5D=3&e1i%5B32%5D=32&e1i%5B1%5D=1&e1i%5B87%5D=87&e1i%5B86%5D=86&e1i%5B2%5D=2&e1i%5B140%5D=140&e1i%5B139%5D=139&e1i%5B7%5D=7&e1i%5B69%5D=69&e1i%5B31%5D=31&f1i%5B0%5D=&e1i=81%7C70%7C58%7C69%7C79%7C3%7C68%7C32%7C1%7C87%7C86%7C119%7C2%7C140%7C139%7C7%7C31&f1i=&l_pokoi_min=2&l_pokoi_max=&pietro_min=&pietro_max=&l_pieter_min=&l_pieter_max=&rok_budowy_min=&rok_budowy_max=&powierzchnia_dzialki_min=&powierzchnia_dzialki_max=&typ_ogrzewania=&slowa_option=all_phrases&slowa=&onlyPrivateOffers=1&obList=&data_wprow=all&order=data_wazne_SMS+DESC%2C+data_wprow+DESC&limit=100&id_kat=&limit=100&id_kat=101';
		
		$list = new \Application\Models\Services\Trojmiasto\ResultsList($file);
		
		foreach($list as $el) {
			
			$url = reset($el->urls);
			
			$adv =  new \Application\Models\Services\Trojmiasto\Advertisement($url);
			return $adv;
		}
		
		return $all;
	}
	
}
