<?php

namespace Application\Models\Services\Olx;

use \Application\Models\Services\AdvertisementAbstract;

/**
 * Description of TrojmiastoAdvertisement
 *
 * @author bnowakowski
 */
class Advertisement extends AdvertisementAbstract
{

	public $sourceName = "gratka";
	
	public static $districts = [
		'Wrzeszcz' => '99',
		'Oliwa' => '109',
		'Przymorze Małe' => '115',
		'Przymorze Wielkie' => '119',
		'Żabianka' => '111',
	];

	protected function parse()
	{
		$start = microtime(true);

		$content = $this->content;
		$estimates = null;
		$success = preg_match('/<div class=\"clr offerbody\">(.*?)<\/div>\s*<div id=\"mapcontainer\"/s', $content, $estimates);

		if ($success === 1) {
			$this->content = (string) $estimates[1];
		} elseif (!$this->content || strlen($this->content) < 10) {
			throw new \Exception('Empty content on advertisement: ' . $this->url);
		}

//		var_dump($this->headers);
//		die();		

		if (preg_match('/CID3\-ID([A-Z0-9]+)\.htm/si', $this->url, $estimates) == 1) {
			$this->sourceId = $estimates[1];
		}

		if (preg_match('/<h1 .*?>(.*?)\s*<\/h1/si', $this->content, $estimates) == 1) {
			$this->title = trim($estimates[1]);
		}
		
		if (preg_match('/<p class="userdetails.*?<span class="block [a-z0-5\-\s]+">(.*?)<\/span>/si', $this->content, $estimates) == 1) {
			$this->author = trim(strip_tags($estimates[1]));
		}
		
//		if (preg_match('/Ulica i nr.*?"value">(.*?)\s*<[a-z\/]+/si', $this->content, $estimates) == 1) {
//			$this->address = $estimates[1];
//		}
		
//		if (preg_match('/<span class="show\-map\-link.*?<strong class=".*?">(.*?)<\/strong/si', $this->content, $estimates) == 1) {
//			$estimates = explode(',',$estimates[1]);
//			if( count($estimates) == 3) {
//				$this->district = trim(end($estimates));
//			} else {
//				$this->district = 'Wrzeszcz +5km';
//			}
//		}
		
		if (preg_match('/Liczba pokoi.*? title="([1-4]+) /si', $this->content, $estimates) == 1) {
			$this->rooms = intval($estimates[1]);
		}

		// <strong class="xxxx-large margintop7 block not-arranged">255 000 zł</strong>
		if (preg_match('/">([\s0-9]+) zł<\/strong/si', $this->content, $estimates) == 1) {
			$estimate = preg_replace(['/[a-z\s]+/i','/,/'], ['', '.'], $estimates[1]);
			$this->pricePerArea = floatval($estimate);
		}

		if (preg_match('/([0-9\.\s]+)zł\/.*?<\/strong/si', $this->content, $estimates) == 1) {
			$estimate = preg_replace(['/[a-z\s]+/i','/,/'], ['', '.'], $estimates[1]);
			$this->pricePerMeter = floatval($estimate);
		}

		if (preg_match('/([0-9\.\,]+) m<sup>2<\/sup>/si', $this->content, $estimates) == 1) {
			$estimate = preg_replace(['/[a-z\s]+/i','/,/'], ['', '.'], $estimates[1]);
			$this->area = floatval($estimate);
		}
		
		if (preg_match('/Dodane \s*o ([0-9:\,\.a-z\s]+)/si', $this->content, $estimates) == 1) {
			$estimate = trim($estimates[1], "\, ");
			$this->added = $this->date($estimate);
		}
		
//		if (preg_match('/ostatnia aktualizacja:\s+(?:&nbsp;)*([0-9\-\s\:]+)\s/si', $this->content, $estimates) == 1) {
//			$this->updated = trim($estimates[1]);
//		}

		$this->middleman = true;
		switch (1) {
			case preg_match('/Osoby prywatnej/i', $this->content):
				$this->middleman = false;
				break;
		}


		$this->contacts();

//		$this->timeParsing = microtime(true) - $start;
	}

	/**
	 * I personally hate this part.
	 * @param string $content full page content
	 */
	private function contacts()
	{
		$resp = @file_get_contents('http://olx.pl/ajax/misc/contact/phone/' . $this->sourceId . '/');
		
		$var = json_decode($resp, JSON_OBJECT_AS_ARRAY);
		if($var && $var['value']) {
			$this->phone = $var['value'];
		} else {
			$this->phone = null;
		}
		
	}
	
	protected function date($string) {
		
		$string = strtolower($string);
		
		$dates = [
			'styczeń' => '-01-',
			'stycznia' => '-01-',
			'luty' => '-02-',
			'lutego' => '-02-',
			'marzec' => '-03-',
			'marca' => '-03-',
			'kwiecień' => '-04-',
			'kwietnia' => '-04-',
			'maj' => '-05-',
			'maja' => '-05-',
			'czerwiec' => '-06-',
			'czerwca' => '-06-',
			'lipiec' => '-07-',
			'lipca' => '-07-',
			'sierpień' => '-08-',
			'sierpnia' => '-08-',
			'wrzesień' => '-09-',
			'wrzesienia' => '-09-',
			'październik' => '-10-',
			'października' => '-10-',
			'listopad' => '-11-',
			'listopada' => '-11-',
			'grudzień' => '-12-',
			'grudnia' => '-12-',
		];
		
		$string = str_replace(array_keys($dates), array_values($dates), $string);
		$string = str_replace([',','.'], '', $string);
		$string = str_replace([' -','- '], '-', $string);
		
		return date('Y-m-d H:i:s', strtotime($string));
	}

}
