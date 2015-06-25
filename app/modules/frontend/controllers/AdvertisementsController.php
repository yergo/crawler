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
		
//		$cache = $this->di->getCache();
//		$key = TAdvertisement::CACHE_KEY;

		$options = [
			'with-ignored' => false,
			'with-trojmiasto' => false,
			'with-olx' => false,
		];

		foreach($this->request->getQuery() as $key => $value) {	
			if(array_key_exists($key, $options) && $value == 'on') {
				$options[$key] = true;
			}
		}
		
//		$hash = base64_encode(json_encode($options));
//		$fetch = $cache->get($key) ?: [];
//		
//		if($fetch && array_key_exists($hash, $fetch)) {
//
//			$result = $fetch[$hash];
//			
//		} else {
		
			$builder = $this->modelsManager->createBuilder()
				->addFrom('\Application\Models\Entities\Advertisement', 'A')
				->where('A.middleman = 0');

			if(!$options['with-ignored']) {
				$builder->leftJoin('\Application\Models\Entities\AdvertisementIgnore', 'A.id = I.advertisement_id', 'I')
				->andWhere('A.skipped = 0')
				->andWhere('I.id IS NULL OR I.timeout < NOW()');
			}

			$sources = [];
			if($options['with-olx']) {
				$sources[] = 'olx';
			}
			if($options['with-trojmiasto']) {
				$sources[] = 'trojmiasto';
			}
			$builder->andWhere('A.source_name IN("' . join('","', $sources) . '")');

			$builder->orderBy('A.updated DESC');

			$advertisements = ($options['with-olx'] || $options['with-trojmiasto']) ? $builder->getQuery()->execute() : [];
			
			$result = [];

			foreach($advertisements as $advertisement) {
				$result[$advertisement->getPhone()][] = $advertisement->toArray();
			}
			
//			$fetch[$hash] = $result;
//			$cache->save($key, $fetch, 60*60);
//		}
		
		$this->view->advertisements = $result;
		$this->view->options = $options;
		
	}

}
