tvSuperSelect.combo.Options = function (config) {
    config = config || {};
    Ext.applyIf(config,
        {
            xtype: 'superboxselect',
            allowBlank: true,
            msgTarget: 'under',
            allowAddNewData: true,
            addNewDataOnBlur: true,
            resizable: true,
            name: config['name'] || 'tvss-option-0',
            anchor: '100%',
            minChars: 2,
            enableKeyEvents: true,
            store: new Ext.data.JsonStore({
                id: (config.name || 'tvss-option') + '-store',
                root: 'results',
                autoLoad: true,
                autoSave: false,
                totalProperty: 'total',
                fields: ['value', 'display'],
                url: config['connector_url'] || tvSuperSelect.config['connector_url'],
                baseParams: {
                    action: config['processor_action'] || 'mgr/option/getoptions',
                    context_key: config['context_key'] || 'web',
                    resource_id: config['resource_id'] || 0,
                    tv_id: config['tv_id'],
                },
            }),
            mode: 'remote',
            displayField: 'display',
            valueField: 'value',
            triggerAction: 'all',
            extraItemCls: 'x-tag',
            expandBtnCls: 'x-form-trigger',
            clearBtnCls: 'x-form-trigger',
            displayFieldTpl: new Ext.XTemplate('{value}', {compiled: true}),
            tpl: new Ext.XTemplate('\
                    <tpl for="."><div class="x-combo-list-item tvss-combo__list-item">\
                        <span class="tvss-combo__row" data-tvss-value="{value}">\
                            {display}\
                        </span>\
                    </div></tpl>\
                ',
                {compiled: true}
            ),
            listeners: {
                newitem: function (bs, v, f) {
                    bs.addItem({tag: v});
                },
                select: {fn: MODx.fireResourceFormChange, scope: this},
                beforeadditem: {fn: MODx.fireResourceFormChange, scope: this},
                beforeremoveitem: {fn: MODx.fireResourceFormChange, scope: this},
                clear: {fn: MODx.fireResourceFormChange, scope: this},
                render: {
                    fn: function (r) {
                        // console.log(r)
                        // console.log(r.getWidth())

                        if (typeof r.defaultItems != 'undefined' && Ext.isArray(r.defaultItems)) {
                            r.setValueEx(r.defaultItems);
                        }
                    },
                    scope: this
                },
            },
        });
    config.name += '[]';
    tvSuperSelect.combo.Options.superclass.constructor.call(this, config);
};
Ext.extend(tvSuperSelect.combo.Options, Ext.ux.form.SuperBoxSelect);
Ext.reg('tvss-minishop2-combo-options', tvSuperSelect.combo.Options);