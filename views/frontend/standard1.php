
<?php 
// $mp4_source = "http://localhost/wp/wp-content/plugins/os-media-video/videostream.php?file=demo1.mp4"; 
$mp4_source = plugins_url( OSmedia_FOLDER ) . "/videostream.php?file=demo1.mp4"; 

?>

	<video controls preload="auto" src="<?php echo $mp4_source ?>" width="100%" poster="<?php echo $poster ?>">
		<source src="<?php echo $mp4_source ?>" type="video/mp4" />
	</video>

