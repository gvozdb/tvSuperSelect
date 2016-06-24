<?php

$tvss = $modx->getService('tvsuperselect', 'tvsuperselect', $modx->getOption('core_path').'components/tvsuperselect/model/tvsuperselect/');
if (!($tvss instanceof tvSuperSelect)) {
    return '';
}

switch ($modx->event->name) {
    case 'OnTVInputRenderList':
        $modx->event->output($tvss->config['corePath'].'tv/input/');
    break;

    case 'OnTVOutputRenderList':
        $modx->event->output($tvss->config['corePath'].'tv/output/');
    break;

    case 'OnTVInputPropertiesList':
        $modx->event->output($tvss->config['corePath'].'tv/inputproperties/');
    break;

    case 'OnTVOutputRenderPropertiesList':
        $modx->event->output($tvss->config['corePath'].'tv/properties/');
    break;

    case 'OnManagerPageBeforeRender':
        $modx23 = !empty($modx->version) && version_compare($modx->version['full_version'], '2.3.0', '>=');
        $modx->controller->addHtml('<script type="text/javascript">
            Ext.onReady(function() {
                MODx.modx23 = '.(int) $modx23.';
            });
        </script>');
        if (!$modx23) {
            $modx->controller->addCss($tvss->config['cssUrl'].'mgr/bootstrap.min.css');
        }
        $modx->controller->addCss($tvss->config['cssUrl'].'mgr/main.css');
    break;

    case 'OnDocFormRender':
        $modx->regClientCSS($tvss->config['cssUrl'].'mgr/main.css');

        $modx->regClientStartupScript('
            <script type="text/javascript">
                if(typeof jQuery == "undefined")
                {
                    document.write(\'<script type="text/javascript" src="'.$tvss->config['jsUrl'].'jquery-2.1.1.min.js" ></\'+\'script>\');
                }
            </script>
        ', true);

        $modx->regClientStartupScript($tvss->config['jsUrl'].'mgr/tvsuperselect.js');
        $modx->regClientStartupScript('
            <script type="text/javascript">
                tvSuperSelect.config = '.$modx->toJSON($tvss->config).';
                tvSuperSelect.config.connector_url = "'.$tvss->config['connectorUrl'].'";
            </script>
        ', true);

        $modx->regClientStartupScript($tvss->config['jsUrl'].'mgr/misc/ms2.combo.js');
    break;

    case 'OnDocFormSave':
        if (is_object($resource) && is_array($resource->_fields)) {
            $data = $resource->_fields;
            $resource_id = $data['id'];
            // $modx->log(1, print_r($data, 1));

            $flds = $tv_values = array();
            foreach ($data as $key => $value) {
                if (strstr($key, 'tvss-option-')) {
                    $tv_id = str_replace('tvss-option-', '', $key);

                    $array = array_diff($value, array(''));
                    if (!empty($array)) {
                        $flds[] = array(
                            'resource_id' => $resource_id,
                            'tv_id' => $tv_id,
                            'data' => $array,
                        );

                        $tv_values[$tv_id] = $modx->toJSON($array);
                    } else {
                        $flds[] = array(
                            'resource_id' => $resource_id,
                            'tv_id' => $tv_id,
                            'data' => array(),
                        );
                    }
                }
            }

            // пишем в таблицу пакета
            if (!empty($flds)) {
                // $modx->log(1, 'if (!empty($flds)) { '.print_r($flds, 1));

                $table = $modx->getTableName('tvssOption');

                foreach ($flds as $fld) {
                    $sql = 'DELETE FROM '.$table.' WHERE `resource_id` = '.$fld['resource_id'].' AND `tv_id` = '.$fld['tv_id'];
                    $stmt = $modx->prepare($sql);
                    $stmt->execute();
                    $stmt->closeCursor();

                    $values = array();
                    if ($fld['data']) {
                        foreach ($fld['data'] as $value) {
                            if (!empty($value)) {
                                $values[] = '('.$fld['resource_id'].',"'.$fld['tv_id'].'","'.addslashes($value).'")';
                            }
                        }
                    }

                    if ($values) {
                        $sql = 'INSERT INTO '.$table.' (`resource_id`,`tv_id`,`value`) VALUES '.implode(',', $values);
                        $stmt = $modx->prepare($sql);
                        $stmt->execute();
                        $stmt->closeCursor();
                    }
                }
            }

            // пишем в таблицу modTemplateVarResource
            if (!empty($tv_values)) {
                // $modx->log(1, 'if (!empty($tv_values)) { '.print_r($tv_values, 1));

                foreach ($tv_values as $tv_id => $values) {
                    if (!$tv_obj = $modx->getObject('modTemplateVarResource', array(
                        'tmplvarid' => $tv_id,
                        'contentid' => $resource_id,
                    ))) {
                        $tv_obj = $modx->newObject('modTemplateVarResource');
                    }

                    $tv_obj->fromArray(array(
                        'tmplvarid' => $tv_id,
                        'contentid' => $resource_id,
                        'value' => $values,
                    ));
                    $tv_obj->save();
                    // $modx->log(1, print_r($tv_obj->toArray(), 1));

                    unset($tv_obj);
                }
            }
        }
    break;
}
