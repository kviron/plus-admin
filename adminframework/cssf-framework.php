<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

require_once plugin_dir_path( __FILE__ ) .'/cssf-framework-path.php';

if (!function_exists('cssf_registry')){
	/**
	 * @return null|\CSSFramework_Registry
	 */
	function cssf_registry() {
		return CSSFramework_Registry::instance();
	}
}

if (!function_exists('cssf_field_registry')){
	/**
	* @return null|\CSSFramework_Field_Registry
	*/
	function cssf_field_registry() {
		return CSSFramework_Field_Registry::instance();
	}
}

if (!function_exists('cssf_template')){
	/**
	* @param       $override_location
	* @param       $template_name
	* @param array $args
	*
	* @return bool
	*/
	function cssf_template( $override_location, $template_name, $args = array() ) {
		if ( file_exists( $override_location . '/' . $template_name ) ) {
			$path = $override_location . '/' . $template_name;
		} elseif ( file_exists( CSSF_DIR . '/templates/' . $template_name ) ) {
			$path = CSSF_DIR . '/templates/' . $template_name;
		} else {
			return false;
		}
		extract( $args );
		include( $path );
		return true;
	}
}

if (!function_exists('cssf_autoloader')){
	/**
	* CSSF Autoloader Function to auto load required class files on the go.
	*
	* @param      $class
	*
	* @return bool
	*/
	function cssf_autoloader( $class ) {
		if ( true === $class && true === class_exists( $class, false ) ) {
			return true;
		}

		if ( 0 === strpos( $class, 'CSSFramework_Option_' ) ) {
			$path = strtolower( substr( $class, 20 ) );
			cssf_locate_template( 'fields/' . $path . '/' . $path . '.php' );
		} elseif ( 0 === strpos( $class, 'CSSFramework_' ) ) {
			$path  = strtolower( substr( str_replace( '_', '-', $class ), 13 ) );
			$path1 = CSSF_DIR . '/classes/' . $path . '.class.php';
			$path2 = CSSF_DIR . '/classes/core/' . $path . '.class.php';

			if ( file_exists( $path1 ) ) {
				include( $path1 );
			} elseif ( file_exists( $path2 ) ) {
				include( $path2 );
			}
		}
		return true;
	}
}

if (!function_exists( 'cssf_framework_init' ) && !class_exists('CSSFramework')){
	function cssf_framework_init() {		
		/**
		* Required CSSFramework Default Helper Functions
		*/
		cssf_locate_template( 'functions/deprecated.php'     );
		cssf_locate_template( 'functions/fallback.php'       );
		cssf_locate_template( 'functions/helpers.php'        );
		cssf_locate_template( 'functions/actions.php'        );
		cssf_locate_template( 'functions/enqueue.php'        );
		cssf_locate_template( 'functions/sanitize.php'       );
		cssf_locate_template( 'functions/validate.php'       );
		
		
		/**
		* Required CSSFramework Default Classes
		*/
		spl_autoload_register('cssf_autoloader');

		// cssf_locate_template( 'classes/core/registry.class.php'   );
		// cssf_locate_template( 'classes/core/field_registry.class.php'   );
		// cssf_locate_template( 'classes/abstract.class.php'   );
		// cssf_locate_template( 'classes/core/db-save-handler.class.php'   );
		// cssf_locate_template( 'classes/options.class.php'    );
		cssf_locate_template( 'classes/framework.class.php'  );
		
		// Custom Post Type
		cssf_locate_template( 'classes/posttype/posttype.php' );
		cssf_locate_template( 'classes/posttype/taxonomy.php' );
		cssf_locate_template( 'classes/posttype/columns.php'  );
		
		// spl_autoload_register('cssf_autoloader');
		
		cssf_load_option_fields();
		cssf_registry();
		cssf_field_registry();
		
		// Hook added to load Extra Configs settings
		do_action('cssf_framework_load_config');
		
		do_action('cssf_framework_loaded');
	}

	/**
	* Sets up framework config settings and registers support for various WordPress features.
	*
	* Note that this function is hooked into the 'after_setup_theme' hook, which
	* runs before the init hook. The init hook is too late for some features, such
	* as Custom Post Types that need to be hooked only on 'init'
	*/
	add_action('after_setup_theme','cssf_framework_init',10);
}

if (!function_exists('cssf_new_options_page')){
	function cssf_new_options_page($settings,$options,$priority = 10){
		$cssf = new CSSFramework($settings);
		
		$fn = function() use($cssf,$options){
			$opts = new $options();
			$opts = $opts->set_options();
			$cssf->set_options($opts);
		};
	
		add_action( 'admin_menu', $fn, $priority );
	}
}

if (!function_exists('cssf_new_options_tab')){
	function cssf_new_options_tab($settings_uniqueid,$module_options,$module_options_name = null,$position = 'end',$index = false,$priority = 10){
		$fn = function($options) use($settings_uniqueid,$module_options,$module_options_name,$position,$index){
			$opts = new $module_options();
			$opts = $opts->set_options();
	
			if ($position == 'end'){
				$options[$module_options_name] = $opts;
			} else if ($position == 'after'){
				// Plus_admin::array_insert($options,'logo', array('customhelptabs' => $new_options));
			}
	
			return $options;
		};
		add_filter($settings_uniqueid,$fn,$priority);
	}
}