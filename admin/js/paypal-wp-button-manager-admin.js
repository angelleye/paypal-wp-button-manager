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

    /*=========================================================================================================================*/
  
    jQuery('#ddl_companyname').change(function() {
        var ddl_companyname = jQuery(this).val();
        var data = {
            'action': 'checkconfig',
            'ddl_companyname': ddl_companyname
        };
        var wp_adminurl = paypal_wp_button_manager_wpurl.wp_admin_url;
        if (ddl_companyname == '') {
        	jQuery('.cls_wrap').css('display','none');
				
        }
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
    
    jQuery('.btn_can_notice').click(function(e) {
    var data = {
			'action': 'cancel_donate'
			
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			location.reload();		
		});
		
		});
  
    jQuery('.submitdelete').click(function(e) {
 		
        var post_id = jQuery(this).attr('href');
        var cur_post_type = jQuery(location).attr('href');  
        var del_post_id = parseURL(post_id);
        var action_name = parseURL_action(post_id);
        var current_post_page = parseURL_post_type(cur_post_type);
        if (current_post_page == 'paypal_buttons' && action_name == 'delete') {
			e.preventDefault();
			 var data1 = {
                    'action': 'checkhosted_button',
                    'btnid': del_post_id
                };
        	jQuery.post(ajaxurl, data1, function(response) {
        	
        		if(response) {
        			var istrue = (confirm('Do you want to also delete the button from PayPal ?'));
        				
        			if (istrue)  {
        				
        					 var data = {
	                   		 'action': 'delete_paypal_button',
	                  		 'del_post_id': del_post_id
	              			  };
        					
        					 jQuery.post(ajaxurl, data, function(response) {
	                    		location.reload();		
	              			  });
        				
        				}else {
        					
							var data3 = {
	                   		 'action': 'delete_post_own',
	                  		 'del_post': del_post_id
	              			  };
        					
        					 jQuery.post(ajaxurl, data3, function(response) {
	                    				location.reload();
	              			  });
        				}
        			
        		}else {
        			 		var data2 = {
	                   		 'action': 'delete_post_own',
	                  		 'del_post': del_post_id
	              			  };
        					
        					 jQuery.post(ajaxurl, data2, function(response) {
	                    				location.reload();
	              			  });
        		
	              }
        		
				
			});
        }
    });
 
    function sleep(milliseconds) {
        var start = new Date().getTime();
        for (var i = 0; i < 1e7; i++) {
            if ((new Date().getTime() - start) > milliseconds){
                break;
            }
        }
    }

    function parseURL(theLink) {
        return decodeURI((RegExp("post" + '=' + '(.+?)(&|$)').exec(theLink) || [, null])[1]);
    }
    function parseURL_action(theAction) {
        return decodeURI((RegExp("action" + '=' + '(.+?)(&|$)').exec(theAction) || [, null])[1]);
    }
    function parseURL_post_type(thePosttype) {
        return decodeURI((RegExp("post_type" + '=' + '(.+?)(&|$)').exec(thePosttype) || [, null])[1]);
    }
    
    jQuery('#buttonType').change(function() {
        var img_type = jQuery(this).val();
        if (img_type == 'donations') {
            jQuery('#previewImage').attr('src','https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif');
        } else if (img_type == 'gift_certs') {
            jQuery('#previewImage').attr('src','https://www.paypalobjects.com/en_US/i/btn/btn_gift_LG.gif');
	
        } 
    });

    var select_all = function(control){
       
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
                onselect: function() {
                     tinyMCE.activeEditor.selection.setContent( '[paypal_wp_button_manager id=' + this.value() + ']' );
                },
                values: shortcodeValues
            });
        });
    }
    
    $(document).on('click','#non_hosted_button_edit',function(){
       $('#snippet_textarea').removeAttr('readonly');
       $('#snippet_textarea').focus();
       $(this).addClass('hidden');
       $('#non_hosted_button_save').removeClass('hidden');
       $('#non_hosted_button_cancel').removeClass('hidden');
    });
    
    $(document).on('click','#non_hosted_button_cancel',function(){
        $('#snippet_textarea').attr('readonly','readonly');
        $('#non_hosted_button_edit').removeClass('hidden');
        $('#non_hosted_button_save').addClass('hidden');
        $(this).addClass('hidden');
    });
    
    $(document).on('click','#non_hosted_button_save',function(){
        $(this).text('Processing..');
        var post_id = $(this).data('postid');
        var textarea_snippet = $('#snippet_textarea').val();
        $.ajax({
            type: 'POST',
            url: ajaxurl,
             data: {
                action  : 'save_non_hosted_button_snippet',
                textarea_snippet : textarea_snippet,
                post_id : post_id
            },
            dataType: "json",
            success: function (result) {
                $('#non_hosted_button_save').text('Saved');
                $('#snippet_textarea').attr('readonly','readonly');
            }
        });
    });
    
    $(document).on('click','#saveOptionPrice',function(){
        var dropdownPriceTitle = $('#dropdownPriceTitle').val();
        if(dropdownPriceTitle == ''){
            alert('Please Enter title for Dropdown.');
            setTimeout(function(){ 
                if($('#dropdownPriceSection').hasClass('hide')){
                    $('#dropdownPriceSection').removeClass('hide');
                    $('#dropdownPriceSection').addClass('opened');
                }                
            }, 1000);
        }
    });


    $(document).on('click','.upload_custom_image_pp_button ', function(e){
        e.preventDefault();
        var image = wp.media({
            title: 'Upload Image',
            multiple: false
        }).open()
            .on('select', function (e) {
                var uploaded_image = image.state().get('selection').first();
                var image_url = uploaded_image.toJSON().url;
                $('#customImageUrl').val(image_url);
            });
    });

});

jQuery(function () {
    jQuery('[id^=angelleye_notification]').slice(1).remove();
    var el_notice = jQuery(".angelleye-notice");
    el_notice.fadeIn(750);
    jQuery(".angelleye-notice-dismiss").click(function(e){
        e.preventDefault();
        jQuery( this ).parent().parent(".angelleye-notice").fadeOut(600, function () {
            jQuery( this ).parent().parent(".angelleye-notice").remove();
        });
        notify_wordpress(jQuery( this ).data("msg"));
    });
    function notify_wordpress(message) {
        var param = {
            action: 'angelleye_paypal_here_adismiss_notice',
            data: message
        };
        jQuery.post(ajaxurl, param);
    }
});