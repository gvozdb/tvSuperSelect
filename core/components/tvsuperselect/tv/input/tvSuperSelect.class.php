<?php

class modTemplateVarInputRendertvssTV extends modTemplateVarInputRender
{
    public function getTemplate()
    {
        $corePath = $this->modx->getOption('core_path') . 'components/tvsuperselect/';

        return $corePath . 'tv/input/tpl/tv.tvSuperSelect.input.tpl';
    }
}

return 'modTemplateVarInputRendertvssTV';