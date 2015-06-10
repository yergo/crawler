<?php

namespace Application\Models\Services\Trojmiasto;

use \Application\Models\Services\AdvertisementAbstract;

/**
 * Description of TrojmiastoAdvertisement
 *
 * @author bnowakowski
 */
class TrojmiastoAdvertisement extends AdvertisementAbstract {
	
	private function parse() {
		
		$estimates = null;
		$success = preg_match_all('/<div class=\"adv\-body\">(.*?)<\/div>\s*<div id=\"footer\">/s', $content, $estimates);
		
		if($success) {
			$this->content = $estimates[1][0];
		} elseif ($this->content === false) {
			throw new \Exception('Empty content on advertisement: ' . $this->url);
		}
		
	}
	
}
