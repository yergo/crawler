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
	
	protected function parse()
	{
		$start = microtime(true);
		
		if (preg_match_all('/<p class="title">\s*<a href="(.*?)"/is', $this->content, $matches)) {

			foreach($matches[1] as $url) {
				preg_match('/ogl([0-9]+)\.htm/si', $url, $estimates);
				$this->urls[$estimates[1]] = $url;
			}
			
			if (preg_match_all('/cPage=([0-9]+)/si', $this->content, $matches)) {
				$this->pages = intval(max($matches[1]));
			}
			
			$this->timeParsing = microtime(true)-$start;
			return true;
		}
		
		return false;
		
	}
	
		
	protected function get_context() {
		
		return stream_context_create([
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
		
	}
}
