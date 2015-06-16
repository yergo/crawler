<?php

namespace Application\Frontend\Controllers;

use Application\Frontend\Controllers\ControllerBase;
use Application\Models\Entities\Advertisement as TAdvertisement;

/**
 * Description of AdvertisementsController
 *
 * @author bnowakowski
 */
class AdvertisementsController extends ControllerBase
{
	
	private static $districts = [
		'Aniołki' => ['Aniołki', 'Focha'],
		'Jelitkowo' => ['Jelitkowo', 'Jelitkowski Dwór'],
		'Morena' => ['Morena'],
		'Oliwa' => ['Oliwa']
	];

	/**
	 * Produces result list
	 */
	public function indexAction()
	{
		$default = [
			'middleman' => 0,
			'district' => 'Wrzeszcz Górny',
			'timeout' => null,
			'order' => 'updated ASC',
		];
		
		$advertisements = TAdvertisement::find([
			'conditions' => 'middleman = 0 AND skipped = 0',
			'order' => 'updated ASC'
		]);
		
		$result = [];
		
		foreach($advertisements as $advertisement) {
			$result[$advertisement->getPhone()][] = $advertisement->toArray();
		}
		
		$this->view->advertisements = $result;
		
		
	}

}
