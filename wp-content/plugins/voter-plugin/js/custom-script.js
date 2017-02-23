jQuery(document).ready(function($){
	/**Color Pickers**/
    $('.az_custom_up_color').wpColorPicker();
	$('.az_custom_down_color').wpColorPicker();
	$('.az_custom_button_text_color').wpColorPicker();
	
	/**Button Type Selection**/
	jQuery(".az_voter_display_options").change(function() {
		var val = jQuery(this).val();
		az_set_buttons(jQuery(".az_voter_display_options").val());
	});
	
	az_set_buttons(jQuery(".az_voter_display_options").val());
	
	/**Image Upload**/
	$('.custom-upload-btn').click(function(e) {
		e.preventDefault();
		var inputtextid = $(this).attr('setvalto');
		var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            var uploaded_image = image.state().get('selection').first();
            var image_url = uploaded_image.toJSON().url;
            $('#'+inputtextid).val(image_url);
        });
    });
});

function az_set_buttons(val){
	jQuery('#az_custom_up_text_tr').hide();
	jQuery('#az_custom_up_color_tr').hide();
	jQuery('#az_custom_down_text_tr').hide();
	jQuery('#az_custom_down_color_tr').hide();
	jQuery('#az_custom_button_text_color_tr').hide();
	jQuery('#az_custom_up_html_tr').hide();
	jQuery('#az_custom_down_html_tr').hide();
	jQuery('#az_custom_up_image_tr').hide();
	jQuery('#az_custom_down_image_tr').hide();
		
	if(val == 'customtex'){
		jQuery('#az_custom_up_text_tr').show();
		jQuery('#az_custom_up_color_tr').show();
		jQuery('#az_custom_down_text_tr').show();
		jQuery('#az_custom_down_color_tr').show();
		jQuery('#az_custom_button_text_color_tr').show();
	}else if(val == 'customhtml'){
		jQuery('#az_custom_up_html_tr').show();
		jQuery('#az_custom_down_html_tr').show();
	}else if(val == 'customimage'){
		jQuery('#az_custom_up_image_tr').show();
		jQuery('#az_custom_down_image_tr').show();
	}
}