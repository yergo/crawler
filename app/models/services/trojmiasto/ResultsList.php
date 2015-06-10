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

	protected function parse()
	{
		$start = microtime(true);
		
		preg_match_all('/<p class="title">\s*<a href="(.*?)"/is', $this->content, $matches);
		
		$this->urls = array_merge($this->urls, $matches[1]);
		
		$this->timeParsing = microtime(true)-$start;
	}

}
