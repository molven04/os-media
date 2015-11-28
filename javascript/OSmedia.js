/**
 * Wrapper function to safely use $
 */
function OSmediaWrapper( $ ) {

	var custom_uploader1;
	var custom_uploader2;
	var custom_uploader3;
	var custom_uploader4;
	var OSmedia = {

		/**
		 * Main entry point
		 */
		init: function () {
			OSmedia.prefix      = 'OSmedia_';
			OSmedia.templateURL = $( '#template-url' ).val();
			OSmedia.ajaxPostURL = $( '#ajax-post-url' ).val();

			// gestione colori 
			$('.OSmedia_color_field').wpColorPicker();

			OSmedia.registerEventHandlers();
		},

		/**
		 * Registers event handlers
		 */
		registerEventHandlers: function () {	

			$("#OSmedia_file").change(function() {
				sel_url = $(this).find(':selected').attr('data-url');			
				$("#OSmedia_fileurl").val(sel_url);
			});
			$( '#gen_shortcode' ).click( OSmedia.genShortcode );
			$( '#reset_btn' ).click( OSmedia.resetForm );
			$( '#upload_button1' ).click( OSmedia.upload1 );
			$( '#upload_button2' ).click( OSmedia.upload2 );
			$( '#upload_button3' ).click( OSmedia.upload3 );
			$( '#upload_button4' ).click( OSmedia.upload4 );
		},

		/**
		 * Event Handler
		 *
		 * @param object event
		 */
		genShortcode: function ( event ) {

			var shortcode_name = $('#OSmedia_shortcode_name').val();

			var shortcode = '['+shortcode_name;
			// input items
			var input_items =  { 
				file: 		$('#OSmedia_file').val(),
				fileurl: 	$('#OSmedia_fileurl').val(),
				mp4: 		$('#OSmedia_mp4').val(),
				webm: 		$('#OSmedia_webm').val(),
				ogg: 		$('#OSmedia_ogg').val(),
				img: 		$('#OSmedia_img').val(),
				youtube: 	$('#OSmedia_youtube').val(),
				vimeo: 		$('#OSmedia_vimeo').val(),
				width: 		$('#OSmedia_width').val(),
				height: 	$('#OSmedia_height').val(),
				class: 		$('#OSmedia_class').val(),
				start_m: 	$('#OSmedia_start_m').val(),
				start_s: 	$('#OSmedia_start_s').val()
			};

			$.each(input_items, function(index, value) {
			    if ( value && value !== '' )  shortcode += ' ' + index + '="' + value + '"'; 
			});

			//checkbox items
			var autoplay = $('#OSmedia_autoplay').prop('checked');
			var loop = $('#OSmedia_loop').prop('checked');
			var responsive = $('#OSmedia_responsive').prop('checked');
			var preload = $('#OSmedia_preload').prop('checked');
			var controls = $('#OSmedia_controls').prop('checked');
			var yt_https = $('#OSmedia_yt_https').prop('checked');
			var yt_showinfo = $('#OSmedia_yt_showinfo').prop('checked');
			var yt_related = $('#OSmedia_yt_related').prop('checked');
			
			if (autoplay) shortcode += ' autoplay="true"';;
			if (loop) shortcode += ' loop="true"'; 
			if (responsive) shortcode += ' responsive="true"';
			if (preload) shortcode += ' preload="true"'; else shortcode += ' preload="false"';
			if (controls) shortcode += ' controls="false"';

			if (yt_https && input_items['youtube']) shortcode += ' yt_https="true"';
			if (yt_showinfo && input_items['youtube']) shortcode += ' yt_showinfo="true"';
			if (yt_related && input_items['youtube']) shortcode += ' yt_related="true"';
			
			//close the shortcode
			shortcode += ']';
			// inserts the shortcode into the active editor
			send_to_editor( shortcode );
			// reset shortcode
			shortcode='';		
			event.preventDefault();

		}, // END genShortcode

		/**
		 * Event Handler
		 *
		 * @param object event
		 */
		// controllo reset form --------------------------------------------- 
		resetForm: function ( e ) {
	    	if(!confirm('are you sure?')) {
	        	e.preventDefault();
	        	return;
	    	}
	    	// submit the form via ajax, e.g. via the forms plugin
		},

		/**
		 * Event Handler for upload video mp4 format
		 *
		 * @param object event
		 */
		upload1: function ( event ) {
		        event.preventDefault();	 
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
		            $('#OSmedia_mp4').val(attachment.url);
		        });	 
		        //Open the uploader dialog
		        custom_uploader1.open(); 
		},

		/**
		 * Event Handler for upload video webm format
		 *
		 * @param object event
		 */
		upload2: function ( event ) {
		        event.preventDefault();	 
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
		            multiple: false // da cambiare !!!
		        });	 
		        //When a file is selected, grab the URL and set it as the text field's value
		        custom_uploader2.on('select', function() {
		            attachment = custom_uploader2.state().get('selection').first().toJSON();
		            $('#OSmedia_webm').val(attachment.url);
		        });	 
		        //Open the uploader dialog
		        custom_uploader2.open(); 
		},

		/**
		 * Event Handler for upload video ogg format
		 *
		 * @param object event
		 */
		upload3: function ( event ) {
		        event.preventDefault();	 
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
		            $('#OSmedia_ogg').val(attachment.url);
		        });	 
		        //Open the uploader dialog
		        custom_uploader3.open(); 
		},

		/**
		 * Event Handler for upload poster images
		 *
		 * @param object event
		 */
		upload4: function ( event ) {
		        event.preventDefault();	 
		        //If the uploader object has already been created, reopen the dialog
		        if (custom_uploader4) {
		            custom_uploader4.open();
		            return;
		        }	 
		        //Extend the wp.media object
		        custom_uploader4 = wp.media.frames.file_frame = wp.media({
		            title: 'Choose item',
		            button: {
		                text: 'Choose item'
		            },
		            multiple: false
		        });	 
		        //When a file is selected, grab the URL and set it as the text field's value
		        custom_uploader4.on('select', function() {
		            attachment = custom_uploader4.state().get('selection').first().toJSON();
		            $('#OSmedia_img').val(attachment.url);
		        });	 
		        //Open the uploader dialog
		        custom_uploader4.open(); 
		}

	}; // end OSmedia

	$( document ).ready( OSmedia.init );

} // end OSmediaWrapper()

OSmediaWrapper( jQuery );
