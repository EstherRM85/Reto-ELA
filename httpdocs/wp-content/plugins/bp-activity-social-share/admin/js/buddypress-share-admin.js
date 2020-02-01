jQuery(function() {

        jQuery(document).on('click', '.add_services_btn', function () {

        jQuery('.error_service').hide();

                var service_value = jQuery('#social_services_selector_id').val();

                var service_name = jQuery('#social_services_selector_id :selected').text();

                var service_faw = jQuery('.faw_class_input').val();

                var service_description = jQuery('.bp_share_description').val();

                if (service_value == '' && service_name == '-select-') {

                        jQuery('.error_service_selector').show();

                }

                if (service_faw == '') {

                        jQuery('.error_service_faw-icon').show();

                }

                if (service_description == '') {

                        jQuery('.error_service_description').show();

                }



                if (service_value != '' && service_name != '-select-' && service_faw != '' && service_description != '') {

                        jQuery('.spint_action').show();

                        var data = {

                                'action': 'bp_share_insert_services_ajax',

                                'service_name': service_name,

                                'service_faw': service_faw,

                                'service_value': service_value,

                                'service_description': service_description,

                                'nonce' : my_ajax_object.nonce

                        };

                        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php

                        jQuery.post(my_ajax_object.ajax_url, data, function (response) {

                                jQuery('.faw_class_input').val('');

                                jQuery('.bp_share_description').val('');

                                var obj = jQuery.parseJSON(response);

                                if (obj.status == true) {

                                        jQuery('#tr_' + service_value).fadeOut("fast", function () {

                                                jQuery(this).remove();

                                                jQuery('.bp_share_social_list').append(obj.view);

                                        });

                                } else {

                                        jQuery('.bp_share_social_list').append(obj.view);

                                }

                                        jQuery('.spint_action').hide();

                        });

                }

        });



        jQuery(document).on('click', '.service_delete_icon', function () {

                var service = jQuery(this).data().bind;

                var data = {

                        'action': 'bp_share_delete_services_ajax',

                        'service_name': service,

                        'nonce' : my_ajax_object.nonce

                };

                jQuery(this).html('<i class="fa fa-circle-o-notch fa-spin fa-1x fa-fw"></i>');

                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php

                jQuery.post(my_ajax_object.ajax_url, data, function (response) {

                        jQuery('#tr_' + jQuery.trim(response)).fadeOut("normal", function () {

                                jQuery(this).remove();

                        });

                });

        });



        jQuery(document).on('click', '.button-primary.bp_share_option_save', function () {

                var selected = [];

                jQuery('#bp_share_chb input:checked').each(function () {

                        selected.push(jQuery(this).attr('name'));

                });

                var selected_extras = [];

                jQuery('.bp_share_settings_section_callback_class input:checked').each(function () {

                        selected_extras.push(jQuery(this).attr('name'));

                });

                var data = {

                        'action': 'bp_share_chb_services_ajax',

                        'active_chb_array': selected,

                        'active_chb_extras': selected_extras,

                        'nonce' : my_ajax_object.nonce

                };

                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php

                jQuery.post(my_ajax_object.ajax_url, data, function (response) {

                       location.reload();

                });

                return false;

        });

});