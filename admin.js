/*/////////////////////////////////////////////////////////////////////////// MM2013 /////////////////////////////////////////////////////////////////////////////////*/
jQuery(document).ready(function($){
	
	// gestione colori ----------------------------------------------------
    	$('.OSvid-color-field').wpColorPicker();
 	// gestione colori ----------------------------------------------------
	
	// controllo reset form --------------------------------------------- 
	$('.reset_form').submit(function(e) {
	    if(!confirm('are you sure?')) {
	        e.preventDefault();
	        return;
	    }
	    // submit the form via ajax, e.g. via the forms plugin
	});
	
	/* Fancybox autoplay video
	'onComplete': function(){
		$(".video-js").find('video').attr('autoplay','autoplay');
	}
	*/
	
	// METABOX ---------------------------------------------------------------
	//controllo featured video mode  --------------------------------
	if($('#OSvid_feat').is(':checked')){
		$('#gen_shortcode').hide();
		$('#box_OSvid_class').hide();
		// $('#publish_feat').show();
		//alert('ERROR: You are in "featured video mode"!');
	}else{
		$('#gen_shortcode').show();
		$('#box_OSvid_class').show();
		// $('#publish_feat').hide();
	};
	$("#OSvid_feat").change(function () {
		if($('#OSvid_feat').is(':checked')) {
			$('#gen_shortcode').hide();
			$('#box_OSvid_class').hide();
			// $('#publish_feat').show();	
		}else{
			$('#gen_shortcode').show();
			$('#box_OSvid_class').show();
			// $('#publish_feat').hide();
		};
	});
	
	// TYPE MENU: default options: 
	var type = jQuery("input[name=OSvid_type]:checked").val();
	if (type=='self-hosted'){
		$('#box_embed').hide(); 
		$('#r3').attr('disabled', true);
		$('#box_OSvid_https input').attr('disabled', true);  
		$('#box_OSvid_showinfo input').attr('disabled', true); 
		$('#box_OSvid_related input').attr('disabled', true); 
	}else if (type=='youtube'){
		$('#box_selfhost').hide();
	}else if (type=='vimeo'){
		//
	};

	// animated base menu
	$(".video_type").change(function () {
		var val = jQuery("input[name=OSvid_type]:checked").val();

		switch(val){
			case 'self-hosted': 
				jQuery('#box_embed').hide();
				jQuery('#box_selfhost').show();

				jQuery('#box_OSvid_https input').attr('disabled', true);  
				jQuery('#box_OSvid_showinfo input').attr('disabled', true); 
				jQuery('#box_OSvid_related input').attr('disabled', true); 
			break;
			case 'youtube': 
				jQuery('#box_embed').show();
				jQuery('#box_selfhost').hide();
 
				jQuery('#box_OSvid_https input').removeAttr('disabled');  
				jQuery('#box_OSvid_showinfo input').removeAttr('disabled');
				jQuery('#box_OSvid_related input').removeAttr('disabled');
			break;
			case 'vimeo': 	
				jQuery('#box_OSvid_https input').removeAttr('disabled');  
				jQuery('#box_OSvid_showinfo input').removeAttr('disabled');
				jQuery('#box_OSvid_related input').removeAttr('disabled');
			break;
			default: val ="self-hosted";
		}
          });

	// genera lo shortcode e lo aggiunge alla textarea 
	jQuery('#gen_shortcode').click(function(){
		// SWITCH
		var val = jQuery("input[name=OSvid_type]:checked").val();
		switch(val) {
		//self-hosted ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		case 'self-hosted': 	
		if(!jQuery('#OSvid_mp4').val() && !jQuery('#OSvid_webm').val() && !jQuery('#OSvid_ogg').val()) {alert('ERROR: all URL field is empty!'); return;}
			// dalle config generali
			var items =  { 
				'mp4'     	 : '',
				'webm'    	 : '',
				'ogg'     	 : '',
				'img'  	 : '',
				'width'   	 :  jQuery('#OSvid_width_gen').val(),
				'height'   	 :  jQuery('#OSvid_height_gen').val(),
				'class'    	 : '',
				'start_m' 	 : '',
				'start_s'	: ''
				//'id'       	: '', 
			};
		
			var shortcode = '[video';			 

			jQuery.each(items, function(index, value) {
				var value_post = jQuery('#OSvid_' + index).val(); // valore ricavato dal campo del form
				// confronta i valori in modo da mettere lo shortcode solo se i postmeta sono diversi dalle options generali
       				if ( value !== value_post && value_post !== '')  shortcode += ' ' + index + '="' + value_post + '"'; 
   			});
			
			//checkbox items
			var items =  { 
				'autoplay': jQuery('#OSvid_autoplay_gen').val(),
				'loop'     	:  jQuery('#OSvid_loop_gen').val()
				//'controls' 	: controls
			};
			
			jQuery.each(items, function(index, value) {				
				var value_post = jQuery('#OSvid_' + index).is(':checked');							
				if ( value_post == true ) checked = 'on'; else checked = '';	
				// confonta i valori in modo da mettere lo shortcode solo se i postmeta sono diversi dalle options generali								
				if ( checked !== value ) shortcode += ' ' + index + '="' + value_post + '"';
   			});
			
		break;
		// youtube -------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		case 'youtube':
			if(!jQuery('#OSvid_id').val()) {alert('ERROR: ID field can not be empty!'); return;}
			// dalle config generali
			var items =  { 
				'id' 		: '',
				'width' 	:  jQuery('#OSvid_width_gen').val(),
				'height' 	:  jQuery('#OSvid_height_gen').val(),
				'class'	: '',
				'start_m'	: '',
				'start_s'	: ''
			}
			
			var shortcode = '[youtube ';
			
			jQuery.each(items, function(index, value) {
				// controlla valore ricavato dal campo del form e se non esiste mette quello delle options generali 
				if (jQuery('#OSvid_' + index).val() !== '') value= jQuery('#OSvid_' + index).val(); 
				if (value !=='') shortcode += ' ' + index + '="' + value + '"'; 
   			});
			
			//checkbox items
			var items =  { 
				'autoplay'		: '',
				'loop'     		: '',
				'https'		: '',
				'html5'		: '',
				'showinfo'	: '',
				'related'		: '',
				'logo'		: ''
			};
			
			jQuery.each(items, function(index, value) {		
				// SE ESISTE, mette sempre i valori ricavati dal campo POSTMETA, altrimenti pesca dalle options generali	
				if (jQuery('#OSvid_' + index).length) value = jQuery('#OSvid_' + index).is(':checked'); else value = jQuery('#OSvid_'+index+'_gen').val();		
				if ( value == true || value == 'on') shortcode += ' ' + index + '="true"';
   			});
			
		break;
		// vimeo ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		case 'vimeo':
		if(!jQuery('#OSvid_id').val()) {alert('ERROR: ID field can not be empty!'); return;}
		break;
		} // END switch
			
		// inserts the shortcode into the active editor
		//close the shortcode
		shortcode += ']';
		tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
		// reset shortcode
		shortcode='';
		
	});
	
	////////// custom Add media
	/*
	var custom_uploader=[];
	custom_uploader[1]= 'custom_uploader1';
	custom_uploader[2]= 'custom_uploader2';
	custom_uploader[3]= 'custom_uploader3';
	custom_uploader[4]= 'custom_uploader4';
	
 	var suff = { 
			1 : 'mp4',
			2 : 'webm',
			3 : 'ogg',
			4 : 'img'
		};

	for (var i = 1; i <= 4; i++){		
		
		$('#upload_button'+i).click(function(e) { 
		        e.preventDefault();	 
		        //If the uploader object has already been created, reopen the dialog
		        if (custom_uploader[i]) {
		            custom_uploader[i].open();
		            return;
		        }	 
		        //Extend the wp.media object
		        custom_uploader[i] = wp.media.frames.file_frame = wp.media({
		            title: 'Choose item',
		            button: {
		                text: 'Choose item'
		            },
		            multiple: false
		        });	 
		        //When a file is selected, grab the URL and set it as the text field's value
		        custom_uploader[i].on('select', function() {
		            attachment = custom_uploader[i].state().get('selection').first().toJSON();
				$('#OSvid_url_'+suff[i]).val(attachment.url); 
		        });	 
		        //Open the uploader dialog
		        custom_uploader[i].open(); 
	   	 });
		
	}
	*/
		var custom_uploader1;
		var custom_uploader2;
		var custom_uploader3;
		var custom_uploader4;
		
		$('#upload_button1').click(function(e) { 
		        e.preventDefault();	 
		        //If the uploader object has already been created, reopen the dialog
		        if (custom_uploader1) {
		            custom_uploader1.open();
		            return;
		        }	 
		        //Extend the wp.media object
		        custom_uploader1 = wp.media.frames.file_frame = wp.media({
		            title: 'Choose item',
		            button: {
		                text: 'Choose item'
		            },
		            multiple: false
		        });	 
		        //When a file is selected, grab the URL and set it as the text field's value
		        custom_uploader1.on('select', function() {
		            attachment = custom_uploader1.state().get('selection').first().toJSON();
		            $('#OSvid_mp4').val(attachment.url);
		        });	 
		        //Open the uploader dialog
		        custom_uploader1.open(); 
	   	 });
		
		$('#upload_button2').click(function(e) { 
		        e.preventDefault();	 
		        //If the uploader object has already been created, reopen the dialog
		        if (custom_uploader2) {
		            custom_uploader2.open();
		            return;
		        }	 
		        //Extend the wp.media object
		        custom_uploader2 = wp.media.frames.file_frame = wp.media({
		            title: 'Choose item',
		            button: {
		                text: 'Choose item'
		            },
		            multiple: false
		        });	 
		        //When a file is selected, grab the URL and set it as the text field's value
		        custom_uploader2.on('select', function() {
		            attachment = custom_uploader2.state().get('selection').first().toJSON();
		            $('#OSvid_webm').val(attachment.url);
		        });	 
		        //Open the uploader dialog
		        custom_uploader2.open(); 
	   	 });
		
		$('#upload_button3').click(function(e) { 
		        e.preventDefault();	 
		        //If the uploader object has already been created, reopen the dialog
		        if (custom_uploader3) {
		            custom_uploader3.open();
		            return;
		        }	 
		        //Extend the wp.media object
		        custom_uploader3 = wp.media.frames.file_frame = wp.media({
		            title: 'Choose item',
		            button: {
		                text: 'Choose item'
		            },
		            multiple: false
		        });	 
		        //When a file is selected, grab the URL and set it as the text field's value
		        custom_uploader3.on('select', function() {
		            attachment = custom_uploader3.state().get('selection').first().toJSON();
		            $('#OSvid_ogg').val(attachment.url);
		        });	 
		        //Open the uploader dialog
		        custom_uploader3.open(); 
	   	 });
		
		$('#upload_button4').click(function(e) { 
		        e.preventDefault();	 
		        //If the uploader object has already been created, reopen the dialog
		        if (custom_uploader4) {
		            custom_uploader4.open();
		            return;
		        }	 
		        //Extend the wp.media object
		        custom_uploader4 = wp.media.frames.file_frame = wp.media({
		            title: 'Choose image',
		            button: {
		                text: 'Choose image'
		            },
		            multiple: false
		        });	 
		        //When a file is selected, grab the URL and set it as the text field's value
		        custom_uploader4.on('select', function() {
		            attachment = custom_uploader4.state().get('selection').first().toJSON();
		            $('#OSvid_img').val(attachment.url);
		        });	 
		        //Open the uploader dialog
		        custom_uploader4.open(); 
	   	 });
	////////////////////////////////////

});
