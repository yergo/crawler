<?php

namespace Application\Models\Services\Trojmiasto;

use \Application\Models\Services\ResultsListAbstract;

/**
 * Description of ResultsList
 *
 * @author bnowakowski
 */
class ResultsList extends ResultsListAbstract
{

	public $source_name = "trojmiasto";
	
	protected function parse()
	{
		$start = microtime(true);
		
		if (preg_match_all('/<p class="title">\s*<a href="(.*?)"/is', $this->content, $matches)) {

			foreach($matches[1] as $url) {
				preg_match('/ogl([0-9]+)\.htm/si', $url, $estimates);
				$this->urls[$estimates[1]] = $url;
			}
			
			$this->timeParsing = microtime(true)-$start;
			return true;
		}
		
		return false;
		
	}

}
