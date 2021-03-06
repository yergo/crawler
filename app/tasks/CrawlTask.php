<?php

use Application\Models\Entities\Advertisement as AdvEntity;

class CrawlTask extends \Phalcon\CLI\Task
{

	private static $processes;
	private $start;
	public static $threads = 4;

	public function olxAction()
	{
		
		foreach(\Application\Models\Services\Olx\Advertisement::$districts as $districtId) {
			$this->start = microtime(true);
			$results = new \Application\Models\Services\Olx\ResultsList($districtId);
			$this->controlCluster($results);
			sleep(1);
		}
	}
	
	public function trojmiastoAction()
	{

		$quickie = current($this->dispatcher->getParams()) || false;

		$this->start = microtime(true);

		if (!$quickie) {
			// crawl all
//			$filename = "http://ogloszenia.trojmiasto.pl/nieruchomosci-sprzedam/?searchFormSended=1&id_kat=101&katlist=1&id_kat_list%5B101%5D=101&cena_min=&cena_max=&rodzaj_nieruchomosci=100&cenam2_min=&cenam2_max=&powierzchnia_min=&powierzchnia_max=&adres_ulica_i_nr=&districtListWhatSelected=wybranych%3A+17&f1i=&e1i=81%7C70%7C58%7C69%7C79%7C3%7C68%7C32%7C1%7C87%7C86%7C119%7C2%7C140%7C139%7C7%7C31&l_pokoi_min=2&l_pokoi_max=&pietro_min=&pietro_max=&l_pieter_min=&l_pieter_max=&rok_budowy_min=&rok_budowy_max=&powierzchnia_dzialki_min=&powierzchnia_dzialki_max=&typ_ogrzewania=&slowa_option=all_phrases&slowa=&obList=&data_wprow=all&order=data_wazne_SMS+DESC%2C+data_wprow+DESC&limit=100&limit=100";
//			$filename = "http://ogloszenia.trojmiasto.pl/nieruchomosci-sprzedam/?formSended=1&formSendedFrom=advSearchLeft&searchFormSended=1&id_kat=101&katlist=1&id_kat_list%5B101%5D=101&cena_min=&cena_max=&rodzaj_nieruchomosci=100&cenam2_min=&cenam2_max=&powierzchnia_min=&powierzchnia_max=&adres_ulica_i_nr=&districtListWhatSelected=wybranych%3A+15&f1i%5B0%5D=&e1i=81%7C33%7C70%7C58%7C69%7C68%7C32%7C1%7C87%7C86%7C2%7C140%7C139%7C7%7C31&f1i=&l_pokoi_min=2&l_pokoi_max=&pietro_min=&pietro_max=&l_pieter_min=&l_pieter_max=&rok_budowy_min=&rok_budowy_max=&powierzchnia_dzialki_min=&powierzchnia_dzialki_max=&typ_ogrzewania=&slowa_option=all_phrases&slowa=&obList=&data_wprow=all&order=data_wazne_SMS+DESC%2C+data_wprow+DESC&limit=20&id_kat=&limit=20&id_kat=101";
			$filename = "http://ogloszenia.trojmiasto.pl/nieruchomosci-sprzedam/?formSended=1&formSendedFrom=advSearchLeft&searchFormSended=1&id_kat=101&katlist=1&id_kat_list%5B101%5D=101&cena_min=&cena_max=&rodzaj_nieruchomosci=100&cenam2_min=&cenam2_max=&powierzchnia_min=&powierzchnia_max=&adres_ulica_i_nr=&districtListWhatSelected=wybranych%3A+25&f1i%5B0%5D=&e1i=81%7C37%7C70%7C58%7C69%7C41%7C79%7C3%7C68%7C34%7C32%7C57%7C1%7C36%7C96%7C87%7C5%7C76%7C86%7C119%7C2%7C140%7C139%7C7%7C31&f1i=&l_pokoi_min=&l_pokoi_max=3&pietro_min=&pietro_max=&l_pieter_min=&l_pieter_max=&rok_budowy_min=&rok_budowy_max=&powierzchnia_dzialki_min=&powierzchnia_dzialki_max=&typ_ogrzewania=&slowa_option=all_phrases&slowa=&obList=&data_wprow=all&order=data_wazne_SMS+DESC%2C+data_wprow+DESC&limit=20&id_kat=&limit=20&id_kat=101";
		} else {
			// quick crawl
//			$filename = "http://ogloszenia.trojmiasto.pl/nieruchomosci-sprzedam/?formSended=1&formSendedFrom=advSearchLeft&searchFormSended=1&id_kat=101&katlist=1&id_kat_list%5B101%5D=101&cena_min=&cena_max=&rodzaj_nieruchomosci=100&cenam2_min=&cenam2_max=&powierzchnia_min=&powierzchnia_max=&adres_ulica_i_nr=&districtListWhatSelected=wybranych%3A+17&e1i%5B81%5D=81&e1i%5B70%5D=70&e1i%5B58%5D=58&e1i%5B68%5D=68&e1i%5B79%5D=79&e1i%5B119%5D=119&e1i%5B3%5D=3&e1i%5B32%5D=32&e1i%5B1%5D=1&e1i%5B87%5D=87&e1i%5B86%5D=86&e1i%5B2%5D=2&e1i%5B140%5D=140&e1i%5B139%5D=139&e1i%5B7%5D=7&e1i%5B69%5D=69&e1i%5B31%5D=31&f1i%5B0%5D=&e1i=81%7C70%7C58%7C69%7C79%7C3%7C68%7C32%7C1%7C87%7C86%7C119%7C2%7C140%7C139%7C7%7C31&f1i=&l_pokoi_min=2&l_pokoi_max=&pietro_min=&pietro_max=&l_pieter_min=&l_pieter_max=&rok_budowy_min=&rok_budowy_max=&powierzchnia_dzialki_min=&powierzchnia_dzialki_max=&typ_ogrzewania=&slowa_option=all_phrases&slowa=&onlyPrivateOffers=1&obList=&data_wprow=all&order=data_wazne_SMS+DESC%2C+data_wprow+DESC&limit=100&id_kat=&limit=100&id_kat=101";
//			$filename = "http://ogloszenia.trojmiasto.pl/nieruchomosci-sprzedam/?formSended=1&formSendedFrom=advSearchLeft&searchFormSended=1&id_kat=101&katlist=1&id_kat_list%5B101%5D=101&cena_min=&cena_max=&rodzaj_nieruchomosci=100&cenam2_min=&cenam2_max=&powierzchnia_min=&powierzchnia_max=&adres_ulica_i_nr=&districtListWhatSelected=wybranych%3A+15&f1i%5B0%5D=&e1i=81%7C33%7C70%7C58%7C69%7C68%7C32%7C1%7C87%7C86%7C2%7C140%7C139%7C7%7C31&f1i=&l_pokoi_min=2&l_pokoi_max=&pietro_min=&pietro_max=&l_pieter_min=&l_pieter_max=&rok_budowy_min=&rok_budowy_max=&powierzchnia_dzialki_min=&powierzchnia_dzialki_max=&typ_ogrzewania=&slowa_option=all_phrases&slowa=&onlyPrivateOffers=1&obList=&data_wprow=all&order=data_wazne_SMS+DESC%2C+data_wprow+DESC&limit=20&id_kat=&limit=20&id_kat=101";
			$filename = "http://ogloszenia.trojmiasto.pl/nieruchomosci-sprzedam/?formSended=1&formSendedFrom=advSearchLeft&searchFormSended=1&id_kat=101&katlist=1&id_kat_list%5B101%5D=101&cena_min=&cena_max=&rodzaj_nieruchomosci=100&cenam2_min=&cenam2_max=&powierzchnia_min=&powierzchnia_max=&adres_ulica_i_nr=&districtListWhatSelected=wybranych%3A+25&f1i%5B0%5D=&e1i=81%7C37%7C70%7C58%7C69%7C41%7C79%7C3%7C68%7C34%7C32%7C57%7C1%7C36%7C96%7C87%7C5%7C76%7C86%7C119%7C2%7C140%7C139%7C7%7C31&f1i=&l_pokoi_min=&l_pokoi_max=3&pietro_min=&pietro_max=&l_pieter_min=&l_pieter_max=&rok_budowy_min=&rok_budowy_max=&powierzchnia_dzialki_min=&powierzchnia_dzialki_max=&typ_ogrzewania=&slowa_option=all_phrases&slowa=&onlyPrivateOffers=1&obList=&data_wprow=added3DaysAgo&order=data_wazne_SMS+DESC%2C+data_wprow+DESC&limit=20&id_kat=&limit=20&id_kat=101";
		}

		$results = new \Application\Models\Services\Trojmiasto\ResultsList($filename);
		
		$this->controlCluster($results);
	}
	
	public function controlCluster($results) {
		
		print(date('Y-m-d H:i:s') . ': job started..' . PHP_EOL);

		foreach ($results as $resultList) {
			print('Downloaded page ' . $resultList->page . ': ');

			$existent = AdvEntity::find([
						'columns' => 'source_id',
						'conditions' => 'source_name = "' . $resultList->source_name . '" AND source_id IN("' . join('","', array_keys($resultList->urls)) . '")'
					])->toArray();

			$ids = [];
			foreach ($existent as $values) {
				$ids[] = $values['source_id'];
			}
			
			print(' (-' . count($ids) . ') ');

			$processes = [];
			foreach ($resultList->urls as $id => $url) {

				if (in_array($id, $ids)) {
					print('-');
					continue;
				}

				$input = [
					'source_name' => $resultList->source_name,
					'source_id' => $id,
					'url' => $url,
					'district' => property_exists($results, 'district') ? $results->district : null
				];

				$input = json_encode($input);

				while (count($processes) >= self::$threads) {
					$this->killProcesses($processes, false);
					sleep(1);
				}

				array_push($processes, $this->proceedContent($input));
			}

			$this->killProcesses($processes);

			sleep(1);
			print(PHP_EOL);
		}

		print(PHP_EOL . 'Done in ' . (microtime(true) - $this->start) . 's' . PHP_EOL);

	}

	public function clusterAction()
	{
		$params = json_decode(file_get_contents('php://stdin'));
		
		$done = false;
		while (!$done) {
			try {
				usleep(mt_rand(1000000, 4000000)); // 1000000 == 1s
				switch($params->source_name) {
					case 'trojmiasto':
						$advertisement = new \Application\Models\Services\Trojmiasto\Advertisement($params->url);
						break;
					case 'olx':
						$advertisement = new \Application\Models\Services\Olx\Advertisement($params->url);
						$advertisement->district = array_flip(\Application\Models\Services\Olx\Advertisement::$districts)[$params->district];
						break;
				}
		
				$ent = $advertisement->getEntity();
				$done = true;
			} catch (\Exception $e) {
				print('!');
				var_dump($e->getMessage());
				$done = false;
				sleep(5);
			}
		}
		
		if(!$ent->getPhone()) {
			print('*');
		} else if ($ent->getPhone() && !$ent->save()) {
			print('x');
			print('Save failed from from url ' . $params->url . PHP_EOL);
			var_dump($ent->getMessages());
		} else {
			print('+');
		}

		exit(0);
	}

	private function proceedContent($input)
	{
		$descriptors = [
			0 => array("pipe", "r"), // stdin is a pipe that the child will read from
			2 => array("file", "error-output.txt", "a") // stderr is a file to write to
		];

		$process = proc_open('./console crawl cluster', $descriptors, $pipes);

		if (is_resource($process)) {
			fwrite($pipes[0], $input);
			fclose($pipes[0]);
		}

		return $process;
	}

	private function killProcesses(&$processes, $infinite = true)
	{
		$killed = 0;

		while (count($processes) > 0) {
			$unset = [];
			foreach ($processes as $k => $process) {
				$status = proc_get_status($process);
				if (!$status['running']) {
					$signal = proc_close($process);
					$killed++;
					$unset[] = $k;
//					print($signal);
				}
			}

			foreach ($unset as $k) {
				unset($processes[$k]);
			}

			$processes = array_filter($processes);

			if (!$infinite) {
				break;
			}
		}
	}

}
