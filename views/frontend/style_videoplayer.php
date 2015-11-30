<!-- dinamyc style video-js player -->
<style id='custom_videojs' type='text/css'>		
	.video-js 
		{ color: <?php echo $color1 ?>; }
	.video-js .vjs-volume-level, .video-js .vjs-play-progress, .video-js .vjs-slider-bar 
		{ background: <?php echo $color2 ?>; }
	.video-js .vjs-control-bar, .video-js .vjs-big-play-button, .video-js .vjs-menu-button .vjs-menu-content 
		{ background-color: rgba(<?php echo $color3['red'] ?>, <?php echo $color3['green'] ?>, <?php echo $color3['blue'] ?>, 0.7); }
	/*.container { width: 80%; margin: 0px auto;} */
	/* video { max-width: 100%; height: auto;} */		
</style>
<!--  -->