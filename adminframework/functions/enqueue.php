<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
*
* Framework admin enqueue style and scripts
*
* @since 1.0.0
* @version 1.0.0
*
*/

if ( ! class_exists( 'CSSFramework_Assets' ) ) {
	/**
	* Class CSSFramework_Assets
	*/
	final class CSSFramework_Assets {
		/**
		* _instance
		*
		* @var null
		*/
		private static $_instance = null;
		
		/**
		* scripts
		*
		* @var array
		*/
		public $scripts = array();
		
		/**
		* styles
		*
		* @var array
		*/
		public $styles = array();
		
		/**
		* CSSFramework_Assets constructor.
		*/
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( &$this, 'register_assets' ) );
		}

		/**
		 * Registers Assets With WordPress
		 */
		public function register_assets() {
			$this->init_array();
			
			foreach ( $this->styles as $id => $file ) {
				wp_register_style( $id, $file[0], $file[1], $file[2], 'all' );
			}
			
			foreach ( $this->scripts as $iid => $ffile ) {
				wp_register_script( $iid, $ffile[0], $ffile[1], $ffile[2], true );
			}
		}
		
		/**
		* Stores All default CSSF Assets Into A Array
		*
		* @uses $this->styles
		* @uses $this->scripts
		*/
		public function init_array() {
			// Define Styles
			$this->styles = array(
				'cssf-framework' => array(
					self::is_debug( CSSF_URI . '/assets/css/cssf-framework.css', 'css' ),
					array(),
					CSSF_VERSION,
				),
			);
			
			// Add Icons
			$jsons = apply_filters('cssf_add_icons', glob( CSSF_DIR . '/assets/icons/*.json' ));
			if( ! empty( $jsons ) ) {
				foreach ( $jsons as $path ) {
					// $object = cssf_get_icon_fonts( 'assets/icons/'. basename( $path ) );
					$_path      = pathinfo($path);
					$_name      = $_path['filename'];
					$filepath   = "/assets/icons/{$_name}/{$_name}.css";
					$_filepath  = CSSF_DIR . $filepath;
					$_fileuri   = CSSF_URI . $filepath;
					
					if (file_exists($_filepath)){
						$this->styles['cssf-'.$_name] = array(
							self::is_debug($_fileuri, 'css' ),
							array(),
							CSSF_VERSION,
						);
					}
				}
			}
			
			// Define Scripts
			$this->scripts = array(
				'cssf-plugins'   => array(
					self::is_debug( CSSF_URI . '/assets/js/cssf-plugins.js', 'js' ),
					null,
					CSSF_VERSION,
					true,
				),
				'cssf-framework'   => array(
					self::is_debug( CSSF_URI . '/assets/js/cssf-framework.js', 'js' ),
					array('cssf-plugins'),
					CSSF_VERSION,
					true,
				),
				'cssf-vendor-ace'  => array(
					self::is_debug( CSSF_URI . '/assets/js/vendor/ace/ace.js', 'js' ),
					array('cssf-plugins'),
					'1.0.0',
					true,
				),
				'cssf-vendor-ace-mode' => array(
					self::is_debug( CSSF_URI . '/assets/js/vendor/ace/mode-css.js', 'js' ),
					array('cssf-vendor-ace'),
					'1.0.0',
					true,
				),
				'cssf-vendor-ace-language_tools'   => array(
					self::is_debug( CSSF_URI . '/assets/js/vendor/ace/ext-language_tools.js', 'js' ),
					array('cssf-vendor-ace'),
					'1.0.0',
					true,
				),
				'jquery-deserialize'  => array(
					self::is_debug( CSSF_URI . '/assets/js/vendor/jquery.deserialize.js', 'js' ),
					array( 'cssf-plugins' ),
					'1.0.0',
					true,
				),
			);
			
			/**
			 * Filter Framework Assets
			 */
			$assets = apply_filters('cssf_register_framework_assets',array($this->styles,$this->scripts));

			$this->styles 	= $assets[0];
			$this->scripts 	= $assets[1];
		}
		
		/**
		* Creates A Instance for CSSFramework_Assets.
		*
		* @return null|\CSSFramework_Assets
		* @static
		*/
		public static function instance() {
			if ( null === self::$_instance ) {
				self::$_instance = new self;
			}
			return self::$_instance;
		}
		
		/**
		 * Loads All Default Styles & Assets.
		 */
		public function render_framework_style_scripts() {
			do_action('cssf_enqueue_framework_assets');

			wp_enqueue_media();
			
			/**
			* Enqueue Styles
			*/
			wp_enqueue_style( 'editor-buttons' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			
			// Enqueue Dynamic Styles
			foreach ($this->styles as $style => $value){
				wp_enqueue_style($style);
			}
			
			
			/**
			* Enqueue Scripts
			*/
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'jquery-ui-dialog' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-ui-accordion' );
			wp_enqueue_script( 'wplink' );
			
			// Enqueue Dynamic Scripts
			foreach ($this->scripts as $script => $value){
				wp_enqueue_script($script);
			}

			do_action('cssf_enqueue_framework_assets_after');
			
			/**
			* Localize Framework
			*/
			$localize_data = array( 
				'ajax_url' 	=> admin_url('admin-ajax.php'),
				'nonce'		=> wp_create_nonce('cssf-framework-nonce'),
			);
			wp_localize_script(
				'cssf-framework', 
				'cssf_framework', 
				$localize_data
			);

			// do_action('cssf_localize_framework_assets_before');

			// Localize Dynamic Scripts
			// foreach ($this->scripts as $script => $value){
			// 	wp_localize_script(
			// 		$script, 
			// 		'cssf_framework', 
			// 		$localize_data
			// 	);
			// }

			// do_action('cssf_localize_framework_assets_after');
		}

		
		/**
		* Check if WP_DEBUG & SCRIPT_DEBUG Is enabled.
		*
		* @param string $file_name
		* @param string $ext
		*
		* @return mixed|string
		* @static
		*/
		private static function is_debug( $file_name = '', $ext = 'css' ) {
			$search  = '.' . $ext;
			$replace = '.' . $ext;
			if ( ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) {
				return $file_name;
			}
			return str_replace( $search, $replace, $file_name );
		}
	}
}





if ( ! function_exists( 'cssf_assets' ) ) {
	/**
	* @return null|\CSSFramework_Assets
	*/
	function cssf_assets() {
		return CSSFramework_Assets::instance();
	}
}

if ( ! function_exists( 'cssf_load_customizer_assets' ) ) {
	/**
	* Loads CSSF Assets on customizer page.
	*/
	function cssf_load_customizer_assets() {
		cssf_assets()->render_framework_style_scripts();
	}
	
	
	if ( has_action( 'cssf_widgets' ) ) {
		add_action( 'admin_print_styles-widgets.php', 'cssf_load_customizer_assets' );
	}
}

return cssf_assets();