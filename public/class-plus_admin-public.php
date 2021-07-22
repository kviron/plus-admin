<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.castorstudio.com
 * @since      1.0.0
 *
 * @package    Plus_admin
 * @subpackage Plus_admin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Plus_admin
 * @subpackage Plus_admin/public
 * @author     Castorstudio <support@castorstudio.com>
 */
class Plus_admin_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * The Plus_admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'cst-castor-line-icons', CS_PLUS_PLUGIN_URI .'/icons/castor-line-icons/castor-line-icons.css',array(), '1.0.2', 'all');
		wp_enqueue_style( 'fontawesome', CS_PLUS_PLUGIN_URI .'/icons/fontawesome/fontawesome.css',array(), '5.0.6', 'all');
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plus_admin-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * The Plus_admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plus_admin-public.js', array( 'jquery' ), $this->version, false );

	}


	public function show_admin_bar(){
		if (!is_user_logged_in()) { return false; }
	}
	public function for_testing($adminbar){
		// echo "CHAPALAPACHALA";
		// global $wp_admin_bar;
		// $wp_admin_bar->remove_menu('wp-logo');				// Remove the WordPress logo
		// $wp_admin_bar->remove_menu('about');				// Remove the about WordPress link
		// $wp_admin_bar->remove_menu('wporg');				// Remove the WordPress.org link
		// $wp_admin_bar->remove_menu('documentation');		// Remove the WordPress documentation
		// $wp_admin_bar->remove_menu('support-forums');		// Remove the support forums link
		// $wp_admin_bar->remove_menu('feedback');				// Remove the feedback link	
		// $wp_admin_bar->remove_menu('site-name');			// Remove the site name menu
		// $wp_admin_bar->remove_menu('view-site');			// Remove the view site link
		// $wp_admin_bar->remove_menu('updates');				// Remove the updates link
		// $wp_admin_bar->remove_menu('comments');				// Remove the comments link
		// $wp_admin_bar->remove_menu('new-content');			// Remove the content link
		// $wp_admin_bar->remove_menu('my-account');			// Remove the user details tab
		// $wp_admin_bar->remove_menu('customize');			// Remove customizer link
		// $wp_admin_bar->remove_menu('delete-cache');			// Remove WP Supercache Delete Cache link
		// $wp_admin_bar->remove_menu('updraft_admin_node');	// Remove Updraft plugin link
		// $wp_admin_bar->remove_menu('w3tc');	
	}

}
