<?php

/**
 * Create an Item
 */
class tvSuperSelectOfficeItemCreateProcessor extends modObjectCreateProcessor {
	public $objectType = 'tvSuperSelectItem';
	public $classKey = 'tvSuperSelectItem';
	public $languageTopics = array('tvsuperselect');
	//public $permission = 'create';


	/**
	 * @return bool
	 */
	public function beforeSet() {
		$name = trim($this->getProperty('name'));
		if (empty($name)) {
			$this->modx->error->addField('name', $this->modx->lexicon('tvsuperselect_item_err_name'));
		}
		elseif ($this->modx->getCount($this->classKey, array('name' => $name))) {
			$this->modx->error->addField('name', $this->modx->lexicon('tvsuperselect_item_err_ae'));
		}

		return parent::beforeSet();
	}

}

return 'tvSuperSelectOfficeItemCreateProcessor';