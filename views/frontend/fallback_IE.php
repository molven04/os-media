<?php 
// var_dump($type);

// http://www.in-formazione.tv/home/wp-content/uploads/video
/*
$url = $videopath;
$html = file_get_contents($url);
echo '----------'. $html; ;
$count = preg_match_all('/<td><a href="([^"]+)">[^<]*<\/a><\/td>/i', $html, $files);
for ($i = 0; $i < $count; ++$i) {
    echo "<br>-----------------------------------------------File: " . $files[1][$i] . "<br />\n";
}
*/

if (!isset($type) || $type =='' ) return;
?>

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


<!-- YOUTUBE -->
<?php if ($type == 'youtube') : ?>

<?php
	// ID youtube (required)
	if ($id_youtube == '') return;
	$id_player = 'example_video_id_'.rand();
	// options
	if ($autoplay == "true" || $autoplay == "on") $autoplay_attribute = "autoplay=1&"; else $autoplay_attribute = "";
	if ($loop == "true" || $loop == "on")	$loop_attribute = "loop=1&"; else $loop_attribute = "";
	if ($yt_https == "true" || $yt_https == "on")	$https_attribute = "https=1&"; else $https_attribute = "";
	if ($yt_html5 == "true" || $yt_html5 == "on")	$html5_attribute = "html5=1&"; else $html5_attribute = "";
	if ($yt_info == "true" || $yt_info == "on") $showinfo_attribute = "showinfo=0&"; else $showinfo_attribute = "";
	if ($yt_related == "true" || $yt_related == "on") $related_attribute = "rel=0&"; else $related_attribute = "";
	if ($yt_logo == "true" || $yt_logo == "on") $logo_attribute = "modestbranding=0&"; else $logo_attribute = "";
	if ($start_m || $start_s) {
		$start=(60*$start_m) + $start_s; // total seconds
		$start_attribute = "start=".$start."&";
	}else{
		$start_attribute = "";
	};
?>
	<!-- Youtube through videojs - Allows YouTube URL as source with Videojs -->
	<?php if ($yt_vjs == "true" || $yt_vjs == 'on') : ?>	

		<!-- Begin Video.js -->
		  <video id="<?php echo $id ?>" class="video-js vjs-default-skin<?php // if( $responsive ) echo " vjs-16-9" ?>" controls preload="auto" width="<?php echo $width ?>" height="<?php echo $height ?>"
		  	data-setup='{ 			
		  		"example_option":true
				"techOrder": ["youtube"],
            	"src": "https://www.youtube.com/watch?v=<?php echo $id_youtube ?>" 
            }'>
		    <source src="https://www.youtube.com/watch?v=<?php echo $id_youtube  ?>" type='video/youtube'>
		  </video>
		<!-- End Video.js -->

	<?php else : ?>

		<?php if($responsive) echo '<div class="container">'; ?>
		<iframe src="//www.youtube.com/embed/<?php echo $id_youtube ?>?<?php echo $autoplay_attribute ?><?php echo $related_attribute ?><?php echo $showinfo_attribute ?><?php echo $html5_attribute ?><?php echo $logo_attribute ?><?php echo $loop_attribute ?><?php echo $start_attribute ?> "
			<?php if(!$responsive) : ?>
			width="<?php echo $width ?>" 
			height="<?php echo $height ?>" 
			<?php endif ?>
		frameborder="0" allowfullscreen class="video"></iframe>
		<?php if($responsive) echo '</div>'; ?>

	<?php endif ?>

<!-- VIMEO -->
<?php elseif ($type == 'vimeo') : ?>

<?php if($responsive) echo '<div class="container">'; ?>
<iframe src="//player.vimeo.com/video/<?php echo $id_vimeo ?>" 
	<?php if(!$responsive) : ?>
	width="<?php echo $width ?>" 
	height="<?php echo $height ?>" 
	<?php endif ?>
	allowfullscreen class="video">
</iframe>
<?php if($responsive) echo '</div>'; ?>

<!-- start HTML5 player [ho usato la nuova direttiva class=vjs-16-9 per i "featured video" con caratteristiche "responsive"]-->
<?php else : ?>
	<video id="<?php echo $id ?>" class="<?php if( $responsive ) echo "vjs-16-9"; ?> video-js <?php echo $skin ?> <?php echo $class ?>" 
		width="<?php echo $width ?>" height="<?php echo $height ?>"
		width="<?php echo $width ?>" height="<?php echo $height ?>" poster="<?php echo $poster ?>" src="<?php echo $mp4_source ?>"
		<?php echo $controls_atts ?> 
		<?php echo $preload_atts ?> 
		<?php echo $autoplay_atts ?> 
		<?php echo $loop_atts ?>
		data-setup='{
			"example_option":true
		}'
		>
	    <source src="<?php echo $mp4_source ?>" type='video/mp4' />
	    <source src="<?php echo $webm_source ?>" type='video/webm' />
	    <source src="<?php echo $ogv_source ?>" type='video/ogg' />
	</video>
	<script>
		// VideoJS.setupAllWhenReady();
		document.getElementById('<?php echo $id ?>').addEventListener('loadedmetadata', function() {
  			this.currentTime = <?php echo $start ?>;
		}, false);
	</script>
<!-- End player -->

<?php endif ?>