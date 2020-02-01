function SGPBSubscribers() {}

SGPBSubscribers.prototype.init = function()
{
	this.openAddSubscriberPopup();
	this.openImportSubscriberPopup();
	this.subscriberFileUploader();
	this.closeAddSubscriberPopup();
	this.importSubscriber();
	this.dataImport();
	this.deleteSubscribers();
	this.addSubscribers();
	this.toggleCheckedSubscribers();
	this.fixSubscriptionBulkCheckbox();
	this.exportSubscribers();
};

SGPBSubscribers.prototype.deleteSubscribers = function()
{
	var checkedSubscribersList = [];
	var that = this;
	jQuery('.sg-subs-delete-button').bind('click', function() {
		var data = {};
		data.ajaxNonce = jQuery(this).attr('data-ajaxNonce');
		jQuery('.subs-delete-checkbox').each(function() {
			var isChecked = jQuery(this).prop('checked');
			if (isChecked) {
				var subscriberId = jQuery(this).attr('data-delete-id');
				checkedSubscribersList.push(subscriberId);
			}
		});
		if (checkedSubscribersList.length == 0) {
			alert('Please select at least one subscriber.');
		}
		else {
			var isSure = confirm(SGPB_JS_LOCALIZATION.areYouSure);
			if (isSure) {
				that.deleteSubscribersAjax(checkedSubscribersList, data);
			}
		}
	})
};

SGPBSubscribers.prototype.openImportSubscriberPopup = function()
{
	if (jQuery('.bulkactions').empty()) {
		jQuery('.bulkactions').remove();
	}
	var that = this;
	jQuery('.sgpb-import-subscriber').bind('click', function() {
		jQuery('#sgpb-import-subscribers').addClass('sgpb-show-add-subscriber-popup');
		SGPBSubscribers.prototype.escKeyClosePopup();
		that.closePopup();
	});
};

SGPBSubscribers.prototype.subscriberFileUploader = function()
{
	var uploadButton = jQuery('#js-import-subscriber-button');
	var supportedAudioTypes = ['text/plain', 'text/x-csv', 'text/csv'];

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
			library : {type : supportedAudioTypes},
			multiple : false
		});

		/* When a file is selected, grab the URL and set it as the text field's value */
		uploader.on('select', function() {
			var attachment = uploader.state().get('selection').first().toJSON();
			if (supportedAudioTypes.indexOf(attachment.mime) === -1) {
				alert(SGPB_JS_LOCALIZATION.audioSupportAlertMessage);
				return;
			}
			jQuery('#js-import-subscriber-file-url').val(attachment.url);
		});
		/* Open the uploader dialog */
		uploader.open();
	});
};


SGPBSubscribers.prototype.importSubscriber = function()
{
	var importSubscriber = jQuery('.sgpb-import-subscriber-to-list');
	var that = this;

	if (!importSubscriber.length) {
		return false;
	}

	importSubscriber.bind('click', function() {
		var popupSubscriptionList = jQuery('.js-sg-import-list').val();
		var importListURL = jQuery('#js-import-subscriber-file-url').val();

		var data = {
			action: 'sgpb_import_subscribers',
			nonce: SGPB_JS_PARAMS.nonce,
			popupSubscriptionList: popupSubscriptionList,
			importListURL: importListURL,
			beforeSend: function() {
				importSubscriber.prop('disabled', true);
			}
		};

		jQuery.post(ajaxurl, data, function(response) {
			importSubscriber.prop('disabled', false);
			jQuery('.sgpb-add-subscriber-content-wrapper').html('');
			jQuery('.sgpb-add-subscriber-content-wrapper').append(response);
			that.closePopup();
			that.disableSelectedValue();
			jQuery('.sgpb-add-new-subscribers-wrapper').addClass('sgpb-to-center-window');
			jQuery('.sgpb-add-new-subscribers-wrapper').css({'margin-top': 0});
			that.saveImportValue();
		});
	});
};

SGPBSubscribers.prototype.disableSelectedValue = function() {
	var selectOptioon = jQuery('.sgpb-our-fields-keys');

	if (!selectOptioon.length) {
		return false;
	}

	selectOptioon.bind('change', function() {
		var currentVal = jQuery(this).val();
		jQuery('.sgpb-our-fields-keys option[value="'+jQuery(this).attr('data-saved-value')+'"]').removeAttr('disabled');
		jQuery(this).attr('data-saved-value', currentVal);
		jQuery('.sgpb-our-fields-keys option[value="'+currentVal+'"]').not(jQuery(this)).attr('disabled', 'disabled');
	});
};

SGPBSubscribers.prototype.saveImportValue = function()
{
	var importSubscriber = jQuery('.sgpb-import-subscriber-to-list');
	var mapping = {};
	var data = {
		action: 'sgpb_save_imported_subscribers',
		nonce: SGPB_JS_PARAMS.nonce,
		popupSubscriptionList: jQuery('.sgpb-to-import-popup-id').val(),
		importListURL:  jQuery('.sgpb-imported-file-url').val(),
	};

	jQuery('.sgpb-save-subscriber').bind('click', function() {
		mapping = {};

		var ourFields = jQuery('.sgpb-our-fields-keys');
		ourFields.each(function () {
			var currentValue = jQuery('option:selected', this).val();
			if (currentValue) {
				mapping[currentValue] = jQuery(this).attr('data-index');
			}
		});
		data.namesMapping = mapping;
		data.popupId = jQuery('.sgpb-to-import-popup-id').val();
		data.beforeSend = function() {
			jQuery('.sgpb-save-subscriber').prop('disabled', true);
		};
		jQuery.post(ajaxurl, data, function(response) {
			window.location.reload();
			jQuery('.sgpb-save-subscriber').prop('disabled', false);
		});
	});
};

SGPBSubscribers.prototype.closePopup = function ()
{
	jQuery('.sgpb-subscriber-popup-close-btn-js').bind('click', function() {
		jQuery(this).parents('.sgpb-subscribers-popup').first().removeClass('sgpb-show-add-subscriber-popup');
		jQuery('.sgpb-add-subscriber-input:selected').prop('selected', false);
		jQuery('.sgpb-add-subscriber-input').val('');
	});
};

SGPBSubscribers.prototype.openAddSubscriberPopup = function()
{
	if (jQuery('.bulkactions').empty()) {
		jQuery('.bulkactions').remove();
	}
	jQuery('.sgpb-add-subscriber').bind('click', function() {
		jQuery('#sgpb-add-new-subscriber').addClass('sgpb-show-add-subscriber-popup');
		SGPBSubscribers.prototype.escKeyClosePopup();
	});
};

SGPBSubscribers.prototype.escKeyClosePopup = function()
{
	jQuery(document).keyup(function(e) {
		 if (e.keyCode == 27) {
			if (jQuery('#sgpb-add-new-subscriber').hasClass('sgpb-show-add-subscriber-popup')) {
				jQuery('#sgpb-add-new-subscriber').removeClass('sgpb-show-add-subscriber-popup');
			}
		}
	});
};

SGPBSubscribers.prototype.closeAddSubscriberPopup = function()
{
	jQuery('.sgpb-add-subscriber-popup-close-btn-js').bind('click', function() {
		jQuery('#sgpb-add-new-subscriber').removeClass('sgpb-show-add-subscriber-popup');
		jQuery('.sgpb-add-subscriber-input:selected').prop('selected', false);
		jQuery('.sgpb-add-subscriber-input').val('');
	});
};

SGPBSubscribers.prototype.addSubscribers = function()
{
	var that = this;

	jQuery('.sgpb-add-to-list-js').bind('click', function() {
		jQuery('.sgpb-subscription-error').addClass('sg-hide-element');
		jQuery('.sgpb-email-error').addClass('sg-hide-element');
		var email = jQuery('.sgpb-add-subscribers-email').val();
		var firstName = jQuery('.sgpb-add-subscribers-first-name').val();
		var lastName = jQuery('.sgpb-add-subscribers-last-name').val();
		var subscriptionPopups = [];

		jQuery('.js-sg-newsletter-forms > option').each(function() {
			if (jQuery(this).prop('selected')) {
				subscriptionPopups.push(jQuery(this).val());
			}
		});
		var validationString = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		var validEmail = email.search(validationString);

		if (jQuery('.js-sg-newsletter-forms > option').is(':checked') == false && validEmail == -1) {
			jQuery('.sgpb-subscription-error').removeClass('sg-hide-element');
			jQuery('.sgpb-email-error').removeClass('sg-hide-element');
			return;
		}
		/* if no subscription (popup) not selected
		   is(':checked') always returns boolean
		*/
		if (jQuery('.js-sg-newsletter-forms > option').is(':checked') == false) {
			jQuery('.sgpb-subscription-error').removeClass('sg-hide-element');
			return;
		}
		/* if email is not valid */
		if (validEmail == -1) {
			jQuery('.sgpb-email-error').removeClass('sg-hide-element');
			return;
		}
		jQuery('.sgpb-email-error').addClass('sg-hide-element');

		var data = {
			action: 'sgpb_add_subscribers',
			nonce: SGPB_JS_PARAMS.nonce,
			firstName: firstName,
			lastName: lastName,
			email: email,
			popups: subscriptionPopups,
			beforeSend: function() {
				jQuery('.js-sgpb-add-spinner').removeClass('sg-hide-element');
			}
		};

		that.addToSubscribersAjax(data);
	});
};

SGPBSubscribers.prototype.exportSubscribers = function()
{
	var that = this;

	jQuery('#sgpb-subscription-popup').on('change', function() {
		jQuery('.sgpb-subscription-popup-id').val(jQuery(this).val());
	})
	jQuery('#sgpb-subscribers-dates').on('change', function() {
		jQuery('.sgpb-subscribers-date').val(jQuery(this).val());
	})
	jQuery('.sgpb-export-subscriber').bind('click', function() {
		var parameters = '';
		var params = {};
			params['sgpb-subscription-popup-id'] = that.getUrlParameter('sgpb-subscription-popup-id');
			params['s'] = that.getUrlParameter('s');
			params['sgpb-subscribers-date'] = that.getUrlParameter('sgpb-subscribers-date');
			params['orderby'] = that.getUrlParameter('orderby');
			params['order'] = that.getUrlParameter('order');
		for (var i in params) {
			if (typeof params[i] != 'undefined' && params[i] != '') {
				parameters += '&' + i + '=' + params[i];
			}
		}
		window.location.href = SGPB_JS_ADMIN_URL.url+'?action=csv_file'+parameters;
	});
};

SGPBSubscribers.prototype.getUrlParameter = function(key)
{
	var pageUrl = window.location.search.substring(1);
	var urlVariables = pageUrl.split('&');
	for (var i = 0; i < urlVariables.length; i++) {
		var param = urlVariables[i].split('=');
		if (param[0] == key) {
			if (typeof param[1] != 'undefined') {
				return param[1];
			}
			else {
				return '';
			}
		}
	}
};

SGPBSubscribers.prototype.addToSubscribersAjax = function(data)
{
	jQuery.post(ajaxurl, data, function(response) {
		if (response != 1) {
			jQuery('.sgpb-subscriber-adding-error').removeClass('sg-hide-element');
			jQuery('.sgpb-subscribers-add-spinner').addClass('sg-hide-element');
		}
		else {
			location.reload();
		}
	});
};

SGPBSubscribers.prototype.toggleCheckedSubscribers = function()
{
	var that = this;
	jQuery('.subs-bulk').each(function() {
		jQuery(this).bind('click', function() {
			var bulkStatus = jQuery(this).prop('checked');
			that.changeCheckedSubscribers(bulkStatus);
		});
	});
};

SGPBSubscribers.prototype.changeCheckedSubscribers = function(bulkStatus)
{
	jQuery('.subs-delete-checkbox').each(function() {
		jQuery(this).prop('checked', bulkStatus);
	})
};

SGPBSubscribers.prototype.fixSubscriptionBulkCheckbox = function()
{
	jQuery('#bulk,.column-bulk').removeClass().addClass('manage-column column-cb check-column');
};

SGPBSubscribers.prototype.dataImport = function()
{
	var custom_uploader;
	jQuery('#js-upload-export-file').click(function(e) {
		e.preventDefault();
		var ajaxNonce = jQuery(this).attr('data-ajaxNonce');

		/* If the uploader object has already been created, reopen the dialog */
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}

		/* Extend the wp.media object */
		custom_uploader = wp.media.frames.file_frame = wp.media({
			titleFF: 'Select Export File',
			button: {
				text: 'Select Export File'
			},
			multiple: false,
			library : { type  :  'text/plain'}
		});
		/* When a file is selected, grab the URL and set it as the text field's value */
		custom_uploader.on('select', function() {
			attachment = custom_uploader.state().get('selection').first().toJSON();

			var data = {
				action: 'import_popups',
				ajaxNonce: ajaxNonce,
				attachmentUrl: attachment.url
			};
			jQuery('.js-sg-import-gif').removeClass('sg-hide-element');
			jQuery.post(ajaxurl, data, function(response, d) {
				location.reload();
				jQuery('.js-sg-import-gif').addClass('sg-hide-element');
			});
		});
		/* Open the uploader dialog */
		custom_uploader.open();
	});
};

SGPBSubscribers.prototype.deleteSubscribersAjax = function(checkedSubscribersList)
{
	var data = {
		action: 'sgpb_subscribers_delete',
		nonce: SGPB_JS_PARAMS.nonce,
		subscribersId: checkedSubscribersList,
		beforeSend: function() {
			jQuery('.sgpb-subscribers-remove-spinner').removeClass('sg-hide-element');
		}
	};

	jQuery.post(ajaxurl, data, function(response) {
		jQuery('.sgpb-subscribers-remove-spinner').addClass('sg-hide-element');
		jQuery('.subs-delete-checkbox').prop('checked', '');
		window.location.reload();
	});
};

jQuery(document).ready(function() {
	var subscribers = new SGPBSubscribers();
	subscribers.init();
});
