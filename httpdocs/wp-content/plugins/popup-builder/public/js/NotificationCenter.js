function SGPBNotificationCenter() {

}

SGPBNotificationCenter.prototype.init = function()
{
	this.dismiss();
	this.reactivate();
	this.closePromotionalNotification();
};

SGPBNotificationCenter.prototype.dismiss = function()
{
	var that = this;
	jQuery('.sgpb-dismiss-notification-js').click(function() {
		var id = jQuery(this).attr('data-id');
		jQuery(this).addClass('disabled');
		jQuery(this).parent().prev().addClass('sgpb-disabled');

		var data = {
			nonce: SGPB_JS_PARAMS.nonce,
			action: 'sgpb_dismiss_notification',
			id: id
		};

		jQuery.post(ajaxurl, data, function(response) {
			response = JSON.parse(response);
			jQuery('.sgpb-each-notification-wrapper-js').empty();
			jQuery('.sgpb-each-notification-wrapper-js').html(response['content']);
			jQuery('.sgpb-notifications-count-span').html(response['count']);
			jQuery('.sgpb-menu-item-notification').html(response['count']);
			if (response['count'] == 0) {
				jQuery('.sgpb-menu-item-notification').addClass('sgpb-hide-add-button');
			}
			that.init();
		});
	});
};

SGPBNotificationCenter.prototype.reactivate = function()
{
	var that = this;
	jQuery('.sgpb-activate-notification-js').click(function() {
		var id = jQuery(this).attr('data-id');
		jQuery(this).addClass('disabled');

		var data = {
			nonce: SGPB_JS_PARAMS.nonce,
			action: 'sgpb_reactivate_notification',
			id: id
		};

		jQuery.post(ajaxurl, data, function(response) {
			response = JSON.parse(response);
			jQuery('.sgpb-each-notification-wrapper-js').empty();
			jQuery('.sgpb-each-notification-wrapper-js').html(response['content']);
			jQuery('.sgpb-notifications-count-span').html(response['count']);
			jQuery('.sgpb-menu-item-notification').removeClass('sgpb-hide-add-button');
			jQuery('.sgpb-menu-item-notification').html(response['count']);
			that.init();
		});
	});
};

SGPBNotificationCenter.prototype.closePromotionalNotification = function()
{
	var that = this;
	jQuery('.sgpb-dont-show-again-support-notification').click(function() {
		var currentNotification = jQuery(this).parents('.sgpb-single-notification-wrapper');
		currentNotification.addClass('sgpb-disabled');
		var data = {
			action: 'sgpb_close_banner',
			nonce: SGPB_JS_PARAMS.nonce,
		};

		jQuery.post(ajaxurl, data, function(response) {
			currentNotification.remove();
		});
	});
	jQuery('.sgpb-close-promo-notification').each(function () {
		jQuery(this).click(function() {
			jQuery(this).parents('.sgpb-single-notification-wrapper').addClass('sgpb-disabled');
			var dataAction = jQuery(this).attr('data-action');
			if (dataAction == 'sg-show-popup-period') {
				jQuery(this).parents('.sgpb-single-notification-wrapper').find('.sgpb-dismiss-notification-js').click();
				return true;
			}
			var ajaxData = {
				action: 'sgpb_dont_show_review_popup',
				nonce: SGPB_JS_PARAMS.nonce
			};
			jQuery.post(SGPB_JS_PARAMS.url, ajaxData, function (res) {
				if (jQuery('.sgpb-review-wrapper').length) {
					jQuery('.sgpb-review-wrapper').parents('.sgpb-single-notification-wrapper').remove();
				}
				if (dataAction == 'sg-you-worth-it') {
					window.open(SGPB_JS_EXTENSIONS_PARAMS.reviewUrl, '_blank');
				}
			});
		});
	});
};

jQuery(document).ready(function() {
	var notificationCenter = new SGPBNotificationCenter();
	notificationCenter.init();
});
