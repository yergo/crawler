<?php

namespace Application\Models\Services;

use Application\Models\Entities\Advertisement as AdvEntity;

/**
 * Description of TrojmiastoAdvertisementInterface
 *
 * @author bnowakowski
 */
abstract class AdvertisementThreadAbstract extends \Thread
{

	protected $source_name;
	protected $source_id;
	protected $url;

	public function __construct($sourceId, $url)
	{

		$this->source_id = $sourceId;
		$this->url = $url;
	}

	public function run()
	{
		if (!$this->source_name) {
			die('Undefined source name.');
		}

		if (!$this->source_id) {
			die('Undefined source id.');
		}

		if (!$this->url) {
			die('Undefined url.');
		}

		$ent = AdvEntity::findFirst('source_name = "' . $this->source_name . '" AND source_id = "' . $this->source_id . '"');

		if (!$ent) {
			print('Downloading ' . $this->url . PHP_EOL);
			$this->proceedEntity();
		} else {
			print('Advertisement already in DB.' . PHP_EOL);
		}
	}

	protected function proceedEntity()
	{

		$advertisement = new \Application\Models\Services\Trojmiasto\Advertisement($this->url);
		$ent = $advertisement->getEntity();

		print('Gathered information from ' . $this->url . PHP_EOL);
		
		$error = false;
		if (!$ent->save()) {
			print('Save Failed.');
			$error = [
				'advertisement' => $ent->toArray(),
				'errors' => []
			];
			foreach ($ent->getMessages() as $message) {
				$error['errors'][] = $message->getMessage();
			}
			
		} else {
			print('Save successful.' . PHP_EOL);
		}
	}

}
