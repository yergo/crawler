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

	public $sourceName = "olx";

	protected function parse()
	{
		$start = microtime(true);

		$content = $this->content;
		$estimates = null;
		$success = preg_match('/<div class=\"adv\-body\">(.*?)<\/div>\s*<div id=\"footer\">/s', $content, $estimates);

		if ($success === 1) {
			$this->content = (string) $estimates[1];
		} elseif (!$this->content || strlen($this->content) < 10) {
			throw new \Exception('Empty content on advertisement: ' . $this->url);
		}


		if (preg_match('/ogl([0-9]+)\.htm/si', $this->url, $estimates) == 1) {
			$this->sourceId = $estimates[1];
		}

		if (preg_match('/<h1>(.*?)\s*<[a-z\/]+/si', $this->content, $estimates) == 1) {
			$this->title = $estimates[1];
		}

		if (preg_match('/dane kontaktowe.*?<strong>(.*?)\s*<\/strong/si', $this->content, $estimates) == 1) {
			$this->author = trim(strip_tags($estimates[1]));
		}
		
		if (preg_match('/Ulica i nr.*?"value">(.*?)\s*<[a-z\/]+/si', $this->content, $estimates) == 1) {
			$this->address = $estimates[1];
		}

		if (preg_match('/dzielnica.*?"value">(.*?)<\/[a-z]+/si', $this->content, $estimates) == 1) {
			$this->district = trim(strip_tags($estimates[1]));
		}

		if (preg_match('/Liczba pokoi.*?"value">(.*?)\s*<[a-z\/]+/si', $this->content, $estimates) == 1) {
			$this->rooms = intval($estimates[1]);
		}

		if (preg_match('/Cena:.*?"value">(.*?)\s*<[a-z\/]+/si', $this->content, $estimates) == 1) {
			$estimate = preg_replace(['/[a-z\s]+/i','/,/'], ['', '.'], $estimates[1]);
			$this->pricePerArea = floatval($estimate);
		}

		if (preg_match('/Cena za m.*?"value">(.*?)\s*<[a-z\/]+/si', $this->content, $estimates) == 1) {
			$estimate = preg_replace(['/[a-z\s]+/i','/,/'], ['', '.'], $estimates[1]);
			$this->pricePerMeter = floatval($estimate);
		}

		if (preg_match('/Powierzchnia.*?"value">(.*?)\s*<[a-z\/]+/si', $this->content, $estimates) == 1) {
			$estimate = preg_replace(['/[a-z\s]+/i','/,/'], ['', '.'], $estimates[1]);
			$this->area = floatval($estimate);
		}
		
		if (preg_match('/ogłoszenie wprowadzono:\s+([0-9\-\s\:]+)\s/si', $this->content, $estimates) == 1) {
			$this->added = trim($estimates[1]);
		}
		
		if (preg_match('/ostatnia aktualizacja:\s+(?:&nbsp;)*([0-9\-\s\:]+)\s/si', $this->content, $estimates) == 1) {
			$this->updated = trim($estimates[1]);
		}

		switch (1) {
			case preg_match('/pośrednictwo/i', $this->content):
			case preg_match('/agencja nieruchomości/i', $this->content):
			case preg_match('/Nr licencji/i', $this->content):
			case preg_match('/asariWeb/i', $this->content):
			case preg_match('/prowizja 0%/i', $this->content):
				$this->middleman = true;
				break;
		}

		if (preg_match('/Nie interesują mnie oferty biur nieruchomości/i', $this->content) == 1) {
			$this->middleman = false;
		}


		$this->contacts($content);

//		$this->timeParsing = microtime(true) - $start;
	}

	/**
	 * I personally hate this part.
	 * @param string $content full page content
	 */
	private function contacts($content)
	{

		preg_match("/var adv_sid = '([a-zA-Z0-9]+)';/i", $content, $matches);
		$adv_sid = $matches[1];
		$url = 'http://ogloszenia.trojmiasto.pl/_ajax/ogloszenia/ogl_contact_o.php?sid=' . $adv_sid;

		foreach ($this->headers as $header) {
			if (strpos($header, 'Set-Cookie: PHPSESSID=') !== false && preg_match('/Set\-Cookie: PHPSESSID=([a-z0-9]+)\;/i', $header, $matches) === 1) {
				$sid = $matches[1];
				break;
			}
		}

		preg_match_all('/id="(tel|o)_[a-z0-9]+"\s+value="(.*?)"/i', $this->content, $matches);
		foreach ($matches[2] as $match) {

			$formData = 'o=' . $match;

			$context = stream_context_create([
				'http' => [
					'method' => 'POST',
					'header' => headers([
						'Accept' => '*/*',
						'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.81 Safari/537.36',
						'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
						'Content-Length' => strlen($formData),
						'Accept-Encoding' => 'gzip, deflate',
						'Accept-Language' => 'pl-PL,pl;q=0.8,en-US;q=0.6,en;q=0.4',
						'Cookie' => 'PHPSESSID=' . $sid . '; mobile_device=0'
					]),
					'content' => $formData
				]
			]);

			$contact = $this->getContent($url, $context)['content'];
			$contact = explode(',', $contact)[0];
			
			if (strpos($contact, '@') !== false) {
				$this->email = $contact;
			} else {
				$phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

				try {
					$phoneProto = $phoneUtil->parse($contact, 'PL');
				} catch(\Exception $e) {
					$this->phone = null;
					return;
				}
				
				$this->phone = $phoneUtil->format($phoneProto, \libphonenumber\PhoneNumberFormat::INTERNATIONAL);
			}
		}
	}

}
