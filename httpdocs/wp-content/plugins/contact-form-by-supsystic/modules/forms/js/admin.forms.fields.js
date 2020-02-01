var g_cfsFieldsFrame = {
	_$addWnd: null
,	_$editWnd: null
,	_$htmlEditorWnd: null
,	_htmlEditorId: 'cfs_html_field_editor'
,	_$googleMapsWnd: null
,	_googleMapsId: '#cfsFieldGoogleMapsSel'
,	_$editFieldShell: null
,	_$mainShell: null
,	_$addFieldNextTo: null
,	_addFieldNextToPos: ''
,	_$listOptsShell: null
,	_sortInProgress: false
,	_$patternInput: null
,	_fields: {
		name: {html: 'text', mandatory: 1}
	,	label: {html: 'text', mandatory: 1}
	,	placeholder: {html: 'text', mandatory: 1}
	,	value: {html: 'text'}
	,	value_preset: {html: 'selectbox'}
	,	html: {html: 'text'}	// TODO: Will be selectbox for PRO version
	,	def_checked: {html: 'checkbox'}
	,	mandatory: {html: 'checkbox'}
	,	label_delim: {html: 'text'}
	,	display: {html: 'selectbox'}
	
	,	min_size: {html: 'text'}
	,	max_size: {html: 'text'}
	,	add_classes: {html: 'text'}
	,	add_styles: {html: 'text'}
	,	add_attr: {html: 'text'}
	
	,	vn_only_number: {html: 'checkbox'}
	,	vn_only_letters: {html: 'checkbox'}
	,	vn_pattern: {html: 'text'}
	,	vn_equal: {html: 'selectbox'}
	,	icon_class: {html: 'text'}
	,	icon_size: {html: 'selectbox'}
	,	icon_color: {html: 'colorpicker'}
	,	icon_selected_color: {html: 'colorpicker'}
	,	rate_num: {html: 'text'}
	,	time_format: {html: 'selectbox'}
	,	wooattrs: {html: 'selectbox'}
	,	wooattrs_mult: {html: 'checkbox'}
	,	tax: {html: 'selectbox'}
	,	terms: {html: 'selectlist'}
	,	wpcat_display: {html: 'selectbox'}
	,	gmapkey: {html: 'text'}
	}
,	_recapFields: {
		'recap-sitekey': {html: 'text', mandatory: 1}
	,	'recap-secret': {html: 'text', mandatory: 1}
	,	'recap-theme': {html: 'selectbox'}
	,	'recap-type': {html: 'selectbox'}
	,	'recap-size': {html: 'selectbox'}
	}
,	_fieldsDataForInited: false
,	_iconsSlider: null
,	_iconsSliderHeight: 125
,	_curEditField: null
,	_isClearingEditWnd: false
,	init: function() {
		var self = this;
		this._$mainShell = jQuery('#cfsFieldsEditShell');
		// Add field window - where user will select Field HTML Type
		this._$addWnd = jQuery('#cfsFieldsAddWnd').dialog({
			modal:    true
		,	autoOpen: false
		,	width: 560
		,	buttons:  {
				Cancel: function() {
					self._clearBindToField();
					self.closeAddWnd();
				}
			}
		});
		// Main edit fields window
		this._$editWnd = jQuery('#cfsFieldsEditWnd').dialog({
			modal:    true
		,	autoOpen: false
		,	width: 750
		,	close: function() {
				self._clearBindToField();
		}
		,	buttons:  {
				Ok: function() {
					if(self.saveFieldWnd()) {
						self.closeEditWnd();
					}
				}
			,	Cancel: function() {
					self.closeEditWnd();
				}
			}
		});
		// Html delim edit field window
		this._$htmlEditorWnd = jQuery('#cfsFormFieldHtmlInpWnd').dialog({
			modal:    true
		,	autoOpen: false
		,	width: 600
		,	close: function() {
				self._clearBindToField();
		}
		,	buttons:  {
				Ok: function() {
					if(self.saveHtmlDelimFieldWnd()) {
						self.closeHtmlDelimEditWnd();
					}
				}
			,	Cancel: function() {
					self.closeHtmlDelimEditWnd();
				}
			}
		});
		// Google maps field window
		this._$googleMapsWnd = jQuery('#cfsFormFieldGoogleMapsWnd').dialog({
			modal:    true
		,	autoOpen: false
		,	width: 600
		,	close: function() {
				self._clearBindToField();
		}
		,	buttons:  {
				Ok: function() {
					if(self.saveGoogleMapFieldWnd()) {
						self.closeGoogleMapsWnd();
					}
				}
			,	Cancel: function() {
					self.closeGoogleMapsWnd();
				}
			}
		});
		// In edit window - options will be separated into several tabs
		this._$editWnd.wpTabs({
			uniqId: 'cfsFieldsEditWnd'
		,	change: function( tabSelector ) {
				if(tabSelector == '#cfsFormFieldIconSettings') {
					self._showIconsSlider( self._curEditField );
				}
			}
		});
		// Init field validate pattern builder for edit window
		this._bindPatternBuilder();
		// Select element type from Add wnd list
		jQuery('.cfsFieldWndElement').click(function(){
			var ftHtml = jQuery(this).data('html')
			,	isPro = parseInt(jQuery(this).data('pro'));
			self.closeAddWnd();
			if(isPro) {
				cfsFillAndShowMainPromoWnd( jQuery(this) );
			} else {
				self.showEditWnd({html: ftHtml}, true);
			}
			return false;
		});
		// Add list opts
		jQuery('.cfsFieldsAddListOpt').click(function(){
			self._addListOpt();
			return false;
		});
		// Add field
		jQuery('.cfsAddFieldBtn').click(function(){
			// Add new fields - right before last submit or button, as this should be desired by users.
			// In any case - there are always possibility to drag field in any other place
			var $shells = self._$mainShell.find('.cfsFieldShell');
			if($shells && $shells.size()) {
				$shells.each(function(){
					var html = jQuery(this).data('html');
					if(toeInArrayCfs(html, ['submit', 'button'])) {
						self._$addFieldNextTo = jQuery(this);
					}
				});
			}
			if(self._$addFieldNextTo) {
				self._addFieldNextToPos = 'top';
			}
			self.showAddWnd();
			return false;
		});
		// Make fields - sortable
		jQuery('#cfsFieldsEditShell').sortable({
			items: '.cfsFieldRow'
		,	handle: '.cfsMoveVFieldHandle'
		,	axis: 'y'
		,	start: function() {
				self._sortInProgress = true;
			}
		,	update: function() {
				self.updateSortOrder();
			}
		});
		// Field list type options - sortable too
		jQuery('#cfsFieldsListOptsShell').sortable({
			items: '.cfsFieldListOptShell:not(#cfsFieldListOptShellExl)'
		,	axis: 'y'
		,	update: function() {
				self._updateOptsListSortOrder();
			}
		});
		// Init parameters to html types relationship
		this._initParamToHtmlTypeRelation();
		if(!CFS_DATA.isPro) {
			this._$editWnd.find('[name="value_preset"]').change(function(){
				if(jQuery(this).val())
					cfsFillAndShowMainPromoWnd( jQuery(this).parents('td:first') );
			});
		}
		jQuery(document).trigger('cfsAfterFieldsEditInit');
	}
,	_initParamToHtmlTypeRelation: function() {
		var $paramRows = this._$editWnd.find('.cfsFieldParamRow');
		$paramRows.each(function(){
			var $this = jQuery(this)
			,	forStr = $this.data('for')
			,	notForStr = $this.data('not-for');
			if(forStr && forStr != '') {
				this._cfsFor = forStr.split(',');
			}
			if(notForStr && notForStr != '') {
				this._cfsNotFor = notForStr.split(',');
			}
		});
	}
,	_checkParamToHtmlRelation: function( htmlCode ) {
		var $paramRows = this._$editWnd.find('.cfsFieldParamRow');
		$paramRows.show().attr('data-show-for-field', 1);
		$paramRows.each(function(){
			var forArr = this._cfsFor
			,	notForArr = this._cfsNotFor
			,	hide = false
			,	$this = jQuery(this);
			if(forArr && !toeInArrayCfs(htmlCode, forArr)) {
				hide = true;
			}
			if(notForArr && toeInArrayCfs(htmlCode, notForArr)) {
				hide = true;
			}
			if(hide) {
				jQuery(this).hide().attr('data-show-for-field', 0);
			}
		});
		// Hide fully empty tabs
		var tabsIds = ['cfsFormFieldBaseSettings', 'cfsFormFieldAdvancedSettings', 'cfsFormFieldValidation', 'cfsFormFieldIconSettings'];
		this._$editWnd.find('.nav-tab').show();
		for(var i = 0; i < tabsIds.length; i++) {
			var $tab = this._$editWnd.find('#'+ tabsIds[ i ]);
			if(!$tab.find('.cfsFieldParamRow[data-show-for-field=1]').size()) {
				$tab.hide();
				this._$editWnd.find('.nav-tab[href="#'+ tabsIds[ i ]+ '"]').hide();
			}
		}
	}
,	_bindPatternBuilder: function() {
		var self = this;
		this._$patternInput = this._$editWnd.find('[name="vn_pattern"]');
		this._$editWnd.find('[name="vn_only_number"]').change(function(){
			self._setPattern('\\d+', !jQuery(this).prop('checked'));
		});
		this._$editWnd.find('[name="vn_only_letters"]').change(function(){
			self._setPattern('\\w+', !jQuery(this).prop('checked'));
		});
	}
,	_setPattern: function( pattern, unset ) {
		var currPattern = this._$patternInput.val()
		,	newPattern = currPattern;
		if(unset) {
			newPattern = currPattern ? str_replace(currPattern, pattern, '') : '';
		} else if(!currPattern || strpos(currPattern, pattern) === false) {
			newPattern = (currPattern ? (currPattern+ '|') : '')+ pattern;
		}
		if(newPattern !== currPattern) {
			if(newPattern.indexOf('|') == 0) {
				newPattern = newPattern.substr(1, newPattern.length);
			}
			if(newPattern.lastIndexOf('|') == newPattern.length - 1) {
				newPattern = newPattern.substr(0, newPattern.length - 1);
			}
			this._$patternInput.val( newPattern );
		}
	}
,	showAddWnd: function() {
		this._$addWnd.dialog('open');
	}
,	closeAddWnd: function() {
		this._$addWnd.dialog('close');
	}
,	showEditWnd: function( field, isCreate ) {
		if(field && field.html == 'htmldelim') {
			cfsSetTxtEditorVal( this._htmlEditorId, (!isCreate && field.value ? field.value : '') );
			this._$htmlEditorWnd.dialog('open');
		} else if(field && field.html == 'googlemap') {
			jQuery(this._googleMapsId).val((!isCreate && field.value ? field.value : ''));
			this._$googleMapsWnd.dialog('open');
		} else {
			this.clearEditWnd();
			this._updateVnEqualToSel( field );
			this._curEditField = field;
			if(field) {
				this.fillInEditWnd( field, isCreate );
			}
			//this._checkDefValueInp( field.html, field.value );
			this._$editWnd.dialog('open');
			this._$editWnd.find('.wnd-chosen').chosen({
				disable_search_threshold: 10
			}).trigger('chosen:updated');
		}
	}
,	_rebuildIconsSlider: function( filter ) {
		var $fieldIcons = jQuery('#cfsFieldIconsShell');
		if( this._iconsSlider ) {
			this._iconsSlider.destroySlider();
			this._iconsSlider = null;
			$fieldIcons.html('');
		}
		var icons = cfsGetFaIconsList()
		,	i = 0
		,	$slide = null
		,	slideWidth = 30
		,	fullFieldIconsWidth = $fieldIcons.width();
		if( !fullFieldIconsWidth ) {
			fullFieldIconsWidth = 750;
		}
		var slidesCnt = Math.floor( fullFieldIconsWidth / slideWidth );

		if( filter ) {
			filter = filter.toLowerCase();
		}
		for(var iconId in icons) {
			if( filter ) {
				var found = false;
				for(var k in icons[ iconId ]) {
					if(icons[ iconId ][ k ].indexOf( filter ) != -1) {
						found = true;
						break;
					}
				}
				if( !found ) {
					continue;
				}
			}
			if(i >= 4) {
				i = 0;
			}
			if(!i) {
				$slide = jQuery('<li></li>');
			}
			$slide.append('<a class="cfsFieldIconBtn" href="'+ iconId+ '" onclick="_cfsSelectFieldIconClb(this); return false;"><i class="fa fa-'+ iconId+ ' fa-2x"></i></a>');
			$fieldIcons.append( $slide );
			i++;
		}
		this._iconsSlider = $fieldIcons.bxSlider({
			slideWidth: slideWidth
		,	minSlides: slidesCnt
		,	maxSlides: slidesCnt
		,	slideMargin: 6
		});
		$fieldIcons.parents('.bx-viewport').height( this._iconsSliderHeight );	// Bad hach to set whole slider height
		$fieldIcons.parents('.bx-wrapper').css({
			'float': 'left'
		});
	}
,	_showIconsSlider: function( field ) {
		var $fieldIcons = jQuery('#cfsFieldIconsShell')
		,	sliderCreated = this._iconsSlider ? true : false;
		this._rebuildIconsSlider();	// this._iconsSlider will be created here, so it should be detected before
		if(!sliderCreated) {
			var self = this;
			jQuery('#cfsFieldIconSearchInp').keyup(function(e){
				// DO not overload client with this search procedure
				if( this._cfsSearchTimeout ) {
					clearTimeout( this._cfsSearchTimeout );
				}
				this._cfsSearchTimeout = setTimeout(jQuery.proxy(function(){
					var search = jQuery.trim(jQuery(this).val());
					self._rebuildIconsSlider( search );
				}, this), 600);
			});
		}
		if( field && field.icon_class ) {
			jQuery('#cfsFieldIconSelected').html( field.icon_class );
			$fieldIcons.find('a[href="'+ field.icon_class+ '"]').addClass('active');
		} else {
			jQuery('#cfsFieldIconSelected').html('');
		}
	}
,	_updateVnEqualToSel: function( field ) {
		// Fill in all fields select
		var fields = this.getFieldsList()
		,	$fieldsSel = this._$editWnd.find('[name="vn_equal"]');
		$fieldsSel.find('option').remove();
		$fieldsSel.append('<option value="">'+ toeLangCfs('Not set')+ '</option>');
		if(fields) {
			for(var i = 0; i < fields.length; i++) {
				if(toeInArrayCfs(fields[ i ].html, ['button', 'submit', 'reset', 'recaptcha', 'googlemap', 'htmldelim'])) continue;
				if(field && field.name == fields[ i ].name) continue;
				var label = fields[ i ].label ? fields[ i ].label : fields[ i ].placeholder;
				$fieldsSel.append('<option value="'+ fields[ i ].name+ '">'+ label+ '</option>');
			}
		}
		if(field && field.vn_equal) {
			$fieldsSel.val( field.vn_equal );
		}
		this._$editWnd.find('.wnd-equal-to-chosen').chosen({
			width: 100
		,	disable_search_threshold: 10
		}).trigger('chosen:updated');
	}
,	closeEditWnd: function() {
		this._$editWnd.dialog('close');
		this._curEditField = null;
	}
,	closeHtmlDelimEditWnd: function() {
		this._$htmlEditorWnd.dialog('close');
	}
,	closeGoogleMapsWnd: function() {
		this._$googleMapsWnd.dialog('close');
	}
,	_clearBindToField: function() {
		this._$addFieldNextTo = null;
		this._addFieldNextToPos = '';
		this._$editFieldShell = null;
	}
,	clearEditWnd: function() {
		this._isClearingEditWnd = true;
		g_cfsDsblWndPromo = true;
		this._$editWnd.find('input:not([type="checkbox"])').val('');
		this._$editWnd.find('select').each(function(){
			this.selectedIndex = 0;
		}).trigger('change');
		cfsCheckUpdate(this._$editWnd.find('input[type=checkbox]').removeProp('checked'));
		this._$editWnd.find('.cfsFieldListOptShell:not(#cfsFieldListOptShellExl)').remove();
		this._$editWnd.find('.cfsFieldsEditForLists').hide();
		this._$editWnd.find('.cfsFieldsEditForCheckRadioLists').hide();
		this._hideEditFieldErrors();
		// Clear all color pickers
		var $colorPickers = this._$editWnd.find('.wp-color-picker');
		if($colorPickers && $colorPickers.size() && $colorPickers.wpColorPicker) {
			$colorPickers.each(function(){
				var $this = jQuery(this);
				if( $this.wpColorPicker ) {
					$this.parents('.wp-picker-input-wrap:first').find('.wp-picker-clear').click();
				}
			});
		}
		// Open first - Basic - tab by default
		this._$editWnd.wpTabs('activate', '#cfsFormFieldBaseSettings');
		g_cfsDsblWndPromo = false;
		this._isClearingEditWnd = false;
	}
,	fillInEditWnd: function( field, isCreate ) {
		for(var key in field) {
			var $input = this._$editWnd.find('[name="'+ key+ '"]');
			if($input && $input.size()) {
				if(this._fields[ key ]) {
					switch(this._fields[ key ].html) {
						case 'checkbox':
							parseInt(field[ key ])
								? $input.prop('checked', 'checked')
								: $input.removeProp('checked');
							break;
						case 'colorpicker':
							if( $input.wpColorPicker ) {
								$input.wpColorPicker('color', field[ key ]);
							} else
								$input.val( field[ key ] );
							break;
						default:
							$input.val( field[ key ] );
							break;
					}
				} else
					$input.val( field[ key ] );
			}
		}
		if(field.html == 'checkboxsubscribe') {
			this._$editWnd.find('[name="name"]').val('checkboxsubscribe');
			if(isCreate) {
				this._$editWnd.find('[name="def_checked"]').prop('checked', 'checked');
			}
		}
		if(field.html == 'wooattrs') {
			if(typeof(this._initWooAttrsSelect) === 'function') {
				this._initWooAttrsSelect();
			}
		}
		if(field.html == 'wpcategories') {
			if(typeof(this._initWpCategoriesSelect) === 'function') {
				this._initWpCategoriesSelect();
			}
		}
		cfsCheckUpdate( this._$editWnd.find('input[type=checkbox]') );
		this._checkParamToHtmlRelation( field.html );
		if(this.isFieldListSupported( field.html )) {
			this._$editWnd.find('.cfsFieldsEditForLists').show();
			this._fillInListsOpts( field );
		} else {
			this._$editWnd.find('.cfsFieldsEditForLists').hide();
		}
		if(toeInArrayCfs(field.html, ['radiobuttons', 'checkboxlist'])) {
			var $checkListShell = this._$editWnd.find('.cfsFieldsEditForCheckRadioLists');
			if(!field.display) {	// Set default value
				$checkListShell.find('[name="display"]').val('row');
			}
		}
	}
,	_fillInListsOpts: function( field ) {
		var options = this._listOptsToArr( field );
		if(options && options.length) {
			var $optExRow = jQuery('#cfsFieldListOptShellExl');
			for(var i = 0; i < options.length; i++) {
				this._addListOpt(options[ i ], {
					$optExRow: $optExRow
				});
			}
			this._updateOptsListSortOrder();
		}
	}
,	_addListOpt: function( opt, params ) {
		// Lazy-load - yeah?:)
		if(!this._$listOptsShell) {
			this._$listOptsShell = jQuery('#cfsFieldsListOptsShell');
		}
		params = params || {};
		var $optShell = (params.$optExRow ? params.$optExRow : jQuery('#cfsFieldListOptShellExl')).clone().removeAttr('id')
		,	self = this;
		$optShell.appendTo( this._$listOptsShell );
		if(opt) {
			$optShell.find('[name="options[][name]"]').val( opt.name );
			$optShell.find('[name="options[][label]"]').val( opt.label );
		}
		$optShell.find('input').removeAttr('disabled');
		// Remove opt
		$optShell.find('.cfsFieldsListOptRemoveBtn').click(function(){
			self._removeListOpt( $optShell );
			return false;
		});
		if(!params.$optExRow) {	// This mea that we add only one opt, not adding batch of them
			this._updateOptsListSortOrder()
		}
	}
,	_removeListOpt: function( $optShell ) {
		if(confirm('Are you sure want to remove this option?')) {
			var self = this;
			$optShell.animateRemoveCfs(g_cfsAnimationSpeed, function(){
				self._updateOptsListSortOrder();
			});
		}
	}
,	_updateOptsListSortOrder: function() {
		var $shells = this._$listOptsShell.find('.cfsFieldListOptShell:not(#cfsFieldListOptShellExl)')
		,	i = 0;
		$shells.each(function(){
			var $inputs = jQuery(this).find('[name^="options["]');
			$inputs.each(function(){
				var name = jQuery(this).attr('name');
				jQuery(this).attr('name', name.replace(/(options\[\]|options\[\d+\])/g, 'options['+ i+ ']'));
			});
			i++;
		});
	}
,	storeField: function( params ) {
		var update = params.update && this._$editFieldShell
		,	data = params.data ? params.data : false
		,	$shell = null
		,	baseInit = params.baseInit;

		if(update) {
			$shell = this._$editFieldShell;
		} else {
			var $fieldsExRow = params.$fieldsExRow ? params.$fieldsExRow : jQuery('#cfsFieldShellEx');
			cfsCheckDestroyArea( $fieldsExRow );
			$shell = $fieldsExRow.clone().removeAttr('id');
			$shell.find('input').removeAttr('disabled');
		}
		if(data && data.html) {
			var htmlCode = data.html
			,	isListSupported = this.isFieldListSupported( htmlCode );
			if( isListSupported ) {
				this._clearListOptsFromShell( $shell );
			}
			// Update input fields - to save data on server
			var fieldFields = this._getFieldFields( htmlCode );
			for(var k in fieldFields) {
				if(fieldFields[ k ].html == 'selectlist' && typeof(data[ k ]) === 'undefined') {
					// For case it's saving from form
					data[ k ] = data[ k+ '[]' ];
				}
				if(typeof(data[ k ]) === 'undefined' && fieldFields[ k ].html !== 'checkbox') continue;
				var $input = $shell.find('[name*="['+ k+ ']"]')
				,	value = data[ k ];
				
				if(!$input || !$input.size()) {
					$input = this._createShellFieldField( $shell, k );
				}
				switch(fieldFields[ k ].html) {
					case 'checkbox':
						value = parseInt(value) ? 1 : 0;
						break;
				}
				$input.val( value );
			}
			// Really rare situation - most for developing
			if(typeof(cfsFormTypes[ htmlCode ]) === 'undefined') return;
			// Update HTML labels - to show user what he is editing now
			var showLabel = data.label ? data.label : data.placeholder
			,	showHtmlType = cfsFormTypes[ htmlCode ].label;
			$shell.find('.csfFieldIcon').html( '<i class="fa '+ cfsFormTypes[ htmlCode ].icon+ '"></i>' );
			$shell.find('.csfFieldLabel').html( showLabel );
			$shell.find('.csfFieldType').html( showHtmlType );
			$shell.attr('title', showLabel+ ' ['+ showHtmlType+ ']');
			if(isListSupported) {
				this._storeListOpts( $shell, data );
			}
			$shell.data('html', data.html);
		}
		if(!update) {
			$shell.find('input,select').removeAttr('disabled');
			var $row = null;
			if(baseInit) {
				var bsClassId = parseInt(data.bs_class_id)
				,	$prevFieldShell = params.$prevFieldShell
				,	bsClassIdCounter = params.bsClassIdCounter;
				if(bsClassId && bsClassId < 12 && $prevFieldShell && bsClassIdCounter && bsClassIdCounter % 12) {
					this._addFieldNextToPos = 'right';
					this._$addFieldNextTo = $prevFieldShell;
				}
			}
			switch(this._addFieldNextToPos) {
				case 'left': case 'right':
					$row = this._getParentRow( this._$addFieldNextTo );
					break;
				case 'top': case 'bottom': default:
					$row = this._wrapRow( $shell );
					break;
			}
			if(this._$addFieldNextTo) {
				switch(this._addFieldNextToPos) {
					case 'top':
						$row.insertBefore( this._getParentRow( this._$addFieldNextTo ) );
						break;
					case 'right':
						$shell.insertAfter( this._$addFieldNextTo );
						break;
					case 'bottom':
						$row.insertAfter( this._getParentRow( this._$addFieldNextTo ) );
						break;
					case 'left':
						$shell.insertBefore( this._$addFieldNextTo );
						break;
					default:	// Add it in any case
						$row.appendTo( this._$mainShell );
						break;
				}
			} else {
				$row.appendTo( this._$mainShell );
			}
			this._assignRowShellsClasses( $row );
			this._initShellActions( $shell, data );
		}
		this._clearBindToField();
		return $shell;
	}
,	_getFieldFields: function( htmlCode ) {
		var res = {};
		if(!this._fieldsDataForInited) {
			// Init fields correcponding to html types
			var $paramRows = this._$editWnd.find('.cfsFieldParamRow')
			,	self = this;
			$paramRows.each(function(){
				var forArr = this._cfsFor
				,	notForArr = this._cfsNotFor
				,	$inputs = jQuery(this).find(':input');
				if((forArr || notForArr) && $inputs && $inputs.size() > 0) {
					$inputs.each(function(){
						var inputName = jQuery(this).attr('name');
						// Those should be present at all
						if(inputName == 'name' || inputName == 'label') return;
						if(inputName && self._fields[ inputName ]) {
							if(forArr)
								self._fields[ inputName ]._for = forArr;
							if(notForArr)
								self._fields[ inputName ]._notFor = notForArr;
						}
					});
				}
			});
			this._fieldsDataForInited = true;
		}
		var fieldsForHtml = {};
		for(var fName in this._fields) {
			var usable = true;
			if(this._fields[ fName ]._for || this._fields[ fName ]._notFor) {
				if((this._fields[ fName ]._for && !toeInArrayCfs(htmlCode, this._fields[ fName ]._for))
					|| (this._fields[ fName ]._notFor && toeInArrayCfs(htmlCode, this._fields[ fName ]._notFor))
				) {
					usable = false;
				}
			}
			if(usable) {
				fieldsForHtml[ fName ] = this._fields[ fName ];
			}
		}
		res = jQuery.extend(res, fieldsForHtml);
		if(htmlCode == 'recaptcha') {
			res = jQuery.extend(res, this._recapFields);
		}
		return res;
	}
,	_createShellFieldField: function( $shell, name ) {
		return jQuery('<input type="hidden" name="params[fields][]['+ name+ ']" />').appendTo( $shell );
	}
,	_clearListOptsFromShell: function( $row ) {
		$row.find('input[name*="[options]["]').remove();
	}
,	_getParentRow: function( $shell ) {
		return $shell.parents('.cfsFieldRow:first');
	} 
,	_wrapRow: function( $shell ) {
		var $row = jQuery('<div class="row cfsFieldRow" />').append( jQuery('#cfsMoveVFieldHandleExl').clone().removeAttr('id') ).append( $shell )
		,	self = this;
		$row.sortable({
			items: '.cfsFieldShell'
		,	handle: '.cfsMoveHFieldHandle'
		,	axis: 'x'
		,	start: function() {
				self._sortInProgress = true;
			}
		,	update: function() {
				self.updateSortOrder();
			}
		});
		return $row;
	}
,	_assignRowShellsClasses: function( $row, newBsClassId ) {
		var $shells = $row.find('.cfsFieldShell')
		,	shellsNum = $shells.size();
		if(!shellsNum) {	// No fields in this row - we don't need this anymore. Cruel world.........
			$row.remove();
			return;
		}
		if(!newBsClassId) {
			var currBsClasses = this._extractBootstrapColsClasses( $shells.first() )
			,	newBsClassId = Math.floor( 12 / shellsNum );
			$shells.removeClass( currBsClasses.join(',') )
		}
		$shells
			.addClass('col-sm-'+ newBsClassId)
			.data('bs_class_id', newBsClassId);
		$shells.find('[name*="[bs_class_id]"]').val( newBsClassId );
		if(newBsClassId < 12) {
			$shells.find('.cfsMoveHFieldHandle').show();
		} else {
			$shells.find('.cfsMoveHFieldHandle').hide();
		}
	}
,	_extractBootstrapColsClasses: function( $shell ) {
		var	currClasses = jQuery.map($shell.attr('class').split(' '), jQuery.trim)
		,	newClasses = [];
		for(var i = 0; i < currClasses.length; i++) {
			if(currClasses[ i ] == 'col' || currClasses[ i ].match(/col\-\w{2}\-\d{1,2}/)) {
				newClasses.push( currClasses[ i ] );
			}
		}
		return newClasses;
	}
,	_storeListOpts: function( $row, field ) {
		var options = this._listOptsToArr( field );
		if(options.length) {
			var j = 0;
			for(i = 0; i < options.length; i++) {
				if(options[ i ].name && options[ i ].name != '') {
					$row.append('<input type="hidden" name="params[fields][][options]['+ j+ '][name]" value="'+ options[ i ].name+ '" />');
					$row.append('<input type="hidden" name="params[fields][][options]['+ j+ '][label]" value="'+ options[ i ].label+ '" />');
					j++;
				}
			}
		}
	}
,	_listOptsToArr: function( field ) {
		var options = []
		,	i = 0;
		if(field && field.options) {	// This will be triggered when we add field - from it's DB settings - where it is already as array
			options = field.options;
		} else {	// This will be triggered when we add it from edit form
			for(var key in field) {
				if(typeof(key) === 'string' && key.indexOf('options[') !== -1) {
					if(i % 2 == 0) {
						options.push({name: field[ key ]});
					} else {
						options[ options.length - 1 ].label = field[ key ];
					}
					i++;
				}
			}
		}
		return options;
	}
,	isFieldListSupported: function( htmlCode ) {
		return htmlCode && toeInArrayCfs(htmlCode, ['selectbox', 'selectlist', 'radiobuttons', 'checkboxlist']);
	}
,	_rowToData: function( $row ) {
		var res = {}
		,	fData = $row.serializeAnythingCfs(false, true);
		for(var key in fData) {
			var name = key.replace(/params\[fields\](\[\d+\]|\[\])\[/, '').replace(/\]/, '');
			res[ name ] = fData[ key ]; 
		}
		return res;
	} 
,	_initShellActions: function( $shell, data ) {
		var self = this
		,	$panel = $shell.find('.cfsFieldPanel');
		// Edit field
		$shell.click(function(){
			if(self._sortInProgress) {	// Sorting was just stopped - this is not a click
				self._sortInProgress = false;
				return false;
			}
			self.editField( $shell, self._rowToData($shell) );
			return false;
		});
		// Move menu to current cursor post by X axis - I think this will be pretty UI solution
		$shell.hover(function(e){
			if(e.type == 'mouseenter') {
				self._moveFieldPanelToCursor( $panel, e.offsetX );
			}
		});
		// Remove field
		$panel.find('.cfsFieldRemoveBtn').click(function(){
			self.removeField( $shell, data );
			return false;
		});
		// Add fields next to current
		$panel.find('.cfsAddTopBtn').click(function(){
			self._addFieldNextToClb( $shell, 'top' );
			return false;
		});
		$panel.find('.cfsAddRightBtn').click(function(){
			self._addFieldNextToClb( $shell, 'right' );
			return false;
		});
		$panel.find('.cfsAddBottomBtn').click(function(){
			self._addFieldNextToClb( $shell, 'bottom' );
			return false;
		});
		$panel.find('.cfsAddLeftBtn').click(function(){
			self._addFieldNextToClb( $shell, 'left' );
			return false;
		});
	}
,	_moveFieldPanelToCursor: function( $panel, x ) {
		$panel.css('left', x);
	}
,	_addFieldNextToClb: function( $shell, pos ) {
		this._$addFieldNextTo = $shell;
		this._addFieldNextToPos = pos;
		this.showAddWnd();
	}
,	editField: function( $shell, data ) {
		this._$editFieldShell = $shell;
		this.showEditWnd( data );
	}
,	removeField: function( $shell, data ) {
		var label = data.label ? data.label : data.placeholder;
		// data here can contain old data values - so need to make update label value from current shell
		if(confirm('Are you sure want to remove "'+ label+ '" field?')) {
			var self = this
			,	$parentRow = this._getParentRow( $shell );
			$shell.animateRemoveCfs( g_cfsAnimationSpeed, function(){
				self.updateSortOrder();
				self._assignRowShellsClasses( $parentRow );
			});
		}
	}
,	saveFieldWnd: function() {
		this._hideEditFieldErrors();
		var fieldData = this._$editWnd.serializeAnythingCfs(false, true);
		fieldData = this._prepareFieldData( fieldData );
		if(!this.validateFieldData( fieldData ))
			return false;
		this.storeField({
			data: fieldData
		,	update: true
		});
		this.updateSortOrder();
		return true;	// TODO: Add validation and false result here
	}
,	saveHtmlDelimFieldWnd: function() {
		this.storeField({
			data: {html: 'htmldelim', value: cfsGetTxtEditorVal(this._htmlEditorId)}
		,	update: true
		});
		this.updateSortOrder();
		return true;
	}
,	saveGoogleMapFieldWnd: function() {
		var mapId = parseInt(jQuery(this._googleMapsId).val());
		if(mapId) {
			var mapLabel = jQuery(this._googleMapsId).find('option[value="'+ mapId+ '"]').text();
			this.storeField({
				data: {html: 'googlemap', value: mapId, label: mapLabel}
			,	update: true
			});
			this.updateSortOrder();
		}
		return true;
	}
,	_prepareFieldData: function( fieldData ) {
		fieldData.name = fieldData.name ? jQuery.trim( fieldData.name ) : '';
		fieldData.label = fieldData.label ? jQuery.trim( fieldData.label ) : '';
		fieldData.placeholder = fieldData.placeholder ? jQuery.trim( fieldData.placeholder ) : '';
		if(fieldData.html == 'wooattrs') {
			fieldData.name = fieldData.wooattrs;
		}
		return fieldData;
	}
,	validateFieldData: function( fieldData ) {
		var errors = []
		,	nameRegExp = /^[a-z0-9\-_]+$/i;
		if(!fieldData.name || !nameRegExp.test( fieldData.name )
			&& !toeInArrayCfs(fieldData.html, ['wooattrs'])
		) {
			errors.push('name');
		}
		if((!fieldData.label || fieldData.label == '') 
			&& (!fieldData.placeholder || fieldData.placeholder == '')
			&& !toeInArrayCfs(fieldData.html, ['recaptcha'])
		) {
			errors.push('label-placeholder');
		}
		if(errors.length) {
			this._showEditFieldErrors( errors );
			return false;
		}
		return true;
	}
,	_showEditFieldErrors: function( errors ) {
		for(var i = 0; i < errors.length; i++) {
			this._$editWnd.find('[name="'+ errors[ i ]+ '"]').addClass('cfsInputError');
			this._$editWnd.find('.cfsFieldEditErrorRow[data-for="'+ errors[ i ]+ '"]').slideDown( g_cfsAnimationSpeed );
		}
	}
,	_hideEditFieldErrors: function() {
		this._$editWnd.find('input,select,textarea').removeClass('cfsInputError');
		this._$editWnd.find('.cfsFieldEditErrorRow').hide();
	}
,	updateSortOrder: function() {
		var $rows = this._$mainShell.find('.cfsFieldShell:not(#cfsFieldShellEx)')
		,	i = 0;
		$rows.each(function(){
			var $inputs = jQuery(this).find('[name^="params[fields]"]');
			$inputs.each(function(){
				var name = jQuery(this).attr('name');
				jQuery(this).attr('name', name.replace(/(\[fields\]\[\]|\[fields\]\[\d+\])/g, '[fields]['+ i+ ']'));
			});
			i++;
		});
	}
,	haveSubmitField: function() {
		return (this._$mainShell.find('input[name*="[html]"][value="submit"]').size() 
			|| this._$mainShell.find('input[name*="[html]"][value="button"]').size());
	}
,	getFieldsList: function() {
		var htmlData = this._$mainShell.serializeAnythingDeepCfs();
		if(htmlData && htmlData['params'] && htmlData['params']['fields']) {
			return htmlData['params']['fields'];
		}
		return false;
	}
};
jQuery(document).ready(function(){
	// Set all exampled inputs as disabled
	jQuery('#cfsFieldShellEx').find('input').attr('disabled', 'disabled');
	// Init fields frame with it's basic features
	g_cfsFieldsFrame.init();
	if(typeof(cfsForm) !== 'undefined' 
		&& cfsForm.params 
		&& cfsForm.params.fields 
		&& cfsForm.params.fields.length
	) {
		var $fieldsExRow = jQuery('#cfsFieldShellEx')
		,	$prevFieldShell = null
		,	bsClassIdCounter = 0;
		for(var i = 0; i < cfsForm.params.fields.length; i++) {
			$prevFieldShell = g_cfsFieldsFrame.storeField({
				data: cfsForm.params.fields[ i ]
			,	$fieldsExRow: $fieldsExRow
			,	baseInit: true
			,	$prevFieldShell: $prevFieldShell
			,	bsClassIdCounter: bsClassIdCounter
			});
			bsClassIdCounter += parseInt(cfsForm.params.fields[ i ].bs_class_id);
		}
		g_cfsFieldsFrame.updateSortOrder();
		g_cfsFieldsFrame._clearBindToField();
	}
	jQuery(document).trigger('cfsAfterFieldsEditFilledIn');
	if(typeof(cfsAddMapField) != 'undefined' && cfsAddMapField) {
		g_cfsFieldsFrame.showEditWnd({ html: 'googlemap', value: cfsAddMapField });
	}
});
function cfsGetFaIconsList() {
	return {"glass":["martini","drink","bar","alcohol","liquor","glass"],"music":["note","sound","music"],"search":["magnify","zoom","enlarge","bigger","search"],"envelope-o":["email","support","e-mail","letter","mail","notification","envelope outlined"],"heart":["love","like","favorite","heart"],"star":["award","achievement","night","rating","score","favorite","star"],"star-o":["award","achievement","night","rating","score","favorite","star outlined"],"user":["person","man","head","profile","user"],"film":["movie","film"],"th-large":["blocks","squares","boxes","grid","th-large"],"th":["blocks","squares","boxes","grid","th"],"th-list":["ul","ol","checklist","finished","completed","done","todo","th-list"],"check":["checkmark","done","todo","agree","accept","confirm","tick","ok","check"],"times":["close","exit","x","cross","times"],"search-plus":["magnify","zoom","enlarge","bigger","search plus"],"search-minus":["magnify","minify","zoom","smaller","search minus"],"power-off":["on","power off"],"signal":["graph","bars","signal"],"cog":["settings","cog"],"trash-o":["garbage","delete","remove","trash","hide","trash outlined"],"home":["main","house","home"],"file-o":["new","page","pdf","document","file outlined"],"clock-o":["watch","timer","late","timestamp","clock outlined"],"road":["street","road"],"download":["import","download"],"arrow-circle-o-down":["download","arrow circle outlined down"],"arrow-circle-o-up":["arrow circle outlined up"],"inbox":["inbox"],"play-circle-o":["play circle outlined"],"repeat":["redo","forward","repeat"],"refresh":["reload","sync","refresh"],"list-alt":["ul","ol","checklist","finished","completed","done","todo","list-alt"],"lock":["protect","admin","security","lock"],"flag":["report","notification","notify","flag"],"headphones":["sound","listen","music","audio","headphones"],"volume-off":["audio","mute","sound","music","volume-off"],"volume-down":["audio","lower","quieter","sound","music","volume-down"],"volume-up":["audio","higher","louder","sound","music","volume-up"],"qrcode":["scan","qrcode"],"barcode":["scan","barcode"],"tag":["label","tag"],"tags":["labels","tags"],"book":["read","documentation","book"],"bookmark":["save","bookmark"],"print":["print"],"camera":["photo","picture","record","camera"],"font":["text","font"],"bold":["bold"],"italic":["italics","italic"],"text-height":["text-height"],"text-width":["text-width"],"align-left":["text","align-left"],"align-center":["middle","text","align-center"],"align-right":["text","align-right"],"align-justify":["text","align-justify"],"list":["ul","ol","checklist","finished","completed","done","todo","list"],"outdent":["outdent"],"indent":["indent"],"video-camera":["film","movie","record","video camera"],"picture-o":["picture outlined"],"pencil":["write","edit","update","pencil"],"map-marker":["map","pin","location","coordinates","localize","address","travel","where","place","map-marker"],"adjust":["contrast","adjust"],"tint":["raindrop","waterdrop","drop","droplet","tint"],"pencil-square-o":["write","edit","update","pencil square outlined"],"share-square-o":["social","send","arrow","share square outlined"],"check-square-o":["todo","done","agree","accept","confirm","ok","check square outlined"],"arrows":["move","reorder","resize","arrows"],"step-backward":["rewind","previous","beginning","start","first","step-backward"],"fast-backward":["rewind","previous","beginning","start","first","fast-backward"],"backward":["rewind","previous","backward"],"play":["start","playing","music","sound","play"],"pause":["wait","pause"],"stop":["block","box","square","stop"],"forward":["forward","next","forward"],"fast-forward":["next","end","last","fast-forward"],"step-forward":["next","end","last","step-forward"],"eject":["eject"],"chevron-left":["bracket","previous","back","chevron-left"],"chevron-right":["bracket","next","forward","chevron-right"],"plus-circle":["add","new","create","expand","plus circle"],"minus-circle":["delete","remove","trash","hide","minus circle"],"times-circle":["close","exit","x","times circle"],"check-circle":["todo","done","agree","accept","confirm","ok","check circle"],"question-circle":["help","information","unknown","support","question circle"],"info-circle":["help","information","more","details","info circle"],"crosshairs":["picker","crosshairs"],"times-circle-o":["close","exit","x","times circle outlined"],"check-circle-o":["todo","done","agree","accept","confirm","ok","check circle outlined"],"ban":["delete","remove","trash","hide","block","stop","abort","cancel","ban"],"arrow-left":["previous","back","arrow-left"],"arrow-right":["next","forward","arrow-right"],"arrow-up":["arrow-up"],"arrow-down":["download","arrow-down"],"share":["share"],"expand":["enlarge","bigger","resize","expand"],"compress":["collapse","combine","contract","merge","smaller","compress"],"plus":["add","new","create","expand","plus"],"minus":["hide","minify","delete","remove","trash","hide","collapse","minus"],"asterisk":["details","asterisk"],"exclamation-circle":["warning","error","problem","notification","alert","exclamation circle"],"gift":["present","gift"],"leaf":["eco","nature","plant","leaf"],"fire":["flame","hot","popular","fire"],"eye":["show","visible","views","eye"],"eye-slash":["toggle","show","hide","visible","visiblity","views","eye slash"],"exclamation-triangle":["warning","error","problem","notification","alert","exclamation triangle"],"plane":["travel","trip","location","destination","airplane","fly","mode","plane"],"calendar":["date","time","when","event","calendar"],"random":["sort","shuffle","random"],"comment":["speech","notification","note","chat","bubble","feedback","message","texting","sms","conversation","comment"],"magnet":["magnet"],"chevron-up":["chevron-up"],"chevron-down":["chevron-down"],"retweet":["refresh","reload","share","retweet"],"shopping-cart":["checkout","buy","purchase","payment","shopping-cart"],"folder":["folder"],"folder-open":["folder open"],"arrows-v":["resize","arrows vertical"],"arrows-h":["resize","arrows horizontal"],"bar-chart":["graph","analytics","bar chart"],"twitter-square":["tweet","social network","twitter square"],"facebook-square":["social network","facebook square"],"camera-retro":["photo","picture","record","camera-retro"],"key":["unlock","password","key"],"cogs":["settings","cogs"],"comments":["speech","notification","note","chat","bubble","feedback","message","texting","sms","conversation","comments"],"thumbs-o-up":["like","approve","favorite","agree","hand","thumbs up outlined"],"thumbs-o-down":["dislike","disapprove","disagree","hand","thumbs down outlined"],"star-half":["award","achievement","rating","score","star-half"],"heart-o":["love","like","favorite","heart outlined"],"sign-out":["log out","logout","leave","exit","arrow","sign out"],"linkedin-square":["linkedin square"],"thumb-tack":["marker","pin","location","coordinates","thumb tack"],"external-link":["open","new","external link"],"sign-in":["enter","join","log in","login","sign up","sign in","signin","signup","arrow","sign in"],"trophy":["award","achievement","cup","winner","game","trophy"],"github-square":["octocat","github square"],"upload":["import","upload"],"lemon-o":["food","lemon outlined"],"phone":["call","voice","number","support","earphone","telephone","phone"],"square-o":["block","square","box","square outlined"],"bookmark-o":["save","bookmark outlined"],"phone-square":["call","voice","number","support","telephone","phone square"],"twitter":["tweet","social network","twitter"],"facebook":["social network","facebook"],"github":["octocat","github"],"unlock":["protect","admin","password","lock","unlock"],"credit-card":["money","buy","debit","checkout","purchase","payment","credit-card"],"rss":["blog","rss"],"hdd-o":["harddrive","hard drive","storage","save","hdd"],"bullhorn":["announcement","share","broadcast","louder","megaphone","bullhorn"],"bell":["alert","reminder","notification","bell"],"certificate":["badge","star","certificate"],"hand-o-right":["point","right","next","forward","finger","hand outlined right"],"hand-o-left":["point","left","previous","back","finger","hand outlined left"],"hand-o-up":["point","finger","hand outlined up"],"hand-o-down":["point","finger","hand outlined down"],"arrow-circle-left":["previous","back","arrow circle left"],"arrow-circle-right":["next","forward","arrow circle right"],"arrow-circle-up":["arrow circle up"],"arrow-circle-down":["download","arrow circle down"],"globe":["world","planet","map","place","travel","earth","global","translate","all","language","localize","location","coordinates","country","globe"],"wrench":["settings","fix","update","spanner","wrench"],"tasks":["progress","loading","downloading","downloads","settings","tasks"],"filter":["funnel","options","filter"],"briefcase":["work","business","office","luggage","bag","briefcase"],"arrows-alt":["expand","enlarge","fullscreen","bigger","move","reorder","resize","arrow","arrows alt"],"users":["people","profiles","persons","users"],"link":["chain","link"],"cloud":["save","cloud"],"flask":["science","beaker","experimental","labs","flask"],"scissors":["scissors"],"files-o":["duplicate","clone","copy","files outlined"],"paperclip":["attachment","paperclip"],"floppy-o":["floppy outlined"],"square":["block","box","square"],"bars":["menu","drag","reorder","settings","list","ul","ol","checklist","todo","list","hamburger","bars"],"list-ul":["ul","ol","checklist","todo","list","list-ul"],"list-ol":["ul","ol","checklist","list","todo","list","numbers","list-ol"],"strikethrough":["strikethrough"],"underline":["underline"],"table":["data","excel","spreadsheet","table"],"magic":["wizard","automatic","autocomplete","magic"],"truck":["shipping","truck"],"pinterest":["pinterest"],"pinterest-square":["pinterest square"],"google-plus-square":["social network","google plus square"],"google-plus":["social network","google plus"],"money":["cash","money","buy","checkout","purchase","payment","money"],"caret-down":["more","dropdown","menu","triangle down","arrow","caret down"],"caret-up":["triangle up","arrow","caret up"],"caret-left":["previous","back","triangle left","arrow","caret left"],"caret-right":["next","forward","triangle right","arrow","caret right"],"columns":["split","panes","columns"],"sort":["order","sort"],"sort-desc":["dropdown","more","menu","arrow","sort descending"],"sort-asc":["arrow","sort ascending"],"envelope":["email","e-mail","letter","support","mail","notification","envelope"],"linkedin":["linkedin"],"undo":["back","undo"],"gavel":["judge","lawyer","opinion","gavel"],"tachometer":["speedometer","fast","tachometer"],"comment-o":["speech","notification","note","chat","bubble","feedback","message","texting","sms","conversation","comment-o"],"comments-o":["speech","notification","note","chat","bubble","feedback","message","texting","sms","conversation","comments-o"],"bolt":["lightning","weather","lightning bolt"],"sitemap":["directory","hierarchy","organization","sitemap"],"umbrella":["umbrella"],"clipboard":["copy","clipboard"],"lightbulb-o":["idea","inspiration","lightbulb outlined"],"exchange":["transfer","arrows","arrow","exchange"],"cloud-download":["import","cloud download"],"cloud-upload":["import","cloud upload"],"user-md":["doctor","profile","medical","nurse","user-md"],"stethoscope":["stethoscope"],"suitcase":["trip","luggage","travel","move","baggage","suitcase"],"bell-o":["alert","reminder","notification","bell outlined"],"coffee":["morning","mug","breakfast","tea","drink","cafe","coffee"],"cutlery":["food","restaurant","spoon","knife","dinner","eat","cutlery"],"file-text-o":["new","page","pdf","document","file text outlined"],"building-o":["work","business","apartment","office","company","building outlined"],"hospital-o":["building","hospital outlined"],"ambulance":["vehicle","support","help","ambulance"],"medkit":["first aid","firstaid","help","support","health","medkit"],"fighter-jet":["fly","plane","airplane","quick","fast","travel","fighter-jet"],"beer":["alcohol","stein","drink","mug","bar","liquor","beer"],"h-square":["hospital","hotel","h square"],"plus-square":["add","new","create","expand","plus square"],"angle-double-left":["laquo","quote","previous","back","arrows","angle double left"],"angle-double-right":["raquo","quote","next","forward","arrows","angle double right"],"angle-double-up":["arrows","angle double up"],"angle-double-down":["arrows","angle double down"],"angle-left":["previous","back","arrow","angle-left"],"angle-right":["next","forward","arrow","angle-right"],"angle-up":["arrow","angle-up"],"angle-down":["arrow","angle-down"],"desktop":["monitor","screen","desktop","computer","demo","device","desktop"],"laptop":["demo","computer","device","laptop"],"tablet":["ipad","device","tablet"],"mobile":["cell phone","cellphone","text","call","iphone","number","telephone","mobile phone"],"circle-o":["circle outlined"],"quote-left":["quote-left"],"quote-right":["quote-right"],"spinner":["loading","progress","spinner"],"circle":["dot","notification","circle"],"reply":["reply"],"github-alt":["octocat","github alt"],"folder-o":["folder outlined"],"folder-open-o":["folder open outlined"],"smile-o":["face","emoticon","happy","approve","satisfied","rating","smile outlined"],"frown-o":["face","emoticon","sad","disapprove","rating","frown outlined"],"meh-o":["face","emoticon","rating","neutral","meh outlined"],"gamepad":["controller","gamepad"],"keyboard-o":["type","input","keyboard outlined"],"flag-o":["report","notification","flag outlined"],"flag-checkered":["report","notification","notify","flag-checkered"],"terminal":["command","prompt","code","terminal"],"code":["html","brackets","code"],"reply-all":["reply-all"],"star-half-o":["award","achievement","rating","score","star half outlined"],"location-arrow":["map","coordinates","location","address","place","where","location-arrow"],"crop":["crop"],"code-fork":["git","fork","vcs","svn","github","rebase","version","merge","code-fork"],"chain-broken":["remove","chain broken"],"question":["help","information","unknown","support","question"],"info":["help","information","more","details","info"],"exclamation":["warning","error","problem","notification","notify","alert","exclamation"],"superscript":["exponential","superscript"],"subscript":["subscript"],"eraser":["remove","delete","eraser"],"puzzle-piece":["addon","add-on","section","puzzle piece"],"microphone":["record","voice","sound","microphone"],"microphone-slash":["record","voice","sound","mute","microphone slash"],"shield":["award","achievement","security","winner","shield"],"calendar-o":["date","time","when","event","calendar-o"],"fire-extinguisher":["fire-extinguisher"],"rocket":["app","rocket"],"maxcdn":["maxcdn"],"chevron-circle-left":["previous","back","arrow","chevron circle left"],"chevron-circle-right":["next","forward","arrow","chevron circle right"],"chevron-circle-up":["arrow","chevron circle up"],"chevron-circle-down":["more","dropdown","menu","arrow","chevron circle down"],"html5":["html 5 logo"],"css3":["code","css 3 logo"],"anchor":["link","anchor"],"unlock-alt":["protect","admin","password","lock","unlock alt"],"bullseye":["target","bullseye"],"ellipsis-h":["dots","ellipsis horizontal"],"ellipsis-v":["dots","ellipsis vertical"],"rss-square":["feed","blog","rss square"],"play-circle":["start","playing","play circle"],"ticket":["movie","pass","support","ticket"],"minus-square":["hide","minify","delete","remove","trash","hide","collapse","minus square"],"minus-square-o":["hide","minify","delete","remove","trash","hide","collapse","minus square outlined"],"level-up":["arrow","level up"],"level-down":["arrow","level down"],"check-square":["checkmark","done","todo","agree","accept","confirm","ok","check square"],"pencil-square":["write","edit","update","pencil square"],"external-link-square":["open","new","external link square"],"share-square":["social","send","share square"],"compass":["safari","directory","menu","location","compass"],"caret-square-o-down":["more","dropdown","menu","caret square outlined down"],"caret-square-o-up":["caret square outlined up"],"caret-square-o-right":["next","forward","caret square outlined right"],"eur":["euro (eur)"],"gbp":["gbp"],"usd":["us dollar"],"inr":["indian rupee (inr)"],"jpy":["japanese yen (jpy)"],"rub":["russian ruble (rub)"],"krw":["korean won (krw)"],"btc":["bitcoin (btc)"],"file":["new","page","pdf","document","file"],"file-text":["new","page","pdf","document","file text"],"sort-alpha-asc":["sort alpha ascending"],"sort-alpha-desc":["sort alpha descending"],"sort-amount-asc":["sort amount ascending"],"sort-amount-desc":["sort amount descending"],"sort-numeric-asc":["numbers","sort numeric ascending"],"sort-numeric-desc":["numbers","sort numeric descending"],"thumbs-up":["like","favorite","approve","agree","hand","thumbs-up"],"thumbs-down":["dislike","disapprove","disagree","hand","thumbs-down"],"youtube-square":["video","film","youtube square"],"youtube":["video","film","youtube"],"xing":["xing"],"xing-square":["xing square"],"youtube-play":["start","playing","youtube play"],"dropbox":["dropbox"],"stack-overflow":["stack overflow"],"instagram":["instagram"],"flickr":["flickr"],"adn":["app.net"],"bitbucket":["git","bitbucket"],"bitbucket-square":["git","bitbucket square"],"tumblr":["tumblr"],"tumblr-square":["tumblr square"],"long-arrow-down":["long arrow down"],"long-arrow-up":["long arrow up"],"long-arrow-left":["previous","back","long arrow left"],"long-arrow-right":["long arrow right"],"apple":["osx","food","apple"],"windows":["microsoft","windows"],"android":["robot","android"],"linux":["tux","linux"],"dribbble":["dribbble"],"skype":["skype"],"foursquare":["foursquare"],"trello":["trello"],"female":["woman","user","person","profile","female"],"male":["man","user","person","profile","male"],"gratipay":["heart","like","favorite","love","gratipay (gittip)"],"sun-o":["weather","contrast","lighter","brighten","day","sun outlined"],"moon-o":["night","darker","contrast","moon outlined"],"archive":["box","storage","archive"],"bug":["report","insect","bug"],"vk":["vk"],"weibo":["weibo"],"renren":["renren"],"pagelines":["leaf","leaves","tree","plant","eco","nature","pagelines"],"stack-exchange":["stack exchange"],"arrow-circle-o-right":["next","forward","arrow circle outlined right"],"arrow-circle-o-left":["previous","back","arrow circle outlined left"],"caret-square-o-left":["previous","back","caret square outlined left"],"dot-circle-o":["target","bullseye","notification","dot circle outlined"],"wheelchair":["handicap","person","wheelchair"],"vimeo-square":["vimeo square"],"try":["turkish lira (try)"],"plus-square-o":["add","new","create","expand","plus square outlined"],"space-shuttle":["space shuttle"],"slack":["hashtag","anchor","hash","slack logo"],"envelope-square":["envelope square"],"wordpress":["wordpress logo"],"openid":["openid"],"university":["university"],"graduation-cap":["learning","school","student","graduation cap"],"yahoo":["yahoo logo"],"google":["google logo"],"reddit":["reddit logo"],"reddit-square":["reddit square"],"stumbleupon-circle":["stumbleupon circle"],"stumbleupon":["stumbleupon logo"],"delicious":["delicious logo"],"digg":["digg logo"],"pied-piper-pp":["pied piper pp logo (old)"],"pied-piper-alt":["pied piper alternate logo"],"drupal":["drupal logo"],"joomla":["joomla logo"],"language":["language"],"fax":["fax"],"building":["work","business","apartment","office","company","building"],"child":["child"],"paw":["pet","paw"],"spoon":["spoon"],"cube":["cube"],"cubes":["cubes"],"behance":["behance"],"behance-square":["behance square"],"steam":["steam"],"steam-square":["steam square"],"recycle":["recycle"],"car":["vehicle","car"],"taxi":["vehicle","taxi"],"tree":["tree"],"spotify":["spotify"],"deviantart":["deviantart"],"soundcloud":["soundcloud"],"database":["database"],"file-pdf-o":["pdf file outlined"],"file-word-o":["word file outlined"],"file-excel-o":["excel file outlined"],"file-powerpoint-o":["powerpoint file outlined"],"file-image-o":["image file outlined"],"file-archive-o":["archive file outlined"],"file-audio-o":["audio file outlined"],"file-video-o":["video file outlined"],"file-code-o":["code file outlined"],"vine":["vine"],"codepen":["codepen"],"jsfiddle":["jsfiddle"],"life-ring":["life ring"],"circle-o-notch":["circle outlined notched"],"rebel":["rebel alliance"],"empire":["galactic empire"],"git-square":["git square"],"git":["git"],"hacker-news":["hacker news"],"tencent-weibo":["tencent weibo"],"qq":["qq"],"weixin":["weixin (wechat)"],"paper-plane":["paper plane"],"paper-plane-o":["paper plane outlined"],"history":["history"],"circle-thin":["circle outlined thin"],"header":["heading","header"],"paragraph":["paragraph"],"sliders":["settings","sliders"],"share-alt":["share alt"],"share-alt-square":["share alt square"],"bomb":["bomb"],"futbol-o":["futbol outlined"],"tty":["tty"],"binoculars":["binoculars"],"plug":["power","connect","plug"],"slideshare":["slideshare"],"twitch":["twitch"],"yelp":["yelp"],"newspaper-o":["press","newspaper outlined"],"wifi":["wifi"],"calculator":["calculator"],"paypal":["paypal"],"google-wallet":["google wallet"],"cc-visa":["visa credit card"],"cc-mastercard":["mastercard credit card"],"cc-discover":["discover credit card"],"cc-amex":["amex","american express credit card"],"cc-paypal":["paypal credit card"],"cc-stripe":["stripe credit card"],"bell-slash":["bell slash"],"bell-slash-o":["bell slash outlined"],"trash":["garbage","delete","remove","hide","trash"],"copyright":["copyright"],"at":["at"],"eyedropper":["eyedropper"],"paint-brush":["paint brush"],"birthday-cake":["birthday cake"],"area-chart":["graph","analytics","area chart"],"pie-chart":["graph","analytics","pie chart"],"line-chart":["graph","analytics","line chart"],"lastfm":["last.fm"],"lastfm-square":["last.fm square"],"toggle-off":["toggle off"],"toggle-on":["toggle on"],"bicycle":["vehicle","bike","bicycle"],"bus":["vehicle","bus"],"ioxhost":["ioxhost"],"angellist":["angellist"],"cc":["closed captions"],"ils":["shekel (ils)"],"meanpath":["meanpath"],"buysellads":["buysellads"],"connectdevelop":["connect develop"],"dashcube":["dashcube"],"forumbee":["forumbee"],"leanpub":["leanpub"],"sellsy":["sellsy"],"shirtsinbulk":["shirts in bulk"],"simplybuilt":["simplybuilt"],"skyatlas":["skyatlas"],"cart-plus":["add","shopping","add to shopping cart"],"cart-arrow-down":["shopping","shopping cart arrow down"],"diamond":["gem","gemstone","diamond"],"ship":["boat","sea","ship"],"user-secret":["whisper","spy","incognito","privacy","user secret"],"motorcycle":["vehicle","bike","motorcycle"],"street-view":["map","street view"],"heartbeat":["ekg","heartbeat"],"venus":["female","venus"],"mars":["male","mars"],"mercury":["transgender","mercury"],"transgender":["transgender"],"transgender-alt":["transgender alt"],"venus-double":["venus double"],"mars-double":["mars double"],"venus-mars":["venus mars"],"mars-stroke":["mars stroke"],"mars-stroke-v":["mars stroke vertical"],"mars-stroke-h":["mars stroke horizontal"],"neuter":["neuter"],"genderless":["genderless"],"facebook-official":["facebook official"],"pinterest-p":["pinterest p"],"whatsapp":["what's app"],"server":["server"],"user-plus":["sign up","signup","add user"],"user-times":["remove user"],"bed":["travel","bed"],"viacoin":["viacoin"],"train":["train"],"subway":["subway"],"medium":["medium"],"y-combinator":["y combinator"],"optin-monster":["optin monster"],"opencart":["opencart"],"expeditedssl":["expeditedssl"],"battery-full":["power","battery full"],"battery-three-quarters":["power","battery 3/4 full"],"battery-half":["power","battery 1/2 full"],"battery-quarter":["power","battery 1/4 full"],"battery-empty":["power","battery empty"],"mouse-pointer":["mouse pointer"],"i-cursor":["i beam cursor"],"object-group":["object group"],"object-ungroup":["object ungroup"],"sticky-note":["sticky note"],"sticky-note-o":["sticky note outlined"],"cc-jcb":["jcb credit card"],"cc-diners-club":["diner's club credit card"],"clone":["copy","clone"],"balance-scale":["balance scale"],"hourglass-o":["hourglass outlined"],"hourglass-start":["hourglass start"],"hourglass-half":["hourglass half"],"hourglass-end":["hourglass end"],"hourglass":["hourglass"],"hand-rock-o":["rock (hand)"],"hand-paper-o":["stop","paper (hand)"],"hand-scissors-o":["scissors (hand)"],"hand-lizard-o":["lizard (hand)"],"hand-spock-o":["spock (hand)"],"hand-pointer-o":["hand pointer"],"hand-peace-o":["hand peace"],"trademark":["trademark"],"registered":["registered trademark"],"creative-commons":["creative commons"],"gg":["gg currency"],"gg-circle":["gg currency circle"],"tripadvisor":["tripadvisor"],"odnoklassniki":["odnoklassniki"],"odnoklassniki-square":["odnoklassniki square"],"get-pocket":["get pocket"],"wikipedia-w":["wikipedia w"],"safari":["browser","safari"],"chrome":["browser","chrome"],"firefox":["browser","firefox"],"opera":["opera"],"internet-explorer":["browser","ie","internet-explorer"],"television":["display","computer","monitor","television"],"contao":["contao"],"500px":["500px"],"amazon":["amazon"],"calendar-plus-o":["calendar plus outlined"],"calendar-minus-o":["calendar minus outlined"],"calendar-times-o":["calendar times outlined"],"calendar-check-o":["ok","calendar check outlined"],"industry":["factory","industry"],"map-pin":["map pin"],"map-signs":["map signs"],"map-o":["map outlined"],"map":["map"],"commenting":["speech","notification","note","chat","bubble","feedback","message","texting","sms","conversation","commenting"],"commenting-o":["speech","notification","note","chat","bubble","feedback","message","texting","sms","conversation","commenting outlined"],"houzz":["houzz"],"vimeo":["vimeo"],"black-tie":["font awesome black tie"],"fonticons":["fonticons"],"reddit-alien":["reddit alien"],"edge":["browser","ie","edge browser"],"credit-card-alt":["money","buy","debit","checkout","purchase","payment","credit card","credit card"],"codiepie":["codie pie"],"modx":["modx"],"fort-awesome":["fort awesome"],"usb":["usb"],"product-hunt":["product hunt"],"mixcloud":["mixcloud"],"scribd":["scribd"],"pause-circle":["pause circle"],"pause-circle-o":["pause circle outlined"],"stop-circle":["stop circle"],"stop-circle-o":["stop circle outlined"],"shopping-bag":["shopping bag"],"shopping-basket":["shopping basket"],"hashtag":["hashtag"],"bluetooth":["bluetooth"],"bluetooth-b":["bluetooth"],"percent":["percent"],"gitlab":["gitlab"],"wpbeginner":["wpbeginner"],"wpforms":["wpforms"],"envira":["leaf","envira gallery"],"universal-access":["universal access"],"wheelchair-alt":["handicap","person","wheelchair alt"],"question-circle-o":["question circle outlined"],"blind":["blind"],"audio-description":["audio description"],"volume-control-phone":["telephone","volume control phone"],"braille":["braille"],"assistive-listening-systems":["assistive listening systems"],"american-sign-language-interpreting":["american sign language interpreting"],"deaf":["deaf"],"glide":["glide"],"glide-g":["glide g"],"sign-language":["sign language"],"low-vision":["low vision"],"viadeo":["viadeo"],"viadeo-square":["viadeo square"],"snapchat":["snapchat"],"snapchat-ghost":["snapchat ghost"],"snapchat-square":["snapchat square"],"pied-piper":["pied piper logo"],"first-order":["first order"],"yoast":["yoast"],"themeisle":["themeisle"],"google-plus-official":["google plus official"],"font-awesome":["font awesome"],"handshake-o":["handshake outlined"],"envelope-open":["envelope open"],"envelope-open-o":["envelope open outlined"],"linode":["linode"],"address-book":["address book"],"address-book-o":["address book outlined"],"address-card":["address card"],"address-card-o":["address card outlined"],"user-circle":["user circle"],"user-circle-o":["user circle outlined"],"user-o":["user outlined"],"id-badge":["identification badge"],"id-card":["identification card"],"id-card-o":["identification card outlined"],"quora":["quora"],"free-code-camp":["free code camp"],"telegram":["telegram"],"thermometer-full":["thermometer full"],"thermometer-three-quarters":["thermometer 3/4 full"],"thermometer-half":["thermometer 1/2 full"],"thermometer-quarter":["thermometer 1/4 full"],"thermometer-empty":["thermometer empty"],"shower":["shower"],"bath":["bath"],"podcast":["podcast"],"window-maximize":["window maximize"],"window-minimize":["window minimize"],"window-restore":["window restore"],"window-close":["window close"],"window-close-o":["window close outline"],"bandcamp":["bandcamp"],"grav":["grav"],"etsy":["etsy"],"imdb":["imdb"],"ravelry":["ravelry"],"eercast":["eercast"],"microchip":["microchip"],"snowflake-o":["snowflake outlined"],"superpowers":["superpowers"],"wpexplorer":["wpexplorer"],"meetup":["meetup"]};
}
function _cfsSelectFieldIconClb( btn ) {
	if(!CFS_DATA.isPro) {
		cfsFillAndShowMainPromoWnd(jQuery('#cfsFormFieldIconSettings'));
		return;
	}
	var $btn = jQuery( btn )
	,	isActive = $btn.hasClass('active');
	jQuery('#cfsFieldIconsShell').find('.active').removeClass('active');
	if( isActive ) {	// Deselect
		jQuery('#cfsFieldIconSelected').html('');
		jQuery('#cfsFieldIconClassInp').val('');
	} else {
		var iconClass = $btn.attr('href');
		$btn.addClass('active');
		jQuery('#cfsFieldIconSelected').html( iconClass );
		jQuery('#cfsFieldIconClassInp').val( iconClass );
	}
}
