<?php

namespace Application\Models\Services;

/**
 * Description of ResultsListAbstract
 *
 * @author bnowakowski
 */
abstract class ResultsListAbstract
{

	public $urls = [];
	public $url;
	protected $headers = false;
	protected $content = false;

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

		if (!$content) {
			$response = $this->getContent($url);

			$this->headers = $response['headers'];
			$this->content = $response['content'];
		} else {
			$this->content = $content;
		}

		$this->parse();
	}

	/**
	 * Gets content from Cache/Internet
	 * @return string HTML content of full website
	 */
	protected function getContent($url, $context = false)
	{

		$context = $context ? : stream_context_create([
					'http' => [
						'method' => 'GET',
						'header' => headers([
							'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
							'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.81 Safari/537.36',
							'Accept-Encoding' => 'gzip, deflate, sdch',
							'Accept-Language' => 'pl-PL,pl;q=0.8,en-US;q=0.6,en;q=0.4',
						])
					]
		]);

		$content = file_get_contents($url, false, $context);
		$headers = $http_response_header;


		return [
			'headers' => $headers,
			'content' => $this->normalize($headers, $content)
		];
	}

	/**
	 * Initializes object with content or by gathering content from cache/Internet
	 * 
	 * @param string $url
	 * @param string|false $content
	 */
	protected function normalize($headers, $content)
	{

		if (in_array('Content-Encoding: gzip', $headers)) {
			$content = gzinflate(substr($content, 10, -8));
		}

		$enc = mb_detect_encoding($content, ['ISO-8859-2', 'ISO-8859-1', 'latin2', 'auto', 'UTF-8']);

		if ($enc === false) {

			$encHeader = false;
			foreach ($headers as $header) {
				if (strpos($header, 'Content-Type') !== false) {
					$encHeader = $header;
					break;
				}
			}

			preg_match('/charset=(.*)$/s', $encHeader, $matches);
			$enc = $matches[1];
		}

		if ($enc !== 'UTF-8') {
			if ($enc !== false) {
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

	/**
	 * Exports object to array
	 * @return array
	 */
	public function toArray()
	{
		$result = get_object_vars($this);
		unset($result['content'], $result['headers']);

		return $result;
	}

}