<?php

/**
 * Class tvSuperSelectMainController
 */
abstract class tvSuperSelectMainController extends modExtraManagerController
{
    /** @var tvSuperSelect $tvSuperSelect */
    public $tvSuperSelect;

    /**
     * @return void
     */
    public function initialize()
    {
        $corePath = $this->modx->getOption('tvsuperselect_core_path', null, $this->modx->getOption('core_path') . 'components/tvsuperselect/');
        require_once $corePath . 'model/tvsuperselect/tvsuperselect.class.php';

        $this->tvSuperSelect = new tvSuperSelect($this->modx);
        //$this->addCss($this->tvSuperSelect->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->tvSuperSelect->config['jsUrl'] . 'mgr/tvsuperselect.js');
        $this->addHtml('
		<script type="text/javascript">
			tvSuperSelect.config = ' . $this->modx->toJSON($this->tvSuperSelect->config) . ';
			tvSuperSelect.config.connector_url = "' . $this->tvSuperSelect->config['connectorUrl'] . '";
		</script>
		');

        parent::initialize();
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return array('tvsuperselect:default');
    }

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }
}

/**
 * Class IndexManagerController
 */
class IndexManagerController extends tvSuperSelectMainController
{
    /**
     * @return string
     */
    public static function getDefaultController()
    {
        return 'home';
    }
}