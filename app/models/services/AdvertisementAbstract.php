<?php

namespace Application\Models\Services;

/**
 * Description of TrojmiastoAdvertisementInterface
 *
 * @author bnowakowski
 */
abstract class AdvertisementAbstract
{

	public $sourceName;
	public $sourceId;
	public $title;
	public $district;
	public $address;
	public $phone;
	public $email;
	public $author;
	public $area;
	public $pricePerArea;
	public $pricePerMeter;
	public $rooms;
	public $middleman = false;
	public $added;
	public $updated;
	public $url = false;
	
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
			
			if(!$this->content) {
				throw new \Exception('Empty content!');
			}
			
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
//					'Referer' => 'http://ogloszenia.trojmiasto.pl/nieruchomosci-sprzedam/',
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
	 * Converts to UTF-8 for JSON
	 * @param array $headers
	 * @param string $content
	 * @return string
	 * @throws Exception
	 */
	protected function normalize($headers, $content)
	{

		if (in_array('Content-Encoding: gzip', $headers)) {
			$content = gzinflate(substr($content, 10, -8));
		}


		$encHeader = false;
		foreach ($headers as $header) {
			if (strpos($header, 'Content-Type') !== false) {
				$encHeader = $header;
				break;
			}
		}

		preg_match('/charset=(.*)$/s', $encHeader, $matches);
		$enc = strtoupper($matches[1]);

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
	
	/**
	 * 
	 * @return \Application\Models\Entities\Advertisement
	 */
	public function getEntity() {
		$entity = new \Application\Models\Entities\Advertisement();
		
		foreach(get_object_vars($this) as $key => $var) {
			
			$method_name = ("set") . ucfirst($key);

			if (method_exists($entity, $method_name)) {
				$entity->$method_name($var);
			}
			
		}
		
		return $entity;
		
	}

}
