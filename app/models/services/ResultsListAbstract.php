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
	public $source_name = "trojmiasto";
	public $pages = 1;

	protected $headers = false;
	protected $content = false;

	/**
	 * Initializes object with content or by gathering content from cache/Internet
	 * 
	 * @param string $url
	 * @param string|false $content
	 */
	public function __construct($url, $content = false, $context = false)
	{

		$this->url = $url;
		$this->headers = false;

		if (!$content) {
			$response = $this->getContent($url, $context);

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

		$context = $context ? : $this->get_context();

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
	 * Creates stream context for HTTP requests.
	 */
	abstract protected function get_context();

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
