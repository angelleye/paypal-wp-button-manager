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



var select_all = function(control){
       // alert(document.getElementById("showthis").value);
       jQuery(control).focus().select();
        var copy = $(control).val();
       //window.prompt ("Copy to clipboard: Ctrl+C, Enter", copy);
    }
    jQuery(".txtarea_response").click(function(){
       select_all(this);
    })


 tinymce.PluginManager.add('pushortcodes', function( editor )
    {
        var shortcodeValues = [];
      	var pluginurl = paypal_wp_button_manager_plugin_url.plugin_url;
        jQuery.each(shortcodes_button_array.shortcodes_button, function( post_id, post_title )
        {
        
            shortcodeValues.push({text: post_title, value: post_id});
            
        });
		
        editor.addButton('pushortcodes', {
        	
        	text: 'Shortcodes',
            type: 'listbox',
            title: 'PayPal Buttons',
            icon: ' icon-paypal',
            
            onselect: function(e) {
                var v = e.control._value;
                if (v != '0') {
	                tinyMCE.activeEditor.selection.setContent( '[paypal_wp_button_manager id=' + v + ']' );
	               	jQuery('.icon-paypal').next().html('Shortcodes');
                } else {
                	jQuery('.icon-paypal').next().html('Shortcodes');
                }
                              
            },
           		
            	 	values: shortcodeValues
           			
            
        });
    });
	
});

