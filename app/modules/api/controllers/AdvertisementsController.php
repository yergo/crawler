<?php

namespace Application\Api\Controllers;

use Application\Models\Entities\Advertisement as TAdvertisement;
use Application\Models\Entities\AdvertisementIgnore as TIgnored;

/**
 * Description of AdvertisementsController
 *
 * @author bnowakowski
 */
class AdvertisementsController extends ControllerBase
{

	public function similarAction() {
		
		$advBase = TAdvertisement::findFirst($this->_request['data']['id']);
		
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
		
		$advBase = TAdvertisement::findFirst($this->_request['data']['id']);
		
		// already deleted, surpress error
		if(!$advBase) {
			return ['id' => $this->_request['data']['id']];
		}
		
		if($advBase->delete()) {
			return ['id' => $this->_request['data']['id']];
		} else {
			throw new \Exception($advBase->getMessages()->getMessage());
		}
	}
	
	public function skippedAction() {
		$advBase = TAdvertisement::findFirst($this->_request['data']['id']);
		$advBase->setSkipped(1);
		
		if($advBase->update()) {
			return ['id' => $this->_request['data']['id']];
		} else {
			throw new \Exception($advBase->getMessages()->getMessage());
		}
	}
	
	public function ignoredAction() {
		
		$times = [];
		
		$times['start'] = microtime(true);
		
		$advBase = TAdvertisement::findFirst('id = '. $this->_request['data']['id']);
		$date = date('Y-m-d', strtotime(sprintf('+%d weeks', $this->_request['data']['weeks'])));
		
		$times['fetch'] = microtime(true) - $times['start'];
		
		if(!$advBase) {
			return [
				'id' => $this->_request['data']['id'],
				'till' => $date
			];
		}
		$times['check'] = microtime(true) - $times['start'];
		
		$ign = new TIgnored();
		$ign->setAdvertisementId($advBase->getId());
		$ign->setTimeout($date);
		
		if($ign->create()) {
			$times['save'] = microtime(true) - $times['start'];
			return [
				'id' => $this->_request['data']['id'],
				'till' => $ign->getTimeout(),
				'times' => $times
			];
		} else {
			throw new \Exception($ign->getMessages()->getMessage());
		}
		
		return $this->_request;
	}
	
}