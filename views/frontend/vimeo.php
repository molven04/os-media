<!-- CSS -->
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

<!-- VIMEO iframe-->
<?php if($responsive) echo '<div class="container">'; ?>

<iframe src="//player.vimeo.com/video/<?php echo $vimeo ?>?autoplay=<? echo $autoplay ?>&loop=<?php echo $loop ?>" <?php if($responsive) echo ' class="iframe-video"' ?>
	<?php if(!$responsive) : ?>
	width="<?php echo $width ?>" 
	height="<?php echo $height ?>" 
	<?php endif ?>
	allowfullscreen class="video" frameborder="0">
</iframe>

<?php if($responsive) echo '</div>'; ?>
<!-- END VIMEO -->