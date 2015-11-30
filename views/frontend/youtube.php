<!-- dinamyc style video-js player -->
<?php require_once "style_videoplayer.php" ?>

<style id='responsive_videojs' type='text/css'>
.container {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%;
}
.iframe-video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
</style>
<!-- YOUTUBE -->
<!-- Youtube through videojs - Allows YouTube URL as source with Videojs -->
<?php if ( isset($yt_vjs) && $yt_vjs == "true" ) : ?>

	<!-- Begin Video.js -->
	<?php if( $responsive ) echo '<div class="container">'; ?>
	<video id="<?php echo $id_player ?>" poster="<?php echo $img ?>" class="video-js <?php echo $skin ?> <?php if( $responsive ) echo " iframe-video" ?>" controls preload="auto" width="<?php echo $width ?>" height="<?php echo $height ?>" 
		data-setup='{ 			
			"techOrder": ["youtube"],
            "src": "https://www.youtube.com/watch?v=<?php echo $youtube_source ?>" 
       	}'>
		<source src="https://www.youtube.com/watch?v=<?php echo $youtube_source  ?>" type='video/youtube'>
	</video>
	<?php if( $responsive ) echo '</div>'; ?>
	<!-- End Video.js -->

<?php else: ?>

	<?php if( isset($responsive) && $responsive ) echo '<div class="container">'; ?>
		<iframe type="text/html" <?php if( !isset($responsive) ) echo ' width="'.$width.'" height="'.$height.'" '; ?>
		src="//www.youtube.com/embed/<?php echo $youtube_source ?> "
		frameborder="0" allowfullscreen
		<?php if( isset($responsive) && $responsive ) echo ' class="iframe-video"' ?>>
		</iframe>
	<?php if( isset($responsive) && $responsive ) echo '</div>'; ?>

<?php endif ?>
<!-- End YOUTUBE -->