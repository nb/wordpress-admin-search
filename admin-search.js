var adminSearch = function($, adminMenuItems) {
	
	this.load = function() {
		this.addSearchBox();
		this.installShortcut('/');
		this.attachAutoComplete();
	}
	
	this.addSearchBox = function() {
		$('ul#adminmenu').after('<div class="menu-top"><input id="admin-search" type="search" placeholder="Search wp-admin…"></input></div>');
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
			source: adminMenuItems,
			html: true
		});
	}
}
jQuery(function() {
	var search = new adminSearch(jQuery, adminMenuItems);
	search.load();
});

