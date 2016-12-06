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
            name: config.name || 'tvss-option-0',
            anchor: '100%',
            minChars: 2,
            enableKeyEvents: true,
            store: new Ext.data.JsonStore({
                id: (config.name || 'tvss-option') + '-store',
                root: 'results',
                autoLoad: true,
                autoSave: false,
                totalProperty: 'total',
                fields: ['value'],
                url: tvSuperSelect.config['connector_url'],
                baseParams: {
                    action: 'mgr/option/getoptions',
                    tv_id: config.tv_id,
                },
            }),
            mode: 'remote',
            displayField: 'value',
            valueField: 'value',
            triggerAction: 'all',
            extraItemCls: 'x-tag',
            expandBtnCls: 'x-form-trigger',
            clearBtnCls: 'x-form-trigger',
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