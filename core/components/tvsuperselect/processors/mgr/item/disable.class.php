<?php

/**
 * Disable an Item
 */
class tvSuperSelectItemDisableProcessor extends modObjectProcessor {
	public $objectType = 'tvSuperSelectItem';
	public $classKey = 'tvSuperSelectItem';
	public $languageTopics = array('tvsuperselect');
	//public $permission = 'save';


	/**
	 * @return array|string
	 */
	public function process() {
		if (!$this->checkPermissions()) {
			return $this->failure($this->modx->lexicon('access_denied'));
		}

		$ids = $this->modx->fromJSON($this->getProperty('ids'));
		if (empty($ids)) {
			return $this->failure($this->modx->lexicon('tvsuperselect_item_err_ns'));
		}

		foreach ($ids as $id) {
			/** @var tvSuperSelectItem $object */
			if (!$object = $this->modx->getObject($this->classKey, $id)) {
				return $this->failure($this->modx->lexicon('tvsuperselect_item_err_nf'));
			}

			$object->set('active', false);
			$object->save();
		}

		return $this->success();
	}

}

return 'tvSuperSelectItemDisableProcessor';
