<?php

namespace Application\Models\Services\Trojmiasto;

use \Application\Models\Services\AdvertisementAbstract;

/**
 * Description of TrojmiastoAdvertisement
 *
 * @author bnowakowski
 */
class Advertisement extends AdvertisementAbstract
{

	protected function parse()
	{

		$content = $this->content;
		$estimates = null;
		$success = preg_match_all('/<div class=\"adv\-body\">(.*?)<\/div>\s*<div id=\"footer\">/s', $content, $estimates);

		if ($success === 1) {
			$this->content = (string) $estimates[1][0];
		} elseif (!$this->content || strlen($this->content) < 10) {
			throw new \Exception('Empty content on advertisement: ' . $this->url);
		}
	}

}
