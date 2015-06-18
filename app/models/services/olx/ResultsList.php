<?php

namespace Application\Models\Services\Olx;

use \Application\Models\Services\ResultsListAbstract;

/**
 * Description of ResultsList
 *
 * @author bnowakowski
 */
class ResultsList extends ResultsListAbstract
{

	public $source_name = "olx";
	public $district=99;
	
	public function __construct($url, $content = false, $page = 0)
	{
		$this->district = $url;
		parent::__construct('http://olx.pl/ajax/gdansk/search/list/', $content, $page);
	}
	
	protected function parse()
	{
		$start = microtime(true);
		
		if (preg_match_all('/<a href="([a-z0-9\-\.:\/]+)#[a-z0-9]+"/is', $this->content, $matches)) {

			foreach($matches[1] as $url) {
				preg_match('/CID3\-ID([A-Z0-9]+)\.htm/si', $url, $estimates);
				$this->urls[$estimates[1]] = $url;
			}
			
			if (preg_match_all('/&page=([0-9]+)/si', $this->content, $matches)) {
				$this->pages = intval(max($matches[1]));
			}
			
			$this->timeParsing = microtime(true)-$start;
			return true;
		}
		return false;
		
	}
	
	protected function get_page($page = 0) {
		
		return $this->url;
		
	}
		
	protected function get_context() {
		
		$content = 'view=&min_id=&q=&search%5Bcity_id%5D=5659&search%5Bregion_id%5D=0&search%5Bdistrict_id%5D=' . intval($this->district) . '&search%5Bdist%5D=0&search%5Bfilter_enum_market%5D%5B%5D=secondary&search%5Bfilter_enum_rooms%5D%5B%5D=three&search%5Bfilter_enum_rooms%5D%5B%5D=two&search%5Bcategory_id%5D=14';
		if($this->page > 0) {
			$content .= '&page=' . ($this->page+1);
		}

		return stream_context_create([
			'http' => [
				'method' => 'POST',
				'header' => headers([
					'Accept' => '*/*',
					'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.81 Safari/537.36',
					'Accept-Encoding' => 'gzip, deflate, sdch',
					'Accept-Language' => 'pl-PL,pl;q=0.8,en-US;q=0.6,en;q=0.4',
					'Content-Type' => 'application/x-www-form-urlencoded',
					'Content-Length' => strlen($content),
					'X-Requested-With' => 'XMLHttpRequest'
				]),
				'content' => $content
			]
		]);
		
	}
}
