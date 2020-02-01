function SGPBBanner() {

}

SGPBBanner.prototype.init = function() {
	this.close();
	this.closeLicenseNotice();
};

SGPBBanner.prototype.close = function() {
	if (!jQuery('.sgpb-banner-wrapper').length) {
		return;
	}

	jQuery('.sgpb-info-close').click(function() {
		jQuery('.sgpb-banner-wrapper').remove();
	});
};

SGPBBanner.prototype.closeLicenseNotice = function() {
	if (!jQuery('.sgpb-extensions-notices').length) {
		return;
	}

	jQuery('.sgpb-dont-show-again-license-notice').click(function() {
		var data = {
			action: 'sgpb_close_license_notice',
			nonce: SGPB_JS_PARAMS.nonce,
		};

		jQuery.post(ajaxurl, data, function(response) {
			jQuery('.sgpb-extensions-notices').remove();
		});
	});
};

jQuery(document).ready(function() {
	var banner = new SGPBBanner();
	banner.init();
});
