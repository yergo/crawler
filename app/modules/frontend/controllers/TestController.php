<?php

namespace Application\Frontend\Controllers;

use Application\Models\Entities\Advertisement as TAdvertisement;

/**
 * Description of TestController
 *
 * @author bnowakowski
 */
class TestController extends ControllerBase
{
	
	public function indexAction() {
		
		$advertisements = TAdvertisement::find([
			'conditions' => 'middleman = 0',
			'order' => 'updated ASC'
		]);
		
		$result = [];
		
		foreach($advertisements as $advertisement) {
			$result[$advertisement->getPhone()][] = $advertisement->toArray();
		}
		
		$this->view->advertisements = $result;
		
	}
	
}
