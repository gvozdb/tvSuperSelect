<div id="tv-tvss-{$tv->id}"></div>

<script type="text/javascript">
    // <![CDATA[
    {literal}
    Ext.onReady(function () {
        var _defaultItems = {/literal}{if $tv->value}{$tv->value}{else}[]{/if}{literal};
        var defaultItems = [];

        if (Ext.isArray(_defaultItems)) {
            Ext.each(_defaultItems, function (item) {
                defaultItems.push({
                    'value': item,
                });
            });
        }

        var fld{/literal}{$tv->id}{literal} = MODx.load({
            {/literal}
            xtype: 'tvss-minishop2-combo-options',
            renderTo: 'tv-tvss-{$tv->id}',
            name: 'tvss-option-{$tv->id}',
            tv_id: '{$tv->id}',
            defaultItems: defaultItems,
            {literal}
        });
        {/literal}

        fld{$tv->id}.setWidth('auto');
        fld{$tv->id}.positionEl.setWidth('auto');

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