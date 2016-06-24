<?php

/**
 * Get an Item
 */
class tvSuperSelectItemGetProcessor extends modObjectGetProcessor {
	public $objectType = 'tvSuperSelectItem';
	public $classKey = 'tvSuperSelectItem';
	public $languageTopics = array('tvsuperselect:default');
	//public $permission = 'view';


	/**
	 * We doing special check of permission
	 * because of our objects is not an instances of modAccessibleObject
	 *
	 * @return mixed
	 */
	public function process() {
		if (!$this->checkPermissions()) {
			return $this->failure($this->modx->lexicon('access_denied'));
		}

		return parent::process();
	}

}

return 'tvSuperSelectItemGetProcessor';