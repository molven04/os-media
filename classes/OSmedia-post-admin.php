<?php

if ( ! class_exists( 'OSmedia_Post_Admin' ) ) {

	/**
	 * Creates a custom post type, associated taxonomies and managed data in admin area
	 */
	class OSmedia_Post_Admin extends OSmedia_Module  {

		// protected static $readable_properties  = array(); // da togliere
		// protected static $writeable_properties = array(); // da togliere
		// public static $OSmedia_postmeta = array();

		public static $video_ext = array('mp4', 'ogv', 'ogg', 'webm'); // Arrays are not allowed in class constants in PHP :(

		//////////////////////////////////////////////// Magic methods ////////////////////////////////////////////////////////

		/**
		 * Constructor
		 *
		 * @mvc Controller
		 */
		protected function __construct() {

			$this->register_hook_callbacks();

		}


		//////////////////////////////////////////////// Static methods ///////////////////////////////////////////////////////

		/**
		 * Defines the parameters for the postmeta fields with default values: 
		 * NOTE: if exists a parameters with same key of OPTIONS, this parameters values PREVALE over that OPTIONS array values.
		 * If the post/page are new and NOT exists OPTIONS with same key, take the below default data value.
		 *
		 * Qui devono esservi solo quei parametri necessari in ogni singolo post per generare il player e i cui valori PREVALGONO sulle Options generali 
		 * (es. 'autoplay') e NON devono esseri parametri generali validi per tutti i player del sito (es. 'skin').
		 * [vedi metodo 'data_model']
		 * NOTA: da sostituire con i metodi magici __get __set
		 *
		 * @mvc Model
		 *
		 */
		public static function get_metabox_params() {

			self::$OSmedia_postmeta = array(  
				'OSmedia_feat' 			=> '', // retrocomp.
			    'OSmedia_file' 			=> '',
			    'OSmedia_fileurl'		=> '',
				'OSmedia_mp4'			=> '',
				'OSmedia_webm'			=> '',
				'OSmedia_ogg'			=> '',
				'OSmedia_img'			=> '', 
				'OSmedia_youtube'		=> '',
				'OSmedia_vimeo'			=> '',
				'OSmedia_width' 		=> '',
				'OSmedia_height' 		=> '',
				'OSmedia_start_m' 		=> '',
				'OSmedia_start_s' 		=> '',
				'OSmedia_class' 		=> '',
			    'OSmedia_autoplay'		=> '',
			    'OSmedia_loop' 			=> '',
			    'OSmedia_preload'		=> '',
			    'OSmedia_responsive' 	=> '',
			   	'OSmedia_yt_https' 		=> '',
			    'OSmedia_yt_info'		=> '', 
			   	'OSmedia_yt_related' 	=> '',
			   	'OSmedia_controls'		=> ''			   	
			);
		}

		/**
		 * Get Options - plugin general settings
		 * NOTA: da sostituire con i metodi magici __get __set
		 *
		 * @mvc Model
		 *
		 */
/*		public static function get_options() {
			// flattern array
			foreach( new RecursiveIteratorIterator (new RecursiveArrayIterator( get_option('OSmedia_settings') )) as $k1=>$v1 )  
				$options[$k1] = $v1;

			self::$OSmedia_options = $options;
		}
*/
		/**
		* Registers the custom post type
		*
		* @mvc Controller
		*/
		public static function create_post_type() {
			if ( ! post_type_exists( POST_TYPE_SLUG ) ) {
				$post_type_params = self::get_post_type_params();
				$post_type        = register_post_type( POST_TYPE_SLUG, $post_type_params );

				// CPT columns (Ohad Raz Class)
				$post_columns = new CPT_columns( POST_TYPE_SLUG, true);
				// $post_columns->_column_orderby( (object) array('orderby' => 'date') );
				$post_columns->add_column('cb', 
					array(
				        'label' => __(''),
				        'type'  => 'cb'
				    )
				);
				$post_columns->add_column('post_thumb', 
					array(
				        'label' => __(''),
				        'type'  => 'thumb',
 						'size'  => array('50,50') //size accepted  by the_post_thumbnail as array or string
				    )
				);
				$post_columns->add_column('title',
					array(
				        'label'    => __('title'),
				        'type'     => 'native',
				        'sortable' => true
				    )
				);
				$post_columns->add_column('custom_tax_id',
					array(
				        'label'    => __('OSmedia video category'),
				        'type'     => 'custom_tax',
				        'taxonomy' => TAG_SLUG //taxonomy name
				    )
				);
				$post_columns->add_column('tags',
					array(
				        'label'    => __('tag'), 
				        'type'     => 'tags'
				    )
				);
				$post_columns->add_column('comments',
					array(
				        'label'    => __('comments'), 
				        'type'     => 'comments'
				    )
				);
				$post_columns->add_column('date',
					array(
				        'label'    => __('date'),
				        'type'     => 'date',
				        'sortable' => true
				    )
				);

				if ( is_wp_error( $post_type ) ) {
					add_notice( __METHOD__ . ' error: ' . $post_type->get_error_message(), 'error' );
				}
			}
		}

		/**
		 * Defines the parameters for the custom post type
		 *
		 * @mvc Model
		 *
		 * @return array
		 */
		protected static function get_post_type_params() {
			$labels = array(
				'name'               => POST_TYPE_NAME . 's',
				'singular_name'      => POST_TYPE_NAME,
				'add_new'            => 'Add New',
				'add_new_item'       => 'Add New ' . POST_TYPE_NAME,
				'edit'               => 'Edit',
				'edit_item'          => 'Edit ' .    POST_TYPE_NAME,
				'new_item'           => 'New ' .     POST_TYPE_NAME,
				'view'               => 'View ' .    POST_TYPE_NAME . 's',
				'view_item'          => 'View ' .    POST_TYPE_NAME,
				'search_items'       => 'Search ' .  POST_TYPE_NAME . 's',
				'not_found'          => 'No ' .      POST_TYPE_NAME . 's found',
				'not_found_in_trash' => 'No ' .      POST_TYPE_NAME . 's found in Trash',
				'parent'             => 'Parent ' .  POST_TYPE_NAME
			);

			$post_type_params = array(
				'labels'               => $labels,
				'singular_label'       => POST_TYPE_NAME,
				'public'               => true,
				'exclude_from_search'  => false,
				'publicly_queryable'   => true,								// se FALSE non sarà visibile nel frontend !!!
				'show_ui'              => true,
				'show_in_menu'         => true,
				'register_meta_box_cb' => __CLASS__ . '::add_postmeta', 	// crea i meta-box
				'taxonomies'           => array( TAG_SLUG, 'post_tag'), 	// il 2° parametro AGGIUNGE LA TASSONOMIA PER I CPT!
				'menu_position'        => 20,
				'hierarchical'         => true,
				'capability_type'      => 'post',
				// 'has_archive'          => false,
				// 'rewrite'              => false,
				// 'query_var'            => false,								
				'menu_icon' 		   => 'dashicons-video-alt3',
				// elementi supportati admin area CPT (editor=textarea)
				'supports'             => array( 'cb', 'editor', 'thumbnail', 'title', 'custom_tax', 'custom-fields', 'excerpt', 'tags', 'revisions', 'comments', 'page-attributes')
			);

			return apply_filters( 'OSmedia_post-type-params', $post_type_params );
		}

		/**
		 * Registers the category taxonomy
		 *
		 * @mvc Controller
		 */
		public static function create_taxonomies() {
			if ( ! taxonomy_exists( TAG_SLUG ) ) {
				$tag_taxonomy_params = self::get_tag_taxonomy_params();
				register_taxonomy( TAG_SLUG, POST_TYPE_SLUG, $tag_taxonomy_params ); // categorie
				// register_taxonomy( 'xxxx', POST_TYPE_SLUG ); // tag
			}
		}

		/**
		 * Defines the parameters for the custom taxonomy -----> CREA LE CATEGORIE DEL CPT
		 *
		 * @mvc Model
		 *
		 * @return array
		 */
		protected static function get_tag_taxonomy_params() {
			$tag_taxonomy_params = array(
				'label'                 => TAG_NAME,
				'labels'                => array( 'name' => TAG_NAME, 'singular_name' => TAG_NAME ),
				'hierarchical'          => true,
				'rewrite'               => array( 'slug' => TAG_SLUG ),
				'update_count_callback' => '_update_post_term_count'
			);

			return apply_filters( 'OSmedia_tag-taxonomy-params', $tag_taxonomy_params ); 
		}

		/**
		* Adds the meta box container for normal post, page and CPT.
		*
		* @mvc Controller
		*
		*/
		public static function add_postmeta( $post_type ) {
			if ( $post_type == POST_TYPE_SLUG ) $label = 'OSmedia Featured Video'; else $label = POSTMETA_NAME;
			//limit meta box to certain post types
			$post_types = array('post', 'page');     
		    if ( in_array( $post_type, $post_types ) || $post_type == POST_TYPE_SLUG ) {
				add_meta_box(
					'OSmedia_metabox',
					__( $label, 'myplugin_textdomain' ),
					__CLASS__ . '::markup_postmeta',
					$post_type,
					'normal',
					'core'
				);
		    }
		}

		// 
		/**
		* [MM2015] Questo metodo crea una lista di file di una directory da 3 sorgenti: PATH, URL, S3.
		* tale contenuto potrà aggiungersi a quello di una specifica directory di un server S3 (Simple Storage Server)
		* in base ai dati della configurazione presi dalle Options
		*
		* @mvc @model
		*
		* @param string $dir input local folder path or URL !
		* @param array $search_ext search extension (null=search all ext)
		* @param boolean $sanitize remove ext and double values in output array
		* @return array forma [file]=>path_file senza ext // DA CONFERMARE !!!!!
		*/
		protected static function OSmedia_dir_list( $options, $search_ext = null, $sanitize = true ){ 
			
			$s3_list = array();
			$path_list = array();
			$url_list = array();
			$result = array();
			$result['file_list'] = array();
			$result['monitor_server'] = array();

			extract($options);
			$dir = $OSmedia_path;
			$url = $OSmedia_url;

/*
			if ( !empty($option['basic']['OSmedia_url']) && file_exists($url) ){ 
				$url = $option['basic']['OSmedia_url'];
				if ( !$fp = fopen($url, 'r') ) trigger_error("Unable to open URL ($url)", E_USER_ERROR);
				// echo '<pre>'; print_r( stream_get_meta_data($fp) ); echo '</pre>'; fclose($fp);
			}
*/
			$s3_url = $OSmedia_s3server . $OSmedia_s3bucket . '/' . $OSmedia_s3dir;
			// $result = array();

			/////////////////////////////////// S3 -> cURL
			if ( isset($OSmedia_s3enable) && $OSmedia_s3enable ) {
			    $s3 = new S3($OSmedia_s3access, $OSmedia_s3secret);
			    $s3_array = $s3->getBucket($OSmedia_s3bucket);
			    if($s3_array) $monitor_server['s3'] = 'ok'; elseif(!$s3_url) $monitor_server['s3'] = 'unset'; else $monitor_server['s3'] = 'error';
			    if( $s3_array ) {
			    // array forma [file]=>path_file
				    foreach ($s3_array as $kk => $vv){
				    	$file = str_replace( $OSmedia_s3dir, '', $vv['name']); // toglie la directory s3 dal nome del file
				    	// $file = preg_replace( '/\.[^.]+$/', '', $file ); // SANITIZE
				    	if( $file ) $s3_list[$file] = $s3_url;
				    }
				    
				}
			}else{
				$monitor_server['s3'] = 'unset';
			}

			///////////////////////////////////// PATH videofile -> scandir
			if( $dir ){
				if(	file_exists($dir) ){
					$cdir_path = scandir($dir);
					// array forma [file]=>path_file
					if( is_array($cdir_path) ) {
						foreach ($cdir_path as $key => $value) {
							// $value = preg_replace( '/\.[^.]+$/', '', $value );
							// if ( $value != '.' && $value != '..' ) 
								$path_list[$value] = ''; // $dir /*. $value*/;
						}
						$monitor_server['path'] = 'ok';
					}
				}else{
					$monitor_server['path'] = 'error';
				}		   	
			}else{
				$monitor_server['path'] = 'unset';
			}

			///////////////////////////// URL (media server) -> fopen
		   	if ( $url ) { // input URL
		   		if( OSmedia_isValidUrl($url) ){
			   		$res = array();
			   		$content = '';
				   	$fp_load = fopen( $url, "rb");
				   	if ( $fp_load ) 
						while ( !feof($fp_load) ) 
					    	$content .= fgets($fp_load, 8192);
					fclose($fp_load);
					preg_match_all("/(a href\=\")([^\?\"]*)(\")/i", $content, $res);
					// array forma [file]=>path_file senza ext
					// DA VERIFICARE !!!!!!
					foreach ($res[2] as $val) {
						// $value = preg_replace( '/\.[^.]+$/', '', $value );
						if ( $val != '.' && $val != '..' ) $url_list[$val] = $url /*. $val*/;
					}
					$monitor_server['url'] = 'ok';
				}else{
					$monitor_server['url'] = 'error';
				}
			}else {
				$monitor_server['url'] = 'unset';
			}

			// unisce i 3 risultati
			$file_list = $s3_list;
			if( !empty($path_list) ) foreach( $path_list as $k2 => $v2 ) $file_list[$k2] = $v2;
			if( !empty($url_list) ) foreach( $url_list as $k3 => $v3 ) $file_list[$k3] = $v3;			

			// SANITIZE
			// filtra i risultati togliendo i file di estensione non richiesta, poi le estensioni e poi gli elementi con lo stesso valore: 
			// in caso di chiavi doppie, rimangono i primi inseriti, ovvero, in ordine quelli di S3 -> PATH -> URL
			if ( !empty($file_list) && $sanitize ) {
			   foreach ($file_list as $key => $value)  { 
			      // if (!in_array($key, array(".",".."))) { 
			        // if (is_dir($dir . DIRECTORY_SEPARATOR . $key))  { 

			            // $result[$key] = self::OSmedia_dir_list ( $dir, $search_ext ); // Ricorsività

			        // }else{ 

			         	if ( !empty($search_ext) && is_array($search_ext) ){
							foreach ($search_ext as $ext) {
								if ( strpos($key, '.'.$ext) !== false) {
									// toglie l'estensione
									$key = preg_replace('/\.[^.]+$/','',$key); 
									// essendo unificata la chiave dei file con estensione diversa, vengono inseriti una sola volta nell'array $result
									$result['file_list'][$key] = $value; 
								}

							}
						}

			        // } 
			     // } 

			   	}

				// if ($sanitize) $result = array_unique ($result); // 

			}

			$result['monitor_server'] = $monitor_server;

			// echo 'ARRAY_S3: <pre>'; print_r($monitor_server); echo '</pre>'; // MONITOR
			// echo 'ARRAY_PATH: <pre>'; print_r($path_list); echo '</pre>'; // MONITOR
			// echo 'ARRAY_URL: <pre>'; print_r($url_list); echo '</pre>'; // MONITOR
			// echo 'RESULT: <pre>'; print_r($result); echo '</pre>'; // MONITOR	

			return $result; 
		}

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
		public static function OSmedia_data_model( $options ) {
			global $post;		
			$post_id = $post->ID;		
			$out = array();
			$meta_values = self::postmeta_vars_filter( get_post_meta( $post_id ) );
		
			// se il post è nuovo..
			if ( self::is_edit_page('new') ) { 
				// ..per ogni parametro pesca i valori..
				foreach ( self::$OSmedia_postmeta as $k => $v ) { 
					// ..in prima istanza dalle option (se esite la chiave),
					if ( array_key_exists($k, $options) )
						$out[$k] = $options[$k]; 
					// ..in seconda istanza dai valori di default (postmeta)
					else
						$out[$k] = $v; // caso per ora non usato				
				}
			// se il post esiste già 
			}else{ 
				$out = $options;
				// carica le OPTIONS in $out, pescando dai postmeta i valori con le stesse chiavi (prevalgono i postmeta sulle options)
				foreach ( $options as $k => $v ) { 
					if ( array_key_exists($k, $meta_values ) )
						$out[$k] = $meta_values[$k]; 
					// else	$out[$k] = $v;
				}
				// carica i POSTMETA di questo plugin, aggiungo i valori con chiavi che NON ci sono già in $out..
				foreach ( self::$OSmedia_postmeta as $k_meta => $v_meta ) {						
					if ( !array_key_exists($k_meta, $out) ) {
						// uniform exit values
						if( !isset($meta_values[$k_meta]) || $meta_values[$k_meta] == null ) 
							$out[$k_meta] = '';
						else
							$out[$k_meta] = $meta_values[$k_meta];
					}
				}
			}			

			// echo '<pre> DATA_MODEL: post_id->'.$post_id.'  ';var_dump($out);  echo '</pre>'; // MONITOR
			return $out;
		}

		/**
		 * filter postmeta_vars for OSmedia post meta-fields and for retro-compatibility (old vars "OSvid_")
		 *
		 * @mvc Model
		 *
		 * @param array $meta
		 * @param array  $out
		 */
		public static function postmeta_vars_filter( $meta ) {
			global $post;		
			$post_id = $post->ID;
			$out = array();

			foreach ( $meta as $k => $v ){
				if( 'OSmedia_'== substr($k, 0, 8) ){
					$out[$k] = $v[0];
				////////////////////// RETROCOMPATIBILITY: FILTER OLD_VARS
				}elseif ( 'OSvid_' == substr($k, 0, 6) ){
					$new_k = '';
					$new_k = OSmedia_Version_Vars::merge_old_vars_post( $k );
					if( $new_k !='' ){ // if old postmeta is necessary in new version
						$out[$new_k] = $v[0];
						update_post_meta( $post_id, $new_k, $v[0] );
					}
					delete_post_meta( $post_id, $k ); // delete old postmeta 
				}
				////////////////////////////////////////////////////////////
			}
			// var_dump($out);
			return $out;
		}

		/**
		 * Builds the markup for all meta boxes
		 *
		 * @mvc Controller
		 *
		 * @param object $post
		 * @param array  $box
		 */
		public static function markup_postmeta( $post, $box ) {

			$variables = array();

			$variables = self::OSmedia_data_model( self::$OSmedia_options );
			$resource = self::OSmedia_dir_list ( self::$OSmedia_options, self::$video_ext );

			switch ( $box['id'] ) {
				case 'OSmedia_metabox':
					// crea lista dei file video presenti nel folder configurato da poter selezionare per il post
					$variables['video_list'] = array();
					$variables['video_list'] = $resource['file_list']; 
					$variables['monitor_server'] = $resource['monitor_server'];
					$view                    = 'OSmedia-postmeta/metabox.php';
					// $variables['s3_url'] = 'https://' . $options['OSmedia_s3bucket'] . '/' . $options['OSmedia_s3dir'] .'.s3.amazonaws.com/';
					break;
			}

			// admin area post-type 
			$variables['post_type'] = get_post_type($post->ID);

			///////////////// MONITOR
			// foreach ($variables as $mon_k => $mon_atts) if( $mon_atts !='' ) $mon[$mon_k] = $mon_atts; // nasconde valori nulli
			// echo '<pre style="font-size:12px"> --->post_type: '.get_post_type($post->ID).' -->GET_POSTMETA: '; var_dump($variables); echo '</pre>'; 
			// echo '<br>--------- max upload file size ----->'.ini_get('post_max_size');
			/////////////////////////

			echo self::render_template( $view , $variables );
		}

		/**
		 * Determines whether a meta key should be considered private or not
		 *
		 * @mvc Model
		 *
		 * @param bool $protected
		 * @param string $meta_key
		 * @param mixed $meta_type
		 * @return bool
		 */
		public static function is_protected_meta( $protected, $meta_key, $meta_type ) {
			$protected = false;
			/*
			switch( $meta_key ) {
				case 'OSmedia_box':
				case 'OSmedia_box2':
					$protected = true;
					break;

				case 'OSmedia_some-other-box':
				case 'OSmedia_some-other-box2':
					$protected = false;
					break;
			}
			*/
			return $protected;
		}

		/**
		 * Saves values of the the custom post type's extra fields
		 *
		 * @mvc Controller
		 *
		 * @param int    $post_id
		 * @param object $post
		 */
		public static function save_post_cpt( $post_id, $revision ) {
			global $post;
			$ignored_actions = array( 'trash', 'untrash', 'restore' );
			if ( isset( $_GET['action'] ) && in_array( $_GET['action'], $ignored_actions ) ) return;
			if ( ! $post || ! current_user_can( 'edit_post', $post_id ) ) return;
			if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || $post->post_status == 'auto-draft' ) return;

			self::save_custom_fields_cpt( $post_id, $_POST );
		}

		/**
		 * Validates and saves values of the the custom post type's extra fields
		 *
		 * @mvc Model
		 *
		 * @param int   $post_id
		 * @param array $new_values
		 */
		protected static function save_custom_fields_cpt( $post_id, $new_values ) {

			// if ( self::is_edit_page('new') ) 
				$postmeta = self::$OSmedia_postmeta;
			// else $postmeta = self::postmeta_vars_filter( get_post_meta( $post_id ) );

			foreach ( $postmeta as $key => $value ) {

				if ( !isset( $new_values[ $key ] ) ) $new_values[ $key ] = '';
				update_post_meta( $post_id, $key, $new_values[ $key ] );

			}
			
		}


		///////////////////////////////// Instance methods //////////////////////////////////////////////////

		/**
		 * Register callbacks for actions and filters
		 *
		 * @mvc Controller
		 */
		public function register_hook_callbacks() {
			// global $post;
			add_action( 'init',                     array( $this, 'init' ) );
			add_action( 'init',                     __CLASS__ . '::create_post_type' );
			add_action( 'init',                     __CLASS__ . '::create_taxonomies' );
			add_action( 'add_meta_boxes', 			array( $this, 'add_postmeta' ) );
			add_action( 'save_post',                __CLASS__ . '::save_post_cpt', 10, 2 );

			add_filter( 'is_protected_meta',        __CLASS__ . '::is_protected_meta', 10, 3 ); // ?
		}

		/**
		 * Prepares site to use the plugin during activation
		 *
		 * @mvc Controller
		 *
		 * @param bool $network_wide
		 */
		public function activate( $network_wide ) {		
			/////////
			self::create_post_type();
			self::create_taxonomies();

			// add demo CPT
			$cpt_id = @wp_insert_post(array (
				'post_type' => 'osmedia_cpt',
				'post_title' => 'OSmedia Demo Featured Video',
				'post_content' => 'OSmedia Demo CPT - Featured Video Content',
				'post_status' => 'publish',
				'comment_status' => 'closed', 
				'ping_status' => 'closed'
			));
			if ($cpt_id && !is_wp_error( $cpt_id ) ) {
				add_post_meta($cpt_id, 'OSmedia_mp4', 'https://s3-eu-west-1.amazonaws.com/openstream.tv/SEP/OSmedia_demo1.mp4');
				add_post_meta($cpt_id, 'OSmedia_webm', 'https://s3-eu-west-1.amazonaws.com/openstream.tv/SEP/OSmedia_demo1.webm');
				add_post_meta($cpt_id, 'OSmedia_autoplay', 'on');
				add_post_meta($cpt_id, 'OSmedia_loop', 'on');
			}

			// add featured video page
			$page_id = @wp_insert_post(array (
				'post_type' => 'page',
				'post_title' => 'OSmedia Featured Video',
				'post_status' => 'publish',
				'comment_status' => 'closed', 
				'ping_status' => 'closed'
			));
	        if ( $page_id && !is_wp_error( $page_id ) ){
	            update_post_meta( $page_id, '_wp_page_template', 'page-templates/featured_video_list.php' );
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

			self::get_metabox_params();

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


	} // end OSmedia_post_admin
}
