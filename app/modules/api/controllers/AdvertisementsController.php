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
	
	public function similarAction() {
		
		$advBase = TAdvertisement::findFirst('source_id = "' . $this->_request['data']['id'] . '"');
		
		$priceBase = intval($advBase->getPricePerArea());
		$percentBase = $priceBase*0.1; // 10%
		
		$addr = $advBase->getAddress();
		$addrs = explode(' ', $addr);
		foreach($addrs as $addrr) {
			if(strlen($addrr) > 3) {
				$addr = $addrr;
				break;
			}
		}
		
		$advs = TAdvertisement::find([
			'conditions' => 'source_id != :ignored_id: AND area = :area: AND district = :district: AND price_per_area BETWEEN :lower_price: AND :upper_price: AND rooms = :rooms: AND skipped = 0',
			'bind' => [
				'ignored_id' => $advBase->getSourceId(),
				'area' => $advBase->getArea(),
				'district' => $advBase->getDistrict(),
				'lower_price' => $priceBase - $percentBase,
				'upper_price' => $priceBase + $percentBase,
				'rooms' => $advBase->getRooms(),
			],
			'order' => 'IF(address LIKE "' . $addr . '%", 1, 0) DESC, price_per_meter DESC'

		]);
		
		return ['items' => $advs->toArray(), 'addr' => $addr];
	
	}
	
	public function deletedAction() {
		
		$advBase = TAdvertisement::findFirst('source_id = "' . $this->_request['data']['id'] . '"');
		if($advBase->delete()) {
			return ['id' => $this->_request['data']['id']];
		} else {
			throw new \Exception($advBase->getMessages()->getMessage());
		}
	}
	
	public function skippedAction() {
		$advBase = TAdvertisement::findFirst('source_id = "' . $this->_request['data']['id'] . '"');
		$advBase->setSkipped(1);
		if($advBase->update()) {
			return ['id' => $this->_request['data']['id']];
		} else {
			throw new \Exception($advBase->getMessages()->getMessage());
		}
	}
	
	public function ignoredAction() {
		
		var_dump($this->_request);die();
	}
	
}