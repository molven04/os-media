<?php

if ( ! class_exists( 'OSmedia_Version_Vars' ) ) {

	/**
	 * for retrocompatibility
	 */
	class OSmedia_Version_Vars extends OSmedia_Module  {

		protected $settings;
		protected static $default_settings;

		const REQUIRED_CAPABILITY = 'manage_options';
		const OPTS = 'OSmedia_settings';
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
		 * Register callbacks for actions and filters
		 *
		 * @mvc Controller
		 */
		public function register_hook_callbacks() {

			add_filter( 'filter_old_vars_admin', __CLASS__ . '::merge_old_vars_admin' );
						
		}

		/**
		 * Retrocompatibility with old Variables of old plugin version
		 *
		 * @mvc Model
		 *
		 * @param bool $network_wide
		 */
		public static function merge_old_vars_admin( $old_vars ) {

			$out = array();

			if ( is_array($old_vars) ) {
				foreach ($old_vars as $key => $value) {

					switch ( $key ) {
						case 'OSvid_autoplay': 				$new_k = 'OSmedia_autoplay'; break;
						case 'OSvid_autoplay': 				$new_k = 'OSmedia_autoplay'; break;
						case 'OSvid_loop': 					$new_k = 'OSmedia_loop'; break;
						case 'OSvid_preload': 				$new_k = 'OSmedia_preload'; break;
						case 'OSvid_player':				$new_k = 'OSmedia_player'; break;
						case 'OSvid_yt-vjs':				$new_k = 'OSmedia_yt_vjs'; break;
						case 'OSvid_https':					$new_k = 'OSmedia_yt_https'; break;
						case 'OSvid_html5':					$new_k = 'OSmedia_yt_html5'; break;
						case 'OSvid_showinfo':				$new_k = 'OSmedia_yt_info'; break;	
						case 'OSvid_related':				$new_k = 'OSmedia_yt_related'; break;	
						case 'OSvid_logo':					$new_k = 'OSmedia_yt_logo'; break;

						case 'OSvid_width':					$new_k = 'OSmedia_width'; break;
						case 'OSvid_height':				$new_k = 'OSmedia_height'; break;
						case 'OSvid_skin': 					$new_k = 'OSmedia_skin'; break;
						case 'OSvid_responsive': 			$new_k = 'OSmedia_responsive'; break;
						case 'OSvid_ratio': 				$new_k = 'OSmedia_ratio'; break;
						case 'OSvid_controlbar_position':	$new_k = 'OSmedia_barpos'; break;
						case 'OSvid_color_one':				$new_k = 'OSmedia_color1'; break;
						case 'OSvid_color_two':				$new_k = 'OSmedia_color2'; break;
						case 'OSvid_color_three':			$new_k = 'OSmedia_color3'; break;

							default: $new_k = ''; break;
					}

					if ($new_k) {
						$out[$new_k] = $value;
					}

				}

				$out['OSmedia_db-version'] = OSmedia_base::VERSION;
			}

			return $out;
		}

		/**
		 * Retrocompatibility with old Variables of old plugin version
		 *
		 * @mvc Model
		 *
		 * @param bool $network_wide
		 */
		public static function merge_old_vars_post( $old_key ) {

				switch ( $old_key ) {
						case 'OSvid_feat': 					$new_k = 'OSmedia_feat'; break;
						case 'OSvid_autoplay': 				$new_k = 'OSmedia_autoplay'; break;
						case 'OSvid_loop': 					$new_k = 'OSmedia_loop'; break;
						case 'OSvid_preload': 				$new_k = 'OSmedia_preload'; break;
						case 'OSvid_player':				$new_k = 'OSmedia_player'; break;
						case 'OSvid_yt-vjs':				$new_k = 'OSmedia_yt_vjs'; break;
						case 'OSvid_https':					$new_k = 'OSmedia_yt_https'; break;
						case 'OSvid_html5':					$new_k = 'OSmedia_yt_html5'; break;
						case 'OSvid_showinfo':				$new_k = 'OSmedia_yt_info'; break;	
						case 'OSvid_related':				$new_k = 'OSmedia_yt_related'; break;	
						case 'OSvid_logo':					$new_k = 'OSmedia_yt_logo'; break;
						case 'OSvid_width':					$new_k = 'OSmedia_width'; break;
						case 'OSvid_height':				$new_k = 'OSmedia_height'; break;
						case 'OSvid_skin': 					$new_k = 'OSmedia_skin'; break;
						case 'OSvid_responsive': 			$new_k = 'OSmedia_responsive'; break;
						case 'OSvid_ratio': 				$new_k = 'OSmedia_ratio'; break;
						case 'OSvid_controlbar_position':	$new_k = 'OSmedia_barpos'; break;
						case 'OSvid_color_one':				$new_k = 'OSmedia_color1'; break;
						case 'OSvid_color_two':				$new_k = 'OSmedia_color2'; break;
						case 'OSvid_color_three':			$new_k = 'OSmedia_color3'; break;
						case 'OSvid_mp4':					$new_k = 'OSmedia_mp4'; break;	
						case 'OSvid_webm':					$new_k = 'OSmedia_webm'; break;	
						case 'OSvid_ogg':					$new_k = 'OSmedia_ogg'; break;
						case 'OSvid_img':					$new_k = 'OSmedia_img'; break;
						case 'OSvid_id':					$new_k = 'OSmedia_youtube'; break;
						case 'OSvid_start_m':				$new_k = 'OSmedia_start_m'; break;	
						case 'OSvid_start_s':				$new_k = 'OSmedia_start_s'; break;	
						case 'OSvid_class':					$new_k = 'OSmedia_class'; break;
							default: $new_k = ''; break;
				}

			return $new_k;
		}

		/**
		 * Prepares site to use the plugin during activation
		 *
		 * @mvc Controller
		 *
		 * @param bool $network_wide
		 */
		public function activate( $network_wide ) {

			if ( get_option( self::OPTS_OLD1 ) || get_option(self::OPTS_OLD2) ){
				$options = get_option(self::OPTS_OLD1);
				$options_player = get_option(self::OPTS_OLD2);
				$settings = get_option( self::OPTS, array() );
				$opts_merge = array_merge($options, $options_player );
				///////////////////////// SAVE SETTINGS
				if ( is_array($options) /*AND !is_array($settings)*/ ) {
					$settings_from_old_vars = apply_filters( 'filter_old_vars_admin', $opts_merge );
					$ok_tranfer = update_option( self::OPTS, $settings_from_old_vars);
					if( $ok_tranfer ){ 
						delete_option( self::OPTS_OLD1 );
						delete_option( self::OPTS_OLD2 );
						delete_option( 'OSvid_db_version' );
					} 
				}
			}

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


	} // END Class OSmedia_Version_Vars
}