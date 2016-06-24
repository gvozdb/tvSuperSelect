<?php

class tvssOptionGetOptionsProcessor extends modObjectProcessor
{
    public $classKey = 'tvssOption';

    /** {@inheritDoc} */
    public function process()
    {
        $query = trim($this->getProperty('query'));
        $limit = trim($this->getProperty('limit', 10));
        $tv_id = $this->getProperty('tv_id');
        // $this->modx->log(modX::LOG_LEVEL_ERROR, print_r($tv_id, 1));

        $c = $this->modx->newQuery($this->classKey);
        $c->sortby('value', 'ASC');
        $c->select('value');
        $c->groupby('value');
        $c->where(array(
            'tv_id' => $tv_id,
        ));
        $c->limit($limit);

        if (!empty($query)) {
            $c->where(array('value:LIKE' => "%{$query}%"));
        }

        $found = false;
        if ($c->prepare() && $c->stmt->execute()) {
            $res = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($res as $v) {
                if ($v['value'] == $query) {
                    $found = true;
                }
            }
        } else {
            $res = array();
        }

        if (!$found && !empty($query)) {
            $res = array_merge_recursive(array(array('value' => $query)), $res);
        }

        $return = $this->outputArray($res);

        // $this->modx->log(modX::LOG_LEVEL_ERROR, print_r($return, 1));

        return $return;
    }
}

return 'tvssOptionGetOptionsProcessor';
