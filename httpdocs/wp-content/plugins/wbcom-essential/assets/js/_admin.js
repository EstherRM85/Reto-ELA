/*! wbcom-elementor-addons - v1.9.3 - 03-10-2017 */
(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
var modules = {
	widget_template_edit_button: require( 'modules/library/assets/js/admin' )
};

window.elementorProAdmin = {
	widget_template_edit_button: new modules.widget_template_edit_button()
};

},{"modules/library/assets/js/admin":2}],2:[function(require,module,exports){
module.exports = function(){
	var EditButton = require( './admin/edit-button' );
	this.editButton = new EditButton();
};
},{"./admin/edit-button":3}],3:[function(require,module,exports){
module.exports = function() {
	var self = this;

	self.init = function() {
		jQuery( document ).on( 'change', '.elementor-widget-template-select', function() {
			var $this = jQuery( this ),
				templateID = $this.val(),
				$editButton = $this.parents( 'p' ).find( '.elementor-edit-template' ),
				type = $this.find( '[value="' + templateID + '"]' ).data( 'type' );

			if ( 'page' !== type ) { // 'widget' is editable only from Elementor page
				$editButton.hide();

				return;
			}

			var editUrl = WbcomElementorAddonsConfig.i18n.home_url + '?p=' + templateID + '&elementor';

			$editButton
				.prop( 'href', editUrl )
				.show();

		} );
	};

	self.init();
};

},{}]},{},[1])
//# sourceMappingURL=admin.js.map
