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
		foreach($addrs as $k => $addrr) {
			if(strlen($addrr) > 3) {
				if(isset($addrs[$k+1]) && strlen($addrs[$k+1]) > 5) {
					// nazwisko
					$addr = '%'.$addrs[$k+1] . '%';
				} else {
				$addr = '%' . $addrr . '%';
				}
				break;
			}
		}
		
		$district = $advBase->getDistrict();
		foreach(explode(' ', $district) as $distr) {
			if(strlen($distr) > 3) {
				$district = $distr . '%';
				break;
			}
		}
		
		$advs = TAdvertisement::find([
			'conditions' => 'source_id != :ignored_id: AND area BETWEEN :area: AND (:area:+1.0) AND district LIKE :district: AND price_per_area BETWEEN :lower_price: AND :upper_price: AND rooms = :rooms: AND address LIKE :addr:',
			'bind' => [
				'ignored_id' => $advBase->getSourceId(),
				'area' => floor($advBase->getArea()),
				'district' => $district,
				'lower_price' => $priceBase - $percentBase,
				'upper_price' => $priceBase + $percentBase,
				'rooms' => $advBase->getRooms(),
				'addr' => $addr
			],
//			'order' => 'IF(address LIKE "%' . $addr . '%", 1, 0) DESC, price_per_meter DESC'

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
			$this->clearCache();
			return ['id' => $this->_request['data']['id']];
		} else {
			throw new \Exception($advBase->getMessages()->getMessage());
		}
	}
	
	public function skippedAction() {
		$advBase = TAdvertisement::findFirst($this->_request['data']['id']);
		$advBase->setSkipped(1);
		
		if($advBase->update()) {
			$this->clearCache();
			return ['id' => $this->_request['data']['id']];
		} else {
			throw new \Exception($advBase->getMessages()->getMessage());
		}
	}
	
	public function ignoredAction() {
		
		$times = [];
		
		$advBase = TAdvertisement::findFirst('id = '. $this->_request['data']['id']);
		$date = date('Y-m-d', strtotime(sprintf('+%d weeks', $this->_request['data']['weeks'])));
		
		if(!$advBase) {
			return [
				'id' => $this->_request['data']['id'],
				'till' => $date
			];
		}
		
		$ign = new TIgnored();
		$ign->setAdvertisementId($advBase->getId());
		$ign->setTimeout($date);
		
		/*
		 * @todo: This create happens long ! Cashes dont help.
		 */
		if($ign->create()) {
			$this->clearCache();
			return [
				'id' => $this->_request['data']['id'],
				'till' => $date
			];
			
		} else {
			throw new \Exception($ign->getMessages()->getMessage());
		}
		
		return $this->_request;
	}
	
	protected function clearCache() {
		

//		$key = TAdvertisement::CACHE_KEY;
//		$cache = $this->di->getCache();
//		if($cache->exists($key)) {
//			$cache->delete($key);
//		} else {
//			apc_clear_cache();
//			error_log('apc_clear_cache in ' . __FILE__ . ' ');
//		}
		
		
	}
	
}