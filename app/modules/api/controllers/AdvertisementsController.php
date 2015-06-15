<?php

namespace Application\Api\Controllers;

use Application\Models\Entities\Advertisement as TAdvertisement;

/**
 * Description of AdvertisementsController
 *
 * @author bnowakowski
 */
class AdvertisementsController extends ControllerBase
{

	public function searchAction() {
		
		// /crawler/api/advertisements/search?json={"where":{"middleman":false},"limit":10,"group":"phone"}
		
		$conditions = [];
		$binds = [];
		
		foreach($this->_request['data']['where'] as $key => $value) {
			$conditions[] = sprintf('%s = :%s:', $key, $key);
			$binds[$key] = $value;
		}
		
		$advertisements = TAdvertisement::find([
			'conditions' => join(', ', $conditions),
			'bind' => $binds,
			'limit' => isset($this->_request['data']['limit']) ? intval($this->_request['data']['limit']) : 10,
			'group' => isset($this->_request['data']['group']) ? join(',', (array) $this->_request['data']['group']) : null,
			'order' => 'updated DESC'
		]);
		
		return[
			'count' => $advertisements->count(),
			'items' => $advertisements->toArray(),
		];
	}
	
}