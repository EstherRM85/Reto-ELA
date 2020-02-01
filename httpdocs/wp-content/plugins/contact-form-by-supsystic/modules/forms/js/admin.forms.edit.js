var cfsFormSaveTimeout = null
,	cfsFormIsSaving = false
,	cfsTinyMceEditorUpdateBinded = false
,	cfsSaveWithoutPreviewUpdate = false
,	cfsOneLineEditors = ['#cfsFormFieldWrapperEditor'];
jQuery(document).ready(function(){
	jQuery('#cfsFormEditTabs').wpTabs({
		uniqId: 'cfsFormEditTabs'
	,	change: function(selector) {
			if(selector == '#cfsFormEditors') {
				jQuery(selector).find('textarea').each(function(i, el){
					if(typeof(this.CodeMirrorEditor) !== 'undefined') {
						this.CodeMirrorEditor.refresh();
					}
				});
			} else if(selector == '#cfsFormStatistics' && typeof(cfsRefreshCharts) === 'function') {
				cfsRefreshCharts();
			}
			if(selector == '#cfsFormStatistics') {	// Hide preview for statistics tab
				jQuery('#cfsFormPreview').hide();
			} else {
				jQuery('#cfsFormPreview').show();
			}
			var tabChangeEvt = str_replace(selector, '#', '')+ '_tabSwitch';
			jQuery(document).trigger( tabChangeEvt, selector );
		}
	});
	jQuery('#cfsFormSubmitEditTabs').wpTabs({
		uniqId: 'cfsFormSubmitEditTabs'
	});
	jQuery('.cfsFormSaveBtn').click(function(){
		cfsSaveForm();
		return false;
	});
	jQuery('#enableForMembership').on('change', function() {
		jQuery('#inpMembershipEnable').val(jQuery('#enableForMembership').val());
	});
	
	jQuery('#cfsFormEditForm').submit(function(){
		if(!cfsValidateFormSave()) {
			return false;
		}
		// Don't save if form isalready submitted
		if(cfsFormIsSaving) {
			cfsMakeAutoUpdate();
			return false;
		}
		if(!cfsSaveWithoutPreviewUpdate)
			cfsShowPreviewUpdating();
		cfsFormIsSaving = true;
		var addData = {};
		if(cfsForm.params.opts_attrs.txt_block_number) {
			for(var i = 0; i < cfsForm.params.opts_attrs.txt_block_number; i++) {
				var textId = 'params_tpl_txt_'+ i
				,	sendValKey = 'params_tpl_txt_val_'+ i;
				addData[ sendValKey ] = encodeURIComponent( cfsGetTxtEditorVal( textId ) );
			}
		}
		var cssEditor = jQuery('#cfsFormCssEditor').get(0).CodeMirrorEditor
		,	htmlEditor = jQuery('#cfsFormHtmlEditor').get(0).CodeMirrorEditor
		,	cssSet = false
		,	htmlSet = false;
		
		if(cssEditor) {
			if(cssEditor._cfsChanged) {
				jQuery('#cfsFormCssEditor').val( cssEditor.getValue() );
				cssEditor._cfsChanged = false;
			} else {
				jQuery('#cfsFormCssEditor').val('');
				cssSet = cssEditor.getValue();
			}
		}
		if(htmlEditor) {
			if(htmlEditor._cfsChanged) {
				jQuery('#cfsFormHtmlEditor').val( htmlEditor.getValue() );
				htmlEditor._cfsChanged = false;
			} else {
				jQuery('#cfsFormHtmlEditor').val('');
				htmlSet = htmlEditor.getValue();
			}
		}
		for(var i = 0; i < cfsOneLineEditors.length; i++) {
			var $currEditField = jQuery(cfsOneLineEditors[ i ])
			,	mirrorEditor = $currEditField.get(0).CodeMirrorEditor;
			if(!mirrorEditor) continue
			$currEditField.val( mirrorEditor.getValue() );
		}
		jQuery(this).sendFormCfs({
			btn: jQuery('.cfsFormSaveBtn')
		,	appendData: addData
		,	onSuccess: function(res) {
				cfsFormIsSaving = false;
				if(!res.error) {
					if(!cfsSaveWithoutPreviewUpdate)
						cfsRefreshPreview();
				}
				cfsSaveWithoutPreviewUpdate = false;
				if(cssSet && cssEditor) {
					jQuery('#cfsFormCssEditor').val( cssEditor.getValue() );
				}
				if(htmlSet && htmlEditor) {
					jQuery('#cfsFormHtmlEditor').val( htmlEditor.getValue() );
				}
			}
		});
		return false;
	});
	
	jQuery('.cfsBgTypeSelect').change(function(){
		var iter = jQuery(this).data('iter');
		jQuery('.cfsBgTypeShell_'+ iter).hide();
		switch(jQuery(this).val()) {
			case 'img':
				jQuery('.cfsBgTypeImgShell_'+ iter).show();
				break;
			case 'color':
				jQuery('.cfsBgTypeColorShell_'+ iter).show();
				break;
		}
	}).change();
	// Fallback for case if library was not loaded
	if(typeof(CodeMirror) !== 'undefined') {
		var cssEditor = CodeMirror.fromTextArea(jQuery('#cfsFormCssEditor').get(0), {
			mode: 'css'
		,	lineWrapping: true
		,	lineNumbers: true
		,	matchBrackets: true
		,	autoCloseBrackets: true
		});
		jQuery('#cfsFormCssEditor').get(0).CodeMirrorEditor = cssEditor;
		if(cssEditor.on && typeof(cssEditor.on) == 'function') {
			cssEditor.on('change', function( editor ){
				editor._cfsChanged = true;
				cfsMakeAutoUpdate( 3000 );
			});
		}
		var htmlEditor = CodeMirror.fromTextArea(jQuery('#cfsFormHtmlEditor').get(0), {
			mode: 'text/html'
		,	lineWrapping: true
		,	lineNumbers: true
		,	matchBrackets: true
		,	autoCloseBrackets: true
		});
		jQuery('#cfsFormHtmlEditor').get(0).CodeMirrorEditor = htmlEditor;
		if(htmlEditor.on && typeof(htmlEditor.on) == 'function') {
			htmlEditor.on('change', function( editor ){
				editor._cfsChanged = true;
				cfsMakeAutoUpdate( 3000 );
			});
		}
		for(var i = 0; i < cfsOneLineEditors.length; i++) {
			var $currEditField = jQuery(cfsOneLineEditors[ i ])
			,	currEditor = CodeMirror.fromTextArea($currEditField.get(0), {
					mode: 'text/html'
				,	lineWrapping: true
				,	lineNumbers: true
				,	matchBrackets: true
				,	autoCloseBrackets: true
			});
			$currEditField.get(0).CodeMirrorEditor = currEditor;
			currEditor.on('keydown', function(mirror, e) {
				if(e.keyCode == 13) {	// Enter
					this._cfsCancelEvent = true;
				}
			});
			currEditor.on('beforeChange', function(mirror, changeObj) {
				if(this._cfsCancelEvent) {
					changeObj.cancel()
					this._cfsCancelEvent = false;
				}
			});
			jQuery(currEditor.getWrapperElement()).addClass('cfsCodeMirrorOneLine');
		}
	}
	// Shortcodes example switch
	jQuery('#cfsFormShortcodeExampleSel').change(function(){
		jQuery('.cfsFormWhereShowBlock').hide().filter('[data-for="'+ jQuery(this).val()+ '"]').show();
	}).trigger('change');
	// Fallback for case if library was not loaded
	if(!jQuery.fn.chosen) {
		jQuery.fn.chosen = function() {
			
		};
	}
	jQuery('.chosen').chosen({
		disable_search_threshold: 10
	});
	jQuery('.chosen.chosen-responsive').each(function(){
		jQuery(this).next('.chosen-container').addClass('chosen-responsive');
	});
	jQuery('.cfsFormPreviewBtn').click(function(){
		cfsSaveFormChanges();
		jQuery('html, body').animate({
			scrollTop: jQuery("#cfsFormPreview").offset().top
		}, 1000);
		return false;
	});
	// Delete btn init
	jQuery('.cfsFormRemoveBtn').click(function(){
		if(confirm(toeLangCfs('Are you sure want to remove this Form?'))) {
			jQuery.sendFormCfs({
				btn: this
			,	data: {mod: 'forms', action: 'remove', id: cfsForm.id}
			,	onSuccess: function(res) {
					if(!res.error) {
						toeRedirect( cfsAddNewUrl );
					}
				}
			});
		}
		return false;
	});
	// Don't allow users to set more then 100% width
	jQuery('#cfsFormEditForm').find('[name="params[tpl][width]"]').keyup(function(){
		var measureType = jQuery('#cfsFormEditForm').find('[name="params[tpl][width_measure]"]:checked').val();
		if(measureType == '%') {
			var currentValue = parseInt( jQuery(this).val() );
			if(currentValue > 100) {
				jQuery(this).val( 100 );
			}
		}
	});
	jQuery('#cfsFormEditForm').find('[name="params[tpl][width_measure]"]').change(function(){
		if(!jQuery(this).attr('checked'))
			return;
		var widthInput = jQuery('#cfsFormEditForm').find('[name="params[tpl][width]"]');
		if(jQuery(this).val() == '%') {
			var currentWidth = parseInt(widthInput.val());
			if(currentWidth > 100) {
				widthInput.data('prev-width', currentWidth);
				widthInput.val(100);
			}
		} else if(widthInput.data('prev-width')) {
			widthInput.val( widthInput.data('prev-width') );
		}
	});
	// Init Save as Copy function
	cfsFormInitSaveAsCopyDlg();
	jQuery(window).resize(function(){
		cfsAdjustFormsEditTabs();
	});
	// Switch Off/Onn button
	// It's working from shortcode or widget only - so no need to switch it's active status
	/*cfsFormCheckSwitchActiveBtn();
	jQuery('.cfsFormSwitchActive').click(function(){
		var newActive = parseInt(cfsForm.active) ? 0 : 1;
		jQuery.sendFormCfs({
			btn: this
		,	data: {mod: 'forms', action: 'switchActive', id: cfsForm.id, active: newActive}
		,	onSuccess: function(res) {
				if(!res.error) {
					cfsForm.active = newActive;
					cfsFormCheckSwitchActiveBtn();
				}
			}
		});
		return false;
	});*/
	jQuery('#supsystic-breadcrumbs').bind('startSticky', function(){
		var currentPadding = parseInt(jQuery('#cfsFormMainControllsShell').css('padding-right'));
		jQuery('#cfsFormMainControllsShell').css('padding-right', currentPadding + 200).attr('data-padding-changed', 'padding is changed in admin.forms.edit.js');
	});
	jQuery('#supsystic-breadcrumbs').bind('stopSticky', function(){
		var currentPadding = parseInt(jQuery('#cfsFormMainControllsShell').css('padding-right'));
		jQuery('#cfsFormMainControllsShell').css('padding-right', currentPadding - 200);
	});
	// Editable Form title
	jQuery('#cfsFormEditableLabelShell').click(function(){
		var isEdit = jQuery(this).data('edit-on');
		if(!isEdit) {
			var $labelHtml = jQuery('#cfsFormEditableLabel')
			,	$labelTxt = jQuery('#cfsFormEditableLabelTxt');
			$labelTxt.val( $labelHtml.text() );
			$labelHtml.hide( g_cfsAnimationSpeed );
			$labelTxt.show( g_cfsAnimationSpeed, function(){
				jQuery(this).data('ready', 1);
			});
			jQuery(this).data('edit-on', 1);
		}
	});
	// Edit Form Label
	jQuery('#cfsFormEditableLabelTxt').blur(function(){
		cfsFinishEditFormLabel( jQuery(this).val() );
	}).keydown(function(e){
		if(e.keyCode == 13) {	// Enter pressed
			cfsFinishEditFormLabel( jQuery(this).val() );
		}
	});
	// Save contacts data change
	jQuery('#cfsFormEditForm [name="params[tpl][save_contacts]"]').change(function(){
		if(jQuery(this).prop('checked')) {
			jQuery('.cfsContactExportCfsBtnShell').slideDown( g_cfsAnimationSpeed );
		} else {
			jQuery('.cfsContactExportCfsBtnShell').slideUp( g_cfsAnimationSpeed );
		}
	}).change();
	// Show/hide whole blocks after it's enable/disable by special attribute - data-switch-block
	jQuery('input[type=checkbox][data-switch-block]').change(function(){
		var blockToSwitch = jQuery(this).data('switch-block');
		if(jQuery(this).prop('checked')) {
			jQuery('[data-block-to-switch='+ blockToSwitch+ ']').slideDown( g_cfsAnimationSpeed );
		} else {
			jQuery('[data-block-to-switch='+ blockToSwitch+ ']').slideUp( g_cfsAnimationSpeed );
		}
	}).change();
	// Email attach settings
	/*jQuery('.cfsFormAddEmailAttachBtn').click(function(){
		cfsAddEmailAttach({
			$parentShell: jQuery(this).parents('.cfsFormAttachFilesShell:first')
		});
		return false;
	});
	jQuery('.cfsFormAttachFilesShell').each(function(){
		var $this = jQuery(this)
		,	key = $this.data('key')
		,	filesKey = 'sub_attach_'+ key;
		if(cfsForm.params 
			&& cfsForm.params.tpl 
			&& cfsForm.params.tpl[ filesKey ]
		) {
			for(var i in cfsForm.params.tpl[ filesKey ]) {
				if(cfsForm.params.tpl[ filesKey ][ i ] && cfsForm.params.tpl[ filesKey ][ i ] != '') {
					cfsAddEmailAttach({
						$parentShell: $this
					,	file: cfsForm.params.tpl[ filesKey ][ i ]
					});
				}
			}
		}
	});*/
	// Submit main form by Enter key
	jQuery('#cfsFormEditForm input[type=text]').keypress(function(e){
		if (e.which == 13) {
			e.preventDefault();
			cfsSaveForm();
		}
	});
});
function cfsValidateFormSave() {
	if(!g_cfsFieldsFrame.haveSubmitField()) {
		_cfsShowSaveFormErrorWnd('submit_btn');
		return false;
	}
	if(!g_cfsFormsSubmit.haveSubmitData()) {
		_cfsShowSaveFormErrorWnd('submit_data');
		return false;
	}
	return true;
}
function _cfsShowSaveFormErrorWnd( code ) {
	if(!this._wnd) {
		var self = this;
		this._wnd = jQuery('#cfsSaveFormErrorWnd').dialog({
			modal:    true
		,	autoOpen: false
		,	width: 460
		,	height: 180
		,	buttons:  {
				OK: function() {
					self._wnd.dialog('close');
				}
			}
		});
	}
	this._wnd.find('.cfsSaveFormErrorEx').hide().filter('[data-code="'+ code+ '"]').show();
	this._wnd.dialog('open');
}
/*function cfsAddEmailAttach(params) {
	var $parent = params.$parentShell
	,	$newShell = jQuery('#cfsFormAttachShell').clone().removeAttr('id')
	,	$input = $newShell.find('[name="params[tpl][sub_attach][]"]').removeAttr('disabled')
	,	$fileName = $newShell.find('.cfsFormAttachFile')
	,	key = $parent.data('key');
	$parent.append( $newShell );
	$input.attr('name', 'params[tpl][sub_attach_'+ key+ '][]');
	var _setFileClb = function( url ) {
		$input.val( url );
		$fileName.html( url );
	};
	$newShell.find('.cfsFormAttachBtn').click(function(){
		var button = jQuery(this);
		_custom_media = true;
		wp.media.editor.send.attachment = function(props, attachment){
			if ( _custom_media ) {
				_setFileClb( attachment.url );
			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
			};
		};
		wp.media.editor.open(button);
		return false;
	});
	$newShell.find('.cfsFormAttachRemoveBtn').click(function(){
		$newShell.remove();
		return false;
	});
	if(params.file) {
		_setFileClb( params.file );
	}
}*/
jQuery(window).load(function(){
	cfsAdjustFormsEditTabs();
});
function cfsFinishEditFormLabel(label) {
	if(jQuery('#cfsFormEditableLabelShell').data('sending')) return;
	if(!jQuery('#cfsFormEditableLabelTxt').data('ready')) return;
	jQuery('#cfsFormEditableLabelShell').data('sending', 1);
	jQuery.sendFormCfs({
		btn: jQuery('#cfsFormEditableLabelShell')
	,	data: {mod: 'forms', action: 'updateLabel', label: label, id: cfsForm.id}
	,	onSuccess: function(res) {
			if(!res.error) {
				var $labelHtml = jQuery('#cfsFormEditableLabel')
				,	$labelTxt = jQuery('#cfsFormEditableLabelTxt');
				$labelHtml.html( jQuery.trim($labelTxt.val()) );
				$labelTxt.hide( g_cfsAnimationSpeed ).data('ready', 0);
				$labelHtml.show( g_cfsAnimationSpeed );
				jQuery('#cfsFormEditableLabelShell').data('edit-on', 0);
			}
			jQuery('#cfsFormEditableLabelShell').data('sending', 0);
		}
	});
}
/**
 * Make forms edit tabs - responsive
 * @param {bool} requring is function - called in requring way
 */
function cfsAdjustFormsEditTabs(requring) {
	jQuery('#cfsFormEditTabs .supsystic-always-top')
			.outerWidth( jQuery('#cfsFormEditTabs').width() )
			.attr('data-code-tip', 'Width was set in admin.forms.edit.js - cfsAdjustFormsEditTabs()');
	
	var checkTabsNavs = ['#cfsFormEditTabs .nav-tab-wrapper:first'];
	for(var i = 0; i < checkTabsNavs.length; i++) {
		var tabs = jQuery(checkTabsNavs[i])
		,	delta = 10
		,	lineWidth = tabs.width() + delta
		,	fullCurrentWidth = 0
		,	currentState = '';	//full, text, icons

		if(!tabs.find('.cfs-edit-icon').is(':visible')) {
			currentState = 'text';
		} else if(!tabs.find('.cfsFormTabTitle').is(':visible')) {
			currentState = 'icons';
		} else {
			currentState = 'full';
		}

		tabs.find('.nav-tab').each(function(){
			fullCurrentWidth += jQuery(this).outerWidth();
		});

		if(fullCurrentWidth > lineWidth) {
			switch(currentState) {
				case 'full':
					tabs.find('.cfs-edit-icon').hide();
					cfsAdjustFormsEditTabs(true);	// Maybe we will require to make it more smaller
					break;
				case 'text':
					tabs.find('.cfs-edit-icon').show().end().find('.cfsFormTabTitle').hide();
					break;
				default:
					// Nothing can do - all that can be hidden - is already hidden
					break;
			}
		} else if(fullCurrentWidth < lineWidth && (lineWidth - fullCurrentWidth > 400) && !requring) {
			switch(currentState) {
				case 'icons':
					tabs.find('.cfs-edit-icon').hide().end().find('.cfsFormTabTitle').show();
					break;
				case 'text':
					tabs.find('.cfs-edit-icon').show().end().find('.cfsFormTabTitle').show();
					break;
				default:
					// Nothing can do - all that can be hidden - is already hidden
					break;
			}
		}
	}
}
function cfsShowImgPrev(url, attach, buttonId) {
	var iter = jQuery('#'+ buttonId).data('iter');
	jQuery('.cfsBgImgPrev_'+ iter).attr('src', url);
}
function cfsSaveFormChanges(withoutPreviewUpdate) {
	// Triger save
	if(withoutPreviewUpdate)
		cfsSaveWithoutPreviewUpdate = true;
	jQuery('.cfsFormSaveBtn').click();
}
function cfsRefreshPreview() {
	document.getElementById('cfsFormPreviewFrame').contentWindow.location.reload();
}
function cfsMakeAutoUpdate(delay) {
	if(parseInt(toeOptionCfs('disable_autosave'))) {
		return;	// Autosave disabled in admin area
	}
	delay = delay ? delay : 1500;
	if(cfsFormSaveTimeout)
		clearTimeout( cfsFormSaveTimeout );
	cfsFormSaveTimeout = setTimeout(cfsSaveFormChanges, delay);
}
function cfsShowPreviewUpdating() {
	this._posSet;
	if(!this._posSet) {
		this._posSet = true;
		jQuery('#cfsFormPreviewUpdatingMsg').css({
			'left': 'calc(50% - '+ (jQuery('#cfsFormPreviewUpdatingMsg').width() / 2)+ 'px)'
		});
	}
	jQuery('#cfsFormPreviewFrame').css({
		'opacity': 0.5
	});
	jQuery('#cfsFormPreviewUpdatingMsg').slideDown( g_cfsAnimationSpeed );
}
function cfsHidePreviewUpdating() {
	jQuery('#cfsFormPreviewFrame').show().css({
		'opacity': 1
	});
	jQuery('#cfsFormPreviewUpdatingMsg').slideUp( 100 );
}
function cfsFormInitSaveAsCopyDlg() {
	var $container = jQuery('#cfsFormSaveAsCopyWnd').dialog({
		modal:    true
	,	autoOpen: false
	,	width: 460
	,	height: 180
	,	buttons:  {
			OK: function() {
				jQuery('#cfsFormSaveAsCopyForm').submit();
			}
		,	Cancel: function() {
				$container.dialog('close');
			}
		}
	});
	jQuery('#cfsFormSaveAsCopyForm').submit(function(){
		jQuery(this).sendFormCfs({
			msgElID: 'cfsFormSaveAsCopyMsg'
		,	onSuccess: function(res) {
				if(!res.error && res.data.edit_link) {
					toeRedirect( res.data.edit_link );
				}
			}
		});
		return false;
	});
	jQuery('.cfsFormCloneBtn').click(function(){
		$container.dialog('open');
		return false;
	});
}
function cfsFormCheckSwitchActiveBtn() {
	if(parseInt(cfsForm.active)) {
		jQuery('.cfsFormSwitchActive .fa').removeClass('fa-toggle-on').addClass('fa-toggle-off');
		jQuery('.cfsFormSwitchActive span').html( jQuery('.cfsFormSwitchActive').data('txt-off') )
	} else {
		jQuery('.cfsFormSwitchActive .fa').removeClass('fa-toggle-off').addClass('fa-toggle-on');
		jQuery('.cfsFormSwitchActive span').html( jQuery('.cfsFormSwitchActive').data('txt-on') );	
	}
}
function cfsSaveForm() {
	jQuery('#cfsFormEditForm').submit();
}
