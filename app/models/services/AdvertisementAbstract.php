<?php

namespace Application\Models\Services;

/**
 * Description of TrojmiastoAdvertisementInterface
 *
 * @author bnowakowski
 */
abstract class AdvertisementAbstract {
	
	protected $url = false;
	protected $htmlContent = false;
	
//	protected $title;
	
	public function __construct($url, $content = false) {
		
		$this->url = $url;
		$this->headers = false;
		$this->content = $content ?: $this->getContent();
		
		$this->parse();
	}
	
	private function getContent() {
		
		$filename = "http://ogloszenia.trojmiasto.pl/nieruchomosci-sprzedam/dzialka-na-kaszubach-dzialka-budowlana-nowa-karczma-uzbrojona-prad-woda-warunki-zabudowy-ogl10009856.html";
		$context = stream_context_create([
			'http' => [
				'method' => 'GET',
				'header' => headers([
					'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
					'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.81 Safari/537.36',
					'Referer' => 'http://ogloszenia.trojmiasto.pl/nieruchomosci-sprzedam/',
					'Accept-Encoding' => 'gzip, deflate, sdch',
					'Accept-Language' => 'pl-PL,pl;q=0.8,en-US;q=0.6,en;q=0.4',
				])
			]
		]);
		
		$content = file_get_contents($filename, false, $context);
		$headers = $http_response_header;
		
		if(in_array('Content-Encoding: gzip', $headers)) {
			$content = gzinflate( substr($content, 10, -8) );
		}
		
		$this->headers = $headers;
		
		return $content;
	}
	
	private function parse();
	
}
