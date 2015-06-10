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
		preg_match_all('/<div class=\"adv\-body\">(.*?)<\/div>\s*<div id=\"footer\">/s', $content, $estimates);
		
		$this->content = $estimates[1][0];
		
	}
	
}
