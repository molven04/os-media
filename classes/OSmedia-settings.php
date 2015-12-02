<?php

if ( ! class_exists( 'OSmedia_Settings' ) ) {

	/**
	 * Handles plugin settings and user profile meta fields
	 */
	class OSmedia_Settings extends OSmedia_Module {

		protected $settings;
		protected static $default_settings;

		protected static $readable_properties  = array( 'settings' );
		protected static $writeable_properties = array( 'settings' );

		const REQUIRED_CAPABILITY = 'manage_options';
		const OPTS = OSmedia_OPTS;
		const OPTS_OLD1 = 'OSvid_options';
		const OPTS_OLD2 = 'OSvid_options_player';

		/*
		 * General methods
		 */

		/**
		 * Constructor
		 *
		 * @mvc Controller
		 */
		protected function __construct() {

			$this->register_hook_callbacks();

		}


		/**
		 * Public setter for protected variables
		 *
		 * Updates settings outside of the Settings API or other subsystems
		 *
		 * @mvc Controller
		 *
		 * @param string $variable
		 * @param array  $value This will be merged with OSmedia_Settings->settings, so it should mimic the structure of the OSmedia_Settings::$default_settings. It only needs the contain the values that will change, though. See OSmedia_base->upgrade() for an example.
		 */
		public function __set( $variable, $value ) {
			// Note: OSmedia_Module::__set() is automatically called before this

			if ( $variable != self::OPTS ) return;

			$this->settings = self::validate_settings( $value );

			/* ATTENZIONE: riga di seguito da ri-considerare. Tolta perchè salvava le options di default semplicemente aggiornando la pagina 
			la ho tolta per garantire il funzionamento della retrocompatibilità
			scrive le option solo se non esistono già (DEFAULT OPTS) 
			SPOSTATO IN FASE DI ATTIVAZIONE => metodo "activate"
			*/

			update_option( self::OPTS, $this->settings );

		}

		/**
		 * Register callbacks for actions and filters
		 *
		 * @mvc Controller
		 */
		public function register_hook_callbacks() {

			add_action( 'admin_menu',               __CLASS__ . '::register_settings_pages' );
			// add_action( 'show_user_profile',        __CLASS__ . '::add_user_fields' );
			// add_action( 'edit_user_profile',        __CLASS__ . '::add_user_fields' );
			// add_action( 'personal_options_update',  __CLASS__ . '::save_user_fields' );
			// add_action( 'edit_user_profile_update', __CLASS__ . '::save_user_fields' );
			add_action( 'init',                     array( $this, 'init' ) );
			add_action( 'admin_init',               array( $this, 'register_settings' ) );
			// function wrapper to call method [MM2015]
			
			add_filter( 'filter_old_vars', 			__CLASS__ . '::merge_old_vars' );
			add_filter(
				'plugin_action_links_' . plugin_basename( dirname( __DIR__ ) ) . '/bootstrap.php',
				__CLASS__ . '::add_plugin_action_links'
			);

		}
		
		// 
		/**
		* funzione ricorsiva per creare un array col contenuto di una lsta di file di una directory [MM2015]
		* tale contenuto potrà aggiungersi a quello di una specifica directory di un server S3 (Simple Storage Server)
		* in base ai dati della configurazione presi dalle Options
		*
		* @mvc @model
		*
		* @param string $dir input local folder path or URL !
		* @param array $search_ext search extension (null=search all ext)
		* @param boolean $sanitize remove ext and double values in output array
		* @return array 
		*/
		protected static function dir_list( $dir = null, $search_ext = null, $sanitize = true ){ 

			$files = array(); // $files = scandir($dir);	

			$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator( $dir ));
			
			foreach ($rii as $file) {
			    if ($file->isDir()) continue;			     
			    $files[] = $file->getFilename(); // $files[] = $file->getPathname();
			}	   	

			// filtra i risultati togliendo i file di estensione non richiesta, poi le estensioni e poi gli elementi con lo stesso valore
			if ( !empty($files) && $sanitize ) {
				foreach ($files as $key => $value)  { 
				   	if ( !empty($search_ext) && is_array($search_ext) ){
						foreach ($search_ext as $ext) {
							if ( strpos($value, '.'.$ext) !== false) {								
								$value = preg_replace('/\.[^.]+$/','',$value); // toglie l'estensione
								$result[] = $value; 
							}
						}
					}
				}
			}
			// echo '<pre>'; print_r($result); echo '</pre>'; // MONITOR
			return $result; 
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
			// opts
			self::$default_settings = self::get_default_settings();
			$this->settings = self::get_settings();
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
		 * @return bool
		 */
		protected function is_valid( $property = 'all' ) {
			// Note: __set() calls validate_settings(), so settings are never invalid

			return true;
		}


		/*
		 * Plugin Settings
		 */

		/**
		 * Establishes initial values for all settings
		 *
		 * @mvc Model
		 *
		 * @return array
		 */
		public static function get_default_settings() {
			$opts = array( 
				'OSmedia_path' 		=> ABSPATH . 'wp-content/uploads/',
				'OSmedia_url' 		=> '' ,
				'OSmedia_autoplay'	=> 'off' ,
				'OSmedia_loop'		=> 'off' ,				
				'OSmedia_preload'	=> 'off' ,
				'OSmedia_player'	=> 'videojs',
				'OSmedia_shortcode'	=> 'video',
				'OSmedia_fallback1' => 'off' ,
				'OSmedia_fallback2' => 'off' ,

				'OSmedia_s3enable'  => '' ,
				'OSmedia_s3server'  => '' ,
				'OSmedia_s3access'  => '' ,
				'OSmedia_s3secret'  => '' ,
				'OSmedia_s3bucket'  => '' ,
				'OSmedia_s3dir'  	=> '' ,

				'OSmedia_yt_vjs'	=> '' ,
			   	'OSmedia_yt_https' 	=> '' ,
			   	'OSmedia_yt_html5' 	=> '' ,
			    'OSmedia_yt_info'	=> 'on' ,
			   	'OSmedia_yt_related'=> 'on' ,
				'OSmedia_yt_logo' 	=> 'on' ,

				'OSmedia_width'		=> '720' ,
				'OSmedia_height'	=> '405' ,				
				'OSmedia_skin'		=> 'OS-skin' ,
				'OSmedia_responsive'=> 'on' ,
				'OSmedia_ratio'  	=> 'vjs-16-9' ,
				'OSmedia_color1'	=> '#fff' ,
			   	'OSmedia_color2' 	=> '#fff' ,
			   	'OSmedia_color3' 	=> '#2b333f',
			   	'OSmedia_db-version'=> OSmedia_base::VERSION
			);

			return $opts;
		}

		/**
		 * Retrieves all of the settings from the database
		 *
		 * @mvc Model
		 *
		 * @return array
		 */
		protected static function get_settings() {
			
			$settings = shortcode_atts(
				self::$default_settings,
				get_option( OSmedia_OPTS, array() )
			);

			return $settings;
/*
			$base_opts = self::get_default_settings();
			$opts = get_option( OSmedia_OPTS, array() );
			// define index for null parameters
			foreach( $base_opts as $k => $v ) {
				// if( is_array($v) ) {
					// foreach ( $v as $kk => $vv ) {
						if( isset($opts[$k]) ) $settings_complete[$k] = $opts[$k];
						else $settings_complete[$k] = '';
					// }
				// }
			} 
			return $settings_complete;
*/
		}

		/**
		 * Adds links to the plugin's action link section on the Plugins page
		 *
		 * @mvc Model
		 *
		 * @param array $links The links currently mapped to the plugin
		 * @return array
		 */
		public static function add_plugin_action_links( $links ) {
			array_unshift( $links, '<a href="http://wordpress.org/extend/plugins/os-media/faq/">Help</a>' );
			array_unshift( $links, '<a href="admin.php?page=' . 'OSmedia_settings">Settings</a>' );

			return $links;
		}

		/**
		 * Adds pages to the Admin Panel menu
		 *
		 * @mvc Controller
		 */
		public static function register_settings_pages() {

			add_menu_page('Video Settings', 'OSmedia conf.', self::REQUIRED_CAPABILITY, self::OPTS, __CLASS__ . '::markup_settings_page');
			
			add_submenu_page(self::OPTS, OSmedia_NAME, 'Base config', self::REQUIRED_CAPABILITY, self::OPTS, __CLASS__ . '::markup_settings_page'); 
			// add_submenu_page(self::OPTS, OSmedia_NAME, 'Player config', self::REQUIRED_CAPABILITY, 'OSmedia_settings_player', __CLASS__ . '::markup_settings_page_player'); 

		}

		/**
		 * Creates the markup for the Settings page
		 *
		 * @mvc Controller
		 */
		public static function markup_settings_page() {
			if ( current_user_can( self::REQUIRED_CAPABILITY ) ) {
				echo self::render_template( 'OSmedia-settings/page-settings.php' );
			} else {
				wp_die( 'Access denied.' );
			}
		}

		/**
		 * Creates the markup for the Settings page
		 *
		 * @mvc Controller
		 */
		public static function markup_settings_page_player () {
			if ( current_user_can( self::REQUIRED_CAPABILITY ) ) {
				echo self::render_template( 'OSmedia-settings/page-settings-player.php' );
			} else {
				wp_die( 'Access denied.' );
			}
		}

		/**
		 * Registers settings sections, fields and settings
		 *
		 * @mvc Controller
		 */
		public function register_settings() {
			//////////////////////////////
			////////////////////////////// General Basic Section
			add_settings_section('OSmedia_section_basic', 'Basic Settings', __CLASS__ . '::markup_section_headers', self::OPTS );
			add_settings_field('OSmedia_path', 'local video path', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_basic', array( 'label_for' => 'OSmedia_path' ));
			add_settings_field('OSmedia_url', 'video server URL', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_basic', array( 'label_for' => 'OSmedia_url' ));
			add_settings_field('OSmedia_shortcode', 'video shortcode', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_basic', array( 'label_for' => 'OSmedia_shortcode' ));
			add_settings_field('OSmedia_autoplay', 'autoplay', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_basic', array( 'label_for' => 'OSmedia_autoplay' ));
			add_settings_field('OSmedia_loop', 'loop', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_basic', array( 'label_for' => 'OSmedia_loop' ));
			add_settings_field('OSmedia_preload', 'preload', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_basic', array( 'label_for' => 'OSmedia_preload' ));
			add_settings_field('OSmedia_fallback1', 'fallback 1', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_basic', array( 'label_for' => 'OSmedia_fallback1' ));
			add_settings_field('OSmedia_fallback2', 'fallback 2', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_basic', array( 'label_for' => 'OSmedia_fallback2' ));
			////////////////////////////// Advanced Section (You Tube)
			add_settings_section('OSmedia_section_advanced', 'Advanced Settings (You Tube)', __CLASS__ . '::markup_section_headers', self::OPTS );
			add_settings_field('OSmedia_yt_vjs', 'Play Youtube videos through videojs', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_advanced', array( 'label_for' => 'OSmedia_yt_vjs' ));
			add_settings_field('OSmedia_yt_html5', 'use Youtube HTML5 player', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_advanced', array( 'label_for' => 'OSmedia_yt_html5' ));
			add_settings_field('OSmedia_yt_logo', 'hide Youtube logo', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_advanced', array( 'label_for' => 'OSmedia_yt_logo' ));
			add_settings_field('OSmedia_yt_https', 'use Youtube HTTPS protocol', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_advanced', array( 'label_for' => 'OSmedia_yt_https' ));
			add_settings_field('OSmedia_yt_info', 'hide Youtube info', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_advanced', array( 'label_for' => 'OSmedia_yt_info' ));
			add_settings_field('OSmedia_yt_related', 'hide Youtube related video', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_advanced', array( 'label_for' => 'OSmedia_yt_related' ));
			//////////////////////////////// Advanced Section (s3)
			add_settings_section('OSmedia_section_advanced_s3', 'Amazon Simple Storage Server (S3)', __CLASS__ . '::markup_section_headers', self::OPTS );
			add_settings_field('OSmedia_s3enable', 'enable S3', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_advanced_s3', array( 'label_for' => 'OSmedia_s3enable' ));
			add_settings_field('OSmedia_s3server', 'S3 server url', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_advanced_s3', array( 'label_for' => 'OSmedia_s3server' ));
			add_settings_field('OSmedia_s3access', 'S3 access key', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_advanced_s3', array( 'label_for' => 'OSmedia_s3access' ));
			add_settings_field('OSmedia_s3secret', 'S3 secret key', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_advanced_s3', array( 'label_for' => 'OSmedia_s3secret' ));
			add_settings_field('OSmedia_s3bucket', 'S3 bucket', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_advanced_s3', array( 'label_for' => 'OSmedia_s3bucket' ));
			add_settings_field('OSmedia_s3dir', 'S3 directory', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_advanced_s3', array( 'label_for' => 'OSmedia_s3dir' ));
			////////////////////////////// Player Section 
			add_settings_section('OSmedia_section_player', 'Player Settings', __CLASS__ . '::markup_section_headers', self::OPTS);
			add_settings_field('OSmedia_width', 'width', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_player', array( 'label_for' => 'OSmedia_width' ));
			add_settings_field('OSmedia_height', 'height', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_player', array( 'label_for' => 'OSmedia_height' ));
			add_settings_field('OSmedia_responsive', 'Responsive Video', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_player', array( 'label_for' => 'OSmedia_responsive' ));
			add_settings_field('OSmedia_ratio', 'Responsive aspect ratio', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_player', array( 'label_for' => 'OSmedia_ratio' ));
			add_settings_field('OSmedia_skin', 'Select player skin', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_player', array( 'label_for' => 'OSmedia_skin' ) );
			add_settings_field('OSmedia_color1', 'Icon Color', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_player', array( 'label_for' => 'OSmedia_color1' ));
			add_settings_field('OSmedia_color2', 'Bar Color', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_player', array( 'label_for' => 'OSmedia_color2' ));
			add_settings_field('OSmedia_color3', 'Background Color', array( $this, 'markup_fields' ), self::OPTS, 'OSmedia_section_player', array( 'label_for' => 'OSmedia_color3' ));

			////////////////////////////// The settings container 
			register_setting(self::OPTS, self::OPTS, array( $this, 'validate_settings' ) );
			// register_setting('OSmedia_settings_player', self::OPTS, array( $this, 'validate_settings_player') );				 
		}
				

		/**
		 * Adds the section introduction text to the Settings page
		 *
		 * @mvc Controller
		 *
		 * @param array $section
		 */
		public static function markup_section_headers( $section ) {

			echo self::render_template( 'OSmedia-settings/page-settings-section-headers.php', array( 'section' => $section ), 'always' );

		}

		/**
		 * Delivers the markup for settings fields
		 *
		 * @mvc Controller
		 *
		 * @param array $field
		 */
		public function markup_fields( $field ) {
			$arr_skin = array();
			switch ( $field['label_for'] ) {
				case 'OSmedia_skin':
					$arr_skin = self::dir_list ( OSmedia_PATH . 'player/videojs/skin', array('css') );
					// var_dump($arr_skin); 
				break;
				///////////////////////////////////////////////////////////////////////////////////////////////////
			}

			// $this->settings['wwpp'] = $readable_properties;

			echo self::render_template( 'OSmedia-settings/page-settings-fields.php', array( 
										'settings' => $this->settings, 
										'field' => $field, 
										// 'options' => get_option('OSmedia_options'), 
										// 'options_player' => get_option('OSmedia_options_player'), 
										'arr_skin'=> $arr_skin), 
				'always' 
			);			
		}

		/**
		 * Validates submitted setting values before they get saved to the database. Invalid data will be overwritten with defaults.
		 *
		 * @mvc Model
		 *
		 * @param array $new_settings
		 * @return array
		 */
		public function validate_settings( $new_settings ) {
			// to save unset checkbox value..
			foreach ($this->settings as $k => $v) 
				if( !isset($new_settings[$k]) ) $new_settings[$k] = '';

			$new_settings = shortcode_atts( $this->settings, $new_settings );

			if ( !isset($new_settings['OSmedia_db-version']) || empty($new_settings['OSmedia_db-version']) )
				$new_settings['OSmedia_db-version'] = OSmedia_base::VERSION;		

			if ( !isset($new_settings['OSmedia_shortcode']) || $new_settings['OSmedia_shortcode'] == '' )
				$new_settings['OSmedia_shortcode']	= 'video';

			// if ( strcmp( $new_settings['OSmedia_autoplay'], 'on' ) !== 0 || strcmp( $new_settings['OSmedia_autoplay'], '' ) !== 0) add_notice( 'autoplay must be valid input tytpe', 'error' );

			///////////// player 
			$new_settings['OSmedia_width'] = absint( $new_settings['OSmedia_width'] );
			$new_settings['OSmedia_height'] = absint( $new_settings['OSmedia_height'] );
			if(!preg_match("/^\d+$/", trim($new_settings['OSmedia_width']))) { $new_settings['OSmedia_width'] = ''; }	 
			if(!preg_match("/^\d+$/", trim($new_settings['OSmedia_height']))) { $new_settings['OSmedia_height'] = ''; }
			///////////// picker	 
			if(!preg_match("/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/", trim($new_settings['OSmedia_color1']))) { $new_settings['OSmedia_color1'] = '#ccc';	 }	 
			if(!preg_match("/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/", trim($new_settings['OSmedia_color2']))) { $new_settings['OSmedia_color2'] = '#66A8CC'; }	 
			if(!preg_match("/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/", trim($new_settings['OSmedia_color3']))) { $new_settings['OSmedia_color3'] = '#000'; }
			// echo 'validate_settings----->' . var_dump($this->settings); echo $fff; var_dump($new_settings);
			return $new_settings;
		}

	} // end OSmedia_Settings
}
