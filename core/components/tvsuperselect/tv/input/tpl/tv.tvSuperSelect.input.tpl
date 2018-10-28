{*@formatter:off*}
<div id="tv-tvss-{$tv->id}"></div>

<script type="text/javascript">
    // <![CDATA[
    {literal}
    Ext.onReady(function () {
        var defaultItems = [];

        {/literal}
        {if ($tv->id|strstr:'_')}
            {* MIGX *}
            var _defaultItems = "{$tv->value}".split('||');
            _defaultItems.forEach(function (el) {
                if (!!el) {
                    defaultItems.push({
                        value: el,
                    });
                }
            });
        {else}
            {* Not MIGX *}
            var _defaultItems = {if $tv->value}{$tv->value}{else}[]{/if};
            if (Ext.isArray(_defaultItems)) {
                Ext.each(_defaultItems, function (item) {
                    defaultItems.push({
                        value: item,
                    });
                });
            }
        {/if}
        {literal}

        {/literal}
        var fldProperties{$tv->id} = {
            xtype: 'tvss-minishop2-combo-options',
            renderTo: 'tv-tvss-{$tv->id}',
            name: 'tvss-option-{$tv->id}',
            context_key: '{$ctx}',
            resource_id: '{$resource_id}',
            tv_id: '{$tv->id}',
            defaultItems: defaultItems,
        };
        {literal}

        // MIGX
        {/literal}
        if (/_/.test('{$tv->id}')) {
            fldProperties{$tv->id}['id'] = 'tv{$tv->id}';
            fldProperties{$tv->id}['name'] = 'tv{$tv->id}';
            // fldProperties{$tv->id}['renderTo'] = 'tv{$tv->id}';
        }
        {literal}

        {/literal}
        {if $params['connectorUrl'] AND $params['processorAction']}
        fldProperties{$tv->id}['connector_url'] = "{$params['connectorUrl']}";
        fldProperties{$tv->id}['processor_action'] = "{$params['processorAction']}";
        {/if}
        {literal}

        {/literal}
        var fld{$tv->id} = MODx.load(fldProperties{$tv->id});
        {literal}

        {/literal}
        fld{$tv->id}.setWidth('auto');
        fld{$tv->id}.positionEl.setWidth('auto');

        // console.log('fld{$tv->id}', fld{$tv->id});

        var tvssInputSelect = fld{$tv->id}.inputEl.select('input[type=text]');
        if (typeof(tvssInputSelect) != 'undefined'
            && Ext.isArray(tvssInputSelect.elements)
            && tvssInputSelect.elements.length > 0
        ) {
            tvssInputSelect.elements[0].className += ' tvss-input';
        }
        {literal}
    });
    {/literal}
    // ]]>
</script>