<?php
	
// Set Defaults on plugin activation
register_activation_hook(plugin_dir_path( __FILE__ ) . 'OSvideo.php', 'OSvid_options_player_reset');
register_activation_hook(plugin_dir_path( __FILE__ ) . 'OSvideo.php', 'OSvid_options_reset');
	
// if( is_admin() ) {	

	add_action('admin_menu', 'OSvid_menu'); // crea la struttura del menu
	add_action('admin_init', 'OSvid_settings_fields'); // crea i campi delle sezioni del menu
	
	// add_action('admin_init', 'OSvid_update_player'); 
	add_action( 'admin_enqueue_scripts', 'OSvid_js' ); // script js admin area
	function OSvid_js() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'OSvid-admin', plugins_url('admin.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	}
	
	// aggiunge script e css alle pagine di amministrazione (serve per il preview del player)
	add_action( 'admin_init', 'my_plugin_admin_init' ); 
	
	// update/reset options
	if (isset($_POST['reset_options_btn'])) 
		add_action( 'admin_init', 'OSvid_options_reset' );	// reset options
	elseif (isset($_POST['reset_options_player_btn']))
		add_action( 'admin_init', 'OSvid_options_player_reset' ); // reset player options
	else	
		add_action('admin_init', 'OSvid_update'); // update our database options
	
// }

/* ************************************************************************** */
/* menu di amministrazione                               							              */
/* ************************************************************************** */
function OSvid_menu() {
	global $page_hook_suffix;
	if (function_exists('add_submenu_page')) {
		add_menu_page('OSvideo Settings','OS media','manage_options',basename(__FILE__),'OSvid_admin_base_callback',OS_PLUGIN_PATH_IMAGE.'video-icon-16.png');
		add_submenu_page(basename(__FILE__),'OSvideo','Configuration','manage_options',basename(__FILE__),'OSvid_admin_base_callback'); 
		$page_hook_suffix = add_submenu_page(basename(__FILE__),'OSvideo','Player config','manage_options','OSvideo.php','OSvid_admin_player_callback');  
		
		add_action('admin_print_scripts-' . $page_hook_suffix, 'my_plugin_admin_scripts'); //aggiunge SCRIPT e CSSa questa pagina di amministrazione (serve per il preview del player)
		//add_action('admin_print_styles-'.$page_hook_suffix, 'OSvid_custom_style' ); // aggiunge CSS a questa pagina di amministrazione (serve per il preview del player) ma mette il codice IN TESTA !!!!!!
	}
}

/*-----------------carica SCRIPT e CSS del player nelle pagine ADMIN (serve per il preview del player) --------------------------------------------------*/
 function my_plugin_admin_init() {
	global $skin;
	$options_player = get_option('OSvid_options_player');
	$options = get_option('OSvid_options');
        	// load player
	if($options['OSvid_player'] == 'videojs'){
		// videojs player
		$path_player = 'player/'.$options['OSvid_player'].'/'.$options['OSvid_player'];
		wp_register_script( 'videojs_player', plugins_url( $path_player.'.js' , __FILE__ ) ); wp_enqueue_script( 'videojs_player' );		
		wp_register_style( 'videojs_player', plugins_url( $path_player.'.css', __FILE__ ) ); wp_enqueue_style( 'videojs_player');	
		// load skin
		OSvid_load_skin();
	}else{ return; };	
}

function my_plugin_admin_scripts() {
        /* Link our already registered script to a page */
        wp_enqueue_script( 'adm_videojs' );
          wp_enqueue_style( 'general_player' );
    	wp_enqueue_style( 'skin' );
	wp_enqueue_script( 'test' );
    }

/*----------------- aggiunge SCRIPT e CSS alle pagine ADMIN ---  FINE ---------------------------------------------*/

/* MM2013 funzione originaria
function OSvid_menu() {
	global $OSvid_admin;
	$OSvid_admin = add_options_page('OSvideo Settings', 'OSvideo settings', 'manage_options', 'OSvid-settings', 'OSvid_settings');
}
*/

/* Contextual Help */
function OSvid_help($contextual_help, $screen_in, $screen) {
	global $OSvid_admin;
	if ($screen_in == $OSvid_admin) {
		$contextual_help = <<<_end_
		<p><strong>OSvideo Settings Screen</strong></p>
		<p>The values set here will be the default values for all videos, unless you specify differently in the shortcode. Uncheck <em>Use CDN hosted version?</em> if you want to use a self-hosted copy of OSvideo instead of the CDN hosted version. <strong>Using the CDN hosted version is preferable in most situations.</strong></p>
		<p>If you are using a responsive WordPress theme, you may want to check the <em>Responsive Video</em> checkbox.</p>
		<p>Uncheck the <em>Use the [video] shortcode?</em> option <strong>only</strong> if you are using WordPress 3.6+ and wish to use the [video] tag for MediaElement.js. You will still be able to use the [OSvid] tag to embed videos using OSvideo.</p>
_end_;
	}
	return $contextual_help;
}
add_filter('contextual_help', 'OSvid_help', 10, 3);


// Funzioni per passaggio variabili per creazione FORM
function OSvid_admin_base_callback() {
	OSvid_common_form(
		'OS media General Config',
		'OSvid_options',
		'OSvid-settings'
	); 
}

function OSvid_admin_player_callback() {
	global $plugin_dir;
	$options = get_option('OSvid_options');
	$options_player = get_option('OSvid_options_player');
	OSvid_common_form(
		'OS media HTML5 player config',
		'OSvid_options_player',
		'OSvid-settings-player'
	); 

?>
	<!-- preview video player 
	[video mp4="http://localhost/m3wordpress/wp-content/uploads/2013/08/quietArea_x264.mp4" width="'.$options_player['OSvid_width'].'" height="'.$options_player['OSvid_height'].'"]
	-->
	<div style="width:auto; height:auto; margin:25px 0 0 0">
		<h2>preview HTML5 video player </h2>
	</div>
	<video id="example_video_1" controls 
		class="video-js <?php echo $options_player['OSvid_skin']; ?>"  
		preload="<?php if($options['OSvid_preload']=='on') echo 'true' ?>" width="<?php echo $options_player['OSvid_width'] ?>" height="<?php  echo $options_player['OSvid_height'] ?>"
		poster="http://openstream.tv/wp-content/uploads/sites/2/2013/10/test5.jpg"  data-setup='{"example_option":true}'>
			 <source src="http://openstream.tv/wp-content/uploads/sites/2/2013/10/test5.mp4" type='video/mp4' />
			 <source src="http://openstream.tv/wp-content/uploads/sites/2/2013/10/test5.mp4" type='video/webm' />
			 <source src="http://openstream.tv/wp-content/uploads/sites/2/2013/10/test5.mp4" type='video/ogg' />
	</video>
	<div style="width:600px; border: solid silver 1px; margin:15px 0 0 0; padding:4px">
		<h2>OS media Info</h2>
		<p>For correct usage you must set the PHP server configuration file <PRE>php.ini</PRE>, adding the follow rows:</p>
		<PRE>upload_max_filesize=512M </PRE>
		<PRE>post_max_size=512M</PRE>

		<p><strong>OSvideo Settings Screen</strong></p>
		<p>For <b>Feature Video mode</b> put in your WP Theme the follow function: <PRE>OSmedia_video($post->ID)</PRE></p>
	</div>	
<?php 
}

// Funzione creazione FORM
function OSvid_common_form($title,$setting,$section) {
	$options = get_option('OSvid_options');
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	?>
	<div class="wrap">
	<h2><?php echo $title ?>
	<?php echo ': '.$options['OSvid_player'];?></h2>
	<form id="options_form" method="post" action="options.php">
	<?php
	settings_fields($setting);
	do_settings_sections($section);
	?>
	<p class="submit">
	<input id="save_options" name="save_options" type="submit" class="button-primary" value="Save Changes" />
	</p>
	</form>
	<?php if($setting=='OSvid_options') : ?>
	<form id="reset_options" class="reset_form" method="POST">
		<input id="reset_options_btn"  name="reset_options_btn" style="margin:0 20px 0 0" class="button-secondary reset_btn" type="submit" value="Reset to Default Value" />
	</form>
	<?php elseif($setting=='OSvid_options_player') : ?>
	<form  id="reset_options_player" class="reset_form" method="POST">
		<input id="reset_options_player_btn"  name="reset_options_player_btn" style="margin:0 20px 0 0" class="button-secondary reset_btn" type="submit" value="Reset to Default Value" />
	</form>
	<?php endif ?>
	</div>
	<?php
}

// funzione creazione campi del form
function OSvid_settings_fields() {
	global $metafield;
	register_setting('OSvid_options', 'OSvid_options', 'OSvid_sanitize_options');
	register_setting('OSvid_options_player', 'OSvid_options_player', 'OSvid_sanitize_options_player');
	
	// config BASE ----------------------------------------------------------------------------------------------------------------------------------
	add_settings_section('OSvid_defaults', 'Default Settings', 'defaults_output', 'OSvid-settings');
	// add_settings_field('OSvid_cdn', 'use CDN hosted version', 'cdn_output', 'OSvid-settings', 'OSvid_defaults');
	add_settings_field('OSvid_feat', 'featured video mode', 'feat_output', 'OSvid-settings', 'OSvid_defaults');
	add_settings_field('OSvid_preload', 'preload', 'preload_output', 'OSvid-settings', 'OSvid_defaults');
	add_settings_field('OSvid_autoplay', $metafield['OSvid_autoplay'], 'autoplay_output', 'OSvid-settings', 'OSvid_defaults');
	add_settings_field('OSvid_loop', $metafield['OSvid_loop'], 'loop_output', 'OSvid-settings', 'OSvid_defaults');
	add_settings_field('OSvid_player', 'select player', 'player_output', 'OSvid-settings', 'OSvid_defaults');
	// config BASE Youtube specific
	add_settings_section('OSvid_defaults_Youtube', 'Embed video Settings (Youtube)', 'defaults_output', 'OSvid-settings');
	add_settings_field('OSvid_yt-vjs','Play Youtube videos through videojs <b>[experimental, not recommended]</b>', 'yt_vjs_output', 'OSvid-settings', 'OSvid_defaults_Youtube');
	add_settings_field('OSvid_https', $metafield['OSvid_https'], 'yt_https_output', 'OSvid-settings', 'OSvid_defaults_Youtube');
	add_settings_field('OSvid_html5', $metafield['OSvid_html5'], 'yt_html5_output', 'OSvid-settings', 'OSvid_defaults_Youtube');
	add_settings_field('OSvid_showinfo', $metafield['OSvid_showinfo'], 'yt_showinfo_output', 'OSvid-settings', 'OSvid_defaults_Youtube');
	add_settings_field('OSvid_related', $metafield['OSvid_related'], 'yt_related_output', 'OSvid-settings', 'OSvid_defaults_Youtube');
	add_settings_field('OSvid_logo', 'hide Youtube logo', 'yt_logo_output', 'OSvid-settings', 'OSvid_defaults_Youtube');
	// config player  ----------------------------------------------------------------------------------------------------------------------------------
	add_settings_section('OSvid_defaults_player', 'Default Settings', 'defaults_output', 'OSvid-settings-player');
	add_settings_field('OSvid_width', $metafield['OSvid_width'], 'width_output', 'OSvid-settings-player', 'OSvid_defaults_player');
	add_settings_field('OSvid_height', $metafield['OSvid_height'], 'height_output', 'OSvid-settings-player', 'OSvid_defaults_player');
	add_settings_field('OSvid_skin', 'Select Skin', 'skin_output', 'OSvid-settings-player', 'OSvid_defaults_player');
	add_settings_field('OSvid_icon', 'Select Icon set', 'icon_skin', 'OSvid-settings-player', 'OSvid_defaults_player');
	add_settings_field('OSvid_responsive', 'Responsive Video', 'responsive_output', 'OSvid-settings-player', 'OSvid_defaults_player');
	add_settings_field('OSvid_ratio', 'Responsive ratio (default: 56.25)', 'ratio_output', 'OSvid-settings-player', 'OSvid_defaults_player');
	
	add_settings_field('OSvid_controlbar_position', 'Control Bar Position ( attached)', 'controlbar_position', 'OSvid-settings-player', 'OSvid_defaults_player');
	add_settings_field('OSvid_color_one', 'Icon Color', 'color_one_output', 'OSvid-settings-player', 'OSvid_defaults_player');
	add_settings_field('OSvid_color_two', 'Bar Color', 'color_two_output', 'OSvid-settings-player', 'OSvid_defaults_player');
	add_settings_field('OSvid_color_three', 'Background Color', 'color_three_output', 'OSvid-settings-player', 'OSvid_defaults_player');
	
}
	
/* Verifica  e scrive nel database i dati in input */
function OSvid_sanitize_options($input) {
	$new_input['OSvid_cdn'] = $input['OSvid_cdn'];
	$new_input['OSvid_preload'] = $input['OSvid_preload'];
	$new_input['OSvid_feat'] = $input['OSvid_feat'];
	$new_input['OSvid_player'] = $input['OSvid_player'];
	$new_input['OSvid_autoplay'] = $input['OSvid_autoplay'];
	$new_input['OSvid_loop'] = $input['OSvid_loop'];
	$new_input['OSvid_yt-vjs'] = $input['OSvid_yt-vjs'];
	$new_input['OSvid_https'] = $input['OSvid_https'];
	$new_input['OSvid_html5'] = $input['OSvid_html5'];
	$new_input['OSvid_showinfo'] = $input['OSvid_showinfo'];
	$new_input['OSvid_related'] = $input['OSvid_related'];
	$new_input['OSvid_logo'] = $input['OSvid_logo'];	
	
	//$new_input['OSvid_reset'] = $input['OSvid_reset'];
	//$new_input['OSvid_video_shortcode'] = $input['OSvid_video_shortcode'];
	return $new_input;
}

function OSvid_sanitize_options_player($input) {	
	$new_input['OSvid_height'] = $input['OSvid_height'];
	$new_input['OSvid_width'] = $input['OSvid_width'];
	$new_input['OSvid_responsive'] = $input['OSvid_responsive'];
	$new_input['OSvid_ratio'] = $input['OSvid_ratio'];
	$new_input['OSvid_skin'] = $input['OSvid_skin'];
	$new_input['OSvid_controlbar_position'] = $input['OSvid_controlbar_position'] ;
	$new_input['OSvid_color_one'] = $input['OSvid_color_one'];
	$new_input['OSvid_color_two'] = $input['OSvid_color_two'];
	$new_input['OSvid_color_three'] = $input['OSvid_color_three'];
	
	if(!preg_match("/^\d+$/", trim($new_input['OSvid_width']))) {	 $new_input['OSvid_width'] = ''; }	 
	if(!preg_match("/^\d+$/", trim($new_input['OSvid_height']))) { $new_input['OSvid_height'] = ''; }	 
	if(!preg_match("/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/", trim($new_input['OSvid_color_one']))) { $new_input['OSvid_color_one'] = '#ccc';	 }	 
	if(!preg_match("/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/", trim($new_input['OSvid_color_two']))) { $new_input['OSvid_color_two'] = '#66A8CC'; }	 
	if(!preg_match("/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/", trim($new_input['OSvid_color_three']))) { $new_input['OSvid_color_three'] = '#000'; }
	
	return $new_input;
}

/* Display the input fields */
function defaults_output() {
	//echo 'change saved, Thank You!';
}

// funzioni per sezione generale ----------------------------------------------------------------------------------------------------
function cdn_output() {
	$options = get_option('OSvid_options');
	if($options['OSvid_cdn']) { $checked = ' checked="checked" '; } else { $checked = ''; }
	echo "<input ".$checked." id='OSvid_cdn' name='OSvid_options[OSvid_cdn]' type='checkbox' />";
}
function preload_output() {
	$options = get_option('OSvid_options');
	if($options['OSvid_preload']) { $checked = ' checked="checked" '; } else { $checked = ''; }
	echo "<input ".$checked." id='OSvid_preload' name='OSvid_options[OSvid_preload]' type='checkbox' />";
}
function player_output() {
	$options = get_option('OSvid_options');
	/*
	if(array_key_exists('OSvid_player', $options)){
		if($options['OSvid_player']) { $checked = ' checked="checked" '; } else { $checked = ''; }
	} else { $checked = ' checked="checked" '; }
	*/

	echo "<select name='OSvid_options[OSvid_player]'>
	<option value='videojs' ";
	if($options['OSvid_player']=='videojs') echo "selected='selected' ";
		echo  ">videojs</option>";
		echo "<option value='mediaelement' ";
	if($options['OSvid_player']=='mediaelement') echo "selected='selected' ";
	echo ">mediaelement</option></select>";
	
}
function yt_vjs_output() {
	$options = get_option('OSvid_options');
	if($options['OSvid_yt-vjs']) { $checked = ' checked="checked" '; } else { $checked = ''; }
	echo "<input ".$checked." id='OSvid_yt-vjs' name='OSvid_options[OSvid_yt-vjs]' type='checkbox' />";
}
function feat_output(){
	$options = get_option('OSvid_options');
	if($options['OSvid_feat']) { $checked = ' checked="checked" '; } else { $checked = ''; }
	echo "<input ".$checked." id='OSvid_feat' name='OSvid_options[OSvid_feat]' type='checkbox' />";
}
function autoplay_output() {
	$options = get_option('OSvid_options');
	if($options['OSvid_autoplay']) { $checked = ' checked="checked" '; } else { $checked = ''; }
	echo "<input ".$checked." id='OSvid_autoplay' name='OSvid_options[OSvid_autoplay]' type='checkbox' />";
}
function loop_output(){
	$options = get_option('OSvid_options');
	if($options['OSvid_loop']) { $checked = ' checked="checked" '; } else { $checked = ''; }
	echo "<input ".$checked." id='OSvid_loop' name='OSvid_options[OSvid_loop]' type='checkbox' />";	
}
function yt_https_output(){
	$options = get_option('OSvid_options');
	if($options['OSvid_https']) { $checked = ' checked="checked" '; } else { $checked = ''; }
	echo "<input ".$checked." id='OSvid_https' name='OSvid_options[OSvid_https]' type='checkbox' />";	
}
function yt_html5_output(){
	$options = get_option('OSvid_options');
	if($options['OSvid_html5']) { $checked = ' checked="checked" '; } else { $checked = ''; }
	echo "<input ".$checked." id='OSvid_html5' name='OSvid_options[OSvid_html5]' type='checkbox' />";	
}
function yt_showinfo_output(){
	$options = get_option('OSvid_options');
	if($options['OSvid_showinfo']) { $checked = ' checked="checked" '; } else { $checked = ''; }
	echo "<input ".$checked." id='OSvid_showinfo' name='OSvid_options[OSvid_showinfo]' type='checkbox' />";	
}
function yt_related_output(){
	$options = get_option('OSvid_options');
	if($options['OSvid_related']) { $checked = ' checked="checked" '; } else { $checked = ''; }
	echo "<input ".$checked." id='OSvid_related' name='OSvid_options[OSvid_related]' type='checkbox' />";
}
function yt_logo_output(){
	$options = get_option('OSvid_options');
	if($options['OSvid_logo']) { $checked = ' checked="checked" '; } else { $checked = ''; }
	echo "<input ".$checked." id='OSvid_logo' name='OSvid_options[OSvid_logo]' type='checkbox' />";	
}
// funzioni per sezione player  ----------------------------------------------------------------------------------------------------
function height_output() {
	$options_player = get_option('OSvid_options_player');
	echo "<input id='OSvid_height' name='OSvid_options_player[OSvid_height]' size='4' type='text' value='{$options_player['OSvid_height']}' /> px";
}
function width_output() {
	$options_player = get_option('OSvid_options_player');
	echo "<input id='OSvid_width' name='OSvid_options_player[OSvid_width]' size='4' type='text' value='{$options_player['OSvid_width']}' /> px";
}
function responsive_output() {
	$options_player = get_option('OSvid_options_player');
	if($options_player['OSvid_responsive'])  $checked = ' checked="checked" ';  else  $checked = ''; 
	echo "<input ".$checked." id='OSvid_responsive' name='OSvid_options_player[OSvid_responsive]' type='checkbox' />";
}
function ratio_output(){
	$options_player = get_option('OSvid_options_player');
	echo "<input id='OSvid_ratio' name='OSvid_options_player[OSvid_ratio]' size='4' type='text' value='{$options_player['OSvid_ratio']}' /> %";
}
function skin_output() {
	global $plugin_dir;
	$options_player = get_option('OSvid_options_player');
	$options = get_option('OSvid_options');
	
	$path = $plugin_dir.'/player/'.$options['OSvid_player'].'/skin';
	$arr_skin=OSvid_dir_array($path);
	// rimuove le estensioni dai nomi file dell'array
	foreach ($arr_skin as $kk=>$val){
			$arr_skin[$kk] = preg_replace('/\.[^.]+$/','',$val);
	}
	// select
	echo "<select name='OSvid_options_player[OSvid_skin]'>";
	foreach ($arr_skin as $key=>$value) {
		echo '<option value="'.$value.'" ';
		if($options_player['OSvid_skin']==$value) echo "selected='selected' ";
		echo  ">$value</option>";
	}
	echo "</select>";
}
function controlbar_position(){
	$options_player = get_option('OSvid_options_player');
	if($options_player['OSvid_controlbar_position'])  $checked = ' checked="checked" ';  else  $checked = ''; 
	echo "<input ".$checked." id='OSvid_controlbar_position' name='OSvid_options_player[OSvid_controlbar_position]' type='checkbox' />";
}
function icon_skin(){
	$options_player = get_option('OSvid_options_player');
	echo "<select name='OSvid_options_player[OSvid_icon]'>
	<option value='icon1' ";
	if($options_player['OSvid_icon']=='icon1') echo "selected='selected' ";
	echo  ">icon set 1</option>";
	// echo "<option value='icon2' ";
	// if($options_player['OSvid_icon']=='icon2') echo "selected='selected'"; echo" >icon set 2</option>";
	echo "</select>";
}
function color_one_output() {
	$options_player = get_option('OSvid_options_player');
	echo "<input id='OSvid_color_one' name='OSvid_options_player[OSvid_color_one]' size='40' type='text' value='{$options_player['OSvid_color_one']}' data-default-color='#ccc' class='OSvid-color-field' />";
}

function color_two_output() {
	$options_player = get_option('OSvid_options_player');
	echo "<input id='OSvid_color_two' name='OSvid_options_player[OSvid_color_two]' size='40' type='text' value='{$options_player['OSvid_color_two']}' data-default-color='#66A8CC' class='OSvid-color-field' />";
}
function color_three_output() {
	$options_player = get_option('OSvid_options_player');
	echo "<input id='OSvid_color_three' name='OSvid_options_player[OSvid_color_three]' size='40' type='text' value='{$options_player['OSvid_color_three']}' data-default-color='#000' class='OSvid-color-field' />";
}

/* Plugin Updater */
function OSvid_update() {
	$OSvid_db_version = "1.0";
	// echo '----->'. get_option("OSvid_db_version");
	if( get_option("OSvid_db_version") != $OSvid_db_version ) { //We need to update our database options
		$options = get_option('OSvid_options');
		$options_player = get_option('OSvid_options_player');
		
		/*Set the new options to their defaults
		$options_player['OSvid_color_one'] = "#ccc";
		$options_player['OSvid_color_two'] = "#66A8CC";
		$options_player['OSvid_color_three'] = "#000";
		$options_player['OSvid_controlbar_position'] = "on";
		*/ 
		
		update_option('OSvid_options', $options);	
		update_option('OSvid_options_player', $options_player);	
		update_option("OSvid_db_version", $OSvid_db_version); //Update the database version setting
	}
}

/* QUI C'è UN ERRORE: LE DUE FUNZIONI NON VANNO DIVISE
function OSvid_update_player() {
	$OSvid_db_version = "1.0";
	echo $OSvid_db_version . '-----'. get_option("OSvid_db_version");
	if( get_option("OSvid_db_version") != $OSvid_db_version ) { //We need to update our database options
		update_option("OSvid_db_version", $OSvid_db_version); //Update the database version setting
	}
}
*/
 
// RESET functions ----------------------------------------------------------------------------------------
function OSvid_options_reset(){
	
	$arr = array (
		'OSvid_cdn' => '',
		'OSvid_preload' => '',
		'OSvid_feat' => '',
		'OSvid_player' => 'videojs',
		'OSvid_yt-vjs' => '',
		'OSvid_autoplay' => '',
		'OSvid_loop' => '',
		'OSvid_https' => '',
		'OSvid_html5' => '',
		'OSvid_showinfo' => '',
		'OSvid_related' => '',
		'OSvid_logo' => '',
	);	
	
	update_option('OSvid_options', $arr);
}

function OSvid_options_player_reset(){
	
	$arr = array (
		'OSvid_width' => '512',
		'OSvid_height' => '258',
		'OSvid_skin' => 'OS-skin',
		'OSvid_icon' => '',
		'OSvid_responsive' => 'on',
		'OSvid_ratio' => '56.25', // default ratio 16:9
		'OSvid_controlbar_position' => 'on',
		'OSvid_color_one' => '#ccc',
		'OSvid_color_two' => '#66A8CC',
		'OSvid_color_three' => '#000'
	);	
	
	update_option('OSvid_options_player', $arr);
}

/* Useful Functions */
function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
}

?>
