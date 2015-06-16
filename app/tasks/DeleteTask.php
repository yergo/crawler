<?php

use Application\Models\Entities\AdvertisementIgnore as AdvIgnores;

/**
 * Description of DeleteTask
 *
 * @author bnowakowski
 */
class DeleteTask extends \Phalcon\CLI\Task
{
	
	public function mainAction() {
		
		print('Use: clearIgnores' . PHP_EOL);
		
	}
	
	/**
	 * Cleard DB from ignores that did expired.
	 */
	public function clearIgnoresAction() {
		
		print(date('Y-m-d H:i:s') . ' Clearing ignores fired up' . PHP_EOL);
		
		$advs = AdvIgnores::find('timeout < NOW()');
		
		if($advs->count() > 0) {
			print('Fixing ' . $advs->count() . ' ignores.' . PHP_EOL);
			foreach($advs as $adv) {
				if(!$adv->delete()) {
					fwrite(STDERR, $adv->getMessages()->getMessage());
				}
			}
		} else {
			print('Empty.' . PHP_EOL);
		}
		
		print(PHP_EOL);
	}
	
}
