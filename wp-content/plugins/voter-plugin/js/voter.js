jQuery('.aheadzen_voter_css').live("click",function(){
		var data_object1 = new Object();
		var data_object = new Object();
		data_object1.user_id = current_user_id;
		data_object1.rtype = 'ajax';
		
		var ajaxurl = this.href;		
		
		var hashes = this.href.slice(this.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++)
		{
			hashes2 = hashes[i].split('=');
			if(hashes2[0]=='component'){data_object.component = hashes2[1];}
			if(hashes2[0]=='type'){data_object.type = hashes2[1];}
			if(hashes2[0]=='action'){data_object.action = hashes2[1];}
			if(hashes2[0]=='item_id'){data_object.item_id = hashes2[1];}
			if(hashes2[0]=='secondary_item_id'){data_object.secondary_item_id = hashes2[1];}
			if(hashes2[0]=='unread_status'){data_object.unread_status = hashes2[1];}
			if(hashes2[0]=='_wpnonce'){data_object._wpnonce = hashes2[1];}
		}
		
		
		
		var voter_div_id = '#aheadzen_voting_'+data_object.secondary_item_id+'_'+data_object.item_id+'_'+data_object.component;
		jQuery(voter_div_id+' .vote-ajax-preloader').show();
		jQuery.ajax({
			type: 'GET',
			url: ajaxurl,
			data: data_object1,
			success: function(data)
			{				
				jQuery(voter_div_id).html(data);
				jQuery(voter_div_id+' .vote-ajax-preloader').hide();
			}
		});
	return false;
})

jQuery(function() {
	dialog = jQuery( "#aheadzen_voting_login" ).dialog({
		autoOpen: false,
		height: 350,
		width: 350,
		modal: true,
		open: function() {
            jQuery('.ui-widget-overlay').bind('click', function() {
                jQuery('#aheadzen_voting_login').dialog('close');
            })
        },
		close: function() {
			//alert('CLOSE');
		}
	});
	jQuery( ".aheadzen_voting_add" ).click(function() {
		jQuery( "#aheadzen_voting_login" ).dialog( "open" );
	});
});