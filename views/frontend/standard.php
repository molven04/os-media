<!-- HTML5 video player -->
<?php require_once "style_videoplayer.php"; // dinamyc style video-js player ?>

<?php if( !$flag_cpt ): ?><div style="padding:6px 0 6px 0" class="<?php if( isset($class)) echo $class ?>"><?php endif ?>
<video id="<?php echo $id_player ?>" class="video-js <?php if( isset($responsive) && $responsive == 'true' ) echo $ratio; ?> <?php if( isset($skin)) echo $skin ?>" width="<?php if( isset($width)) echo $width ?>" height="<?php if( isset($height)) echo $height ?>" poster="<?php if( isset($img)) echo $img ?>" <?php if( isset($controls_atts)) echo $controls_atts ?> <?php if( isset($preload_atts)) echo $preload_atts ?> <?php if( isset($autoplay_atts)) echo $autoplay_atts ?> <?php if( isset($loop_atts)) echo $loop_atts ?> data-setup="{}">
	<?php if( $mp4 != '' ): ?><source src="<?php echo $mp4 ?>" type="video/mp4" /><?php endif ?>
	<?php if( $webm != '' ): ?><source src="<?php echo $webm ?>" type="video/webm" /><?php endif ?>
	<?php if( $ogg != '' ): ?><source src="<?php echo $ogg ?>" type="video/ogg" /><?php endif ?>
</video>
<?php if( !$flag_cpt ): ?></div><?php endif ?>

<?php if( isset($start) && $start !='' ): ?>
<script>
	// js code for start 
	document.getElementById('<?php echo $id_player ?>').addEventListener('loadedmetadata', function() {
  		this.currentTime = <?php echo $start ?>;
	}, false);
</script>
<?php endif ?>
<!-- End HTML5 video player -->