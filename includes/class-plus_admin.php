<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.castorstudio.com
 * @since      1.0.0
 *
 * @package    Plus_admin
 * @subpackage Plus_admin/includes
 */

class Plus_admin {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plus_admin_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The loader that's responsible for maintaining and registering all the modules that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plus_admin_Module    $modules    Maintains and registers all modules for the plugin.
	 */
	private static $modules;


	/**
	 * Themes used on the plugin
	 */
	private static $themes;


	/**
	 * Helper Functions Class
	 */
	private static $helper;



	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUS_ADMIN_VERSION' ) ) {
			$this->version = PLUS_ADMIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'plus_admin';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plus_admin_Loader. Orchestrates the hooks of the plugin.
	 * - Plus_admin_i18n. Defines internationalization functionality.
	 * - Plus_admin_Admin. Defines all hooks for the admin area.
	 * - Plus_admin_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plus_admin-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plus_admin-i18n.php';

		/**
		 * El archivo responsable de cargar el framework de Admin y los archivos externos necesarios
		 * para hacer funcionar este plugin en especifico
		 * 
		 * Se agrega aquí para poder tener disponibles las funciones antes de llamar a las acciones
		 * del área de administración y del área pública
		 * 
		 * @date 22/6/2018
		 * @modified 16/10/2019
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'plugin_includes/class-plus_admin-helperfunctions.php';
		$this::$helper = new Plus_admin_HelperFunctions();
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'plugin_includes/plugin_includes.php';
	

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-plus_admin-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-plus_admin-public.php';

		$this->loader = new Plus_admin_Loader();

		$this::$modules = new Plus_admin_Modules();

		$this::$themes = new Plus_admin_Themes();
		
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plus_admin_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Plus_admin_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Plus_admin_Admin( $this->get_plugin_name(), $this->get_version() );

		// add_filter('cst_plusadmin/navbar/before_navbar_render','__return_false');
		// add_filter('cst_plusadmin/navbar/parse_elements/userprofilelinks','__return_false');
		// add_filter('cst_plusadmin/navbar/parse_elements/usermenulinks','__return_false');
		
		// Admin Framework Hooks
		$this->loader->add_action('cssf_validate_save_after', $plugin_admin, 'save_plugin_settings',999,2);
		$this->loader->add_action('cssf_framework_load_config', $plugin_admin, 'load_plugin_config',1);

		// Admin Menu Pages
		$this->loader->add_action('admin_menu', $plugin_admin, 'register_admin_pages',990);

		/**
		 * Restrict Settings Access
		 * 
		 * @since 1.0.0
		 */ 
		$this->loader->add_filter('cssframework/cs_plusadmin_settings/show_admin',$plugin_admin,'show_admin_menu',2);


		/**
		 * Initial Check
		 * 
		 * @since 1.0.2
		 */
		$this->loader->add_action('admin_notices', $plugin_admin, 'admin_notices');

		$is_firstrun = $plugin_admin->is_firstrun();
		if (!$is_firstrun){
			// Enqueue Core Styles and Scripts
			$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles', 999 ); // Priority '999' to load after all stylesheets
			$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

			// Register Core Theme Settings
			$this->loader->add_filter('cst_plusadmin/parse_theme_settings_after', $plugin_admin, 'register_core_theme_settings');


			/**
			 * Admin Init Actions
			 * 
			 * @since 1.0.0
			 */
			$this->loader->add_action('admin_init', $plugin_admin, 'admin_init');
			$this->loader->add_filter('admin_title', $plugin_admin, 'admin_title',10, 2);
			$this->loader->add_action('admin_head', $plugin_admin, 'include_favicons');
			$this->loader->add_filter('admin_body_class', $plugin_admin, 'admin_body_class');
			$this->loader->add_filter('update_right_now_text', $plugin_admin, 'dashboard_widget_right_now');
			
			/**
			 * Admin Top Navbar
			 * 
			 * Admin Bar & Admin menu
			 * @since 1.0.0
			 */
			// $this->loader->add_action('in_admin_header', $plugin_admin, 'inject_admin_bar');
			$this->loader->add_action('wp_before_admin_bar_render', $plugin_admin, 'inject_admin_bar',99999);
			$this->loader->add_action('adminmenu', $plugin_admin, 'inject_adminmenu_brand');

	
			/**
			 * Login Head
			 * 
			 * @since 1.0.0
			 */
			$this->loader->add_action('login_head', $plugin_admin, 'include_favicons');
			
	
			/**
			 * Admin Footer Customizer
			 * 
			 * @since 1.0.0
			 */
			$this->loader->add_action('admin_print_footer_scripts', $plugin_admin, 'getset_settings'); // Function to transfer the admin settings to the js admin framework
			$this->loader->add_filter('admin_footer_text', $plugin_admin, 'remove_footer_text', 999); // Priority '999'
			$this->loader->add_filter('update_footer', $plugin_admin, 'remove_footer_version', 999); // Priority '999'
	
	
			/**
			 * Site "Generator" Replacement
			 * Clean all responses from VERSION GENERATOR
			 * 
			 * @since 1.2.0
			 */
			if (Plus_admin::gs('site_generator_status')){
				$this->loader->add_action('after_setup_theme', $plugin_admin, 'version_remover');
				$this->loader->add_filter('the_generator', $plugin_admin, 'generator_filter',10,2);
				$this->loader->add_filter('get_the_generator_html', $plugin_admin, 'generator_filter',10,2);
				$this->loader->add_filter('get_the_generator_xhtml', $plugin_admin, 'generator_filter',10,2);
				$this->loader->add_filter('get_the_generator_atom', $plugin_admin, 'generator_filter',10,2);
				$this->loader->add_filter('get_the_generator_rss2', $plugin_admin, 'generator_filter',10,2);
				$this->loader->add_filter('get_the_generator_feed', $plugin_admin, 'generator_filter',10,2);
				$this->loader->add_filter('get_the_generator_rdf', $plugin_admin, 'generator_filter',10,2);
				$this->loader->add_filter('get_the_generator_comment', $plugin_admin, 'generator_filter',10,2);
				$this->loader->add_filter('get_the_generator_export', $plugin_admin, 'generator_filter',10,2);
			}
	
	
			/**
			 * Network Sites
			 * 
			 * @since 2.0.0
			 */
			$this->loader->add_action('in_admin_header', $plugin_admin, 'network_sites_sidebar');
	
	
			/**
			 * AJAX CALLS
			 * 
			 * 1. Dynamic Themes Stylesheets
			 * 2. Dynamic Public Themes Stylesheets
			 * 
			 * @since 1.0.0
			 */
			$this->loader->add_action('wp_ajax_plus_dynamic_themes',$plugin_admin,'dynamic_themes_callback');
			$this->loader->add_action('wp_ajax_nopriv_plus_dynamic_themes',$plugin_admin,'dynamic_themes_callback');
		}


		/**
		 * Plugin Info 
		 * Filters the plugin action links on "Plugins" page
		 * 
		 * 1. Filter for plugin action links 	- Hook: plugin_action_links
		 * 2. Filter for plugin meta links 		- Hook: plugin_row_meta
		 * 
		 * @since 1.0.0
		 */
		$this->loader->add_filter( 'plugin_action_links', $plugin_admin, 'plugin_row_action_links', 10, 2 );
		$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'plugin_row_meta_links' , 10, 2 );
	}

	
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Plus_admin_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		if (Plus_admin::gs('navbar_frontend_status')){
			// ESTE Deberia ser el hook utilizado para "forzar" mostrar u ocultar la barra original en el frontend...
			// $this->loader->add_action('show_admin_bar', $plugin_public, 'for_testing');
			$this->loader->add_action('wp_head', $plugin_public, 'show_admin_bar',999 );
		}
		$this->loader->add_action('wp_before_admin_bar_render', $plugin_public, 'for_testing');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Plus_admin_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version(){
		return $this->version;
	}


	/**
	 * Retrieve the specific module instance
	 *
	 * @since     1.0.0
	 */
	static function get_modules(){
		return self::$modules;
	}
	static function get_module($module){
		return self::$modules->$module;
	}


	/**
	 * Retrieve the specific themes instance
	 *
	 * @since     1.0.0
	 */
	static function get_themes(){
		return self::$themes;
	}
	static function get_theme($theme){
		return self::$themes->$theme;
	}


	/**
	 * Helper Functions Class
	 *
	 * @since 1.0.2
	 */
	static function helper(){
		return self::$helper;
	}

	/**
	 * Get Settings Helper Class
	 *
	 * @param [type] $option
	 * @return void
	 */

	static function gs($option){
		return Plus_admin_HelperFunctions::get_settings($option);
	}
}