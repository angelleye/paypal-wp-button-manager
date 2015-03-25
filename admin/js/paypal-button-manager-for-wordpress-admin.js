jQuery(function ($) {
'use strict';

/*
jQuery( "#addOptionPrice" ).click(function() {
	var rowCount = jQuery('#tblOption tr').length;
	
	var last = jQuery("#tblOption tr:last .ddpOptionName").val();
	var lastoptionvalue = last.replace ( /[^\d.]/g, '' );
	var lastlablevalue =  parseInt(lastoptionvalue)	+ 1;
	if (rowCount >=2) {
		jQuery(".removeOptionPrice").html('Remove Option');
	}else {
		jQuery(".removeOptionPrice").html('');
	}
		
	if ( rowCount >= 11 ) {
		return false;
	}else {
		var selectedCurrency = jQuery(".ddpOptionCurrency").val();
		jQuery("#tblOption tr:last").clone().appendTo("#tblOption");
		jQuery("#tblOption tr:last .tdddpOptionPriceCurrency").html("<span class='spanOptionCurrency'>"+selectedCurrency+"</span>");
		jQuery("#tblOption tr:last .ddpOptionName").val('Option ' +rowCount);
		jQuery('#tblOption tr:last .ddpOptionCurrency').attr('disabled', 'disabled');
	}
});

*/
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

jQuery( ".autoTooltip" ).click(function() {
		
		jQuery('.accessAid').tooltip();
});

	
});