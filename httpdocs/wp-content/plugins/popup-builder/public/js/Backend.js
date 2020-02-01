function SGPBBackend() {
	this.closeButtonDefaultPositions = {};
	this.closeButtonDefaultPositions[1] = {
		'left': 9,
		'right': 9,
		'bottom': 9
	};
	this.closeButtonDefaultPositions[2] = {
		'left': 0,
		'right': 0,
		'top': parseInt('-20'),
		'bottom': parseInt('-20')
	};
	this.closeButtonDefaultPositions[3] = {
		'right': 4,
		'bottom': 4,
		'left': 4,
		'top': 4
	};
	this.closeButtonDefaultPositions[4] = {
		'left': 12,
		'right': 12,
		'bottom': 9
	};
	this.closeButtonDefaultPositions[5] = {
		'left': 8,
		'right': 8,
		'bottom': 8
	};
	this.closeButtonDefaultPositions[6] = {
		'left': parseInt('-18.5'),
		'right': parseInt('-18.5'),
		'bottom': parseInt('-18.5'),
		'top': parseInt('-18.5')
	};
}

SGPBBackend.sgAddEvent = function(element, eventName, fn)
{
	if (element.addEventListener) {
		element.addEventListener(eventName, fn, false);
	}
	else if (element.attachEvent) {
		element.attachEvent('on' + eventName, fn);
	}
};

SGPBBackend.prototype.sgInit = function()
{
	this.makePopupTitleRequired();
	this.targetCondition();
	this.popupSelect2();
	this.sgTabs();
	this.accordion();
	this.initRadioAccordions();
	this.fixedPositionSelection();
	this.popupThemesPreview();
	this.setCloseButtonDefaultPositionValues();
	this.colorPicker();
	this.rangeSlider();
	this.backgroundRangeSliderInit();
	this.overlayRangeSliderInit();
	this.imageUpload();
	this.buttonImageUpload();
	this.buttonImageRemove();
	this.backgroundImageUpload();
	this.backgroundImageRemove();
	this.multipleChoiceButton();
	this.switchPopupActive();
	this.previewInit();
	this.soundUpload();
	this.resetSound();
	this.soundPreview();
	this.showInfo();
	this.openAnimationPreview();
	this.closeAnimationPreview();
	this.resetToDefaultValue();
	this.editPopupSettingsForFullscreenMode();
	this.autosave();
	this.popupBuilderButton();
	this.downloadSystemInfoFile();
};

SGPBBackend.prototype.changeTab = function(tab)
{
	jQuery('#sgpb-editor-options-tab-content-wrapper-'+tab).css('display', 'none');
	var i, tabContent, tabLinks;

	tabContent = jQuery('.sgpb-editor-options-tab-content-wrapper');
	tabContent.each(function(){
		jQuery(this).css('display', 'none');
	});
	tabLinks = jQuery('.sgpb-editor-tab-links');
	tabLinks.each(function(){
		jQuery(this).removeClass('sgpb-active-tab');
	});
	jQuery('#sgpb-editor-options-tab-content-wrapper-'+tab).css('display', 'block');
	jQuery('.sgpb-editor-tab-'+tab).addClass('sgpb-active-tab');
	this.rangeSlider();
};

SGPBBackend.prototype.downloadSystemInfoFile = function() {
	if (!jQuery('.sgpb-download-system-info').length) {
		return false;
	}
	jQuery('.sgpb-download-system-info').bind('click', function() {
		window.location.href = SGPB_JS_PARAMS.postUrl+'?action=sgpb_system_info';
	});
}

SGPBBackend.prototype.resetCssEditorContent = function() {
	jQuery('.editor-content-css').val('');
}

SGPBBackend.prototype.popupBuilderButton = function()
{
	var that = this;
	jQuery(document).on('tinymce-editor-setup', function( event, editor ) {
		if (editor.settings.toolbar1.indexOf('popupBuilderHtmlButton') != -1) {
			return;
		}
		editor.settings.toolbar1 += ', popupBuilderHtmlButton';
		editor.addButton('popupBuilderHtmlButton', {
			text: 'Popup Builder Button',
			tooltip: 'Popup Builder Custom HTML Button',
			icon: 'wp-menu-image dashicons-before dashicons-menu-icon-sgpb-button',
			onclick: function () {
				that.mediaButtonPopup('sgpb-custom-button-wrapper');
			}
		});
	});
};

SGPBBackend.popups = [];

SGPBBackend.prototype.mediaButtonPopup = function(hiddenDivId)
{
	var select2Init = 1;
	var that = this;
	var popupConfigObj = new PopupConfig();
	popupConfigObj.magicCall('setContentPadding', 14);
	popupConfigObj.magicCall('setContentBorderRadius', 4);
	popupConfigObj.magicCall('setContentBorderRadiusType', 'px');
	popupConfigObj.magicCall('setScrollingEnabled', true);
	popupConfigObj.magicCall('setContentBorderWidth', 5);
	popupConfigObj.magicCall('setContentBorderColor', '#506274');
	popupConfigObj.magicCall('setShadowSpread', 1);
	popupConfigObj.magicCall('setContentShadowBlur', 4);
	popupConfigObj.magicCall('setContentShadowColor', '#cccccc');
	popupConfigObj.magicCall('setMinWidth', 400);
	popupConfigObj.magicCall('contents', document.getElementById(hiddenDivId));
	popupConfigObj.magicCall('setOverlayColor', 'black');
	popupConfigObj.magicCall('setOverlayOpacity', 40);
	var config = popupConfigObj.combineConfigObj();
	var popup = new SGPopup(config);
	if (!SGPBBackend.popups.length) {
		SGPBBackend.popups.push(popup);
	}
	SGPBBackend.popups[0].open();
	jQuery(window).bind('sgpbDidOpen', function() {
		jQuery('.sgpb-insert-popup').addClass('js-sg-select2');
		/* one select box for inside popup shortcode */
		if (select2Init == 1) {
				that.popupSelect2();
		}
		select2Init++;
		jQuery('.select2-container--below').remove();
		that.popupSelect2();
		that.customButtonColorPicker();

		if (mediaButtonParams.currentPostType != mediaButtonParams.popupBuilderPostType) {
			that.customButtonColorPicker();
			jQuery('.sgpb-custom-button-popup').addClass('js-sg-select2');
			if (select2Init == 1) {
				that.popupSelect2();
			}
			select2Init++;
			jQuery('.select2-container--below').remove();
			that.popupSelect2();
		}
		else {
			that.multipleChoiceButton();
			that.customButtonColorPicker();
		}

		that.insertHTMLButtonToEditor();
		that.closeMediaButtonPopup(popup);
	});

	that.accordion();
	jQuery(window).on('sgpbMultichoiceChanged', function() {
		that.accordion();
	});
};

SGPBBackend.prototype.customButtonColorPicker = function()
{
	var that = this;
	var colorPicker = jQuery('.sgpb-custom-button-color-picker');
	if (!colorPicker.length) {
		return false;
	}
	colorPicker.wpColorPicker({
		change: function() {
			var colorPickerElement = jQuery(this);
			that.changeColor(colorPickerElement);
		}
	});
	jQuery('.wp-picker-holder').bind('click', function() {
		var selectedInput = jQuery(this).prev().find('.sgpb-color-picker');
		that.changeColor(selectedInput);
	});
};


SGPBBackend.prototype.insertHTMLButtonToEditor = function()
{
	jQuery('.sgpb-insert-custom-button-to-editor').unbind('click').bind('click', function () {
		var buttonTitle = jQuery('#sgpb-custom-btn-title').val();
		var style = '';
		jQuery('.sgpb-custom-button-settings').each(function() {
			var styleType = jQuery(this).data('style-type');
			var val = jQuery(this).val();
			style += styleType+': '+val+';';
		});
		var defaultStyles = {
			padding: 0,
			'font-size': '22px',
			'font-weight': 900
		};

		for (var styleType in defaultStyles) {
			style += styleType+': '+defaultStyles[styleType]+';';
		}


		var serizlizedOption = jQuery('#sgpb-custom-button-wrapper').find('select,textarea, input');
		var allOptionsObj = {};

		serizlizedOption.each(function() {
			var name = jQuery(this).attr('name');
			if (jQuery(this).attr('type') == 'checkbox') {
				if (jQuery(this).is(':checked')) {
					var value = jQuery(this).val();
					allOptionsObj[name] = value;
				}
				return true;
			}
			if (jQuery(this).attr('type') == 'radio') {
				if (jQuery(this).is(':checked')) {
					var value = jQuery(this).val();
					allOptionsObj[name] = value;
				}
			}
			else {
				var value = jQuery(this).val();
				allOptionsObj[name] = value;
			}
		});
		var bgColor = allOptionsObj['sgpb-custom-btn-bg-color'];
		var hoverBgColor = allOptionsObj['sgpb-custom-btn-bg-color'];

		var allOptionsJson = encodeURI(JSON.stringify(allOptionsObj));
		var id = Math.floor(Math.random() * Math.floor(100000));

		var button = '<button style="'+style+'" class="sgpb-html-custom-button sgpb-html-custom-button-'+id+'" data-options='+allOptionsJson+' onMouseOver="this.style.backgroundColor="'+hoverBgColor+'"  onMouseOut="this.style.backgroundColor="'+bgColor+'" ">'+buttonTitle+'</button>';
		button += '<style>.sgpb-html-custom-button-'+id+':hover {background-color: '+hoverBgColor+' !important;}</style>';
		if (allOptionsObj['sgpb-custom-button'] == 'openPopup') {
			var currentPopupId = allOptionsObj['sgpb-custom-button-popup'];
			button = '[sg_popup id="'+currentPopupId+'" insidePopup="on"] <br>'+button+'<br>[/sg_popup]';
		}
		window.send_to_editor(button);
	});
};

SGPBBackend.prototype.closeMediaButtonPopup = function(popup)
{
	jQuery('.sgpb-close-media-popup-js').on('click', function() {
		popup.close();
	});
};

SGPBBackend.prototype.resetToDefaultValue = function()
{
	var htmlTarget = jQuery('.js-sgpb-reset-default-value');

	if (!htmlTarget.length) {
		return false;
	}

	htmlTarget.each(function() {
		jQuery(this).bind('change', function() {
			var currentValue = jQuery(this).val();
			var defaultValue = jQuery(this).data('default');

			if (!defaultValue || currentValue) {
				return false;
			}
			jQuery(this).val(defaultValue)
		});
	})
};

SGPBBackend.prototype.showInfo = function()
{
	jQuery('.sgpb-info-icon').hover(
		function() {
			jQuery(this).nextAll('.sgpb-info-text').first().css({'display' : 'inline-block'});
		},
		function() {
			jQuery(this).nextAll('.sgpb-info-text').first().css({'display' : 'none'});
		}
	);
};

SGPBBackend.prototype.soundPreview = function()
{
	var songValue = 1;
	var lastSong = undefined;

	jQuery('.js-preview-sound').bind('click', function() {
		var uploadFile = jQuery('#js-sound-open-url').val();
		if (typeof lastSong == 'undefined') {
			lastSong = new Audio (uploadFile);
		}

		/*
		 * songValue == 1 should be song
		 * songValue == 2 song should be pause
		 */
		if (songValue == 1) {
			lastSong.play();
			songValue = 2;

		}
		else if (songValue == 2) {
			lastSong.pause();
			songValue = 1;

		}

		lastSong.onended = function()
		{
			lastSong = undefined;
			songValue = 1;
		}
	});

	jQuery('#js-sound-open-url').change(function() {
		if (typeof lastSong != 'undefined') {
			lastSong.pause();
			lastSong = undefined;
		}
		songValue = 1;
	});

	jQuery('#js-reset-to-default-song').click(function(e) {
		e.preventDefault();

		if (typeof lastSong != 'undefined') {
			lastSong.pause();
			lastSong = undefined;
		}
		songValue = 1;

		var defaultSong = jQuery(this).data('default-song');
		jQuery('#js-sound-open-url').val(defaultSong).change();
	});
};

SGPBBackend.prototype.resetSound = function()
{
	var resetButton = jQuery('#js-reset-to-default-song');

	if (!resetButton.length) {
		return false;
	}

	resetButton.bind('click', function() {
		var defaultSoundUrl = jQuery(this).data('default-song');
		jQuery('#js-sound-open-url').val(defaultSoundUrl).change();
	});
};

SGPBBackend.prototype.soundUpload = function()
{
	var uploadButton = jQuery('#js-upload-open-sound-button');
	var supportedAudioTypes = ['audio/mp3', 'audio/m4a', 'audio/ogg', 'audio/wav', 'audio/mpeg'];

	if (!uploadButton.length) {
		return false;
	}
	var uploader;
	uploadButton.bind('click', function(e) {
		e.preventDefault();

		if (uploader) {
			uploader.open();
			return false;
		}

		/* Extend the wp.media object */
		uploader = wp.media.frames.file_frame = wp.media({
			titleFF : SGPB_JS_LOCALIZATION.changeSound,
			button : {
				text : SGPB_JS_LOCALIZATION.changeSound
			},
			library : {type : ['audio/mpeg', 'audio/wav']},
			multiple : false
		});

		/* When a file is selected, grab the URL and set it as the text field's value */
		uploader.on('select', function() {
			var attachment = uploader.state().get('selection').first().toJSON();
			if (supportedAudioTypes.indexOf(attachment.mime) === -1) {
				alert(SGPB_JS_LOCALIZATION.audioSupportAlertMessage);
				return;
			}
			jQuery('#js-sound-open-url').val(attachment.url).change();
		});
		/* Open the uploader dialog */
		uploader.open();
	});
};

SGPBBackend.prototype.openAnimationPreview = function()
{
	var openAnimationPreview = jQuery('.sgpb-preview-animation');

	if (!openAnimationPreview.length) {
		return false;
	}
	var openAnimation = jQuery('.sgpb-preview-open-animation');
	var openAnimationDiv = jQuery('#js-open-animation-effect');
	var speed = jQuery('#sgpb-open-animation-speed');

	var openAnimationAction = function() {
		var speedVal = parseInt(speed.val());

		if (!speedVal) {
			speedVal = 1;
		}
		var speedSeconds =  speedVal * 1000;

		setTimeout(function() {
			openAnimationDiv.hide();
		}, speedSeconds);
		openAnimationDiv.removeClass();
		openAnimationDiv.show();
		openAnimationDiv.css({'animationDuration' : speedSeconds + 'ms'});
		openAnimationDiv.addClass('sg-animated ' + jQuery('.sgpb-open-animation-effects option:selected').val());
	};

	jQuery('.sgpb-open-animation-effects').bind('change', openAnimationAction);
	openAnimationPreview.bind('click', openAnimationAction);
};

SGPBBackend.prototype.closeAnimationPreview = function()
{
	var closeAnimationPreview = jQuery('.sgpb-preview-close-animation');

	if (!closeAnimationPreview.length) {
		return false;
	}
	var closeAnimation = jQuery('.sgpb-preview-close-animation');
	var closeAnimationDiv = jQuery('#js-close-animation-effect');
	var speed = jQuery('#sgpb-close-animation-speed');

	var closeAnimationAction = function() {
		var speedVal = parseInt(speed.val());

		if (!speedVal) {
			speedVal = 1;
		}
		var speedSeconds =  speedVal * 1000;

		setTimeout(function() {
			closeAnimationDiv.hide();
		}, speedSeconds);
		closeAnimationDiv.removeClass();
		closeAnimationDiv.show();
		closeAnimationDiv.css({'animationDuration' : speedSeconds + 'ms'});
		closeAnimationDiv.addClass('sg-animated ' + jQuery('.sgpb-close-animation-effects option:selected').val());
	};

	jQuery('.sgpb-close-animation-effects').bind('change', closeAnimationAction);
	closeAnimationPreview.bind('click', closeAnimationAction);
};

SGPBBackend.prototype.multipleChoiceButton = function()
{
	if (!jQuery('.sgpb-choice-wrapper input').length) {
		return false;
	}
	var that = this;

	jQuery('.sgpb-choice-option-wrapper input').each(function() {
		if (jQuery(this).is(':checked')) {
			that.buildChoiceShowOption(jQuery(this));
		}

		jQuery(this).on("click", function() {
			that.hideAllChoiceWrapper(jQuery('.sgpb-choice-option-wrapper'));
			that.buildChoiceShowOption(jQuery(this));
			jQuery(window).trigger('sgpbMultichoiceChanged');
		});
	})
};

SGPBBackend.prototype.hideAllChoiceWrapper = function(choiceOptionsWrapper)
{
	choiceOptionsWrapper.each(function() {
		var choiceInput = jQuery(this).find('input');
		if (!choiceInput.length) {
			return;
		}
		var choiceInputWrapperId = choiceInput.attr('data-attr-href');
		jQuery('#'+choiceInputWrapperId).addClass('sg-hide');
	})
};

SGPBBackend.prototype.buildChoiceShowOption = function(currentRadioButton)
{
	var choiceOptions = currentRadioButton.attr('data-attr-href');
	var currentOptionWrapper = currentRadioButton.parents('.sgpb-choice-wrapper').first();
	var choiceOptionWrapper = jQuery('#'+choiceOptions).removeClass('sg-hide');
	currentOptionWrapper.after(choiceOptionWrapper);
};

SGPBBackend.prototype.initRadioAccordions = function()
{
	var radioButtonsList = [
		jQuery("[name='sgpb-content-click-behavior']"),
		jQuery("[name='sgpb-popup-dimension-mode']")
	];

	for (var radioButtonIndex in radioButtonsList) {

		var radioButton = radioButtonsList[radioButtonIndex];
		if (typeof radioButton != 'object') {
			continue;
		}
		var that = this;
		radioButton.each(function() {
			that.buildRadioAccordionActions(jQuery(this));
		});
		radioButton.on("change", function() {
			that.buildRadioAccordionActions(jQuery(this), 'change');
		});
	}
};

SGPBBackend.prototype.buildRadioAccordionActions = function(currentRadioButton, event)
{
	if (event == 'change') {
		currentRadioButton.parents('.sg-radio-option-behavior').first().find('.js-radio-accordion').addClass('sg-hide');
	}

	var value = currentRadioButton.val();
	var toggleContent = jQuery('.js-accordion-'+value);
	toggleContent.addClass('sg-hide');
	var addAfter = currentRadioButton;

	if (currentRadioButton.is(':checked')) {
		if (currentRadioButton.parents('.row').first().length) {
			addAfter = currentRadioButton.parents('.row').first();
		}
		jQuery('.js-accordion-'+value).removeClass('sg-hide');
		addAfter.after(toggleContent);
	}
};

SGPBBackend.prototype.accordion = function()
{
	var that = this;
	var element = jQuery(".js-checkbox-accordion");
	element.each(function() {
		that.checkboxAccordion(jQuery(this));
	});

	element.click(function() {
		var elements = jQuery(this);
		that.checkboxAccordion(jQuery(this));
		if (jQuery(this).attr('id') == 'sgpb-show-background') {
			SGPBBackend.prototype.backgroundRangeSliderInit();
		}
		else if (jQuery(this).attr('id') == 'sgpb-enable-popup-overlay') {
			SGPBBackend.prototype.overlayRangeSliderInit();
		}
	});
};

SGPBBackend.prototype.checkboxAccordion = function(element)
{
	if (!element.is(':checked')) {
		element.parents('.row').first().nextAll('div').first().find('input').attr('disabled', 'disabled');
		element.parents('.row').first().nextAll('div').first().css({'display': 'none'});
		if (element.attr('id') == 'schedule-status') {
			element.parents('.row').first().nextAll('div').first().find('select').removeAttr('required');
		}
	}
	else {
		element.parents('.row').first().nextAll('div').first().find('input:disabled').removeAttr('disabled');
		element.parents('.row').first().nextAll('div').first().css({'display':'inline-block'});
		if (element.attr('id') == 'schedule-status') {
			element.parents('.row').first().nextAll('div').first().find('select').attr('required', 'required');
		}
	}
};

SGPBBackend.prototype.sgTabs = function()
{
	jQuery('.events-tabs').each(function() {
		jQuery(this).tabs();
	});
};

SGPBBackend.prototype.targetCondition = function()
{
	this.addRuleRow();
	this.addGroupRow();
	this.removeRuleButton();
	this.editOpenPopup();
	this.changeConditionParams();
};

SGPBBackend.prototype.reInitRulesConfigButton = function()
{
	this.addRuleRow();
	this.addGroupRow();
	this.removeRuleButton();
	this.changeConditionParams();
	this.editOpenPopup();
	this.popupSelect2();
	this.sgTabs();
	this.showInfo();
};

SGPBBackend.prototype.editOpenPopup = function()
{
	if (jQuery('.sg-rules-edit-rule').length == 0) {
		return;
	}
	var that = this;
	that.unbindPopup();

	jQuery('.sg-rules-edit-rule').each(function() {
		jQuery(this).on('click', function(e) {
			e.preventDefault();
			var hiddenDivId = jQuery(this).attr('data-id');
			var popupConfigObj = new PopupConfig();
			popupConfigObj.magicCall('setContentPadding', 8);
			popupConfigObj.magicCall('setMinWidth', 500);
			popupConfigObj.magicCall('setSrcElement', hiddenDivId);
			popupConfigObj.magicCall('setOverlayColor', 'black');
			var config = popupConfigObj.combineConfigObj();
			config.willOpen = function() {
				that.reInitRulesConfigButton();
			};
			var popup = new SGPopup(config);
			popup.open();
			jQuery(window).bind('sgpbDidOpen', function() {
				that.popupContentTabs();
				that.popupHiddenContentAccordions();
			});
			jQuery('.sgpb-popup-option-save').on('click', function(e) {
				e.preventDefault();

				var parent = jQuery(this).parents('.sgpb-wrapper').first();
				var elements = parent.find('.sgpb-popup-option');

				if (elements.length) {
					elements.each(function() {
						var currentValue = jQuery(this).val();
						var type = jQuery(this).attr('type');
						if (type == 'checkbox') {
							var currChecked = jQuery(this).is(':checked');
							jQuery(this).prop('defaultChecked', '');

							if (currChecked) {
								jQuery(this).prop('defaultChecked', 'checked')
							}
						}
						else if (type == 'text') {
							jQuery(this).attr("value", jQuery(this).val());
						}
						else if (type == 'number') {
							currentValue = parseInt(currentValue);
							if (isNaN(currentValue)) {
								/*If user write string inside Expiry time we change the value to 0*/
								currentValue = 0;
							}
							jQuery(this).attr("value", currentValue);
						}
					});
				}
				popup.close();
			});

			jQuery('.events-option-close').on('click', function() {
				popup.close();
			});

			that.unbindPopup();

		});
	});
};

SGPBBackend.prototype.unbindPopup = function()
{
	jQuery('.sg-rules-edit-rule').each(function() {
		jQuery(this).unbind();
	})
};

SGPBBackend.prototype.addRuleRow = function()
{
	var that = this;

	jQuery('.sg-rules-add-rule ').unbind();
	jQuery('.sg-rules-add-rule ').on('click', function(e) {
		e.preventDefault();
		that.eventsAddButtonSpinner(jQuery(this), 'show');
		var prevRuleDiv = jQuery(this).parents('.sg-target-rule').first();
		var currentGroupDiv = jQuery(this).parents('.sg-target-group').first();
		var lastRuleId = parseInt(prevRuleDiv.attr('data-rule-id'));
		var groupId = parseInt(currentGroupDiv.attr('data-group-id'));
		var conditionName = jQuery(this).parents('.popup-conditions-wrapper').attr('data-condition-type');
		var ruleId = lastRuleId + 1;

		var data = {
			action: 'add_condition_rule_row',
			nonce_ajax: SGPB_JS_PARAMS.nonce,
			conditionName: conditionName,
			ruleId: ruleId,
			groupId: groupId
		};

		jQuery.post(ajaxurl, data, function(response) {
			prevRuleDiv.after(response);
			jQuery('.popup-conditions-'+conditionName+' > .sg-target-group-'+groupId+' .sg-target-rule-'+lastRuleId+' .sg-rules-add-button-wrapper').hide();
			that.reInitRulesConfigButton();
			that.eventsAddButtonSpinner(jQuery(this), 'hide');
		});
	});
	if (typeof SGPBSelect2 === 'function') {
		SGPBSelect2.prototype.hideProOptions();
	}
};

SGPBBackend.prototype.addGroupRow = function()
{
	var that = this;
	jQuery('.sg-rules-add-group').unbind();
	jQuery('.sg-rules-add-group').on('click', function(e) {
		e.preventDefault();
		var prevGroupDiv = jQuery(this).prevAll('.sg-target-group').first();
		var currentGroupId = parseInt(prevGroupDiv.attr('data-group-id'));
		var newGroupId = currentGroupId + 1;
		var conditionName = jQuery(this).parents('.popup-conditions-wrapper').attr('data-condition-type');

		var data = {
			action: 'add_condition_group_row',
			nonce_ajax: SGPB_JS_PARAMS.nonce,
			conditionName: conditionName,
			groupId: newGroupId
		};

		jQuery.post(ajaxurl, data, function(response) {
			prevGroupDiv.after(response);
			that.reInitRulesConfigButton();
		});
	});
};

SGPBBackend.prototype.removeRuleButton = function()
{
	jQuery('.sg-rules-delete-rule').unbind();
	jQuery('.sg-rules-delete-rule').on('click', function(e) {
		e.preventDefault();
		var currentTargetWrapperDiv = jQuery(this).parents('.popup-conditions-wrapper').first();
		var currentGroupDiv = jQuery(this).parents('.sg-target-group').first();
		var currentRuleDiv = jQuery(this).parents('.sg-target-rule').first();
		var firstGroupDiv = currentTargetWrapperDiv.find('.sg-target-group').first();
		var lastRuleDiv = currentGroupDiv.find('.sg-target-rule').last();
		var firstRuleDiv = currentGroupDiv.find('.sg-target-rule').first();
		var currentGroupsLength = currentTargetWrapperDiv.find('.sg-target-group').length;
		var currentRulesLength = currentGroupDiv.find('.sg-target-rule').length;

		var currentGroupId = currentGroupDiv.attr('data-group-id');
		var firstGroupId = firstGroupDiv.attr('data-group-id');
		var currentRulId = currentRuleDiv.attr('data-rule-id');
		var lastRuleId = lastRuleDiv.attr('data-rule-id');
		var firstRuleId = firstRuleDiv.attr('data-rule-id');


		if (currentRulId > firstRuleId) {
			currentRuleDiv.remove();
		}

		/*When the last rule*/
		if (currentGroupId == firstGroupId && currentGroupsLength == 1 && currentRulId == lastRuleId && currentRulesLength == 1) {
			alert('You can not delete the last rule.');
		}
		else {
			currentRuleDiv.remove();
		}

		if (currentRulId == lastRuleId) {
			lastRuleDiv = currentGroupDiv.find('.sg-target-rule').last();
			lastRuleDiv.find('.sg-rules-add-button-wrapper').removeAttr('style');
			lastRuleDiv.find('.sg-rules-add-button-wrapper').show();
		}

		if (currentRulId == firstRuleId && currentGroupsLength > 1) {

			if (currentRulesLength == 1) {
				if (currentGroupId == firstGroupId) {
					currentGroupDiv.next('.sg-rules-or').remove();
				}
				else {
					currentGroupDiv.prev('.sg-rules-or').remove();
				}

				currentGroupDiv.remove();
			}
			else {
				currentRuleDiv.remove();
			}
		}
	});
};

SGPBBackend.getParamFromUrl = function(param)
{
	var url = window.location.href;
	param = param.replace(/[\[\]]/g, "\\$&");
	var regex = new RegExp("[?&]" + param + "(=([^&#]*)|&|#|$)"),
		results = regex.exec(url);
	if (!results) {
		return null;
	}
	if (!results[2]) {
		return '';
	}
	return decodeURIComponent(results[2].replace(/\+/g, " "));
};

SGPBBackend.prototype.changeConditionParams = function()
{
	var that = this;

	jQuery('.popup-conditions-wrapper .sg-condition-param-wrapper select').each(function() {
		jQuery(this).unbind('change').change(function(e) {
			/* if value hasn't change, don't do anything */
			if (this.options[this.selectedIndex].defaultSelected) {
				return;
			}

			e.preventDefault();
			that.eventsAddButtonSpinner(jQuery(this).parent().parent().find('.sg-rules-add-rule'), 'show');
			var prevRuleDiv = jQuery(this).parents('.sg-target-rule').first();
			var currentGroupDiv = jQuery(this).parents('.sg-target-group').first();
			var currentTargetDiv = jQuery(this).parents('.popup-conditions-wrapper').first();

			var groupId = parseInt(currentGroupDiv.attr('data-group-id'));
			var ruleId = parseInt(prevRuleDiv.attr('data-rule-id'));
			var conditionName = currentTargetDiv.attr('data-condition-type');

			var parentDiv = currentTargetDiv.find('.sg-target-rule-'+ruleId).prev();
			if (!parentDiv.length) {
				parentDiv = null;
			}
			var paramSavedValue = jQuery(this).val();

			var data = {
				action: 'change_condition_rule_row',
				nonce_ajax: SGPB_JS_PARAMS.nonce,
				conditionName: conditionName,
				paramName: paramSavedValue,
				popupId: SGPBBackend.getParamFromUrl('post'),
				ruleId: ruleId,
				groupId: groupId
			};

			jQuery.post(ajaxurl, data, function(response) {
				currentTargetDiv.find('.sg-target-rule-'+ruleId).after(response);
				currentTargetDiv.find('.sg-target-rule-'+ruleId).first().remove();

				jQuery('.popup-conditions-'+conditionName+' > .sg-target-group-'+groupId+' .sg-target-rule-'+ruleId+' .sg-rules-add-button-wrapper').hide();
				that.reInitRulesConfigButton();

				if (!currentTargetDiv.find('.sg-target-rule-'+ruleId).next().length) {
					currentTargetDiv.find('.sg-rules-add-button-wrapper').last().show();
				}
				that.eventsAddButtonSpinner(jQuery(this).parent().parent().find('.sg-rules-add-rule'), 'hide');
			});

		});
	});

	/*
	This junky code was added because the code related to the column creation is not abstract enough.
	TODO: throw away all related code and create new architecture for the purpose.
	*/
	jQuery('.popup-special-conditions-wrapper .sg-condition-operator-wrapper select').each(function() {
		jQuery(this).change(function(e) {
			e.preventDefault();

			var paramSavedValue = jQuery(this).val();
			var currentTargetDiv = jQuery(this).closest('.popup-special-conditions-wrapper');
			var conditionName = currentTargetDiv.data('condition-type');
			var paramValue = currentTargetDiv.find('.sg-condition-param-wrapper select').first().val();
			var ruleId = 0;
			var groupId = 0;
			var prevRuleDiv = jQuery(this).parents('.sg-target-rule').first();
			var currentGroupDiv = jQuery(this).parents('.sg-target-group').first();
			var currentTargetDiv = jQuery(this).parents('.popup-conditions-wrapper').first();

			var groupId = parseInt(currentGroupDiv.attr('data-group-id'));
			var ruleId = parseInt(prevRuleDiv.attr('data-rule-id'));

			var data = {
				action: 'change_condition_rule_row',
				nonce_ajax: SGPB_JS_PARAMS.nonce,
				conditionName: conditionName,
				paramName: paramSavedValue,
				paramValue: paramValue,
				popupId: SGPBBackend.getParamFromUrl('post'),
				ruleId: ruleId,
				groupId: groupId
			};

			jQuery.post(ajaxurl, data, function(response) {
				currentTargetDiv.find('.sg-target-rule-'+ruleId).after(response);
				currentTargetDiv.find('.sg-target-rule-'+ruleId).first().remove();

				that.reInitRulesConfigButton();
				if (currentTargetDiv.find('.sg-target-rule-'+ruleId).next().length) {
					jQuery('.popup-conditions-'+conditionName+' > .sg-target-group-'+groupId+' .sg-target-rule-'+ruleId+' .sg-rules-add-button-wrapper').hide();
				}
			});
		});
	});
};

SGPBBackend.prototype.popupSelect2 = function()
{
	if (!jQuery('.js-sg-select2').length) {
		return;
	}

	jQuery('select.js-sg-select2').each(function() {

		var type = jQuery(this).attr('data-select-type');
		var className = jQuery(this).attr('data-select-class');
		var options = {
			width: '100%'
		};

		if (type == 'ajax') {

			options = jQuery.extend(options, {
				minimumInputLength: 1,
				ajax: {
					url: SGPB_JS_PARAMS.url,
					dataType: 'json',
					delay: 250,
					type: "POST",
					data: function(params) {
						var searchKey = jQuery(this).attr('data-value-param');
						var dataCallback = jQuery(this).attr('data-select-callback');
						return {
							action: 'select2_search_data',
							nonce_ajax: SGPB_JS_PARAMS.nonce,
							searchTerm: params.term,
							searchCallback: dataCallback,
							searchKey: searchKey
						};
					},
					processResults: function(data) {
						return {
							results: jQuery.map(data.items, function(item) {
								return {
									text: item.text,
									id: item.id
								}

							})
						};
					}
				}
			});
		}

		jQuery(this).sgpbselect2(options);
	});
};

SGPBBackend.prototype.fixedPositionSelection = function()
{
	jQuery(".js-fixed-position-style").bind("click",function() {
		var sgElement = jQuery(this);
		var sgPoss = sgElement.attr('data-sgvalue');
		jQuery(".js-fixed-position-style").css("backgroundColor","#FFFFFF");
		jQuery(this).css("backgroundColor","rgba(70,173,208,0.5)");
		jQuery(".js-fixed-position").val(sgPoss);
	});

	jQuery(".js-fixed-position-style").bind("mouseover",function() {
		jQuery(".js-fixed-position-style").css("backgroundColor","#FFFFFF");
		jQuery(this).css("backgroundColor","rgb(70,173,208)");
		jQuery(".js-fixed-position-style").each(function() {
			if (jQuery(this).attr("data-sgvalue") == jQuery('.js-fixed-position').val())
				jQuery(this).css("backgroundColor","rgba(70,173,208,0.5)");
		});
	});

	jQuery(".js-fixed-position-style").bind("mouseout",function() {
		if (jQuery(".js-fixed-position-style").attr("data-sgvalue") !== jQuery(".js-fixed-position").val() || jQuery(".js-fixed-position").val() == 1) {
			jQuery(this).css("backgroundColor","#FFFFFF");
		}
		jQuery(".js-fixed-position-style").each(function() {
			if (jQuery(this).attr("data-sgvalue") == jQuery('.js-fixed-position').val()) {
				jQuery(this).css("backgroundColor","rgba(70,173,208,0.5)");
			}
		});
	});

	if (jQuery('.js-fixed-position').val()!='') {
		jQuery(".js-fixed-position-style").each(function(){
			if (jQuery(this).attr("data-sgvalue") == jQuery('.js-fixed-position').val()) {
				jQuery(this).css("backgroundColor","rgba(70,173,208,0.5)");
			}
		});
	}
};

SGPBBackend.prototype.hideTabContents = function(ruleId)
{
	jQuery(".sgpb-tab-content-"+ruleId).each(function() {
		var id =  jQuery(this).attr("id");
		var currentTab = jQuery("[data-content-id="+id+"]");

		if (!currentTab.hasClass('sgpb-active')) {
			jQuery(this).hide();
		}

	});
};

SGPBBackend.prototype.popupContentTabs = function()
{
	var ruleId = jQuery("#popup-dialog-main-div .sgpb-tab-links").first().attr('data-rule-id');
	var that = this;
	this.hideTabContents(ruleId);

	jQuery("#popupDialogMainDiv .sgpb-tab-links").bind('click', function() {
		jQuery("#popupDialogMainDiv .sgpb-tab-links").removeClass('sgpb-active');
		that.hideTabContents(ruleId);
		jQuery(".sgpb-tab-content").hide();
		var id = jQuery(this).attr('data-content-id');
		jQuery('#'+id).show();
		jQuery(this).addClass('sgpb-active');
	});
};

SGPBBackend.prototype.popupHiddenContentAccordions = function()
{
	jQuery('.sgpb-popup-accordion').each(function() {
		var key = jQuery(this).attr('data-name');
		var ruleId = jQuery(this).attr('data-rule-id');
		if (!jQuery(this).is(':checked')) {
			jQuery(".sgpb-popup-hidden-content-"+key+"-"+ruleId+"-wrapper").hide();
		}
	});

	jQuery('.sgpb-popup-accordion').bind('change', function() {
		var key = jQuery(this).attr('data-name');
		var ruleId = jQuery(this).attr('data-rule-id');
		jQuery(".sgpb-popup-hidden-content-"+key+"-"+ruleId+"-wrapper").hide();
		if (jQuery(this).is(':checked')) {
			jQuery(".sgpb-popup-hidden-content-"+key+"-"+ruleId+"-wrapper").show();
		}
	});
};

SGPBBackend.prototype.popupThemesPreview = function()
{
	var that = this;
	if (!jQuery('.js-sgpb-popup-themes').length){
		return false;
	}

	that.themeRelatedSettings();
	jQuery('.js-sgpb-popup-themes').bind("mouseover",function(e) {
		var themeId = jQuery(this).attr('data-popup-theme-number');
		jQuery('.theme-preview-'+themeId).css('display', 'block');
		jQuery(this).click(function() {
			jQuery('.sgpb-disable-border-wrapper').addClass('sg-hide');
			that.setCloseButtonDefaultPositions();
			that.setCloseButtonDefaultPositionValues();
			document.getElementById('sgpb-button-position-top').value = 'none';
			document.getElementById('sgpb-button-position-right').value = 'none';
			document.getElementById('sgpb-button-position-bottom').value = 'none';
			document.getElementById('sgpb-button-position-left').value = 'none';
			if (themeId == 4) {/* for theme with close button type=button */
				jQuery('.sgpb-close-button-image-option-wrapper').addClass('sg-hide');
				jQuery('.sgpb-close-button-border-options').addClass('sg-hide');
				jQuery('.sgpb-close-button-text-option-wrapper').removeClass('sg-hide');
				document.getElementById('sgpb-button-position-bottom').value = that.closeButtonDefaultPositions[themeId].bottom;
				document.getElementById('sgpb-button-position-right').value = that.closeButtonDefaultPositions[themeId].right;
			}
			else if (themeId == 3) {
				jQuery('.sgpb-close-button-text-option-wrapper').addClass('sg-hide');
				jQuery('.sgpb-close-button-border-options').removeClass('sg-hide');
				jQuery('.sgpb-close-button-image-option-wrapper').removeClass('sg-hide');
				/* set default close button sizes for the current theme */
				jQuery('input[name=sgpb-button-image-width]').val('38');
				jQuery('input[name=sgpb-button-image-height]').val('19');
				jQuery('.sgpb-disable-border-wrapper').removeClass('sg-hide');
				document.getElementById('sgpb-button-position-right').value = that.closeButtonDefaultPositions[themeId].right;
				document.getElementById('sgpb-button-position-top').value = that.closeButtonDefaultPositions[themeId].top;
			}
			else {
				if (themeId == 2) {
					/* default theme 2 button size */
					jQuery('input[name=sgpb-button-image-width]').val('20');
					jQuery('input[name=sgpb-button-image-height]').val('20');
					jQuery('.sgpb-disable-border-wrapper').removeClass('sg-hide');
					document.getElementById('sgpb-button-position-right').value = that.closeButtonDefaultPositions[themeId].right;
					document.getElementById('sgpb-button-position-top').value = that.closeButtonDefaultPositions[themeId].top;
				}
				else if (themeId == 5) {
					/* default theme 5 button size */
					jQuery('input[name=sgpb-button-image-width]').val('17');
					jQuery('input[name=sgpb-button-image-height]').val('17');
					document.getElementById('sgpb-button-position-bottom').value = that.closeButtonDefaultPositions[themeId].bottom;
					document.getElementById('sgpb-button-position-right').value = that.closeButtonDefaultPositions[themeId].right;
				}
				else if (themeId == 6) {
					/* default theme 6 button size */
					jQuery('input[name=sgpb-button-image-width]').val('30');
					jQuery('input[name=sgpb-button-image-height]').val('30');
					document.getElementById('sgpb-button-position-top').value = that.closeButtonDefaultPositions[themeId].top;
					document.getElementById('sgpb-button-position-right').value = that.closeButtonDefaultPositions[themeId].right;
				}
				else {
					/* for other themes default sizes equel to 21 */
					jQuery('input[name=sgpb-button-image-width]').val('21');
					jQuery('input[name=sgpb-button-image-height]').val('21');
				}
				if (themeId == 1) {
					document.getElementById('sgpb-button-position-bottom').value = that.closeButtonDefaultPositions[themeId].bottom;
					document.getElementById('sgpb-button-position-right').value = that.closeButtonDefaultPositions[themeId].right;
				}
				jQuery('.sgpb-close-button-text-option-wrapper').addClass('sg-hide');
				jQuery('.sgpb-close-button-border-options').addClass('sg-hide');
				jQuery('.sgpb-close-button-image-option-wrapper').removeClass('sg-hide');
			}
		});
	});

	jQuery('.js-sgpb-popup-themes').bind("mouseout",function(e) {
		jQuery('.themes-preview').css('display', 'none');
	});
};

/* set default positions while changing theme */
SGPBBackend.prototype.setCloseButtonDefaultPositions = function()
{
	var theme = jQuery('.js-sgpb-popup-themes:checked').attr('data-popup-theme-number');
	if (theme == 1 || theme == 4 || theme == 5) {
		jQuery('.sgpb-button-position-top-js').hide();
		jQuery('.sgpb-button-position-right-js').show();
		jQuery('.sgpb-button-position-left-js').hide();
		jQuery('.sgpb-button-position-bottom-js').show();
	}
	else if (theme == 2 || theme == 3 || theme == 6) {
		jQuery('.sgpb-button-position-top-js').show();
		jQuery('.sgpb-button-position-right-js').show();
		jQuery('.sgpb-button-position-left-js').hide();
		jQuery('.sgpb-button-position-bottom-js').hide();
	}
};

SGPBBackend.prototype.setCloseButtonDefaultPositionValues = function()
{
	var that = this;
	jQuery('.sgpb-close-button-position').on('change', function(){
		var theme = jQuery('.js-sgpb-popup-themes:checked').attr('data-popup-theme-number');
		/* button location => like topRight, bottomLeft, etc. */
		var buttonLocation = jQuery('.sgpb-close-button-position option:selected').val();
		that.setCloseButtonLocation(buttonLocation);
		document.getElementById('sgpb-button-position-top').value = 'none';
		document.getElementById('sgpb-button-position-right').value = 'none';
		document.getElementById('sgpb-button-position-bottom').value = 'none';
		document.getElementById('sgpb-button-position-left').value = 'none';
		if (theme == 1) {
			document.getElementById('sgpb-button-position-bottom').value = that.closeButtonDefaultPositions[theme].bottom;
			if (buttonLocation == 'bottomRight') {
				document.getElementById('sgpb-button-position-right').value = that.closeButtonDefaultPositions[theme].right;
			}
			else if (buttonLocation == 'bottomLeft') {
				document.getElementById('sgpb-button-position-left').value = that.closeButtonDefaultPositions[theme].left;
			}
		}
		else if (theme == 2) {
			if (buttonLocation == 'topLeft') {
				document.getElementById('sgpb-button-position-left').value = that.closeButtonDefaultPositions[theme].left;
				document.getElementById('sgpb-button-position-top').value = that.closeButtonDefaultPositions[theme].top;
			}
			else if (buttonLocation == 'topRight') {
				document.getElementById('sgpb-button-position-right').value = that.closeButtonDefaultPositions[theme].right;
				document.getElementById('sgpb-button-position-top').value = that.closeButtonDefaultPositions[theme].top;
			}
			else if (buttonLocation == 'bottomLeft') {
				document.getElementById('sgpb-button-position-left').value = that.closeButtonDefaultPositions[theme].left;
				document.getElementById('sgpb-button-position-bottom').value = that.closeButtonDefaultPositions[theme].bottom;
			}
			else if (buttonLocation == 'bottomRight') {
				document.getElementById('sgpb-button-position-right').value = that.closeButtonDefaultPositions[theme].right;
				document.getElementById('sgpb-button-position-bottom').value = that.closeButtonDefaultPositions[theme].bottom;
			}
		}
		else if (theme == 3) {
			if (buttonLocation == 'topLeft') {
				document.getElementById('sgpb-button-position-left').value = that.closeButtonDefaultPositions[theme].left;
				document.getElementById('sgpb-button-position-top').value = that.closeButtonDefaultPositions[theme].top;
			}
			else if (buttonLocation == 'topRight') {
				document.getElementById('sgpb-button-position-right').value = that.closeButtonDefaultPositions[theme].right;
				document.getElementById('sgpb-button-position-top').value = that.closeButtonDefaultPositions[theme].top;
			}
			else if (buttonLocation == 'bottomLeft') {
				document.getElementById('sgpb-button-position-left').value = that.closeButtonDefaultPositions[theme].left;
				document.getElementById('sgpb-button-position-bottom').value = that.closeButtonDefaultPositions[theme].bottom;
			}
			else if (buttonLocation == 'bottomRight') {
				document.getElementById('sgpb-button-position-right').value = that.closeButtonDefaultPositions[theme].right;
				document.getElementById('sgpb-button-position-bottom').value = that.closeButtonDefaultPositions[theme].bottom;
			}
		}
		else if (theme == 4) {
			document.getElementById('sgpb-button-position-bottom').value = that.closeButtonDefaultPositions[theme].bottom;
			if (buttonLocation == 'bottomRight') {
				document.getElementById('sgpb-button-position-right').value = that.closeButtonDefaultPositions[theme].right;
			}
			else if (buttonLocation == 'bottomLeft') {
				document.getElementById('sgpb-button-position-left').value = that.closeButtonDefaultPositions[theme].left;
			}
		}
		else if (theme == 5) {
			document.getElementById('sgpb-button-position-bottom').value = that.closeButtonDefaultPositions[theme].bottom;
			if (buttonLocation == 'bottomRight') {
				document.getElementById('sgpb-button-position-right').value = that.closeButtonDefaultPositions[theme].right;
			}
			else if (buttonLocation == 'bottomLeft') {
				document.getElementById('sgpb-button-position-left').value = that.closeButtonDefaultPositions[theme].left;
			}
		}
		else if (theme == 6) {
			if (buttonLocation == 'topRight') {
				document.getElementById('sgpb-button-position-right').value = that.closeButtonDefaultPositions[theme].right;
				document.getElementById('sgpb-button-position-top').value = that.closeButtonDefaultPositions[theme].top;
			}
			else if (buttonLocation == 'topLeft') {
				document.getElementById('sgpb-button-position-top').value = that.closeButtonDefaultPositions[theme].top;
				document.getElementById('sgpb-button-position-left').value = that.closeButtonDefaultPositions[theme].left;
			}
			else if (buttonLocation == 'bottomLeft') {
				document.getElementById('sgpb-button-position-left').value = that.closeButtonDefaultPositions[theme].left;
				document.getElementById('sgpb-button-position-bottom').value = that.closeButtonDefaultPositions[theme].bottom;
			}
			else if (buttonLocation == 'bottomRight') {
				document.getElementById('sgpb-button-position-right').value = that.closeButtonDefaultPositions[theme].right;
				document.getElementById('sgpb-button-position-bottom').value = that.closeButtonDefaultPositions[theme].bottom;
			}
		}
	});
};

SGPBBackend.prototype.setCloseButtonLocation = function(location)
{
	jQuery('.sgpb-button-position-top-js').hide();
	jQuery('.sgpb-button-position-right-js').hide();
	jQuery('.sgpb-button-position-left-js').hide();
	jQuery('.sgpb-button-position-bottom-js').hide();
	if (location == 'topRight') {
		jQuery('.sgpb-button-position-top-js').show();
		jQuery('.sgpb-button-position-right-js').show();
	}
	else if (location == 'topLeft') {
		jQuery('.sgpb-button-position-top-js').show();
		jQuery('.sgpb-button-position-left-js').show();
	}
	else if (location == 'bottomLeft') {
		jQuery('.sgpb-button-position-left-js').show();
		jQuery('.sgpb-button-position-bottom-js').show();
	}
	else if (location == 'bottomRight') {
		jQuery('.sgpb-button-position-right-js').show();
		jQuery('.sgpb-button-position-bottom-js').show();
	}
};

SGPBBackend.prototype.rangeSlider = function()
{
	if (typeof Powerange != 'undefined') {
		var powerRangeSelectors = ['js-popup-overlay-opacity', 'js-popup-content-opacity', 'js-subs-bg-opacity', 'js-contact-bg-opacity', 'js-login-bg-opacity', 'js-registration-bg-opacity'];

		for (var i in powerRangeSelectors) {
			if (typeof powerRangeSelectors[i] != 'string') {
				continue;
			}

			if (jQuery('.'+powerRangeSelectors[i]).length == 0) {
				continue;
			}
			this.powerRange(powerRangeSelectors[i]);
		}
	}
};

SGPBBackend.prototype.powerRange = function(cssSelectorName)
{
	var dec = document.querySelector('.'+cssSelectorName);
	function displayDecimalValue() {
		var dec = document.querySelector('.'+cssSelectorName);
		document.getElementById(cssSelectorName).innerHTML = jQuery('.'+cssSelectorName).attr('value');
	}
	if (jQuery('#' + cssSelectorName).is(':visible') && jQuery('#' + cssSelectorName).attr('data-init') == 'false') {
		jQuery('#' + cssSelectorName).attr('data-init', true);
		var initDec = new Powerange(dec, { decimal: true, callback: displayDecimalValue, max: 1, start: jQuery('.' + cssSelectorName).attr('value') });
	}
};

SGPBBackend.prototype.backgroundRangeSliderInit = function()
{
	if (jQuery('#sgpb-show-background').is(':checked')) {
		this.powerRange('js-popup-content-opacity');
	}
};

SGPBBackend.prototype.overlayRangeSliderInit = function()
{
	if (jQuery('#sgpb-enable-popup-overlay').is(':checked')) {
		this.powerRange('js-popup-overlay-opacity');
	}
};

SGPBBackend.prototype.imageUpload = function()
{
	var supportedImageTypes = ['image/bmp', 'image/png', 'image/jpeg', 'image/jpg', 'image/ico', 'image/gif'];
	if (jQuery("#js-upload-image").val()) {
		jQuery(".sgpb-show-image-container").html("");
		jQuery(".sgpb-show-image-container").css({'background-image': 'url("' + jQuery("#js-upload-image").val() + '")'});
	}
	var custom_uploader;
	jQuery('#js-upload-image-button').click(function(e) {
		e.preventDefault();
		/* If the uploader object has already been created, reopen the dialog */
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}
		/* Extend the wp.media object */
		custom_uploader = wp.media.frames.file_frame = wp.media({
			titleFF: 'Choose Image',
			button: {
				text: 'Choose Image'
			},
			multiple: false,
			library: {
				type: 'image'
			}
		});
		/* When a file is selected, grab the URL and set it as the text field's value */
		custom_uploader.on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			if (supportedImageTypes.indexOf(attachment.mime) === -1) {
				alert(SGPB_JS_LOCALIZATION.imageSupportAlertMessage);
				return;
			}
			jQuery(".sgpb-show-image-container").css({'background-image': 'url("' + attachment.url + '")'});
			jQuery(".sgpb-show-image-container").html("");
			jQuery('#js-upload-image').val(attachment.url);
		});
		/* Open the uploader dialog */
		custom_uploader.open();
	});

	/* its finish image uploader */
};

SGPBBackend.prototype.buttonImageUpload = function()
{
	var supportedImageTypes = ['image/bmp', 'image/png', 'image/jpeg', 'image/jpg', 'image/ico', 'image/gif'];
	var custom_uploader;
	jQuery('#js-button-upload-image-button').click(function(e) {
		e.preventDefault();

		/* If the uploader object has already been created, reopen the dialog */
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}
		/* Extend the wp.media object */
		custom_uploader = wp.media.frames.file_frame = wp.media({
			titleFF: 'Choose Image',
			button: {
				text: 'Choose Image'
			},
			multiple: false,
			library: {
				type: 'image'
			}
		});
		/* When a file is selected, grab the URL and set it as the text field's value */
		custom_uploader.on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			if (supportedImageTypes.indexOf(attachment.mime) === -1) {
				alert(SGPB_JS_LOCALIZATION.imageSupportAlertMessage);
				return;
			}
			jQuery(".sgpb-show-button-image-container").css({'background-image': 'url("' + attachment.url + '")'});
			jQuery(".sgpb-show-button-image-container").html("");
			jQuery('#js-button-upload-image').val(attachment.url);
			jQuery('.js-sgpb-remove-close-button-image').removeClass('sg-hide');
		});
		/* Open the uploader dialog */
		custom_uploader.open();
	});

	/* its finish image uploader */
};

SGPBBackend.prototype.buttonImageRemove = function()
{
	jQuery('#js-button-upload-image-remove-button').click(function(){
		var selectedTheme = jQuery('.js-sgpb-popup-themes:checked').attr('data-popup-theme-number');
		if (typeof selectedTheme == 'undefined') {
			selectedTheme = 6;
		}
		jQuery(".sgpb-show-button-image-container").html("");
		jQuery("#js-button-upload-image").attr('value', '');
		jQuery('.sgpb-show-button-image-container').attr('style', 'background-image: url("' + sgpbPublicUrl + 'img/theme_' + selectedTheme + '/close.png")');
		jQuery('.js-sgpb-remove-close-button-image').addClass('sg-hide');
	});
};

SGPBBackend.prototype.backgroundImageUpload = function()
{
	if (jQuery('#js-background-upload-image').val()) {
		jQuery('.sgpb-show-background-image-container').html('');
		jQuery('.sgpb-show-background-image-container').css({'background-image': 'url("' + jQuery("#js-background-upload-image").val() + '")'});
	}

	var supportedImageTypes = ['image/bmp', 'image/png', 'image/jpeg', 'image/jpg', 'image/ico', 'image/gif'];
	var custom_uploader;
	jQuery('#js-background-upload-image-button').click(function(e) {
		e.preventDefault();

		/* If the uploader object has already been created, reopen the dialog */
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}
		/* Extend the wp.media object */
		custom_uploader = wp.media.frames.file_frame = wp.media({
			titleFF: 'Choose Image',
			button: {
				text: 'Choose Image'
			},
			multiple: false,
			library: {
				type: 'image'
			}
		});
		/* When a file is selected, grab the URL and set it as the text field's value */
		custom_uploader.on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			if (supportedImageTypes.indexOf(attachment.mime) === -1) {
				alert(SGPB_JS_LOCALIZATION.imageSupportAlertMessage);
				return;
			}
			jQuery('.sgpb-show-background-image-container').css({'background-image': 'url("' + attachment.url + '")'});
			jQuery('.sgpb-show-background-image-container').html('');
			jQuery('#js-background-upload-image').val(attachment.url);
			jQuery('input[name="sgpb-background-image"]').attr('value', attachment.url);
			jQuery('.js-sgpb-remove-background-image').removeClass('sg-hide');
		});
		/* Open the uploader dialog */
		custom_uploader.open();
	});

	/* its finish image uploader */
};

SGPBBackend.prototype.backgroundImageRemove = function()
{
	jQuery('#js-background-upload-image-remove-button').click(function(){
		jQuery('.sgpb-show-background-image-container').html('<span class="sgpb-no-image">(No image selected)</span>');
		jQuery('.sgpb-show-background-image-container').removeAttr('style');
		jQuery('#js-background-upload-image').attr('value', '');
		jQuery('.js-sgpb-remove-background-image').addClass('sg-hide');
	});
};


SGPBBackend.prototype.switchPopupActive = function()
{
	var that = this;

	jQuery('.sg-switch-checkbox').bind('change', function() {
		var popupId = jQuery(this).attr('data-switch-id');
		var ajaxNonce = jQuery(this).attr('data-checkbox-ajaxNonce');

		if (jQuery(this).is(':checked')) {
			that.changePopupStatus('on', popupId);
		}
		else {
			that.changePopupStatus('', popupId);
		}
	});
};

SGPBBackend.prototype.changePopupStatus = function(status, popupId)
{
	var data = {
		action: 'change_popup_status',
		ajaxNonce: SGPB_JS_PARAMS.nonce,
		popupId: popupId,
		popupStatus: status
	};

	jQuery.post(ajaxurl, data, function(response) {
		/* error case */
		if (!response) {
			alert('Can not change the current popup status.');
			location.reload();
		}
	});
};

SGPBBackend.prototype.colorPicker = function()
{
	var that = this;
	var colorPicker = jQuery('.sgpb-color-picker');

	if (!colorPicker.length) {
		return false;
	}

	colorPicker.wpColorPicker({
		change: function() {
			var colorPickerElement = jQuery(this);
			that.changeColor(colorPickerElement);
		}
	});
	jQuery('.wp-picker-holder').bind('click', function() {
		var selectedInput = jQuery(this).prev().find('.sgpb-color-picker');
		that.changeColor(selectedInput);
	});
};

SGPBBackend.prototype.changeColor = function(element)
{
	var selectedName = element.attr("name");
	var elementVal = element.val();
	if (selectedName == 'sgpb-counter-text-color') {
		jQuery('#sgpb-counts-text').remove();
		jQuery('body').append('<style id="sgpb-counts-text">.sgpb-counts-content.sgpb-flipclock-js-' + SgpbCountdownParams.id + '.flip-clock-wrapper ul li a div div.inn { color: '+elementVal+'; }</style>');
	}
	if (selectedName == 'sgpb-counter-background-color') {
		jQuery('#sgpb-counts-style').remove();
		jQuery('body').append('<style id="sgpb-counts-style">.sgpb-counts-content.sgpb-flipclock-js-' + SgpbCountdownParams.id + '.flip-clock-wrapper ul li a div div.inn { background-color: '+elementVal+'; }</style>');
	}
};

SGPBBackend.prototype.previewInit = function()
{
	jQuery('input').on('change', function() {
		jQuery('.wrap').on('click', function() {
			SGPBBackend.prototype.autosave();
		})
	});
	jQuery('#post-preview').click(function() {
		SGPBBackend.prototype.autosave();
		/* when preview button clicked, set input value to 1 */
		jQuery('#sgpb-is-preview').val('1');
	});
	jQuery('#title').on('change', function() {
		SGPBBackend.prototype.autosave();
	});
	jQuery('#publish').click(function() {
		/* when publish/update clicked, set input value to 0 */
		jQuery('#sgpb-is-preview').val('0');
	});
};

SGPBBackend.makeContactAndSubscriptionFieldsRequired = function()
{
	/* subscription */
	if (jQuery('.subs-redirect-to-URL').length) {
		jQuery('input[name=sgpb-subs-success-behavior]').on('change', function() {
			if (jQuery('.subs-redirect-to-URL').is(':checked')) {
				jQuery('#sgpb-subs-success-redirect-URL').attr('required', 'required');
			}
			else {
				jQuery('#sgpb-subs-success-redirect-URL').removeAttr('required');
			}
		});
	}
	/* contact form */
	else if (jQuery('.contact-redirect-to-URL').length) {
		jQuery('input[name=sgpb-contact-success-behavior]').on('change', function() {
			if (jQuery('.contact-redirect-to-URL').is(':checked')) {
				jQuery('#sgpb-contact-success-redirect-URL').attr('required', 'required');
			}
			else {
				jQuery('#sgpb-contact-success-redirect-URL').removeAttr('required');
			}
		});
	}
};

SGPBBackend.prototype.makePopupTitleRequired = function()
{
	if (jQuery('#title').length) {
		var postType = jQuery('#post_type');
		if (postType.length && postType.val() == 'popupbuilder') {
			jQuery('#title').attr('required', 'required');
		}
	}
};

SGPBBackend.prototype.themeRelatedSettings = function()
{
	var that = this;
	/* positionSelectBox => get the value and selected text of select2-js */
	var positionSelectBox = jQuery('select[name=sgpb-close-button-position]').next('span').find('.select2-selection__rendered');
	var borderRadiusSelectBox = jQuery('select[name=sgpb-border-radius-type]').next('span').find('.select2-selection__rendered');
	var selectedTheme = jQuery('.js-sgpb-popup-themes').attr('data-popup-theme-number');
	jQuery('.js-sgpb-popup-themes').bind('click', function() {
		var buttonPosition = 'bottomRight';
		var padding = 7;
		var overlay = '';
		var theme = jQuery(this).attr('data-popup-theme-number');
		/* while changing theme, set dafault theme image */
		that.setThemeDefaultCloseBtnImage(theme);

		if (theme == 1 || theme == 4 || theme == 5) {
			/* first theme does not support close button's top positions */
			jQuery('.sgpb-close-button-position option[value=topRight]').remove();
			jQuery('.sgpb-close-button-position option[value=topLeft]').remove();
			if (theme == 5) {
				/* default theme 5 padding equal to 6 */
				padding = 5;
				overlay = '#000000';
			}
			if (theme == 4) {
				/* default theme 4 padding equal to 12 */
				padding = 12;
				overlay = '#ffffff';
			}
			if (theme == 1) {
				overlay = '';
			}
			/*
			 * set default position for the current themes (1, 4, 5)
			 * and change selectbox value
			 */
			positionSelectBox.attr('title', 'bottom-right');
			positionSelectBox.text('bottom-right');
			buttonPosition = 'bottomRight';
		}
		else if (theme == 2 || theme == 3 || theme == 6) {
			if (theme == 2) {
				/* default theme 2 padding equal to 2 */
				padding = 0;
				overlay = '#ffffff';
			}
			if (theme == 3) {
				/* default theme 3 padding equal to 0 */
				padding = 0;
				overlay = '#000000';
				/*
				 * set default position for the current theme
				 * and change selectbox value
				 */
				borderRadiusSelectBox.attr('title', '%');
				borderRadiusSelectBox.text('%');
				jQuery('.sgpb-border-radius-type').val('%');
				/* set default border black color */
				jQuery('.sgpb-border-color').val('#000000');
				jQuery('.sgpb-border-color .wp-color-result').attr('style', 'background-color: #000000');
			}
			if (theme == 6) {
				/* default theme 6 padding equal to 12 */
				padding = 12;
				overlay = '';
			}
			/* check if topRight & topLeft positions are removed, prepend to selectbox */
			if (jQuery('.sgpb-close-button-position option[value=topRight]').length == 0) {
				jQuery('.sgpb-close-button-position').prepend(jQuery('<option>', {
					value: 'topRight',
					text: 'top-right'
				}));
				jQuery('.sgpb-close-button-position').prepend(jQuery('<option>', {
					value: 'topLeft',
					text: 'top-left'
				}));
			}
			/*
			 * set default position for the current themes (2, 3, 6)
			 * and change selectbox value
			 */
			positionSelectBox.attr('title', 'top-right');
			positionSelectBox.text('top-right');
			buttonPosition = 'topRight';
		}
		jQuery('.sgpb-overlay-color').val(overlay);
		if (overlay) {
			jQuery('.sgpb-overlay-color .wp-color-result').attr('style', 'background-color: ' + overlay);
		}
		else {
			jQuery('.sgpb-overlay-color .wp-color-result').removeAttr('style');
		}
		jQuery('input[name=sgpb-content-padding]').val(padding);
		jQuery('.sgpb-close-button-position').val(buttonPosition);
	});
};

SGPBBackend.prototype.setThemeDefaultCloseBtnImage = function(theme)
{
	jQuery('#js-button-upload-image-remove-button').click();
	jQuery('.sgpb-show-button-image-container').attr('style', 'background-image: url("' + sgpbPublicUrl + 'img/theme_' + theme + '/close.png")');
};

SGPBBackend.prototype.eventsAddButtonSpinner = function(element, showHide)
{
	if (showHide == 'show') {
		element.addClass('sgpb-events-spinner');
		element.text(SGPB_JS_LOCALIZATION.addButtonSpinner + ' ...');
	}
	else {
		jQuery('.sg-rules-add-rule').removeClass('sgpb-events-spinner');
		jQuery('.sg-rules-add-rule').text(' ' + SGPB_JS_LOCALIZATION.addButtonSpinner);
	}
};

SGPBBackend.prototype.editPopupSettingsForFullscreenMode = function(popupId)
{
	var responsiveModeSelector = jQuery('.sgpb-responsive-mode-change-js');
	var that = this;
	var closeButtonCheckbox = jQuery('#close-button');

	if (typeof responsiveModeSelector == 'undefined') {
		return false;
	}
	responsiveModeSelector.change(function() {
		var selectedMode = jQuery(this).val();
		if (selectedMode == 'fullScreen') {
			if (closeButtonCheckbox.is(':checked')) {
				closeButtonCheckbox.click();
			}
		}
	});
};

SGPBBackend.hexToRgba = function(hex, opacity)
{
	var c;
	if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)) {
		c = hex.substring(1).split('');
		if (c.length == 3) {
			c= [c[0], c[0], c[1], c[1], c[2], c[2]];
		}
		c = '0x' + c.join('');
		return 'rgba(' + [(c>>16)&255, (c>>8)&255, c&255].join(',') + ',' + opacity + ')';
	}

	throw new Error('Bad Hex');
};

SGPBBackend.resetCount = function(popupId)
{
	if (confirm(SGPB_JS_LOCALIZATION.areYouSure)) {
		var data = {
			nonce: SGPB_JS_PARAMS.nonce,
			action: 'sgpb_reset_popup_opening_count',
			popupId: popupId
		};

		jQuery.post(ajaxurl, data, function(response) {
			location.reload();
		});
	}
};

SGPBBackend.prototype.autosave = function()
{
	if (!jQuery('#titlediv').length) {
		return false;
	}

	var allPopupData = jQuery('form#post').serializeArray();
	var data = {
		nonce: SGPB_JS_PARAMS.nonce,
		action: 'sgpb_autosave',
		allPopupData: allPopupData
	};

	jQuery.post(ajaxurl, data, function(response) {
		/*success*/
	});
};

jQuery(document).ready(function() {
	var sgpbBackendObj = new SGPBBackend();
	sgpbBackendObj.sgInit();
});
