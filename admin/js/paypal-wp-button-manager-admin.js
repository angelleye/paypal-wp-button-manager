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
/*
 tinymce.create('tinymce.plugins.wpse72394_plugin', {
        init : function(ed, url) {
                // Register command for when button is clicked
                
                ed.addCommand('wpse72394_insert_shortcode', function() {
                   var selected = tinyMCE.activeEditor.selection.getContent({format : 'text'});

                    if( selected ){
                        //If text is selected when button is clicked
                        //Wrap shortcode around it.
                        content =  '[shortcode]'+selected+'[/shortcode]';
                    }else{
                        content =  '[shortcode]';
                    }

                    tinymce.execCommand('mceInsertContent', false, content);
                });

            // Register buttons - trigger above command when clicked
            var pluginurl = paypal_wp_button_manager_plugin_url.plugin_url;
            ed.addButton('wp_button_manager_button', {title : 'Insert shortcode', cmd : 'wpse72394_insert_shortcode', image: pluginurl +'/images/paypal-wp-button-manager-icon.png' });
        },   
    });

    // Register our TinyMCE plugin
    // first parameter is the button ID1
    // second parameter must match the first parameter of the tinymce.create() function above
    tinymce.PluginManager.add('wp_button_manager_button', tinymce.plugins.wpse72394_plugin);
    
    */
 tinymce.PluginManager.add('pushortcodes', function( editor )
    {
        var shortcodeValues = [];
        jQuery.each(shortcodes_button, function(i)
        {
            shortcodeValues.push({text: shortcodes_button[i], value:shortcodes_button[i]});
            
        });

        editor.addButton('pushortcodes', {
            type: 'listbox',
            text: 'Shortcodes',
            onselect: function(e) {
                var v = e.control._value;
                tinyMCE.activeEditor.selection.setContent( '[' + v + ']' );
            },
            values: shortcodeValues
        });
    });
	
});