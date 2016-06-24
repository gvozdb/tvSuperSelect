var tvSuperSelect = function (config) {
	config = config || {};
	tvSuperSelect.superclass.constructor.call(this, config);
};
Ext.extend(tvSuperSelect, Ext.Component, {
	page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('tvsuperselect', tvSuperSelect);

tvSuperSelect = new tvSuperSelect();