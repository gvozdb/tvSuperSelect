tvSuperSelect.panel.Home = function (config) {
	config = config || {};
	Ext.apply(config, {
		baseCls: 'modx-formpanel',
		layout: 'anchor',
		/*
		 stateful: true,
		 stateId: 'tvsuperselect-panel-home',
		 stateEvents: ['tabchange'],
		 getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
		 */
		hideMode: 'offsets',
		items: [{
			html: '<h2>' + _('tvsuperselect') + '</h2>',
			cls: '',
			style: {margin: '15px 0'}
		}, {
			xtype: 'modx-tabs',
			defaults: {border: false, autoHeight: true},
			border: true,
			hideMode: 'offsets',
			items: [{
				title: _('tvsuperselect_items'),
				layout: 'anchor',
				items: [{
					html: _('tvsuperselect_intro_msg'),
					cls: 'panel-desc',
				}, {
					xtype: 'tvsuperselect-grid-items',
					cls: 'main-wrapper',
				}]
			}]
		}]
	});
	tvSuperSelect.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(tvSuperSelect.panel.Home, MODx.Panel);
Ext.reg('tvsuperselect-panel-home', tvSuperSelect.panel.Home);
