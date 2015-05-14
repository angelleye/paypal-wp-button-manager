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

    
    // media uploader function.
   
   
    jQuery('#wpss_upload_image_button').click(function() {
        var formfield = jQuery('#wpss_upload_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    });
    
     jQuery('#ipn_url_chk').click(function() {
       if (jQuery(this).is(':checked')) {
            jQuery('#ipn_urlinput').removeAttr('disabled');
        } else {
            
            jQuery('#ipn_urlinput').attr('disabled', 'disabled');
        }
 });
    window.send_to_editor = function(html) {
        var imgurl = jQuery('img',html).attr('src');
        jQuery('#wpss_upload_image').val(imgurl);
        tb_remove();

        jQuery('.previewCustomImageSection').html("<img height='65' src='"+imgurl+"'/>");
    }
 
    
  
  //////////////////////////////////////////////////////////////////////////////////////
  
  jQuery('#ddl_companyname').change(function() {
    	var ddl_companyname = jQuery(this).val();
	    var data = {
				'action': 'checkconfig',
				'ddl_companyname': ddl_companyname
			};
	    var wp_adminurl = paypal_wp_button_manager_wpurl.wp_admin_url;
	    jQuery.post(ajaxurl, data, function(response) {
					
				if (response == '1'){
					jQuery('#go_to_settings').html('');
					jQuery('.cls_wrap').css('display','inline');
				}else if (response == '2'){
					jQuery('.cls_wrap').css('display','none');
					
					jQuery('#go_to_settings').html("Please fill your API credentials properly for that account to work.&nbsp;&nbsp;<a href='" + wp_adminurl + "'>Go to API Settings</a>");
																
				}else {
					
				}
	    	
				
			});
    
  });  
  
  
    
    jQuery('#buttonType').change(function() {
        var img_type = jQuery(this).val();
        if (img_type == 'donations') {
            jQuery('#previewImage').attr('src','https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif');
        } else if (img_type == 'gift_certs') {
            jQuery('#previewImage').attr('src','https://www.paypalobjects.com/en_US/i/btn/btn_gift_LG.gif');
	
        } else if (img_type == 'subscriptions') {
            jQuery('#addDropdownPrice').hide();
        } else {
            jQuery('#addDropdownPrice').show();
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
    
    jQuery('.cls_tooltip' ).tooltip({
        position: {
            my: "center bottom-20",
            at: "center top",
            using: function( position, feedback ) {
                jQuery( this ).css( position );
                jQuery( "<div>" )
                .addClass( "arrow" )
                .addClass( feedback.vertical )
                .addClass( feedback.horizontal )
                .appendTo( this );
            }
        }
    });

    if(typeof tinymce != 'undefined') {
        tinymce.PluginManager.add('pushortcodes', function( editor )
        {
            var shortcodeValues = [];
            var pluginurl = paypal_wp_button_manager_plugin_url.plugin_url;
            jQuery.each(shortcodes_button_array.shortcodes_button, function( post_id, post_title )
            {
        
                shortcodeValues.push({
                    text: post_title, 
                    value: post_id
                });
            
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
    }


	
});

