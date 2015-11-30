/**
 * Wrapper function to safely use $
 */
function OSmediaWrapper( $ ) {

	var OSmedia = {

		/**
		 * Main entry point
		 */
		init: function () {
			OSmedia.prefix = 'OSmedia_';
			// OSmedia.registerEventHandlers();
			// OSmedia.enableVideoClicks();
		},

		/**
		 * Registers event handlers
		 */
		registerEventHandlers: function () {	
			// $( '#gen_shortcode' ).click( OSmedia.genShortcode );
			alert("document ready occurred!");
		},

		/**
		 * Registers event handlers
		 */
		enableVideoClicks: function () {
		      var video = document.getElementsByTagName('video-js');
				video.addEventListener('click',function(){
				  video.play();
				},false);
		},

	}; // end OSmedia

	$( document ).ready( OSmedia.init );

} // end OSmediaWrapper()

OSmediaWrapper( jQuery );