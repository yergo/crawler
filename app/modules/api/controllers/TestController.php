<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Api\Controllers;

/**
 * Description of TestController
 *
 * @author bnowakowski
 */
class TestController extends ControllerBase
{
	
	public function olxAction() {
		
		$file = 'http://olx.pl/ajax/gdansk/search/list/';
		
		$list = new \Application\Models\Services\Olx\ResultsList($file);
		
		$all = [];
		
		foreach($list as $value) {
			$all[] = $value;
			
			if(count($all) > 2) break;
			sleep(1);
		}
		
		return $all;
		
	}
	
}
