<?php

use OSmedia_browser\Browser;

if ( ! class_exists( 'OSmedia_Post_Frontend' ) ) {

	/**
	 * generate videoplayer 
	 */
	class OSmedia_Post_Frontend extends OSmedia_Module  {

		// public static $OSmedia_postmeta = array();

		// private static $options = array();

		public static $video_ext = array('mp4', 'ogg', 'ogv', 'webm'); // Arrays are not allowed in class constants in PHP :(

		/////////////////////////////// Magic methods ////////////////////////////////////

		/**
		 * Constructor
		 *
		 * @mvc Controller
		 */
		protected function __construct() {

			$this->register_hook_callbacks();

		}


		/////////////////////////////// Static methods ////////////////////////////////////

		/**
		 * Metodo per il confronto dei parametri del singolo post (meta), con quelli generali (option). 
		 * Se il post è nuovo (is_edit_page('new')) i parametri meta INIZIALI sono pescati dalle options generali. 
		 * Se si edita un post esistente sono pescati dai suoi custom field
		 *
		 * @mvc Model 
		 *
		 * @return array $out
		 * 
		 */
		public static function OSmedia_data_model() {
			global $post;		
			$post_id = $post->ID;

			// get_option and flat array 
			// foreach( new RecursiveIteratorIterator (new RecursiveArrayIterator( get_option('OSmedia_settings') )) as $k1=>$v1 )  $options[$k1] = $v1;
			$out = $options = self::$OSmedia_options;
			// $out = $options;
			// get_post_meta and filter the params
			if( get_post_type( $post_id ) == POST_TYPE_SLUG )
				$meta_values = OSmedia_Post_Admin::postmeta_vars_filter( get_post_meta( $post_id ) );
			else
				$meta_values = self::$OSmedia_postmeta;

			// per ogni OPTIONS pesca il valore del postmeta con la stessa chiave (prevalgono i postmeta sulle options)
			foreach ( $options as $k => $v ) { 
				if ( array_key_exists($k, $meta_values ) )
					$out[$k] = $meta_values[$k]; 
					// echo '<br>'.$k.'-'.$out[$k];
			}
			// aggiungo i POSTMETA con le chiavi che NON ci sono già
			foreach ( $meta_values as $k_meta => $v_meta ) {						
				if ( !array_key_exists($k_meta, $out) ) 
					$out[$k_meta] = $v_meta;
			}

			// echo '<pre> DATA_MODEL: post_id->'.$post_id.'  ';var_dump($out);  echo '</pre>';
			return $out;
		}

		/**
		 * Defines the video-tag
		 *
		 * @mvc Controller
		 *
		 * @param array $attributes
		 * return string
		 */
		public static function get_videoplayer( $atts = null ) {			

			$atts = self::validate_attributes( $atts );
			
			// view controller
			if ( $atts['type'] == 'html5' && (Browser::isIphone() || Browser::isAndroid()) ) { 					// 1. mobile (iOS and Android) HTML5
				$view = 'frontend/mobile.php';  
			}elseif( $atts['type'] == 'html5' && (Browser::isIE6() || Browser::isIE7() || Browser::isIE8()) ) { // 2. fallback
				$view = 'frontend/fallback.php';  
			}elseif( $atts['type'] == 'youtube' ) {  															// 3. Youtube
				$view = 'frontend/youtube.php'; 
			}elseif( $atts['type'] == 'vimeo' ) {  																// 4. Vimeo
				$view = 'frontend/vimeo.php'; 
			}else{ 																								// 5. standard HTML5
				$view = 'frontend/standard.php'; 
			}
			
			// MONITOR
			// echo '<pre style="font-size:12px"> ---->GET_VIDEOPLAYER: '; var_dump($atts); echo '</pre>'; 

			if( $atts['type'] ) return self::render_template( $view, $atts, 'always' ); // 'always' = accetta istanze multiple
		}

		/**
		 * Validates the attributes for the CPT meta values & Post shortcode
		 *
		 * NOTA: gli shortcode arrivano come parametro QUANDO SONO PASSATI DAL register_hook_callbacks (add_shortcode),
		 *	NON quando siamo in contesto CPT
		 *
		 * @mvc Model
		 *
		 * @param array $attributes
		 * return array
		 */
		public static function validate_attributes( $shortcode ) {
			global $post;
			$options = self::$OSmedia_options;
			if( get_post_type($post->ID) == POST_TYPE_SLUG ) $cpt_flag = true; else $cpt_flag = false;

			//////////////// youtube retrocompatibilità
			$page_id = get_queried_object_id();	$page_object = get_page( $page_id );
			if ( has_shortcode($page_object->post_content, 'youtube') ) $flag_old_yt_shortcode = true; else $flag_old_yt_shortcode = false;
			// echo '----------->>>>>>>'.$page_object->post_content.'-----xxxx--------'.$flag_old_yt_shortcode;
			//////////////////////////////////////////// 
				
			if ( $cpt_flag ) $shortcode = null; 	// azzera eventuali shortcode nei CPT
			// echo '<pre> SHORTCODE:'; var_dump($shortcode); echo '</pre>';

			$base_atts = self::OSmedia_data_model(); 	// carica i dati e li elabora pescanda dai postmeta (solo CPT)
			// echo '<pre> BASE_ATTS:'; var_dump($base_atts); echo '</pre>';

			// remove prefix keys array
			foreach ($base_atts as $k => $v) {
				$k_new = str_replace('OSmedia_', '', $k);
				$base_atts[$k_new] = $v; 
				unset($base_atts[$k]);
			}

			// add SHORTCODE values (in CPT do nothing)
			$atts = shortcode_atts( $base_atts, $shortcode ); 

			// uniform boolean values
			foreach ($atts as $kk => $vv)
				if ($atts[$kk] == "true" || $atts[$kk] == "on" || $atts[$kk] == "yes" ) 
					$atts[$kk] = 'true';

			$atts['id_player'] = 'OSmedia_video_id_'.rand(); // different ID are required for multiple videos 

			// FILE controller (HTML5)
			if ( isset($atts['file']) ) $file = $atts['file']; else $file = '';
			if ( isset($atts['fileurl']) ) $url = $atts['fileurl']; else $url = '';
			
			if( $file != '' && $url == '' ) 	// local file 
				$filepath = plugins_url( OSmedia_FOLDER ) . "/videostream.php?file="; 
			elseif( $file != '' && $url != '' ) // remote file
				$filepath = $url; 
			else 								// no filepath (direct url)
				$filepath = ''; 

			if( $file && $filepath ) {
				$atts['mp4'] 	= $filepath . $file . ".mp4";
				$atts['webm'] 	= $filepath . $file . ".webm";
				$atts['ogg'] 	= $filepath . $file . ".ogg";
			// WP media library
			}
			// $atts['filepath'] = $filepath;

			// TYPE controller
			// default fallback HTML5 -> Youtube -> Vimeo (se nessuno dei 3 casi viene individuato il rendering dei tag video NON avviene)
			if( !empty($atts['vimeo']) ) $atts['type'] ='vimeo';
			if( !empty($atts['youtube']) || $flag_old_yt_shortcode ) $atts['type'] = 'youtube'; // con retrocompatibilità (SC "youtube")
			if( !empty($file) || !empty($atts['mp4']) || !empty($atts['webm']) || !empty($atts['ogg'])) $atts['type'] ='html5';
			// fallback
			if ( isset($atts['fallback1']) && (($atts['fallback1'] == "true" || $atts['fallback1'] == "on")) AND (!empty($atts['youtube']) || !empty($atts['vimeo'])) )  {
				if ( isset($atts['fallback2']) && (($atts['fallback2'] == "true" || $atts['fallback2'] == "on" )) AND !empty($atts['vimeo'])) {
					if( !empty($atts['vimeo']) ) $atts['type'] ='vimeo';
				}else{
					if( !empty($atts['youtube']) ) $atts['type'] ='youtube';
				}
			}
			if ( isset($atts['fallback2']) && (($atts['fallback2'] == "true" || $atts['fallback2'] == "on" )) AND !empty($atts['vimeo']) AND !$atts['fallback1'] ) {
				if( !empty($atts['vimeo']) ) $atts['type'] ='vimeo';
			}	

			// poster image controller
			// CPT
			if( $cpt_flag ){
				// if ( $file && $filepath && $file != '' && $filepath != '' && @getimagesize($filepath . $file . ".jpg") ) $atts['img'] = $filepath . $file. ".jpg";
				$post_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
				if( isset($post_thumbnail[0]) ) $atts['img'] = $post_thumbnail[0]; 
				else $atts['img'] = $filepath . $file. ".jpg";
			}elseif( isset($atts['yt_vjs']) && $atts['yt_vjs'] =='true' && isset($atts['type']) && $atts['type'] == 'youtube'){
				$atts['img'] = 'img'; // temporary trick
			// POST
			}else{
				if ( (!isset($atts['img']) || $atts['img'] == '' ) && ( $filepath && $file ) )
					$atts['img'] = $filepath . $file. ".jpg"; 
				// elseif ( !isset($atts['img'] ) $atts['img'] = '';
			}		

			// start time (total seconds)
			if ( (isset($atts['start_m']) && $atts['start_m'] != '') || (isset($atts['start_s']) && $atts['start_s'] != '') ) 
				$atts['start'] = (60*$atts['start_m']) + $atts['start_s']; 
			else 
				$atts['start'] = 0; 

			// size & resp.
			if( !isset($atts['width']) || $atts['width'] == '' ){
				$atts['width'] = $options['OSmedia_width'];
				if( !isset($atts['height']) || $atts['height'] == '' ) $atts['height'] = $options['OSmedia_height'];
			}
			
			if( (isset($atts['responsive']) && $atts['responsive'] == "true") || get_post_type($post->ID) == POST_TYPE_SLUG )
				$atts['responsive'] = "true"; 
			else 
				$atts['responsive'] = "false";

			// controlli player
			if ( isset($atts['preload']) && $atts['preload'] == "true" ) $atts['preload_atts'] = 'preload="true"'; else $atts['preload_atts'] = 'preload="auto"';
			if ( isset($atts['loop']) && $atts['loop'] == "true" ) $atts['loop_atts'] = 'loop';
			if ( isset($atts['autoplay']) && $atts['autoplay'] == "true" ) $atts['autoplay_atts'] = 'autoplay';
			if ( isset($atts['controls']) && $atts['controls'] == "true" ) $atts['controls_atts'] = ''; 
				else $atts['controls_atts'] = 'controls';	

			if (isset($atts['color3']) ) $atts['color3'] = self::OSmedia_hex2RGB($atts['color3']);

			// youtube		
			if ( $flag_old_yt_shortcode && isset($atts['id']) && $atts['id'] != '' ) $atts['youtube'] = $atts['id']; // youtube retrocompatibilità 
			$atts['youtube_source'] = self::get_youtube_source( $atts );

			// flag CPT
			$atts['flag_cpt'] = $cpt_flag;

			// return apply_filters( 'OSmedia_validate_shortcode_attributes', $atts ); 
			return $atts;
		}

		/**
		 * return youtube video ID
		 *
		 * @mvc Model
		 *
		 * @return string
		 */
		protected static function get_youtube_source( $atts ) {

			extract( $atts );

			if ( !isset($youtube) || $youtube == '') return; // ID youtube (required)
			$id_player = 'example_video_id_'.rand();
			// options
			if ( isset($autoplay) && $autoplay == "true") 	$autoplay_attribute = "autoplay=1&"; else $autoplay_attribute = "";
			if ( isset($loop) && $loop == "true")		$loop_attribute = "loop=1&playlist=".$youtube."&"; else $loop_attribute = "";
			if ( isset($yt_https) && $yt_https == "true")	$https_attribute = "https=1&"; else $https_attribute = "";
			if ( isset($yt_html5) && $yt_html5 == "true")	$html5_attribute = "html5=1&"; else $html5_attribute = "";
			if ( isset($yt_info) && $yt_info == "true") 	$showinfo_attribute = "showinfo=0&"; else $showinfo_attribute = "";
			if ( isset($yt_related) && $yt_related == "true") 	$related_attribute = "rel=0&"; else $related_attribute = "";
			if ( isset($yt_logo) && $yt_logo == "true") 	$logo_attribute = "modestbranding=0&"; else $logo_attribute = "";
			if ( $start_m || $start_s ) {
				$start=(60*$start_m) + $start_s; // total seconds
				$start_attribute = "start=".$start."&";
			}else{
				$start_attribute = "";
			};

			$youtube_source = "{$youtube}?{$autoplay_attribute}{$related_attribute}{$showinfo_attribute}{$html5_attribute}{$logo_attribute}{$loop_attribute}{$start_attribute}";
			// è valido anche il seguente codice!!! (solo scopo dimostrativo):
			// {$youtube_source = "$youtube?$autoplay_attribute$related_attribute$showinfo_attribute$html5_attribute$logo_attribute$loop_attribute$start_attribute";}

			return $youtube_source;
		}

		/**
		* metodo per convertire codici colore da hex a rgb
		*
		* @mvc @model
		*
		* @param string $hexStr input folder path 
		* @param string $returnAsString search extension (null=search all ext)
		* @param string $seperator remove ext and double values in output array
		* @return array
		*/
		public static function OSmedia_hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
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

		/**
		 * Force the Dedicated Template: combina i CPT con un template dedicato [MM2015]
		 *
		 * @mvc Model
		 *
		 * @param string $template_path 
		 * @return string
		 */
		public static function include_template_function( $template_path ){
			global $post;			
			if( get_post_type($post->ID) == POST_TYPE_SLUG )  {
			// echo '<br><br><br><br><br><br>------------------'.
			$layout = wp_get_theme();
				switch ( $layout ) {
					// case 'Twenty Thirteen': $layout_slug = POST_TYPE_SLUG . '-twentythirteen.php'; break;
					case 'Twenty Fourteen': $layout_slug = POST_TYPE_SLUG . '-twentyfourteen.php'; break;
					case 'Twenty Fifteen': 	$layout_slug = POST_TYPE_SLUG . '-twentyfifteen.php'; break;
					case 'OS Theme': 	$layout_slug = POST_TYPE_SLUG . '-theme.php'; break;
					default: $layout_slug = POST_TYPE_SLUG . '.php'; break;
				}
				// if ( is_single($post->ID) ) {
				    // checks if the file exists in the theme first, otherwise serve the file from the plugin
				    // if ( $theme_file = locate_template( array($layout_slug) ) ) {
				    //    $template_path = $theme_file;
				    $template_path = OSmedia_PATH . 'layout/' . $layout_slug;
				// }
			}
			return $template_path;
		}

		//////////////////////////////////////// Instance methods //////////////////////////////////////////////////

		/**
		 * Register callbacks for actions and filters
		 *
		 * @mvc Controller
		 */
		public function register_hook_callbacks() {
			// global $post; // NON FUNZIONA: DA CAPIRE PERCHÈ
			//////////////////// CPT & POST

			add_action( 'init', array( $this, 			'init' ) );

			// RIMOSSO PER AVERE MAGGIORE CONTROLLO NEL TEMPLATE: NEI CPT IL VIDEOPLAYER È GENERATO DELLA FUNZIONE OSmedia_videoplayer()
			// add_action( 'get_template_part_content', 	__CLASS__ . '::get_videoplayer' );
			// soluz. provvisoria
			if( isset(self::$OSmedia_options['OSmedia_shortcode']) ) 
				add_shortcode( self::$OSmedia_options['OSmedia_shortcode'], 	__CLASS__ . '::get_videoplayer' ); 
			// RETRO-COMPATIBILITA'
			add_shortcode( 'youtube', 	__CLASS__ . '::get_videoplayer' );

			add_filter( 'template_include', __CLASS__ . '::include_template_function', 1, 2);

		}

		/**
		 * Prepares site to use the plugin during activation
		 *
		 * @mvc Controller
		 *
		 * @param bool $network_wide
		 */
		public function activate( $network_wide ) {
		}

		/**
		 * Rolls back activation procedures when de-activating the plugin
		 *
		 * @mvc Controller
		 */
		public function deactivate() {
		}

		/**
		 * Initializes variables
		 *
		 * @mvc Controller
		 */
		public function init() {
			// OSmedia_Post_Admin::get_options();
			OSmedia_Post_Admin::get_metabox_params();
		}

		/**
		 * Executes the logic of upgrading from specific older versions of the plugin to the current version
		 *
		 * @mvc Model
		 *
		 * @param string $db_version
		 */
		public function upgrade( $db_version = 0 ) {
			/*
			if( version_compare( $db_version, 'x.y.z', '<' ) )
			{
				// Do stuff
			}
			*/
		}

		/**
		 * Checks that the object is in a correct state
		 *
		 * @mvc Model
		 *
		 * @param string $property An individual property to check, or 'all' to check all of them
		 *
		 * @return bool
		 */
		protected function is_valid( $property = 'all' ) {
			return true;
		}

		
	} // end OSmedia_post_frontend
}