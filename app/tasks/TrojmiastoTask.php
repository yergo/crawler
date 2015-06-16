<?php

use Application\Models\Entities\Advertisement as AdvEntity;

class TrojmiastoTask extends \Phalcon\CLI\Task
{

	private static $processes;
	private $start;
	public static $threads = 4;

	public function mainAction()
	{

		$this->start = microtime(true);

		// all
		$filename = "http://ogloszenia.trojmiasto.pl/nieruchomosci-sprzedam/?searchFormSended=1&id_kat=101&katlist=1&id_kat_list%5B101%5D=101&cena_min=&cena_max=&rodzaj_nieruchomosci=100&cenam2_min=&cenam2_max=&powierzchnia_min=&powierzchnia_max=&adres_ulica_i_nr=&districtListWhatSelected=wybranych%3A+17&f1i=&e1i=81%7C70%7C58%7C69%7C79%7C3%7C68%7C32%7C1%7C87%7C86%7C119%7C2%7C140%7C139%7C7%7C31&l_pokoi_min=2&l_pokoi_max=&pietro_min=&pietro_max=&l_pieter_min=&l_pieter_max=&rok_budowy_min=&rok_budowy_max=&powierzchnia_dzialki_min=&powierzchnia_dzialki_max=&typ_ogrzewania=&slowa_option=all_phrases&slowa=&obList=&data_wprow=all&order=data_wazne_SMS+DESC%2C+data_wprow+DESC&limit=100&limit=100";
		
		// bez pośredników
//		$filename = "http://ogloszenia.trojmiasto.pl/nieruchomosci-sprzedam/?formSended=1&formSendedFrom=advSearchLeft&searchFormSended=1&id_kat=101&katlist=1&id_kat_list%5B101%5D=101&cena_min=&cena_max=&rodzaj_nieruchomosci=100&cenam2_min=&cenam2_max=&powierzchnia_min=&powierzchnia_max=&adres_ulica_i_nr=&districtListWhatSelected=wybranych%3A+17&e1i%5B81%5D=81&e1i%5B70%5D=70&e1i%5B58%5D=58&e1i%5B68%5D=68&e1i%5B79%5D=79&e1i%5B119%5D=119&e1i%5B3%5D=3&e1i%5B32%5D=32&e1i%5B1%5D=1&e1i%5B87%5D=87&e1i%5B86%5D=86&e1i%5B2%5D=2&e1i%5B140%5D=140&e1i%5B139%5D=139&e1i%5B7%5D=7&e1i%5B69%5D=69&e1i%5B31%5D=31&f1i%5B0%5D=&e1i=81%7C70%7C58%7C69%7C79%7C3%7C68%7C32%7C1%7C87%7C86%7C119%7C2%7C140%7C139%7C7%7C31&f1i=&l_pokoi_min=2&l_pokoi_max=&pietro_min=&pietro_max=&l_pieter_min=&l_pieter_max=&rok_budowy_min=&rok_budowy_max=&powierzchnia_dzialki_min=&powierzchnia_dzialki_max=&typ_ogrzewania=&slowa_option=all_phrases&slowa=&onlyPrivateOffers=1&obList=&data_wprow=all&order=data_wazne_SMS+DESC%2C+data_wprow+DESC&limit=100&id_kat=&limit=100&id_kat=101";
		
		$fileStartName = $filename;
		$page = 0;
		$pages = 0;
		
		/**
		 * @todo iterator
		 */
		while ($page <= $pages) {
			$advertisements = new \Application\Models\Services\Trojmiasto\ResultsList($filename);
			print('Downloaded...' . PHP_EOL);
			
			$pages = $advertisements->pages;
			
			$existent = AdvEntity::find([
				'columns' => 'source_id',
				'conditions' => 'source_name = "' . $advertisements->source_name . '" AND source_id IN(' . join(',', array_keys($advertisements->urls)) . ')'
			])->toArray();
			
			$ids = [];
			foreach($existent as $values) {
				$ids[] = $values['source_id'];
			}
			
			$processes = [];
			foreach ($advertisements->urls as $id => $url) {
				
				if(in_array($id, $ids)) {
					continue;
				}

				$input = [
					'source_name' => $advertisements->source_name,
					'source_id' => $id,
					'url' => $url
				];

				$input = json_encode($input);

				while(count($processes) >= self::$threads) {
					$this->killProcesses($processes, false);
					sleep(1);
				}

				array_push($processes, $this->proceedContent($input));

			}

			$this->killProcesses($processes);
			$page++;
			$filename = $fileStartName . '&cPage=' . $page;
			
			print(PHP_EOL . 'Starting page ' . $page . ' of ' . $pages . PHP_EOL);
			sleep(1);
		}
		
		print(PHP_EOL . 'Done in ' . (microtime(true)-$this->start) . 's' . PHP_EOL);
	}

	public function clusterAction()
	{
		$params = json_decode(file_get_contents('php://stdin'));
		
		$ent = false; // AdvEntity::findFirst('source_name = "' . $params->source_name . '" AND source_id = "' . $params->source_id . '"');
		
		if(!$ent) {
			
			$done = false;
			while(!$done) {
				try {
					usleep(mt_rand(1000000, 4000000)); // 1000000 == 1s
//					print('?');
					$advertisement = new \Application\Models\Services\Trojmiasto\Advertisement($params->url);
					$ent = $advertisement->getEntity();
					$done = true;
//					print('*');
				} catch(\Exception $e) {
					print('!');
					var_dump($e->getMessage());
					$done = false;
					sleep(5);
				}
			}
			
			if($ent->getPhone() && !$ent->save()) {
				print('Save failed from from url ' . $params->url . PHP_EOL);
				var_dump($ent->getMessages());
			}
		}
		exit(0);
	}

	private function proceedContent($input)
	{
		$descriptors = [
				0 => array("pipe", "r"), // stdin is a pipe that the child will read from
				2 => array("file", "error-output.txt", "a") // stderr is a file to write to
			];
		
		$process = proc_open('./console trojmiasto cluster', $descriptors, $pipes);

		if (is_resource($process)) {
			fwrite($pipes[0], $input);
			fclose($pipes[0]);
		}

		return $process;
	}

	private function killProcesses(&$processes, $infinite = true)
	{
		$killed = 0;
		
		while(count($processes) > 0) {
			$unset = [];
			foreach ($processes as $k => $process) {
				$status = proc_get_status($process);
				if(!$status['running']) {
					proc_close($process);
					$killed++;
					$unset[] = $k;
					print('.');
				}
			}
			
			foreach($unset as $k) {
				unset($processes[$k]);
			}
			
			$processes = array_filter($processes);
			
			if(!$infinite) {
				break;
			}
		}
		
	}

}
