/**
 * Common JS scripts for wpDataTables admin panel
 */
 
function wdtAlertDialog(str, title){
      	var alert_dialog_str = '<div class="remodal wpDataTables wdtRemodal"><h1>'+title+'</h1>';
      	alert_dialog_str += '<p>'+str+'</p>';
      	alert_dialog_str += '<button class="remodal-confirm btn" href="#">OK</button></div>';
      	jQuery(alert_dialog_str).remodal({
      		type: 'inline',
      		preloader: false,
      		modal: true
      	}).open();
      	jQuery('#wdtPreloadLayer').hide();
		jQuery('.remodal-confirm').focus();
          return;
}

function applySelecter(){
	jQuery('select').selecter('destroy');
	jQuery('select').selecter();
}

jQuery(window).load(function(){
	postboxes.add_postbox_toggles();
	
	jQuery(document).on('click','button.showHint',function(e){
		e.preventDefault();
		e.stopImmediatePropagation();
		
		if(jQuery(this).parent().find('small.hint').is(':visible')){
			jQuery(this).parent().find('small.hint').fadeOut(300);
		}else{
			jQuery(this).parent().find('small.hint').fadeIn(300);
		}
		
	});
	
});