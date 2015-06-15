<?php

class TrojmiastoTask extends \Phalcon\CLI\Task
{

	private static $processes;

	public function mainAction()
	{

		// ./console trojmiasto <<< "{test:\"test\"}"


		$filename = "http://ogloszenia.trojmiasto.pl/nieruchomosci-sprzedam/?formSended=1&formSendedFrom=advSearchLeft&searchFormSended=1&id_kat=101&katlist=1&id_kat_list%5B101%5D=101&cena_min=&cena_max=&rodzaj_nieruchomosci=100&cenam2_min=&cenam2_max=&powierzchnia_min=35&powierzchnia_max=&adres_ulica_i_nr=&districtListWhatSelected=Gda%F1sk+Wrzeszcz+G%F3rny&f1i%5B0%5D=&e1i=139&f1i=&l_pokoi_min=&l_pokoi_max=&pietro_min=&pietro_max=&l_pieter_min=&l_pieter_max=&rok_budowy_min=&rok_budowy_max=&powierzchnia_dzialki_min=&powierzchnia_dzialki_max=&typ_ogrzewania=&slowa_option=all_phrases&slowa=&obList=&data_wprow=all&order=data_wazne_SMS+DESC%2C+data_wprow+DESC&limit=100&id_kat=&limit=100&id_kat=101";

		/**
		 * @todo iterator
		 */
		$advertisements = new \Application\Models\Services\Trojmiasto\ResultsList($filename);
		print('Downloaded...' . PHP_EOL);
//		$x = 0;
		

		$processes = [];
		foreach ($advertisements->urls as $id => $url) {

			$input = [
				'source_name' => $advertisements->source_name,
				'source_id' => $id,
				'url' => $url
			];

			$input = json_encode($input);

			$processes[] = $this->proceedContent($input);
			
//			if($x++ > 3) {
//				break;
//			}
		}

		return $this->killProcesses($processes);
	}

	public function clusterAction()
	{
		$json = json_decode(file_get_contents('php://stdin'));
		print('downloading' . PHP_EOL);
		sleep(1);

		print('parsed' . PHP_EOL);

//		var_dump($json);
		print('saved' . PHP_EOL);
		
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

	private function killProcesses($processes)
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
					print('finished ' . $killed . PHP_EOL);
				}
			}
			
			foreach($unset as $k) {
				unset($processes[$k]);
			}
			
			$processes = array_filter($processes);
		}
		
		print('total count ' . $killed);
	}

}
