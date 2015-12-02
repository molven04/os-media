<!-- dinamyc style video-js player -->
<?php // include "style_videoplayer.php" ?>

<!-- start HTML5 player mobile -->
<video id="<?php echo $id_player ?>" class="video-js" poster="<?php echo $poster ?>" src="<?php echo $mp4 ?>"
	<?php echo ' ' . $controls_atts ?> 
	<?php echo ' ' . $preload_atts ?> 
	<?php echo ' ' . $autoplay_atts ?> 
	<?php echo ' ' . $loop_atts ?>>
	<source src="<?php echo $mp4 ?>" type='video/mp4' />
</video>

	<script>
		// VideoJS.setupAllWhenReady();
		
		var videos = document.getElementsByTagName('video-js') || [];
		      for (var i = 0; i < videos.length; i++) {
		        videos[i].addEventListener('click', function(videoNode) {
		          return function() {
		            videoNode.play();
		          };
		        }(videos[i]));
		}

		document.getElementById('<?php echo $id_player ?>').addEventListener('loadedmetadata', function() {
  			this.currentTime = <?php echo $start ?>;
		}, false);

	</script>
<!-- End HTML5 player -->