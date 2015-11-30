<?php    

require_once( '../../../wp-config.php' );

if( true ){
	// local FTP files
	$option = get_option('OSmedia_settings');
	$absolute_path = rtrim($option['OSmedia_path'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR; // add or remove slash 
}else{
	// local media library files [NOT IMPLEMENTED YET]
	$upload_dir = ABSPATH . 'wp-content/upload/';
	$absolute_path = $upload_dir['path'] . '/';
}


$filePath = $absolute_path . $_GET['file'];

//////////////// object ////////////
$stream = new OSmedia_videostream( $filePath );
// $s3Client->registerStreamWrapper();
$stream->start();

?>