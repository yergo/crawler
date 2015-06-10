<?php

namespace Application\Models\Services;

/**
 * Quick util to build up headers wrom array for purpose of contexts
 * @param array $headers
 * @return string
 */
function headers($headers)
{

	$result = '';
	foreach ($headers as $key => $value) {
		$result .= sprintf("%s: %s\r\n", $key, $value);
	}

	return $result;
}

/**
 * Description of TrojmiastoAdvertisementInterface
 *
 * @author bnowakowski
 */
abstract class AdvertisementAbstract
{

	public $url = false;
	public $content = false;

	/**
	 * Initializes object with content or by gathering content from cache/Internet
	 * 
	 * @param string $url
	 * @param string|false $content
	 */
	public function __construct($url, $content = false)
	{

		$this->url = $url;
		$this->headers = false;
		$this->content = $content ? : $this->getContent();

		$this->parse();
	}

	/**
	 * Gets content from Cache/Internet
	 * @return string HTML content of full website
	 */
	private function getContent()
	{

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

		$content = file_get_contents($this->url, false, $context);
		$headers = $http_response_header;

		if (in_array('Content-Encoding: gzip', $headers)) {
			$content = gzinflate(substr($content, 10, -8));
		}

		$this->headers = $headers;

		$enc = mb_detect_encoding($content, ['ISO-8859-2', 'ISO-8859-1', 'latin2', 'UTF-8']);
		if($enc !== 'UTF-8') {
			if($enc !== false) {
				$content = iconv($enc, 'UTF-8', $content);
			} else {
				throw new Exception('Encoding not detected: ' . $this->url);
			}
		}
		
		return $content;
	}

	/**
	 * Parses content to an fullfill object representation of Advertisement
	 */
	abstract protected function parse();
}
