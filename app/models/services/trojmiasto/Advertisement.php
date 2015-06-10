<?php

namespace Application\Models\Services\Trojmiasto;

use \Application\Models\Services\AdvertisementAbstract;

/**
 * Description of TrojmiastoAdvertisement
 *
 * @author bnowakowski
 */
class Advertisement extends AdvertisementAbstract
{

	public $source = "trojmiasto";
	
	protected function parse()
	{

		$content = $this->content;
		$estimates = null;
		$success = preg_match('/<div class=\"adv\-body\">(.*?)<\/div>\s*<div id=\"footer\">/s', $content, $estimates);

		if ($success === 1) {
			$this->content = (string) $estimates[1];
		} elseif (!$this->content || strlen($this->content) < 10) {
			throw new \Exception('Empty content on advertisement: ' . $this->url);
		}
		
		
		preg_match('/<h1>(.*?)\s*<[a-z\/]+/si', $this->content, $estimates);
		$this->title = $estimates[1];
		
		preg_match('/dane kontaktowe.*?<strong>(.*?)\s*<[a-z\/]+/si', $this->content, $estimates);
		$this->author = $estimates[1];
		
		preg_match('/Ulica i nr.*?"value">(.*?)\s*<[a-z\/]+/si', $this->content, $estimates);
		$this->adress = $estimates[1];
		
		preg_match('/Liczba pokoi.*?"value">(.*?)\s*<[a-z\/]+/si', $this->content, $estimates);
		$this->rooms = $estimates[1];
		
		preg_match('/Cena:.*?"value">(.*?)\s*<[a-z\/]+/si', $this->content, $estimates);
		$this->pricePerArea = $estimates[1];
		
		preg_match('/Cena za m.*?"value">(.*?)\s*<[a-z\/]+/si', $this->content, $estimates);
		$this->pricePerMeter = $estimates[1];
		
		preg_match('/Powierzchnia.*?"value">(.*?)\s*<[a-z\/]+/si', $this->content, $estimates);
		$this->area = $estimates[1];
		
		
		
		$this->contacts($content);
		
	}

	/**
	 * I personally hate this part.
	 * @param string $content full page content
	 */
	private function contacts($content) {
		
		preg_match("/var adv_sid = '([a-zA-Z0-9]+)';/i", $content, $matches);
		$adv_sid = $matches[1];
		$url = 'http://ogloszenia.trojmiasto.pl/_ajax/ogloszenia/ogl_contact_o.php?sid=' . $adv_sid;
		
		foreach($this->headers as $header) {
			if(strpos($header, 'Set-Cookie: PHPSESSID=') !== false && preg_match('/Set\-Cookie: PHPSESSID=([a-z0-9]+)\;/i', $header, $matches) === 1) {
				$sid = $matches[1];
				break;
			}
		}
		
		preg_match_all('/id="(tel|o)_[a-z0-9]+"\s+value="(.*?)"/i', $this->content, $matches);
		foreach($matches[2] as $match) {
			
			$formData = 'o=' . $match;

			$context = stream_context_create([
				'http' => [
					'method' => 'POST',
					'header' => \Application\Models\Services\headers([
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
			if(strpos($contact, '@') !== false) {
				$this->email = $contact;
			} else {
				$this->phone = $contact;
			}
		}
		
	}
}
