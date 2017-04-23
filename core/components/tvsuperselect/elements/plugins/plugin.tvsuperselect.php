<?php
/** @var modX $modx */
/** @var tvSuperSelect $tvss */
/** @var modTemplateVarResource $obj */

$path = MODX_CORE_PATH . 'components/tvsuperselect/model/tvsuperselect/';
if (!$tvss = $modx->getService('tvsuperselect', 'tvSuperSelect', $path)) {
    return '';
}

switch ($modx->event->name) {
    case 'OnTVInputRenderList':
        $modx->event->output($tvss->config['corePath'] . 'tv/input/');
        break;
    case 'OnTVOutputRenderList':
        // $modx->event->output($tvss->config['corePath'] . 'tv/output/');
        break;
    case 'OnTVInputPropertiesList':
        $modx->event->output($tvss->config['corePath'] . 'tv/inputproperties/');
        break;
    case 'OnTVOutputRenderPropertiesList':
        // $modx->event->output($tvss->config['corePath'] . 'tv/properties/');
        break;
    case 'OnManagerPageBeforeRender':
        $modx->controller->addCss($tvss->config['cssUrl'] . 'mgr/main.css');
        break;

    case 'OnDocFormRender':
        $modx->regClientCSS($tvss->config['cssUrl'] . 'mgr/main.css');
        $modx->regClientStartupScript('<script type="text/javascript">
            if (typeof(jQuery) == "undefined") {
                document.write(\'<script type="text/javascript" src="' . $tvss->config['jsUrl'] . 'jquery-2.1.1.min.js" ></\'+\'script>\');
            }
        </script>', true);
        $modx->regClientStartupScript($tvss->config['jsUrl'] . 'mgr/tvsuperselect.js');
        $modx->regClientStartupScript('<script type="text/javascript">
            tvSuperSelect.config = ' . $modx->toJSON($tvss->config) . ';
            tvSuperSelect.config.connector_url = "' . $tvss->config['connectorUrl'] . '";
        </script>', true);
        $modx->regClientStartupScript($tvss->config['jsUrl'] . 'mgr/misc/ms2.combo.js');
        break;

    case 'OnDocFormSave':
        if (is_object($resource) && is_array($resource->_fields)) {
            $fields = $resource->_fields;
            $id = $fields['id'];

            // Если это товар miniShop2 и ТВ поля в объекте записаны в msProductData
            if ($fields['class_key'] === 'msProduct' && !array_key_exists('tvs', $fields)) {
                $fields = $resource->Data->_fields;
            }

            //
            $data = array();
            foreach ($fields as $k => $v) {
                if (preg_match('/^tvss\-option\-/ui', $k)) {
                    $tv = str_replace('tvss-option-', '', $k);
                    $data[$tv] = array_diff(array_map('trim', $v), array(''));
                    unset($tv);
                }
            }

            //
            if (!empty($data)) {
                $table = $modx->getTableName('tvssOption');
                foreach ($data as $tv => $values) {
                    /**
                     * Пишем в таблицу пакета
                     */
                    // Удаляем старые записи
                    $sql = "DELETE FROM {$table} WHERE `resource_id` =? AND `tv_id` =?";
                    $stmt = $modx->prepare($sql);
                    if (!$stmt->execute(array($id, $tv))) {
                        $modx->log(1, '[tvSuperSelect] ' . print_r($stmt->errorInfo, true) . ' SQL: ' . $sql);
                    }

                    // Добавляем новые записи
                    if (!empty($values)) {
                        // Подготавливаем параметры для запроса
                        $tmp = array();
                        $params = array('id' => $id, 'tv' => $tv);
                        foreach ($values as $k => $v) {
                            $tmp[] = "(:id, :tv, :value{$k})";
                            $params['value' . $k] = $v;
                        }

                        // Совершаем запрос на добавление записей
                        $sql = "INSERT INTO {$table} (`resource_id`, `tv_id`, `value`) VALUES " . implode(', ', $tmp);
                        $stmt = $modx->prepare($sql);
                        if (!$stmt->execute($params)) {
                            $modx->log(1, '[tvSuperSelect] ' . print_r($stmt->errorInfo, true) . ' SQL: ' . $sql);
                        }
                        unset($tmp);
                    }

                    /**
                     * Пишем в таблицу modTemplateVarResource
                     */
                    $condition = array(
                        'tmplvarid' => $tv,
                        'contentid' => $id,
                    );
                    if (!$obj = $modx->getObject('modTemplateVarResource', $condition)) {
                        $obj = $modx->newObject('modTemplateVarResource');
                    }
                    $obj->fromArray(array_merge($condition, array(
                        'value' => $modx->toJSON($values),
                    )));
                    $obj->save();
                    unset($condition, $obj);
                }
            }
        }
        break;
}