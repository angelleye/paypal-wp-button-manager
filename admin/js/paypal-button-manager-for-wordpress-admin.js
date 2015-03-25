jQuery(function ($) {
'use strict';

jQuery('.ddpOptionCurrency').change(function() {
	jQuery(".spanOptionCurrency").html(jQuery(this).val());
});

jQuery( ".removeOptionPrice" ).click(function() {
	var rowCount = jQuery('#tblOption tr').length;
	
	if (rowCount == 3) {
		jQuery("#tblOption tr:last").remove();
		jQuery(".removeOptionPrice").html('');
	}else {
		jQuery("#tblOption tr:last").remove();
	}
		
		
	
});

jQuery('#buttonType').change(function() {
	var img_type = jQuery(this).val();
	if (img_type == 'donations') {
		jQuery('#previewImage').attr('src','https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif');
	} else if (img_type == 'gift_certs') {
		jQuery('#previewImage').attr('src','https://www.paypalobjects.com/en_US/i/btn/btn_gift_LG.gif');
	
	}
});
	
});