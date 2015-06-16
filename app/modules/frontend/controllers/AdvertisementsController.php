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
	
	/**
	 * Produces result list
	 */
	public function indexAction()
	{
		$builder = $this->modelsManager->createBuilder()
			->addFrom('\Application\Models\Entities\Advertisement', 'A')
			->leftJoin('\Application\Models\Entities\AdvertisementIgnore', 'A.id = I.advertisement_id', 'I')
			->where('A.middleman = 0')
			->andWhere('A.skipped = 0')
			->andWhere('I.id IS NULL OR I.timeout < NOW()')
			->orderBy('A.updated ASC')
		;
		
		$advertisements = $builder->getQuery()->execute();
		
		$result = [];
		
		foreach($advertisements as $advertisement) {
			$result[$advertisement->getPhone()][] = $advertisement->toArray();
		}
		
		$this->view->advertisements = $result;
		
	}

}
