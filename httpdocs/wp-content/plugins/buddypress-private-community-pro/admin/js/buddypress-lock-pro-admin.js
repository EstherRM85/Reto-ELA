(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 jQuery(document).ready(function($) {

	 	$('#blpro-bp-components-list').selectize({
	 		placeholder		: "Select buddypress components",
	 		plugins			: ['remove_button']
	 	});

	 	$('#blpro-bp-comp-list').selectize({
	 		placeholder		: "Select buddypress components",
	 		plugins			: ['remove_button']
	 	});

	 	$('#blpro-cpt-list').selectize({
	 		placeholder		: "Select custom post types",
	 		plugins			: ['remove_button']
	 	});

	 	$('#blpro-page-list').selectize({
	 		placeholder		: "Select pages",
	 		plugins			: ['remove_button']
	 	});

	 	$('#blpro-user-roles-list').selectize({
	 		placeholder		: "Select user roles",
	 		plugins			: ['remove_button']
	 	});

	 	$('.blpro-user-roles-list').selectize({
	 		placeholder		: "Select user roles",
	 		plugins			: ['remove_button']
	 	});

	 	$('#blpro-users-list').selectize({
	 		placeholder		: "Select users",
	 		plugins			: ['remove_button']
	 	});

	 	$('.blpro-users-list').selectize({
	 		placeholder		: "Select users",
	 		plugins			: ['remove_button']
	 	});

	 	$('#blpro-remove-users-list').selectize({
	 		placeholder		: "Select users",
	 		plugins			: ['remove_button']
	 	});
	 	
	 	$('#blpro-member-types-list').selectize({
	 		placeholder		: "Select member types",
	 		plugins			: ['remove_button']
	 	});

	 	$('.blpro-member-types-list').selectize({
	 		placeholder		: "Select member types",
	 		plugins			: ['remove_button']
	 	});

	 	$('.blpro_primary_nav').selectize({
	 		placeholder		: "Select primary nav to hide from user profile",
	 		plugins			: ['remove_button']
	 	});

	 	$('.blpro-disp-resp-tr').click(function(){
	 		var tr_id = $(this).data('id');
	 		if($(this).is(':checked')){
	 			$('#'+tr_id).slideDown();
	 		}else{
	 			$('#'+tr_id).slideUp();
	 		}	
	 	});
	 	$('.blpro-lock-acc').click(function(){
	 		var tr_id = $(this).data('id');
	 		var rows_hide = $(this).data('rows');
	 		//$('.blpro-loc-acc-class').hide();
	 		$('.'+rows_hide).hide();
	 		if($(this).is(':checked')){
	 			$('#'+tr_id).slideDown();
	 		}else{
	 			$('#'+tr_id).slideUp();
	 		}	
	 	});

	 	$('.locked_bp_comp_login').click(function(){
	 		var tr_id = $(this).data('id');
	 		var obj = $('input[name$="lock_acc]"]:checked');
	 		var acc_type = obj.data('id');
	 		if($(this).is(':checked')){
	 			$(this).closest('tr').next('tr').slideDown();
	 			$(this).closest('tr').next('tr').next('tr').slideDown();
	 			$('#'+acc_type).slideDown();
	 		}else{
	 			$(this).closest('tr').next('tr').slideUp();
	 			$(this).closest('tr').nextAll('tr').slideUp();
	 		}
	 	});
	 	
	 });

	 $(function() {
	 	var blpro_elmt = document.getElementsByClassName( "blpro-accordion" );
	 	var k;
	 	var blpro_elmt_len = blpro_elmt.length;
	 	for (k = 0; k < blpro_elmt_len; k++) {
	 		blpro_elmt[k].onclick = function() {
	 			this.classList.toggle( "active" );
	 			var panel = this.nextElementSibling;
	 			if (panel.style.maxHeight) {
	 				panel.style.maxHeight = null;
	 			} else {
	 				panel.style.maxHeight = panel.scrollHeight + "px";
	 			}
	 		}
	 	}
	 });
})( jQuery );
