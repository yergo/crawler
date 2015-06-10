<?php

namespace Application\Api\Controllers;

function headers($headers) {
	
	$result = '';
	foreach($headers as $key => $value) {
		$result .= sprintf("%s: %s\r\n", $key, $value);
	}
	
	return $result;
}

class TestController extends ControllerBase
{

	public function trojmiastoAction()
	{
		$this->_response['message'] = 'Trojmiasto action';
		
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
		
		preg_match_all('/<div class=\"adv\-body\">(.*?)<\/div>/s', $content, $estimates);
		
		var_dump($estimates);
		die();
		
		return [
			'headers' => $headers,
			'content_length' => strlen($content)
		];
	}

}