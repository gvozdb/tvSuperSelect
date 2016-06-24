Ext.onReady(function() {
	tvSuperSelect.config.connector_url = OfficeConfig.actionUrl;

	var grid = new tvSuperSelect.panel.Home();
	grid.render('office-tvsuperselect-wrapper');

	var preloader = document.getElementById('office-preloader');
	if (preloader) {
		preloader.parentNode.removeChild(preloader);
	}
});