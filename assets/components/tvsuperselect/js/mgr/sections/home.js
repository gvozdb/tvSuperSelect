tvSuperSelect.page.Home = function (config) {
	config = config || {};
	Ext.applyIf(config, {
		components: [{
			xtype: 'tvsuperselect-panel-home', renderTo: 'tvsuperselect-panel-home-div'
		}]
	});
	tvSuperSelect.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(tvSuperSelect.page.Home, MODx.Component);
Ext.reg('tvsuperselect-page-home', tvSuperSelect.page.Home);