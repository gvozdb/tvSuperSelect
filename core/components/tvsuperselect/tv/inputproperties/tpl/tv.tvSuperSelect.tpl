<div id="tv-input-properties-form{$tv}"></div>
{literal}

<script type="text/javascript">
    // <![CDATA[
    var params = {
        {/literal}{foreach from=$params key=k item=v name='p'}
        '{$k}': '{$v|escape:"javascript"}'{if NOT $smarty.foreach.p.last},{/if}
        {/foreach}{literal}
    };
    var oc = {
        'change': {
            fn: function () {
                Ext.getCmp('modx-panel-tv').markDirty();
            }, scope: this
        }
    };
    MODx.load({
        xtype: 'panel'
        , layout: 'form'
        , autoHeight: true
        , cls: 'form-with-labels'
        , border: false
        , labelAlign: 'top'
        , items: [{
            xtype: 'combo-boolean'
            , fieldLabel: _('required')
            , description: MODx.expandHelp ? '' : _('required_desc')
            , name: 'inopt_allowBlank'
            , hiddenName: 'inopt_allowBlank'
            , id: 'inopt_allowBlank{/literal}{$tv}{literal}'
            , value: !(params['allowBlank'] == 0 || params['allowBlank'] == 'false')
            , width: 200
            , listeners: oc
        }, {
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            , forId: 'inopt_allowBlank{/literal}{$tv}{literal}'
            , html: _('required_desc')
            , cls: 'desc-under'
        }, {
            xtype: 'textfield'
            , fieldLabel: '{/literal}{$tvsslex.tv_connectorUrl}{literal}'
            , description: MODx.expandHelp ? '' : '{/literal}{$tvsslex.tv_connectorUrl_desc}{literal}'
            , name: 'inopt_connectorUrl'
            , id: 'inopt_connectorUrl{/literal}{$tv}{literal}'
            , value: params['connectorUrl'] || ''
            , width: '99%'
            , listeners: oc
        }, {
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            , forId: 'inopt_connectorUrl{/literal}{$tv}{literal}'
            , html: '{/literal}{$tvsslex.tv_connectorUrl_desc}{literal}'
            , cls: 'desc-under'
        }, {
            xtype: 'textfield'
            , fieldLabel: '{/literal}{$tvsslex.tv_processorAction}{literal}'
            , description: MODx.expandHelp ? '' : '{/literal}{$tvsslex.tv_processorAction_desc}{literal}'
            , name: 'inopt_processorAction'
            , id: 'inopt_processorAction{/literal}{$tv}{literal}'
            , value: params['processorAction'] || ''
            , width: '99%'
            , listeners: oc
        }, {
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            , forId: 'inopt_processorAction{/literal}{$tv}{literal}'
            , html: '{/literal}{$tvsslex.tv_processorAction_desc}{literal}'
            , cls: 'desc-under'
        }, /*{
         xtype: 'textfield'
         ,fieldLabel: '{/literal}{$tvsslex.admin_zoom}{literal}'
         ,description: MODx.expandHelp ? '' : '{/literal}{$tvsslex.admin_zoom_desc}{literal}'
         ,name: 'inopt_adminZoom'
         ,id: 'inopt_adminZoom{/literal}{$tv}{literal}'
         ,value: params['adminZoom'] || ''
         ,width: '99%'
         ,listeners: oc
         },{
         xtype: MODx.expandHelp ? 'label' : 'hidden'
         ,forId: 'inopt_adminZoom{/literal}{$tv}{literal}'
         ,html: '{/literal}{$tvsslex.admin_zoom_desc}{literal}'
         ,cls: 'desc-under'
         }*/]
        , renderTo: 'tv-input-properties-form{/literal}{$tv}{literal}'
    });
    // ]]>
</script>
{/literal}