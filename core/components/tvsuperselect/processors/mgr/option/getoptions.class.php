<?php

class tvssComboGetOptionsProcessor extends modObjectProcessor
{
    public $classKey = 'tvssOption';
    /** @var tvSuperSelect $tvss */
    protected $tvss;

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->tvss = $this->modx->getService('tvsuperselect', 'tvSuperSelect',
            $this->modx->getOption('tvsuperselect_core_path', null, MODX_CORE_PATH . 'components/tvsuperselect/') . 'model/tvsuperselect/');

        return parent::initialize();
    }

    /**
     * @return string
     */
    public function process()
    {
        $query = trim($this->getProperty('query'));
        $limit = (int)$this->getProperty('limit', 10);
        $context_key = (int)$this->getProperty('context_key', 'web');
        $resource_id = (int)$this->getProperty('resource_id', 0);
        if (!$tv_id = (int)$this->getProperty('tv_id', 0)) {
            //
        }

        $q = $this->modx->newQuery($this->classKey);
        $q->select('value');
        $q->where(array(
            'tv_id' => $tv_id,
        ));
        if (!empty($query)) {
            $q->where(array('value:LIKE' => "%{$query}%"));
        }
        $q->limit($limit);
        $q->sortby('value', 'ASC');
        $q->groupby('value');

        //
        $found = false;
        if ($q->prepare() && $q->stmt->execute()) {
            $rows = $q->stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $v) {
                if ($v['value'] == $query) {
                    $found = true;
                }
            }
        } else {
            $rows = array();
        }

        //
        if ($found === false && !empty($query)) {
            $rows = array_merge_recursive(array(array('value' => $query)), $rows);
        }

        //
        foreach ($rows as &$row) {
            if (empty($row['display'])) {
                $row['display'] = $row['value'];
            }
        }
        unset($row);

        // $this->modx->log(1, print_r($rows, 1));

        return $this->outputArray($rows);
    }
}

return 'tvssComboGetOptionsProcessor';