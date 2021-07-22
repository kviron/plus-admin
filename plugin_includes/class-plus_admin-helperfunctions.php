<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

class Plus_admin_HelperFunctions{
	/**
	 *
	 * Plugin path finder
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 */
	function get_path_locate(){
		$parent_dirname = realpath(dirname(__FILE__) . '/..');
		// $dirname        = wp_normalize_path( dirname( __FILE__ ) );
		$dirname 		= wp_normalize_path($parent_dirname);

		$plugin_dir     = wp_normalize_path( WP_PLUGIN_DIR );
		$located_plugin = ( preg_match( '#'. $plugin_dir .'#', $dirname ) ) ? true : false;
		$directory      = ( $located_plugin ) ? $plugin_dir : get_template_directory();
		$directory_uri  = ( $located_plugin ) ? WP_PLUGIN_URL : get_template_directory_uri();
		$basename       = str_replace( wp_normalize_path( $directory ), '', $dirname );
		$dir            = $directory . $basename;
		$uri            = $directory_uri . $basename;

		return apply_filters( 'cs_plugin_get_path_locate', array(
			'basename' => wp_normalize_path( $basename ),
			'dir'      => wp_normalize_path( $dir ),
			'uri'      => $uri
		) );
	}
	
	
	/**
	 * Plugin is Network Activated
	 * 
	 * @since 1.0.0
	 */
	static function is_network_active(){
		// Makes sure the plugin is defined before trying to use it
		if (!function_exists( 'is_plugin_active_for_network' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
		
		if (is_plugin_active_for_network( 'plus_admin/plus_admin.php' ) ) {
			// Plugin is activated
			return true;
		}

		return false;
	}
	
	
	/**
	 * Functions to Add, Update and Get Settings
	 *
	 * @since 1.0.0
	 */
	function add_option($variable, $default = false){
		if (self::is_network_active()) {
			add_site_option($variable, $default);
		} else {
			add_option($variable, $default);
		}
	}


	function update_option($variable, $default = false){
		if (self::is_network_active()) {
			update_site_option($variable, $default);
		} else {
			update_option($variable, $default);
		}
	}


	function get_option($variable){
		if (self::is_network_active()) {
			return get_site_option($variable);
		} else {
			return get_option($variable);
		}
	}
	
	
	/**
	 * Get Plugin Settings
	 * 
	 * Use CSSFramework Instance "unique" (option_array setting) to get plugin specific settings
	 * With the unique field_id
	 *
	 * @since 1.0.0
	 */
	static function get_settings($variable = false, $options = 'cs_plusadmin_settings'){
		if (self::is_network_active()) {
			// $settings 	= get_site_option($options); // To get network wide settings
			$settings 	= get_option($options);
			$output 	= $settings;
			if (!empty($settings) && isset($variable)){
				$output 	= self::search_settings_array($settings,$variable);
			}
			return $output;
		} else {
			$settings 	= get_option($options);
			$output 	= $settings;
			if (!empty($settings) && isset($variable)){
				$output 	= self::search_settings_array($settings,$variable);
			}
			return $output;
		}
	}
	
	
	/**
	 * Get User Type
	 *
	 * @return void
	 */
	static function cs_get_user_type(){
		$get_admin_menumng_page = Plus_admin::get_option("framework_option_to_know_when_loading_lkasdklajsdlkjdcmlakxalksdxa", "enable");

		$enablemenumng = true;
		if ((is_super_admin() || current_user_can('manage_options')) && $get_admin_menumng_page == "disable") {
			$enablemenumng = false;
		}
		return true;
	}
	
	
	/**
	 * Helper Function to search plugin settings with the unique field_id
	 *
	 * @since 1.0.0
	 */
	static function search_settings_array( array $array, $search ){
		while( $array ) {
			if ( isset( $array[ $search ] ) ) { 
				return $array[ $search ]; 
			}
			$segment = array_shift( $array );
			if( is_array( $segment ) ) {
				if( $return = self::search_settings_array( $segment, $search ) ) {
					return $return;
				}
			}
		}
		return false;
	}
	
	
	/**
	 * Search a target on a multidimensional array and return index of target
	 * 
	 * - Used on Admin menu manager
	 * 
	 * @since 1.0.0
	 */
	static function search_multi_array($array,$key,$target){
		$output = false;
		if (is_array($array) && isset($key) && isset($target)){
			$output = array_search(
				$target,
				array_filter(
					array_combine(
						array_keys($array),
						array_column(
							$array, $key
						)
					)
				)
			);
		}
		return $output;
	}
	
	
	/**
	 * Search a multidimensional array for an id
	 * 
	 * @since 1.0.0
	 */
	static function search_array_for_id($array,$needkey,$target){
		foreach ($array as $key => $val) {
			if ($val[$needkey] === $target) {
				return $key;
			}
		}
		return null;
	}
	/**
	 * Search a multidimensional array for all ids
	 * @description Same as Plus_admin::search_array_for_id() but returns an array with all the ids of all the matches...
	 * 
	 * @since 1.1.0
	 */
	static function search_array_for_ids($array,$needkey,$target){
		$output = array();
		foreach ($array as $key => $val) {
			if ($val[$needkey] === $target) {
				$output[] = $key;
			}
		}
		return ($output) ? $output : null;
	}
	
	
	/**
	 * Insert New Item in array
	 * A function that can insert at both integer and string positions:
	 * 
	 * @since 1.0.0
	 */
	static function array_insert(&$array, $position, $insert){
		if (is_int($position)) {
			Plus_admin::array_splice_assoc($array, $position, 0, $insert);
		} else {
			$pos   = array_search($position, array_keys($array));
			$array = array_merge(
				array_slice($array, 0, $pos, TRUE),
				$insert,
				array_slice($array, $pos, NULL, TRUE)
			);
		}
	}
	static function array_splice_assoc(&$input, $offset, $length, $replacement){
		$replacement = (array) $replacement;
		$key_indices = array_flip(array_keys($input));
		if (isset($input[$offset]) && is_string($offset)) {
			$offset = $key_indices[$offset];
		}
		if (isset($input[$length]) && is_string($length)) {
			$length = $key_indices[$length] - $offset;
		}
	
		$input = array_slice($input, 0, $offset, TRUE)
			+ $replacement
			+ array_slice($input, $offset + $length, NULL, TRUE);
	}
	
	
	/**
	 * Check User Roles
	 * 
	 * @since 1.0.0
	 */
	static function get_user_roles_by_user_id($user_id){
		$user = get_userdata( $user_id );
		return empty( $user ) ? array() : $user->roles;
	}
	static function is_user_in_role($user_id, $role){
		return in_array( $role, self::get_user_roles_by_user_id($user_id)) ;
	}
	static function get_current_user_roles(){
		if (is_user_logged_in()){
			$user = wp_get_current_user();
			return (array) $user->roles;
		} else {
			return array();
		}
	}
	static function is_current_user_in_role($role){
		if (is_user_logged_in()){
			$user = wp_get_current_user();
			return in_array($role, (array) $user->roles );
		} else {
			return array();
		}
	}
	
	
	/**
	 * 
	 * Helper Functions
	 * 
	 */
	static function removeslashes($string) {
		$string = implode("", explode("\\", $string));
		return stripslashes(trim($string));
	}
	static function cs_reformatstring($str) {
		$str = htmlspecialchars($str, ENT_QUOTES);
		$str = self::removeslashes($str);
		return $str;
	}
	static function sanitize($string){
		return filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	}
	static function dashiconscsv() {
		$str = "menu:f333,
		admin-site:f319,
		dashboard:f226,
		admin-media:f104,
		admin-page:f105,
		admin-comments:f101,
		admin-appearance:f100,
		admin-plugins:f106,
		admin-users:f110,
		admin-tools:f107,
		admin-settings:f108,
		admin-network:f112,
		admin-generic:f111,
		admin-home:f102,
		admin-collapse:f148,
		format-links:f103,
		format-standard:f109,
		format-image:f128,
		format-gallery:f161,
		format-audio:f127,
		format-video:f126,
		format-chat:f125,
		format-status:f130,
		format-aside:f123,
		format-quote:f122,
		welcome-edit-page:f119,
		welcome-add-page:f133,
		welcome-view-site:f115,
		welcome-widgets-menus:f116,
		welcome-comments:f117,
		welcome-learn-more:f118,
		image-crop:f165,
		image-rotate-left:f166,
		image-rotate-right:f167,
		image-flip-vertical:f168,
		image-flip-horizontal:f169,
		undo:f171,
		redo:f172,
		editor-bold:f200,
		editor-italic:f201,
		editor-ul:f203,
		editor-ol:f204,
		editor-quote:f205,
		editor-alignleft:f206,
		editor-aligncenter:f207,
		editor-alignright:f208,
		editor-insertmore:f209,
		editor-spellcheck:f210,
		editor-expand:f211,
		editor-contract:f506,
		editor-kitchensink:f212,
		editor-underline:f213,
		editor-justify:f214,
		editor-textcolor:f215,
		editor-paste-word:f216,
		editor-paste-text:f217,
		editor-removeformatting:f218,
		editor-video:f219,
		editor-customchar:f220,
		editor-outdent:f221,
		editor-indent:f222,
		editor-help:f223,
		editor-strikethrough:f224,
		editor-unlink:f225,
		editor-rtl:f320,
		editor-break:f474,
		editor-code:f475,
		editor-paragraph:f476,
		align-left:f135,
		align-right:f136,
		align-center:f134,
		align-none:f138,
		lock:f160,
		calendar:f145,
		calendar-alt:f508,
		visibility:f177,
		post-status:f173,
		edit:f464,
		trash:f182,
		external:f504,
		arrow-up:f142,
		arrow-down:f140,
		arrow-left:f141,
		arrow-right:f139,
		arrow-up-alt:f342,
		arrow-down-alt:f346,
		arrow-left-alt:f340,
		arrow-right-alt:f344,
		arrow-up-alt2:f343,
		arrow-down-alt2:f347,
		arrow-left-alt2:f341,
		arrow-right-alt2:f345,
		leftright:f229,
		sort:f156,
		randomize:f503,
		list-view:f163,
		exerpt-view:f164,
		grid-view:f509,
		hammer:f308,
		art:f309,
		migrate:f310,
		performance:f311,
		universal-access:f483,
		universal-access-alt:f507,
		tickets:f486,
		nametag:f484,
		clipboard:f481,
		heart:f487,
		megaphone:f488,
		schedule:f489,
		wordpress:f120,
		wordpress-alt:f324,
		pressthis:f157,
		update:f463,
		screenoptions:f180,
		info:f348,
		cart:f174,
		feedback:f175,
		cloud:f176,
		translation:f326,
		tag:f323,
		category:f318,
		archive:f480,
		tagcloud:f479,
		text:f478,
		media-archive:f501,
		media-audio:f500,
		media-code:f499,
		media-default:f498,
		media-document:f497,
		media-interactive:f496,
		media-spreadsheet:f495,
		media-text:f491,
		media-video:f490,
		playlist-audio:f492,
		playlist-video:f493,
		yes:f147,
		no:f158,
		no-alt:f335,
		plus:f132,
		plus-alt:f502,
		minus:f460,
		dismiss:f153,
		marker:f159,
		star-filled:f155,
		star-half:f459,
		star-empty:f154,
		flag:f227,
		share:f237,
		share1:f237,
		share-alt:f240,
		share-alt2:f242,
		twitter:f301,
		rss:f303,
		email:f465,
		email-alt:f466,
		facebook:f304,
		facebook-alt:f305,
		networking:f325,
		googleplus:f462,
		location:f230,
		location-alt:f231,
		camera:f306,
		images-alt:f232,
		images-alt2:f233,
		video-alt:f234,
		video-alt2:f235,
		video-alt3:f236,
		vault:f178,
		shield:f332,
		shield-alt:f334,
		sos:f468,
		search:f179,
		slides:f181,
		analytics:f183,
		chart-pie:f184,
		chart-bar:f185,
		chart-line:f238,
		chart-area:f239,
		groups:f307,
		businessman:f338,
		id:f336,
		id-alt:f337,
		products:f312,
		awards:f313,
		forms:f314,
		testimonial:f473,
		portfolio:f322,
		book:f330,
		book-alt:f331,
		download:f316,
		upload:f317,
		backup:f321,
		clock:f469,
		lightbulb:f339,
		microphone:f482,
		desktop:f472,
		tablet:f471,
		smartphone:f470,
		smiley:f328,
		index-card:f510,
		carrot:f511";

		return $str;
	}

}


