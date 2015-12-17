jQuery(function ($) {
    'use strict';

    // media uploader function.

    jQuery('#wpss_upload_image_button').click(function () {
        var formfield = jQuery('#wpss_upload_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    });

    jQuery('#ipn_url_chk').click(function () {
        if (jQuery(this).is(':checked')) {
            jQuery('#ipn_urlinput').removeAttr('disabled');
        } else {

            jQuery('#ipn_urlinput').attr('disabled', 'disabled');
        }
    });
    window.send_to_editor = function (html) {
        var imgurl = jQuery('img', html).attr('src');
        jQuery('#wpss_upload_image').val(imgurl);
        tb_remove();

        jQuery('.previewCustomImageSection').html("<img id='previewImage' src='" + imgurl + "'/>");
    }

});