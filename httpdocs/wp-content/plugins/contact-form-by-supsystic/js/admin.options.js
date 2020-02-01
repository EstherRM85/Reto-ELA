var cfsAdminFormChanged = []
,	g_cfsDsblWndPromo = false;
window.onbeforeunload = function(){
	// If there are at lease one unsaved form - show message for confirnation for page leave
	if(cfsAdminFormChanged.length)
		return 'Some changes were not-saved. Are you sure you want to leave?';
};
jQuery(document).ready(function(){
	cfsInitMainPromoWnd();
	if(typeof(cfsActiveTab) != 'undefined' && cfsActiveTab != 'main_page' && jQuery('#toplevel_page_contact-form-supsystic').hasClass('wp-has-current-submenu')) {
		var subMenus = jQuery('#toplevel_page_contact-form-supsystic').find('.wp-submenu li');
		subMenus.removeClass('current').each(function(){
			if(jQuery(this).find('a[href$="&tab='+ cfsActiveTab+ '"]').size()) {
				jQuery(this).addClass('current');
			}
		});
	}
	
	// Timeout - is to count only user changes, because some changes can be done auto when form is loaded
	setTimeout(function() {
		// If some changes was made in those forms and they were not saved - show message for confirnation before page reload
		var formsPreventLeave = [];
		if(formsPreventLeave && formsPreventLeave.length) {
			jQuery('#'+ formsPreventLeave.join(', #')).find('input,select').change(function(){
				var formId = jQuery(this).parents('form:first').attr('id');
				changeAdminFormCfs(formId);
			});
			jQuery('#'+ formsPreventLeave.join(', #')).find('input[type=text],textarea').keyup(function(){
				var formId = jQuery(this).parents('form:first').attr('id');
				changeAdminFormCfs(formId);
			});
			jQuery('#'+ formsPreventLeave.join(', #')).submit(function(){
				adminFormSavedCfs( jQuery(this).attr('id') );
			});
		}
	}, 1000);

	if(jQuery('.cfsInputsWithDescrForm').size()) {
		jQuery('.cfsInputsWithDescrForm').find('input[type=checkbox][data-optkey]').change(function(){
			var optKey = jQuery(this).data('optkey')
			,	descShell = jQuery('#cfsFormOptDetails_'+ optKey);
			if(descShell.size()) {
				if(jQuery(this).attr('checked')) {
					descShell.slideDown( 300 );
				} else {
					descShell.slideUp( 300 );
				}
			}
		}).trigger('change');
	}
	cfsInitStickyItem();
	cfsInitCustomCheckRadio();
	//cfsInitCustomSelect();
	
	jQuery('.cfsFieldsetToggled').each(function(){
		var self = this;
		jQuery(self).find('.cfsFieldsetContent').hide();
		jQuery(self).find('.cfsFieldsetToggleBtn').click(function(){
			var icon = jQuery(this).find('i')
			,	show = icon.hasClass('fa-plus');
			show ? icon.removeClass('fa-plus').addClass('fa-minus') : icon.removeClass('fa-minus').addClass('fa-plus');
			jQuery(self).find('.cfsFieldsetContent').slideToggle( 300, function(){
				if(show) {
					jQuery(this).find('textarea').each(function(i, el){
						if(typeof(this.CodeMirrorEditor) !== 'undefined') {
							this.CodeMirrorEditor.refresh();
						}
					});
				}
			} );
			return false;
		});
	});
	// Go to Top button init
	if(jQuery('#cfsFormGoToTopBtn').size()) {
		jQuery('#cfsFormGoToTopBtn').click(function(){
			jQuery('html, body').animate({
				scrollTop: 0
			}, 1000);
			jQuery(this).parents('#cfsFormGoToTop:first').hide();
			return false;
		});
	}
	// Tooltipster initialization
	cfsInitTooltips();
	if(jQuery('.cfsCopyTextCode').size()) {
		var cloneWidthElement =  jQuery('<span class="sup-shortcode" />').appendTo('.supsystic-plugin');
		jQuery('.cfsCopyTextCode').attr('readonly', 'readonly').bind('click focus', function(){
			this.setSelectionRange(0, this.value.length);
		});
		jQuery('.cfsCopyTextCode').each(function(){
			cloneWidthElement.html( str_replace(jQuery(this).val(), '<', 'P') );
			jQuery(this).width( cloneWidthElement.width() );
		});
		cloneWidthElement.remove();
	}
	// Check for showing review notice after a week usage
    cfsInitPlugNotices();

	jQuery(".supsystic-plugin .tooltipstered").removeAttr("title");

});
function cfsInitTooltips( selector ) {
	var tooltipsterSettings = {
		contentAsHTML: true
	,	interactive: true
	,	speed: 0
	,	delay: 0
	//,	animation: 'swing'
	,	maxWidth: 450
	}
	,	findPos = {
		'.supsystic-tooltip': 'top-left'
	,	'.supsystic-tooltip-bottom': 'bottom-left'
	,	'.supsystic-tooltip-left': 'left'
	,	'.supsystic-tooltip-right': 'right'
	}
	,	$findIn = selector ? jQuery( selector ) : false;
	for(var k in findPos) {
		if(typeof(k) === 'string') {
			var $tips = $findIn ? $findIn.find( k ) : jQuery( k ).not('.sup-no-init');
			if($tips && $tips.size()) {
				tooltipsterSettings.position = findPos[ k ];
				// Fallback for case if library was not loaded
				if(!$tips.tooltipster) continue;
				$tips.tooltipster( tooltipsterSettings );
			}
		}
	}
}
function changeAdminFormCfs(formId) {
	if(jQuery.inArray(formId, cfsAdminFormChanged) == -1)
		cfsAdminFormChanged.push(formId);
}
function adminFormSavedCfs(formId) {
	if(cfsAdminFormChanged.length) {
		for(var i in cfsAdminFormChanged) {
			if(cfsAdminFormChanged[i] == formId) {
				cfsAdminFormChanged.pop(i);
			}
		}
	}
}
function checkAdminFormSaved() {
	if(cfsAdminFormChanged.length) {
		if(!confirm(toeLangCfs('Some changes were not-saved. Are you sure you want to leave?'))) {
			return false;
		}
		cfsAdminFormChanged = [];	// Clear unsaved forms array - if user wanted to do this
	}
	return true;
}
function isAdminFormChanged(formId) {
	if(cfsAdminFormChanged.length) {
		for(var i in cfsAdminFormChanged) {
			if(cfsAdminFormChanged[i] == formId) {
				return true;
			}
		}
	}
	return false;
}
/*Some items should be always on users screen*/
function cfsInitStickyItem() {
	jQuery(window).scroll(function(){
		var stickiItemsSelectors = [/*'.ui-jqgrid-hdiv', */'.supsystic-sticky']
		,	elementsUsePaddingNext = [/*'.ui-jqgrid-hdiv', */'.supsystic-bar']	// For example - if we stick row - then all other should not offest to top after we will place element as fixed
		,	wpTollbarHeight = 32
		,	wndScrollTop = jQuery(window).scrollTop() + wpTollbarHeight
		,	footer = jQuery('.cfsAdminFooterShell')
		,	footerHeight = footer && footer.size() ? footer.height() : 0
		,	docHeight = jQuery(document).height()
		,	wasSticking = false
		,	wasUnSticking = false;
		/*if(jQuery('#wpbody-content .update-nag').size()) {	// Not used for now
			wpTollbarHeight += parseInt(jQuery('#wpbody-content .update-nag').outerHeight());
		}*/
		for(var i = 0; i < stickiItemsSelectors.length; i++) {
			jQuery(stickiItemsSelectors[ i ]).each(function(){
				var element = jQuery(this);
				if(element && element.size() && !element.hasClass('sticky-ignore')) {
					var scrollMinPos = element.offset().top
					,	prevScrollMinPos = parseInt(element.data('scrollMinPos'))
					,	useNextElementPadding = toeInArray(stickiItemsSelectors[ i ], elementsUsePaddingNext) !== -1 || element.hasClass('sticky-padd-next')
					,	currentScrollTop = wndScrollTop
					,	calcPrevHeight = element.data('prev-height')
					,	currentBorderHeight = wpTollbarHeight
					,	usePrevHeight = 0;
					if(calcPrevHeight) {
						usePrevHeight = jQuery(calcPrevHeight).outerHeight();
						currentBorderHeight += usePrevHeight;
					}
					if(currentScrollTop > scrollMinPos && !element.hasClass('supsystic-sticky-active')) {	// Start sticking
						if(element.hasClass('sticky-save-width')) {
							element.width( element.width() );
							//element.addClass('sticky-full-width');
						}
						element.addClass('supsystic-sticky-active').data('scrollMinPos', scrollMinPos).css({
							'top': currentBorderHeight
						});
						if(useNextElementPadding) {
							//element.addClass('supsystic-sticky-active-bordered');
							var nextElement = element.next();
							if(nextElement && nextElement.size()) {
								nextElement.data('prevPaddingTop', nextElement.css('padding-top'));
								var addToNextPadding = parseInt(element.data('next-padding-add'));
								addToNextPadding = addToNextPadding ? addToNextPadding : 0;
								nextElement.css({
									'padding-top': (element.hasClass('sticky-outer-height') ? element.outerHeight() : element.height()) + usePrevHeight + addToNextPadding
								});
							}
						}
						wasSticking = true;
						element.trigger('startSticky');
					} else if(!isNaN(prevScrollMinPos) && currentScrollTop <= prevScrollMinPos) {	// Stop sticking
						element.removeClass('supsystic-sticky-active').data('scrollMinPos', 0).css({
							//'top': 0
						});
						if(element.hasClass('sticky-save-width')) {
							if(element.hasClass('sticky-base-width-auto')) {
								element.css('width', 'auto');
							}
							//element.removeClass('sticky-full-width');
						}
						if(useNextElementPadding) {
							//element.removeClass('supsystic-sticky-active-bordered');
							var nextElement = element.next();
							if(nextElement && nextElement.size()) {
								var nextPrevPaddingTop = parseInt(nextElement.data('prevPaddingTop'));
								if(isNaN(nextPrevPaddingTop))
									nextPrevPaddingTop = 0;
								nextElement.css({
									'padding-top': nextPrevPaddingTop
								});
							}
						}
						element.trigger('stopSticky');
						wasUnSticking = true;
					} else {	// Check new stick position
						if(element.hasClass('supsystic-sticky-active')) {
							if(footerHeight) {
								var elementHeight = element.height()
								,	heightCorrection = 32
								,	topDiff = docHeight - footerHeight - (currentScrollTop + elementHeight + heightCorrection);
								if(topDiff < 0) {
									element.css({
										'top': currentBorderHeight + topDiff
									});
								} else {
									element.css({
										'top': currentBorderHeight
									});
								}
							}
							// If at least on element is still sticking - count it as all is working
							wasSticking = wasUnSticking = false;
						}
					}
				}
			});
		}
		if(wasSticking) {
			if(jQuery('#cfsFormGoToTop').size())
				jQuery('#cfsFormGoToTop').show();
		} else if(wasUnSticking) {
			if(jQuery('#cfsFormGoToTop').size())
				jQuery('#cfsFormGoToTop').hide();
		}
	});
}
function cfsInitCustomCheckRadio(selector) {
	var $inputs;
	if(!selector) {
		selector = document;
		$inputs = jQuery(selector).find('input:not(.sup-no-init)');
	} else {
		$inputs = jQuery(selector).find('input');
	}
	$inputs.iCheck('destroy').iCheck({
		checkboxClass: 'icheckbox_minimal'
	,	radioClass: 'iradio_minimal'
	}).on('ifChanged', function(e){
		// for checkboxHiddenVal type, see class htmlCfs
		jQuery(this).trigger('change');
		if(jQuery(this).hasClass('cbox')) {
			var parentRow = jQuery(this).parents('.jqgrow:first');
			if(parentRow && parentRow.size()) {
				jQuery(this).parents('td:first').trigger('click');
			} else {
				var checkId = jQuery(this).attr('id');
				if(checkId && checkId != '' && strpos(checkId, 'cb_') === 0) {
					var parentTblId = str_replace(checkId, 'cb_', '');
					if(parentTblId && parentTblId != '' && jQuery('#'+ parentTblId).size()) {
						jQuery('#'+ parentTblId).find('input[type=checkbox]').iCheck('update');
					}
				}
			}
		}
	}).on('ifClicked', function(e){
		jQuery(this).trigger('click');
	});
}
function cfsCheckDestroy(checkbox) {
	jQuery(checkbox).iCheck('destroy');
}
function cfsCheckDestroyArea(selector) {
	jQuery(selector).find('input[type=checkbox]').iCheck('destroy');
}
function cfsCheckUpdate(checkbox) {
	jQuery(checkbox).iCheck('update');
}
function cfsCheckUpdateArea(selector) {
	jQuery(selector).find('input[type=checkbox]').iCheck('update');
}
/**
 * Add data to jqGrid object post params search
 * @param {object} param Search params to set
 * @param {string} gridSelectorId ID of grid table html element
 */
function cfsGridSetListSearch(param, gridSelectorId) {
	jQuery('#'+ gridSelectorId).setGridParam({
		postData: {
			search: param
		}
	});
}
/**
 * Set data to jqGrid object post params search and trigger search
 * @param {object} param Search params to set
 * @param {string} gridSelectorId ID of grid table html element
 */
function cfsGridDoListSearch(param, gridSelectorId) {
	cfsGridSetListSearch(param, gridSelectorId);
	jQuery('#'+ gridSelectorId).trigger( 'reloadGrid' );
}
/**
 * Get row data from jqGrid
 * @param {number} id Item ID (from database for example)
 * @param {string} gridSelectorId ID of grid table html element
 * @return {object} Row data
 */
function cfsGetGridDataById(id, gridSelectorId) {
	var rowId = getGridRowId(id, gridSelectorId);
	if(rowId) {
		return jQuery('#'+ gridSelectorId).jqGrid ('getRowData', rowId);
	}
	return false;
}
/**
 * Get cell data from jqGrid
 * @param {number} id Item ID (from database for example)
 * @param {string} column Column name
 * @param {string} gridSelectorId ID of grid table html element
 * @return {string} Cell data
 */
function cfsGetGridColDataById(id, column, gridSelectorId) {
	var rowId = getGridRowId(id, gridSelectorId);
	if(rowId) {
		return jQuery('#'+ gridSelectorId).jqGrid ('getCell', rowId, column);
	}
	return false;
}
/**
 * Get grid row ID (ID of table row) from item ID (from database ID for example)
 * @param {number} id Item ID (from database for example)
 * @param {string} gridSelectorId ID of grid table html element
 * @return {number} Table row ID
 */
function getGridRowId(id, gridSelectorId) {
	var rowId = parseInt(jQuery('#'+ gridSelectorId).find('[aria-describedby='+ gridSelectorId+ '_id][title='+ id+ ']').parent('tr:first').index());
	if(!rowId) {
		console.log('CAN NOT FIND ITEM WITH ID  '+ id);
		return false;
	}
	return rowId;
}
function prepareToPlotDate(data) {
	if(typeof(data) === 'string') {
		if(data) {
			data = str_replace(data, '/', '-');
			return (new Date(data)).getTime();
		}
	}
	return data;
}
function cfsInitPlugNotices() {
	var $notices = jQuery('.supsystic-admin-notice');
	if($notices && $notices.size()) {
		$notices.each(function(){
			jQuery(this).find('.notice-dismiss').click(function(){
				var $notice = jQuery(this).parents('.supsystic-admin-notice');
				if(!$notice.data('stats-sent')) {
					// User closed this message - that is his choise, let's respect this and save it's saved status
					jQuery.sendFormCfs({
						data: {mod: 'supsystic_promo', action: 'addNoticeAction', code: $notice.data('code'), choice: 'hide'}
					});
				}
			});
			jQuery(this).find('[data-statistic-code]').click(function(){
				var href = jQuery(this).attr('href')
				,	$notice = jQuery(this).parents('.supsystic-admin-notice');
				jQuery.sendFormCfs({
					data: {mod: 'supsystic_promo', action: 'addNoticeAction', code: $notice.data('code'), choice: jQuery(this).data('statistic-code')}
				});
				$notice.data('stats-sent', 1).find('.notice-dismiss').trigger('click');
				if(!href || href === '' || href === '#')
					return false;
			});
		});
	}
}
/**
 * Main promo forms will show each time user will try to modify PRO option with free version only
 */
function cfsGetMainPromoWnd() {
	if(jQuery('#cfsOptInProWnd').hasClass('ui-dialog-content')) {
		return jQuery('#cfsOptInProWnd');
	}
	return jQuery('#cfsOptInProWnd').dialog({
		modal:    true
	,	autoOpen: false
	,	width: 540
	,	height: 200
	,	open: function() {
			jQuery('#cfsOptWndTemplateTxt').hide();
			jQuery('#cfsOptWndOptionTxt').show();
		}
	});
}
function cfsFillInMainPromoWnd($shell) {
	var $promoLink = $shell.find('.cfsProOptMiniLabel a').attr('href');
	if($promoLink && $promoLink != '') {
		jQuery('#cfsOptInProWnd a').attr('href', $promoLink);
	}
}
function cfsFillAndShowMainPromoWnd($shell) {
	var $proOptWnd = cfsGetMainPromoWnd();
	cfsFillInMainPromoWnd( $shell );
	$proOptWnd.dialog('open');
}
function cfsInitMainPromoWnd() {
	if(!CFS_DATA.isPro) {
		var $proOptWnd = cfsGetMainPromoWnd();
		jQuery('.cfsProOpt').change(function(e){
			e.stopPropagation();
			if( !g_cfsDsblWndPromo ) {
				var needShow = true
				,	isRadio = jQuery(this).attr('type') == 'radio'
				,	isCheck = jQuery(this).attr('type') == 'checkbox';
				if((isRadio || isCheck) && !jQuery(this).prop('checked')) {
					needShow = false;
				}
				if(!needShow) {
					return;
				}
				if(isRadio) {
					jQuery('input[name="'+ jQuery(this).attr('name')+ '"]:first').parents('label:first').click();
					if(jQuery(this).parents('.iradio_minimal:first').size()) {
						var self = this;
						setTimeout(function(){
							jQuery(self).parents('.iradio_minimal:first').removeClass('checked');
						}, 10);
					}
				}
				var parent = null;
				if(jQuery(this).parents('#cfsFormMainOpts').size()) {
					parent = jQuery(this).parents('label:first');
				} else if(jQuery(this).parents('.cfsFormOptRow:first').size()) {
					parent = jQuery(this).parents('.cfsFormOptRow:first');
				} else {
					parent = jQuery(this).parents('tr:first');
				}
				if(!parent.size()) return;
				cfsFillInMainPromoWnd(parent);
				$proOptWnd.dialog('open');
			}
			return false;
		});
	}
}
/**
 * Modify inputs names, that is structured like arrays, to include it's iteration numbers.
 * Like if you have dynamicaly added for example several inputs with names "some_name[][label]" - 
 * it will modify it to "some_name[0][label]", "some_name[1][label]", "some_name[2][label]", etc.
 * @param {jquery} $rows Html rows/cells/blocks to search and replace
 * @param {string} pattern Input names to search for, in description example - it will be "some_name"
 */
function cfsUpdateInputsSortOrder( $rows, pattern ) {
	var i = 0;
	$rows.each(function(){
		var $inputs = jQuery(this).find('[name*="'+ pattern+ '"]');
		$inputs.each(function(){
			var name = jQuery(this).attr('name')
			,	replacePattern = cfsStrReplaceGlob(cfsStrReplaceGlob(pattern, '\\[', '\\['), '\\]', '\\]')
			,	nameRegEx = new RegExp('('+ replacePattern+ '\\[\\]|'+ replacePattern+ '\\[\\d\\]'+ ')', 'g');
			jQuery(this).attr('name', name.replace(nameRegEx, pattern+ '['+ i+ ']'));
		});
		i++;
	});
}