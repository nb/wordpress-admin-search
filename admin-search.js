var adminSearch = function($, adminMenuItems) {
	
	this.load = function() {
		this.addSearchBox();
		this.installShortcut('/');
		this.attachAutoComplete();
	}
	
	this.addSearchBox = function() {
		$('h1').append('<input id="admin-search" type="search" placeholder="Search admin…"></input>');
	}
	
	this.installShortcut = function(shortcut) {
		$.hotkeys.add(shortcut, {disableInInput: true, type: 'keypress'}, function() { $('#admin-search').focus(); });
	}
	
	this.attachAutoComplete = function() {
		$('#admin-search').autocomplete({
			focus: function() {
				return false;
			},
			select: function(event, ui) {
				window.location.href = ui.item.url;
				return false;
			},
			source: adminMenuItems
		});
	}
}
jQuery(function() {
	var search = new adminSearch(jQuery, adminMenuItems);
	search.load();
});

