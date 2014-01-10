<?php
/**
 * @package OSvideo
 * @version 1.0
 */
/*
Plugin Name: OS media - HTML5 Featured Video plugin
Plugin URI: http://mariomarino.eu/
Description: Self-hosted responsive HTML5 video platform for WordPress, based on the Video.js HTML5 video player library. 
Allows you to embed video in your post or page using HTML5 with Flash fallback support for non-HTML5 browsers.
Author: <a href="http://mariomarino.eu/">Mario Marino</a>
Version: 1.1
*/

$plugin_dir = plugin_dir_path( __FILE__ );

define('OS_PLUGIN_PATH',plugin_dir_url(__FILE__));
define('OS_PLUGIN_PATH_IMAGE',OS_PLUGIN_PATH.'images/');

$options=get_option('OSvid_options');
$options_player=get_option('OSvid_options_player');
define('skin', $options_player['OSvid_skin']);
define('player', $options['OSvid_player']);
$skin=skin;
$player=player;

add_action( 'wp_enqueue_scripts', 'add_OSvid_header' );
add_action( 'wp_head', 'OSvid_custom_style' ); // custom style player frontend
add_action( 'admin_head', 'OSvid_custom_style' ); // custom style player backend (preview)
/* Post admin hooks */
add_action('admin_menu', "OSvid_postAdmin"); // crea form

// if ( isset($_POST['reset_btn']) && ! isset($_POST['save'])) 	add_action('save_post', 'OSvid_postmeta_reset'); // reset post meta
add_action('save_post', 'OSvid_save_meta'); // save meta data post 
add_action('publish_post', 'OSvid_save_meta'); // publish meta data post (when the post is NEW !!)

// array META FIELD: player single post OPTIONS
$metafield = array (
	/* i primi 4 generano glii input di video e img */
	'OSvid_mp4' => 'upload mp4 video',
	'OSvid_webm' => 'upload webm video',
	'OSvid_ogg' => 'upload ogg video',
	'OSvid_img' => 'upload cover img',
	/* --------------- */
	'OSvid_type' => '',
	'OSvid_id' => 'embed ID',
	'OSvid_width' => 'width [px]',
	'OSvid_height' => 'height [px]',
	'OSvid_start_m' => 'min.',
	'OSvid_start_s' => 'sec.',
	'OSvid_class' => 'player position',
    	'OSvid_autoplay' => 'autoplay',
    	'OSvid_loop' => 'loop',
   	'OSvid_https' => 'use Youtube HTTPS protocol',
   	'OSvid_html5' => 'use Youtube HTML5 player',
    	'OSvid_showinfo' => 'hide Youtube info',
   	'OSvid_related' => 'hide Youtube related video',
	'OSvid_feat' => 'featured video mode (disable shortcode)'
);
// Eccezioni: parametri generali da ricavare dalla config del player
$exceptions=array(
	1=>'OSvid_width',
	2=>'OSvid_height'
);

/*
//$options['OSvid_player']
$vjs_skin = $plugin_dir.'/player';
$vjs_skin_a=OSvid_dir_array($plugin_dir.'/player'.$options['OSvid_player']);
echo $options['OSvid_player'];
echo '<pre>';
print_r($options);
echo '</pre>';
*/

/* The options page */
include_once($plugin_dir . 'admin.php');

//================================== functions =========================================
// funzione ricorsiva per creare un array col contenuto di una directory
function OSvid_dir_array($dir) {    
   $result = array(); 
   $cdir = scandir($dir); 
   foreach ($cdir as $key => $value)  { 
      if (!in_array($value,array(".",".."))) { 
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value))  { 
            $result[$value] = OSvid_dir_array($dir . DIRECTORY_SEPARATOR . $value); 
         } else  { 
            $result[] = $value; 
         } 
      } 
   }    
   return $result; 
} 

// verifica le caratteristiche del POST  
function is_edit_page($new_edit = null){
    global $pagenow;
    //make sure we are on the backend
    if (!is_admin()) return false;

    if($new_edit == "edit")
        return in_array( $pagenow, array( 'post.php',  ) );
    elseif($new_edit == "new") //check for new post page
        return in_array( $pagenow, array( 'post-new.php' ) );
    else //check for either new or edit
        return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
}

/* Include the script and css file in the page <head> */
function add_OSvid_header(){
	global $plugin_dir, $skin, $player;
	$options = get_option('OSvid_options');
	$options_player = get_option('OSvid_options_player');
	
	wp_register_style( 'OSvid-plugin', plugins_url( 'plugin-styles.css' , __FILE__ ) );
	wp_enqueue_style( 'OSvid-plugin' );
	
/*	
	if($options['OSvid_cdn'] == 'on'): //use the cdn hosted version
		wp_register_script( 'OSvid', 'http://vjs.zencdn.net/4.0/video.js' );
		wp_enqueue_script( 'OSvid' );
		
		wp_register_style( 'OSvid', 'http://vjs.zencdn.net/4.0/video-js.css' );
		wp_enqueue_style( 'OSvid' );
	else: //use the self hosted version
*/	
		// LOAD PLAYER
		if($options['OSvid_player'] == 'videojs'){
			// videojs player
			$path_player = 'player/'.$player.'/'.$player;
			wp_register_script( 'videojs_player', plugins_url( $path_player.'.js' , __FILE__ ) ); wp_enqueue_script( 'videojs_player' );		
			wp_register_style( 'videojs_player', plugins_url( $path_player.'.css', __FILE__ ) ); wp_enqueue_style( 'videojs_player');	
			// LOAD SKIN 
			OSvid_load_skin();
		}else{ return; };
		
		// LOAD SCRIPT Youtube throgh videojs
		if($options['OSvid_yt-vjs'] == 'on'){
			// videojs player
			wp_register_script( 'yt-vjs', plugins_url( 'player/'.$player.'/vjs.youtube.js' , __FILE__ ) ); wp_enqueue_script( 'yt-vjs' );	
			// wp_register_script( 'yt-vjs2', plugins_url( 'player/'.$player.'/media.youtube.js' , __FILE__ ) ); wp_enqueue_script( 'yt-vjs2' );	
		}
		/* elseif($options['OSvid_player'] == 'videojs'){
			wp_register_script( 'mediaelement', plugins_url( 'mediaelement/mediaelement.js' , __FILE__ ) ); wp_enqueue_script( 'videojs' );		
			wp_register_style( 'mediaelement', plugins_url( 'mediaelement/mediaelement.css' , __FILE__ ) ); wp_enqueue_style( 'videojs');
		}
		*/
		// wp_register_script( 'mce', plugins_url( 'test.js' , __FILE__ ) );
		// wp_enqueue_script( 'mce' );
	
	// endif;
	
	if($options_player['OSvid_responsive'] == 'on') { //include the responsive stylesheet
		wp_register_style( 'responsive-OSvid', plugins_url('responsive-video.css', __FILE__) );
		wp_enqueue_style( 'responsive-OSvid' );
	}
}

function OSvid_load_skin() {
	global $plugin_dir;
	$options = get_option('OSvid_options');
	$options_player = get_option('OSvid_options_player');
	$path_skin = $plugin_dir.'player/'.$options['OSvid_player'].'/skin'; //'.$options_player['OSvid_skin'].'.css';
	$arr_skin_file=OSvid_dir_array($path_skin);
		
	// rimuove le estensioni dai nomi file dell'array
	foreach ($arr_skin_file as $kk=>$val) $arr_skin[$kk] = preg_replace('/\.[^.]+$/','',$val);
		
	$path_rel='player/'.$options['OSvid_player'].'/skin/'.$options_player['OSvid_skin'].'.css';
	foreach($arr_skin_file as $kk=>$val){
		if($arr_skin[$kk]== $options_player['OSvid_skin']) {
			wp_register_style( $arr_skin[$kk], plugins_url( $path_rel, __FILE__ ) ); 
			wp_enqueue_style( $arr_skin[$kk] );
		}
	}
}

/* Include custom color styles in the  header (viene incluso anche nell'area admin, vedi funzione OSvid_menu) */
function OSvid_custom_style() {
	$options_player = get_option('OSvid_options_player');
	if ($options_player['OSvid_controlbar_position']) $cb_pos =  "bottom:0 !important;left:0 !important;right:0 !important"; else $cb_pos ="";
	
	if($options_player['OSvid_color_one'] != "#ccc" || $options_player['OSvid_color_two'] != "#66A8CC" || $options_player['OSvid_color_three'] != "#000") { //If custom colors are used
		$color3 = hex2RGB($options_player['OSvid_color_three'], true); //Background color is rgba
		echo "
	<style id='custom_videojs' type='text/css'>
		." . skin . " { color: " . $options_player['OSvid_color_one'] . " !important }
		." . skin . " .vjs-play-progress, ." . skin . " .vjs-volume-level { background-color: " . $options_player['OSvid_color_two'] ."  !important }
		." . skin . " .vjs-control-bar, ." . skin . " .vjs-big-play-button { background: rgba(" . $color3 . ",0.7)  !important}
		." . skin . " .vjs-slider { background: rgba(" . $color3 . ",0.2333333333333333)  !important }
		." . skin . " .vjs-control-bar, ." . skin . " { ". $cb_pos . "  }
	</style>
		";
	}
}

// Prevent mixed content warnings
function add_OSvid_swf(){
		echo '<script type="text/javascript">OSvid.options.flash.swf = "'. plugins_url( 'player/videojs/video-js.swf' , __FILE__ ) .'";</script>';
}
add_action('wp_head','add_OSvid_swf');

/*** Add video posting widget and options post & page. */
function OSvid_postAdmin(){
	if( function_exists("add_meta_box") )	{
		//add_meta_box("OSvid-video-posting", "Featured Video", "OSvid_inner_custom_box", "post", "advanced");
		add_meta_box("OSvid-video-posting", "OS media: featured video", "OSvid_metabox_field", "post", "advanced"); // post
		add_meta_box("OSvid-video-posting", "OS media: featured video", "OSvid_metabox_field", "page", "advanced"); // page
	}	
}

/* Funzione per il controllo dei parametri generali/singolo post (copia le options nei postmeta con le ECCEZIONI dei singoli parametri presenti solo nel post META) ********************************************* */
function OSvid_check_options($array, $exceptions) {
	$options = get_option('OSvid_options');
	$options_player = get_option('OSvid_options_player');
		// per ogni item dell'array in ingresso confronta la corrispondenza delle chiavi con quello delle options generali e se uguali ne sostituisce i valori
		foreach ($array as $key=>$val){
			// scrive i valori options ricavati dalla configuraz. generale (SOLO quelli che hanno la stessa chiave!!)
			$array_out[$key] = $options[$key]; 
			// prende i param. $exceptions  dall'array delle opzioni del player
			foreach ($exceptions as $exc) $array_out[$exc] = $options_player[$exc];
		}
	return $array_out;
}

/* Prints the box content */
function OSvid_metabox_field(){
	global $post, $metafield, $exceptions;
	$post_id=$post->ID;
	$options = get_option('OSvid_options');
	$options_player = get_option('OSvid_options_player');
	 // Add an nonce field so we can check for it later.
  	wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename' );	

	// verifica che in ingresso ci sia un array  ($metafield) che definisce i campi del form	
	if (is_array($metafield)) { 
		// SE IL POST è NUOVO..					
		 if (is_edit_page('new')){
			// ..ricava e scrive i valori delle configurazioni generali nei meta-field del singolo post (SOLO quelli con la stessa chiave)
			$postmeta = OSvid_check_options($metafield, $exceptions); 
		}else{
			// ..altrimenti crea array ricavando i valori post-meta dei singoli campi a partire dall'array in ingresso
			foreach ($metafield as $key=>$val) $postmeta[$key] = get_post_meta($post_id, $key, true);	
		}
	}
	
	/*MONITOR 
	 if (is_edit_page('new')) echo '---->NUOVO!!!! ';
	echo 'POST_ID--> '.$post_id.'<br />'; 
	echo '<pre>';
	echo print_r ($postmeta);
	echo '</pre>';
	*/
	
?>
	
	<fieldset id="#radiobutton" style="float:left; border:solid #767070 1px; width:auto; height:40px; margin:5px 5px 10px 0; padding:3px; background:#afafaf;">
	        <label id="label_r1" for="r1" >self-hosted </label><input id='r1' style="margin:8px 20px 0 0" class="video_type" type="radio" name="OSvid_type" value="self-hosted" <?php if($postmeta['OSvid_type']=='self-hosted' || !$postmeta['OSvid_type']) echo ' checked="checked" '; ?> />
	        <label id="label_r2" for="r2" >Youtube </label><input id='r2'  style="margin:8px 20px 0 0" class="video_type" type="radio" name="OSvid_type" value="youtube"  <?php if($postmeta['OSvid_type']=='youtube') echo ' checked="checked" '; ?>/>
	        <label id="label_r3" for="r3" >Vimeo </label><input  id='r3' style="margin:8px 20px 0 0" class="video_type" type="radio" name="OSvid_type" value="vimeo"  disabled <?php //if($postmeta['OSvid_type']=='vimeo') echo ' checked="checked" '; ?> />
	 </fieldset>
	
	<div id="box_feat" style="float:right; border:solid #767070 1px; width: 200px; margin:5px 0 10px 0; padding:3px; background:#afafaf;">		
		<input id="OSvid_feat" name="OSvid_feat"  <?php if( $postmeta['OSvid_feat']=='on') echo 'checked="checked" '; ?>  type="checkbox" />
		<label for="<?php echo $postmeta['OSvid_feat'] ?>"><?php echo $metafield['OSvid_feat'] ?>&nbsp;</label>
	</div>
		
	<div id="box_selfhost" style="clear:both; width:auto; height:auto">
				
		<?php $index=1; 
			foreach ($metafield as $kk=>$vv): 
				if ($index <=4):?> 
		<input id="<?php echo $kk ?>" style="margin:3px 3px 3px 0; float:left" type="text" size="65" placeholder="URL" name="<?php echo $kk ?>" value="<?php echo $postmeta[$kk]; ?>" />
		<input id="upload_button<?php echo $index ?>"   style="margin:3px 0 3px 0; float:left" class="button" type="button" value="<?php echo $vv?>" /><br />
		<?php 	 $index++; endif; 
			endforeach; ?>

		<!-- IMG PRW -->
		<?php if($postmeta['OSvid_type']=='self-hosted' && $postmeta['OSvid_img']): ?>
		<div id="box_img" style="clear:both; margin:10px 0 0 0">
			<img style="max-height: 90px; max-width: 240px; padding:3px; border: silver solid 1px" src="<?php echo $postmeta['OSvid_img'] ?>"  />
		</div>
		<?php endif ?>
	
	</div>
	
	<div id="box_embed">
		<label for="OSvid_id"><?php echo $metafield['OSvid_id'] ?></label>&nbsp;
		<input style="width: 100px; margin-top:5px;" type="text" id="OSvid_id" name="OSvid_id" value="<?php echo $postmeta['OSvid_id']; ?>" />
	</div>
	
	<div style="clear:both; width:100%; margin:5px 0 0 0;">
		
		<div style="float:left;">
		<label for="OSvid_width"><?php echo $metafield['OSvid_width'] ?></label>&nbsp;
		<input style="margin-top:5px; width:60px" type="number" id="OSvid_width" name="OSvid_width" value="<?php echo $postmeta['OSvid_width']; ?>" />
		<label for="OSvid_height"><?php echo $metafield['OSvid_height'] ?></label>&nbsp;
		<input style="margin-top:5px; width:60px" type="number" id="OSvid_height" name="OSvid_height" value="<?php echo $postmeta['OSvid_height']; ?>" />
 		</div>
	
		<div id="box_OSvid_class" style="float:left; margin-left:40px">
		<label for="OSvid_class"><?php echo $metafield['OSvid_class'] ?></label>
		<select id="OSvid_class" name="OSvid_class">
			<option value="" <?php if($postmeta['OSvid_class']=='') echo "selected='selected' "; ?> >none</option>
			<option value="alignleft" <?php if($postmeta['OSvid_class']=='alignleft') echo "selected='selected' "; ?> >alignleft</option>
			<option value="alignright" <?php if($postmeta['OSvid_class']=='alignright') echo "selected='selected' "; ?> >alignright</option>
		</select>	
		</div>
	
		<div style="float:right; border:solid silver 1px;  margin:0; padding:2px; background:#e8e8e8;">
			<label for="OSvid_start_m"><?php echo "Start at"; ?></label>&nbsp;
			<input style="margin-top:5px; width:50px" type="number" maxlength="2" id="OSvid_start_m" name="OSvid_start_m" placeholder="00" value="<?php echo $postmeta['OSvid_start_m']; ?>" /> min.
			<input style="margin-top:5px; width:50px" type="number" maxlength="2" id="OSvid_start_s" name="OSvid_start_s" placeholder="00" value="<?php echo $postmeta['OSvid_start_s']; ?>" />sec.
		</div>
		
	</div>
	
	<div style="clear:left; width:auto; height:10px; margin:20px 0 0 0"></div>
	
	<?php foreach ($metafield as $key=>$val):  
			if($key=="OSvid_autoplay" OR $key=="OSvid_loop" OR $key=="OSvid_https" OR $key=="OSvid_showinfo" OR $key=="OSvid_related"): ?>
				
		<div id="box_<?php echo $key ?>" style="margin:10px 0 10px 0; padding:2px; background-color:#e8e8e8;" class="metacheck">		
			<input id="<?php echo $key ?>" name="<?php echo $key ?>"  <?php if( $postmeta[$key]=='on') echo 'checked="checked" '; ?>  type="checkbox" />
			&nbsp;<label for="<?php echo $key ?>"><?php echo $val ?></label>
		</div>
		
		<?php endif; 
	endforeach ?>
	
	<?php // passa le variabili generali (options) per CONFRONTARLE con quelle del singolo post (postmeta)) via javascript
	foreach ($options as $key=>$val): ?> 	
	
		<input id="<?php echo $key ?>_gen"  type="hidden" value="<?php echo $val  ?>" />
	
	<?php endforeach ?>
	
	<?php // passa le variabili generali del player (options_player) per CONFRONTARLE con quelle del singolo post (postmeta)) via javascript
	foreach ($options as $key=>$val): 
		if($key=="OSvid_width" OR $key=="OSvid_height"): ?>	
	
		<input id="<?php echo $key ?>_gen"   type="hidden" value="<?php echo $val  ?>" />
	
	<?php endif; 
	endforeach ?>
	
	<div style="height:25px; width:10px"></div>
		<!-- <input id="reset_btn" style="float:right; width:90px; margin:-20px 0 0 0" class="button-secondary reset_btn" type="submit"  value="Reset Value" name="reset_btn"/> -->
		<input  id="gen_shortcode"  style="float:right; margin:-20px 0 0 20px; width:auto" class="button-secondary" type="" value="Generate Shortcode" name="shortcode_btn" />
		<!-- <input style="float:right; margin:0px 0 0 20px;" id="remove_video" class="button-secondary" type="" value="<?php _e("Remove feat video"); ?>" name="save"/> -->

<?php
}

function OSvis_form_reset (){
	?>
	<form id="form_reset">
		<input id="reset_btn" style="float:right; width:90px; margin:-20px 0 0 0" class="button-secondary reset_btn" type=""  value="Reset Value" name="reset_btn"/>
	</form>
	<?php
}

/********************************************************************************************************************************/
/* When the post is saved, saves our custom data */
function OSvid_save_meta( $post_id ) {
	global $post, $metafield;
	// verify if this is an auto save routine. 
	// If it is our form has not been submitted, so we dont want to do anything
	  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )  return;
	  // verify this came from the our screen and with proper authorization,
	  // because save_post can be triggered at other times
	  if ( !wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename( __FILE__ ) ) )  return;  
	  // Check permissions
	  if ( 'page' == $_POST['post_type'] )  {
	    if ( !current_user_can( 'edit_page', $post_id ) )
	        return;
	  }  else  {
	    if ( !current_user_can( 'edit_post', $post_id ) )
	        return;
	  }
	  // OK, we're authenticated: we need to find and save the data
	  /* Sanitize user input
	  $OSvid_embed_id = sanitize_text_field( $_POST['OSvid_embed_id'] );
	  $OSvid_width = sanitize_text_field( $_POST['OSvid_width'] ); 
	*/
	foreach ($metafield as $key=>$val) {
		$value = $_POST[$key];
		update_post_meta($post_id, $key, $value);		
	}
	
}

// Reset Postmeta non usata!
function OSvid_postmeta_reset() {
	global $post; $post_id=$post->ID;
	$options = get_option('OSvid_options'); //load the defaults
	$options_player = get_option('OSvid_options_player'); //load the defaults
	
	$arr = array (
		'OSvid_type' => 'self-hosted',
		'OSvid_mp4' => '',
		'OSvid_webm' => '',
		'OSvid_ogg' => '',
		'OSvid_img' => '',
		'OSvid_id' => '',
		'OSvid_width' =>  $options_player["OSvid_width"],
		'OSvid_height' =>  $options_player["OSvid_height"],
		'OSvid_start_m' => '',
		'OSvid_start_s' => '',
		'OSvid_class' => '',
	    	'OSvid_autoplay' =>  $options["OSvid_autoplay"],
	    	'OSvid_loop' =>  $options["OSvid_loop"],
	   	'OSvid_https' =>  $options["OSvid_https"],
	   	'OSvid_html5' =>  $options["OSvid_html5"],
	    	'OSvid_showinfo' =>  $options["OSvid_showinfo"],
	   	'OSvid_related' =>  $options["OSvid_related"],
		'OSvid_feat' => $options["OSvid_feat"]
	);
	
	foreach ($arr as $key=>$value) update_post_meta($post_id, $key, $value);		
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////// OUTPUT //////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/* The [video] shortcode 
trasforma i parametri dello shortcode (atts) in tag html */
function OSvid_shortcode($atts, $content=null){
	global $player,$skin,$post,$plugin_dir;
	$options = get_option('OSvid_options'); //load the defaults
	$options_player = get_option('OSvid_options_player'); //load the defaults
	
	extract(shortcode_atts(array(
		'mp4' 	=> '',
		'webm' 	=> '',
		'ogg' 	=> '',
		'img' 	=> '',
		'width' 	=> $options_player['OSvid_width'],
		'height' 	=> $options_player['OSvid_height'],
		'preload' 	=> $options['OSvid_preload'],
		'autoplay' => $options['OSvid_autoplay'],
		'loop' 	=> '',
		'class' 	=> '',
		'start_m'	=> '',
		'start_s'	=> ''
		//'controls' => '',
		// 'id' 	=> ''
	), $atts));

	// ID is required for multiple videos
	if ($id == '') $id = 'example_video_id_'.rand();
	// MP4 Source
	if ($mp4)	$mp4_source = '<source src="'.$mp4.'" type=\'video/mp4\' />';  else $mp4_source = '';
	// WebM Source 
	if ($webm) $webm_source = '<source src="'.$webm.'" type=\'video/webm; codecs="vp8, vorbis"\' />'; else $webm_source = '';
	// Ogg source 
	if ($ogg) $ogg_source = '<source src="'.$ogg.'" type=\'video/ogg; codecs="theora, vorbis"\' />'; else	 $ogg_source = '';	
	// Poster image 
	if ($img) $poster_attribute = ' poster="'.$img.'"'; else $poster_attribute = '';	
	
	// Preload (è attivo solo se siamo in modalità FEATURED)
	if (get_post_meta($post->ID, 'OSvid_feat', true) == 'on' || ($preload == "auto" || $preload == "true" || $preload == "on")) $preload_attribute = ' preload="auto"';
	elseif ($preload == "metadata") $preload_attribute = ' preload="metadata"'; // da aggiungere nela gestione degli shortcode
	else $preload_attribute = ' preload="none"';

	// Autoplay the video?
	if ($autoplay == "true" || $autoplay == "on") $autoplay_attribute = " autoplay"; else $autoplay_attribute = "";
	
	// Loop the video?
	if ($loop == "true") $loop_attribute = " loop";	else	$loop_attribute = "";
	
	// Controls?
	if ($controls == "false") $controls_attribute = "";
	else	$controls_attribute = " controls";
	
	// Is there a custom class?
	if ($class)	$class = ' ' . $class;
	
	// Tracks (filtra i contenuti dello shortcode attraverso i "ganci")
	if(!is_null( $content ))
	 $track = do_shortcode($content); 
	else	$track = "";
	
	if ($start_m || $start_s) $start=(60*$start_m) + $start_s; // total seconds
	
	//skin
	$skin_attribute = 
	
	$data_setup = ' "techOrder": ["html5", "flash"]';

	//Output the <video> tag
	$OSvid = <<<_end_

	<!-- Begin Video.js -->
	<video id="{$id}" class="video-js {$skin}{$class}" width="{$width}" height="{$height}" {$poster_attribute}{$controls_attribute}{$preload_attribute}{$autoplay_attribute}{$loop_attribute} data-setup={}>
		{$mp4_source}
		{$webm_source}
		{$ogg_source}{$track}
		<!-- If the browser doesn't understand the <video> element, then reference a Flash file.  -->  
		    <object id="flash_fallback" class="vjs-flash-fallback" width="640" height="360" type="application/x-shockwave-flash" data="{$plugin_dir}player/videojs/video-js.swf">
		      <param name="movie" value="{$plugin_dir}player/videojs/video-js.swf" />
		      <param name="allowfullscreen" value="true" />
		      <param name="flashvars" value='config={"playlist":["{$img}", {"url": "{$mp4}","autoPlay":false,"autoBuffering":true}]}' />
		    </object>
	</video>
	<script>jQuery(window).load(function(){var myPlayer = _V_("{$id}");myPlayer.currentTime({$start}); });</script>
	<!-- End Video.js -->

_end_;
	
	if($options_player['OSvid_responsive'] == 'on') { //add the responsive wrapper		
		// $ratio = ($height && $width) ? $height/$width*100 : $options_player['OSvid_ratio']; //Set the aspect ratio (default 16:9)
		$ratio = $options_player['OSvid_ratio']; 
		$maxwidth = ($width) ? "max-width:{$width}px" : ""; //Set the max-width				
		$OSvid = <<<_end_
			
		<!-- Begin Video.js Responsive Wrapper -->
		<div style='{$maxwidth}'>
			<div class='video-wrapper' style='padding-bottom:{$ratio}%;'>
				{$OSvid}
			</div>
		</div>
		<!-- End Video.js Responsive Wrapper -->
_end_;
	}
	
	return $OSvid;

}
add_shortcode('video', 'OSvid_shortcode'); // associa il tag indicato di uno shortcode alla funzione indicata 

/* The [youtube] shortcode 
trasforma i parametri dello shortcode (atts) in tag html */
function OSvid_yt_shortcode($atts, $content=null){
	$options = get_option('OSvid_options'); //load the defaults
	$options_player = get_option('OSvid_options_player'); //load the defaults
	
	extract(shortcode_atts(array(
		'id' 			=> '',
		'start_m'		=> '',
		'start_s'		=> '',
		'loop' 		=> $options['OSvid_loop'],
		'autoplay' 	=> $options['OSvid_autoplay'],
		'width' 		=> $options_player['OSvid_width'],
		'height' 		=> $options_player['OSvid_height'],	
		'https' 		=> $options['OSvid_https'],
		'html5' 		=> $options['OSvid_html5'],
		'showinfo' 	=> $options['OSvid_showinfo'],
		'related' 		=> $options['OSvid_related'],
		'logo' 		=> $options['OSvid_logo']
	), $atts));
	
	// ID youtube (required)
	if ($id == '') return;
	$id_player = 'example_video_id_'.rand();
	// options
	if ($autoplay == "true" || $autoplay == "on") $autoplay_attribute = "autoplay=1&"; else $autoplay_attribute = "";
	if ($loop == "true")	$loop_attribute = "loop=1&"; else $loop_attribute = "";
	if ($https == "true")	$https_attribute = "https=1&"; else $https_attribute = "";
	if ($html5 == "true")	$html5_attribute = "html5=1&"; else $html5_attribute = "";
	if ($showinfo == "true") $showinfo_attribute = "showinfo=0&"; else $showinfo_attribute = "";
	if ($related == "true") $related_attribute = "rel=0&"; else $related_attribute = "";
	if ($logo == "true") $logo_attribute = "modestbranding=0&"; else $logo_attribute = "";
	if ($start_m || $start_s) {
		$start=(60*$start_m) + $start_s; // total seconds
		$start_attribute = "start=".$start."&";
	}else{
		$start_attribute = "";
	};
	
	// if(!is_null( $content ))  $track = do_shortcode($content); else $track = "";

	// Youtube through videojs - Allows YouTube URL as source with Videojs
	if ($options['OSvid_yt-vjs'] == 'on') {	
	$OSvid = <<<_end_
	<!-- Begin Video.js -->
	  <video id="$id_player" src="" class="video-js vjs-default-skin" controls preload="auto" width="$width" height="$height" data-setup='{ "techOrder": ["youtube"], "src": "http://www.youtube.com/watch?v=$id" }'></video>
	<!-- End Video.js -->
_end_;
	}else{
	$OSvid = <<<_end_
	<!-- Begin iframe-->
	<iframe type="text/html" src="http://www.youtube.com/embed/{$id}?{$autoplay_attribute}{$related_attribute}{$showinfo_attribute}{$html5_attribute}{$logo_attribute}{$loop_attribute}{$start_attribute}} " 
		width="{$width}" 
		height="{$height}"
		frameborder="0">
	</iframe>
	<!-- End iframe -->
_end_;
	}
	//add the responsive wrapper	
	if($options_player['OSvid_responsive'] == 'on' && $options['OSvid_yt-vjs'] == 'on') { 	
		// $ratio = ($height && $width) ? $height/$width*100 :  $options_player['OSvid_ratio']; //Set the aspect ratio (default 16:9)
		$ratio = $options_player['OSvid_ratio']; 
		$maxwidth = ($width) ? "max-width:{$width}px" : ""; //Set the max-width				
		$OSvid = <<<_end_
			
		<!-- Begin Video.js Responsive Wrapper -->
		<div style='{$maxwidth}'>
			<div class='video-wrapper' style='padding-bottom:{$ratio}%;'>
				{$OSvid}
			</div>
		</div>
		<!-- End Video.js Responsive Wrapper -->
_end_;
	}
	
	return $OSvid;

}
add_shortcode('youtube', 'OSvid_yt_shortcode'); // associa il tag indicato di uno shortcode alla funzione indicata 

/* The [vimeo] shortcode 
trasforma i parametri dello shortcode (atts) in tag html */
function OSvid_vimeo_shortcode($atts, $content=null){

	return $OSvid;

}
add_shortcode('vimeo', 'OSvid_vimeo_shortcode'); // associa il tag indicato di uno shortcode alla funzione indicata 

// OS media function for FEATURED VIDEO
function OSmedia_video($post_id) {
	
	if( get_post_meta($post_id, 'OSvid_feat', true) != 'on' ) return; // controlla se siamo in modalità "featured video" 
	
	global $metafield, $exceptions, $post;
	$options = get_option('OSvid_options'); //load the defaults
	$options_player = get_option('OSvid_options_player'); //load the defaults
	
	//genera lo shortcode lato server
	if( get_post_meta($post_id, 'OSvid_type', true) != 'self-hosted' ) {
		$items =  array (
			'id' 			=> '',
			'width'   	 	=> $options_player['OSvid_width'],
			'height'   	 	=> $options_player['OSvid_height'],
			'autoplay'	 	=> $options['OSvid_autoplay'],
			'loop'     	 	=> $options['OSvid_loop'],
			'start_m' 		=> '',
			'start_s' 		=> '',
		   	'https' 		=> '',
		   	'html5' 		=> '',
			'showinfo' 	=> '',
		   	'related' 		=> ''
		);
		if( get_post_meta($post_id, 'OSvid_type', true) == 'youtube' ) $shortcode = '[youtube  '; else $shortcode = '[vimeo  ';
	}else{	
		$items =  array (
			'mp4'     	 	=> '',
			'webm'    	 	=> '',
			'ogg'     	 	=> '',
			'img'  	 	=> '',
			'width'   	 	=> $options_player['OSvid_width'],
			'height'   	 	=> $options_player['OSvid_height'],
			'autoplay'	 	=> $options['OSvid_autoplay'],
			'loop'     	 	=> $options['OSvid_loop'],
			'start_m'		=> '',
			'start_s'		=> ''
		);
		$shortcode = '[video ';
	}			 

	foreach ($items as $key=>$value_opt) {
		$kk='OSvid_'.$key;
		$postmeta[$kk]=get_post_meta($post_id, $kk, true);	 // ricava i postmeta
		// echo '<br />'.$kk.'---->  '.$postmeta[$kk]; // MONITOR
		// confronta i valori in modo da mettere lo shortcode solo se i postmeta sono diversi dalle options generali
		if ($postmeta[$kk]=='on') $postmeta[$kk]='true';
		if ( $value_opt != $postmeta[$kk] && $postmeta[$kk] != '' )  $shortcode .= ' ' . $key . '="' . $postmeta[$kk] . '"'; 
	};
		
	$shortcode .= ']';
	// echo '<br />------>'.$shortcode;
	return do_shortcode($shortcode);	
	
}

?>
