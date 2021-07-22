<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.castorstudio.com
 * @since      1.0.0
 *
 * @package    Plus_admin
 * @subpackage Plus_admin/admin
 */

class Plus_admin_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plus_admin-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'cst-castor-line-icons', CS_PLUS_PLUGIN_URI .'/icons/castor-line-icons/castor-line-icons.css',array(), '1.0.2', 'all');
		wp_enqueue_style( 'fontawesome', CS_PLUS_PLUGIN_URI .'/icons/fontawesome/fontawesome.css',array(), '5.0.6', 'all');
		wp_enqueue_style( 'cst-material-design-icons', CS_PLUS_PLUGIN_URI .'/icons/material-icons/material-icons.css',array(), '2.2.0', 'all');
		wp_enqueue_style( 'thickbox' ); // Used for Custom Help Tabs
		
		wp_enqueue_style( $this->plugin_name.'_google-fonts', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,600,700,800&display=swap', false );
		wp_enqueue_style( $this->plugin_name.'_google-sans', 'https://fonts.googleapis.com/css?family=Google+Sans:100,300,400,500,700,900', false );

		// AJAX CALL: All Available Themes (for all customizable sections)
		wp_enqueue_style( $this->plugin_name . '_dynamic-themes',admin_url('admin-ajax.php').'?action=plus_dynamic_themes', array($this->plugin_name), $this->version, 'all');
	}


	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * Page Loader 
		 * @since 1.0.0
		 */
		if(Plus_admin::gs('page_loader_status')){
			wp_enqueue_script( $this->plugin_name . '_pace', plugin_dir_url( __FILE__ ) . 'js/pace.js', array( ), $this->version, false );
		}


		/**
		 * Notification Center Toast
		 * @since 1.0.0
		 */ 
		if (Plus_admin::gs('notification_center_status')){
			wp_enqueue_script( $this->plugin_name . '_toast', plugin_dir_url( __FILE__ ) . 'js/jquery.toast.min.js', array( 'jquery' ), $this->version, false );
		}


		/** 
		 * PLUS Admin - Core Third Part Plugins
		 * 
		 * 1. Custom Scrollbars - jquery.overlayScrollbars.min.js
		 * 2. Tippy				- Navbar Tooltips - tippy.min.js
		 * 3. Thickbox 			- wordpress core included
		 * @since 1.0.0
		 */
		wp_enqueue_script( $this->plugin_name . '_scrollbars', plugin_dir_url( __FILE__ ) . 'js/jquery.overlayScrollbars.min.js', array( 'jquery' ), '1.7.2', false );
		wp_enqueue_script( $this->plugin_name . '_tippy', plugin_dir_url( __FILE__ ) . 'js/jquery.tippy.min.js', array( 'jquery' ), '2.5.4', false );
		wp_enqueue_script('thickbox'); // Used for Custom Help Tabs


		/** 
		 * PLUS Admin Main Javascript File
		 * @since 1.0.0
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plus_admin-admin.js', array( 'jquery' ), $this->version, false );


		/**
		 * Localize PLUS Admin Main Javascript File
		 */
		wp_localize_script( $this->plugin_name, 'plus_admin', 
			array( 
				'ajax_url' 	=> admin_url('admin-ajax.php'),
				'nonce' 	=> wp_create_nonce('cs-plus-admin-nonce'),
			)
		);
	}


	/**
	 * Get All Available Loaded Modules
	 * 
	 * Used on Options CSSFramework
	 * 
	 * Used on the "modules" checkbox fields under the "Modules" tab 
	 *
	 * @since 1.0.0
	 */
	public static function get_modules(){
		$modules = Plus_admin::get_modules()->get_modules();
		$output_field_options = array();

		if ($modules){
			foreach($modules as $module){
				$output_field_options[$module->raw_name] = array(
					'image'	=> $module->uri .'/preview.png',
					'name'	=> $module->human_name,
				);
			}
		}
		return $output_field_options;
	}

	
	/**
	 * Get Available Admin Themes for Settings Framework
	 *
	 * @since    1.0.0
	 */
	public static function get_admin_themes($preview = true){
		$active_themes = Plus_admin::get_themes()->get_themes();
		$output_field_options = array();

		if ($active_themes){
			foreach ($active_themes['themes'] as $theme){
				if ($theme->type == 'dynamic'){
					$output_field_options[$theme->raw_name] = array(
						'image'	=> $theme->uri .'/preview.png',
						'name'	=> $theme->human_name,
					);
				}
			}
		}
		return $output_field_options;
	}


	/**
	 * Get Available Admin Themes Settings for Settings Framework
	 *
	 * @since    1.0.0
	 */
	public static function get_admin_themes_settings(){
		$active_themes = Plus_admin::get_themes()->get_themes();
		$settings = array();

		if ($active_themes){
			foreach ($active_themes['themes'] as $theme){
				if ($theme->type == 'dynamic'){
					$settings[] = Plus_admin::get_theme($theme->object_name)->get_settings();
				}
			}
		}
		return $settings;
	}


	/**
	 * Generate dynamic css stylesheet
	 *
	 * @since    	1.0.0
	 * @param 		string 		String of css vars to apply to the parsed theme stylesheet
	 */
	public function dynamic_themes_callback() {
		$active_theme 			= Plus_admin::gs('theme');
		$active_theme_settings 	= Plus_admin::gs('plus_theme-'.$active_theme);
		
		$theme_settings_to_parse = array(
			'themes'	=> (object) array(
				'name'		=> $active_theme,
				'settings'	=> $active_theme_settings,
			),
		);

		
		$theme_settings_to_parse = apply_filters('cst_plusadmin/parse_theme_settings', $theme_settings_to_parse);
		
		$themes = Plus_admin::get_themes();
		$output_settings = null;
		foreach ($theme_settings_to_parse as $theme){
			$output_settings .= $themes->parse_theme_settings($theme->name,$theme->settings);
		}
		
		$output_settings = apply_filters('cst_plusadmin/parse_theme_settings_after', $output_settings);
		
		$output_settings = $this->sanitize($output_settings);
		// $showcase_style_vars			= $showcase->get_style_vars(true);
		
		$themes->parse_theme_stylesheet($output_settings,$theme_settings_to_parse);

		die();
	}


	/**
	 * Get general theme style vars
	 *
	 * @description Returns the full list of settings styles variables, to apply and use into the admin themes.
	 *
	 * @since 	1.0.0
	 * @param 	boolean 	$asString 	Return the list as a string instead of array
	 * @return 	string|array
	 */
	public function get_style_vars($asString){
		$vars = $this->style_vars;

		if ($asString){
			$vars = implode('', $vars);
		}
		return $vars;
	}
	private function sanitize($string){
		return filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	}

	/**
	 * Parse Core theme settings or Custom Styles
	 * Settings like: 
	 * - Page Loader custom styles
	 * - Custom CSS code
	 * 
	 * @since 1.0.0
	 */
	function register_core_theme_settings($settings){
		require_once CS_PLUS_PLUGIN_PATH . '/plugin_includes/class-plus_admin-theme-core.php';

		$core_theme = new Plus_admin_Theme_core();
		$core_settings = $core_theme->parse_settings();

		return $settings . $core_settings;
	}


	/**
	 * Admin Head
	 *
	 * @since    1.0.0
	 */
	function admin_init(){
		
	}


	/**
	 * Initial Configuration Check
	 */
	function admin_notices(){
		$is_firstrun = $this->is_firstrun();
		if ($is_firstrun){
			echo $this->admin_notice__firstrun();
		}
	}

	function admin_notice__firstrun(){
		$admin_url 				= admin_url('/admin.php?page=cs-plus-admin-settings');
		$notice_title 			= __("You're almost there!",'plus_admin');
		$notice_content_1		= __("It looks like this is your first run of Plus Admin, and you need to set some settings before you're ready to go.",'plus_admin');
		$notice_content_2		= __("It will take just 5 minutes to put the things in order.",'plus_admin');
		$notice_content_button 	= __('Configure Plus Admin now!','plus_admin');
		
		return "
			<div class='notice notice-warning'>
				<h3>{$notice_title}</h3>
				<p>{$notice_content_1}</p>
				<p><strong>{$notice_content_2}</strong></p>
				<p><a href='{$admin_url}' class='button button-primary'>{$notice_content_button}</a></p>
			</div>
		";
	}


	/**
	 * Admin Title
	 *
	 * @param string $admin_title The page title, with extra context added. 
 	 * @param string $title       The original page title. 
	 * @return void
	 * @since 1.0.2
	 */
	function admin_title($admin_title,$title){
		$custom_title = Plus_admin::gs('custom_title_tag');
		
		if ($custom_title){
			$sitename = get_option('blogname');

			$_tags 		= array('%full_title','%title%','%site_name%');
			$_replace 	= array($admin_title,$title,$sitename);
			$custom_title = str_replace($_tags,$_replace,$custom_title);
		}
		return $custom_title;
	}


	/**
	 * Include Favicons
	 * 
	 * This function shows the favicons generated for the site.
	 * Inject the necessary meta tgs into the admin head
	 * 
	 * @hook [action] admin_head
	 * @since 1.0.0
	 */
	function include_favicons(){
		// Favicons & Device Icons
		$devices_icons = $this->cs_plus_favicon_get_html();
		if ($devices_icons) {
			echo $devices_icons;
		}
	}


	/**
	 * Admin Body Class
	 * 
	 * @since 1.0.0
	 */
	function admin_body_class($classes){
		$cs_classes = array();

		// Page Loader
		// --------------------------------------------------------
		if (Plus_admin::gs('page_loader_status')){
			$theme = Plus_admin::gs('page_loader_theme');
			$cs_classes[] = 'cs-plus-page-loader__'.$theme;
		}


		// User Profile Settings
		// --------------------------------------------------------
		if (Plus_admin::gs('user_profile_status')){
			$cs_classes[] = 'cs-plus-userprofile_hidden';
		}
		$sections = Plus_admin::gs('user_profile_options');
		if ($sections){
			$editor = in_array('editor', $sections);
			if ($editor){
				$cs_classes[] = 'cs-plus-userprofile_hidden-editor';
			}
			$syntaxis = in_array('syntaxis', $sections);
			if ($syntaxis){
				$cs_classes[] = 'cs-plus-userprofile_hidden-syntaxis';
			}
			$colors = in_array('colors', $sections);
			if ($colors){
				$cs_classes[] = 'cs-plus-userprofile_hidden-colors';
			}
			$shortcuts = in_array('shortcuts', $sections);
			if ($shortcuts){
				$cs_classes[] = 'cs-plus-userprofile_hidden-shortcuts';
			}
			$adminbar = in_array('adminbar', $sections);
			if ($adminbar){
				$cs_classes[] = 'cs-plus-userprofile_hidden-adminbar';
			}
			$language = in_array('language', $sections);
			if ($language){
				$cs_classes[] = 'cs-plus-userprofile_hidden-language';
			}
		}


		// Top Navbar: Fixed Style, Unified Style, Hide Page Title
		// --------------------------------------------------------
		// $navbar_title = Plus_admin::gs('navbar_position');
		$navbar_title = 'fixed';
		if ($navbar_title == 'fixed'){
			$cs_classes[] = 'cs-plus-fixed-title';
		}
		
		$navbar_unify = Plus_admin::gs('navbar_titlebar_unify');
		if (!$navbar_unify){
			$cs_classes[] = 'cs-plus-not-unified-navbar';
		} else if ($navbar_unify){
			$cs_classes[] = 'cs-plus-unified-navbar';
		}

		$navbar_pagetitle = Plus_admin::gs('navbar_titlebar_hidepagetitle');
		if ($navbar_pagetitle){
			$cs_classes[] = 'cs-plus-hidden-pagetitle';
		}


		// Sidebar Fixed Style
		// --------------------------------------------------------
		$sidebar_accordion		= Plus_admin::gs('sidebar_accordion');
		$sidebar_scrollbar		= Plus_admin::gs('sidebar_scrollbar');
		// $sidebar_brand_position = Plus_admin::gs('sidebar_brand_position');
		// $sidebar_position		= Plus_admin::gs('sidebar_position');
		$sidebar_brand_position = 'fixed';
		$sidebar_position		= 'fixed';

		$sidebar_mode 			= Plus_admin::gs('sidebar_mode');
		$sidebar_autofold 		= Plus_admin::gs('sidebar_autofold_status');
		$sidebar_autofold_bp 	= Plus_admin::gs('sidebar_autofold_breakpoint');

		if ($sidebar_accordion){
			$cs_classes[] = 'cs-plus-sidebar-accordion';
		}
		if ($sidebar_scrollbar){
			$cs_classes[] = 'cs-plus-sidebar-scrollbar';
		}
		if ($sidebar_brand_position == 'fixed'){
			$cs_classes[] = 'cs-plus-sidebar-brand-fixed';
		}
		if ($sidebar_position == 'fixed'){
			$cs_classes[] = 'cs-plus-sidebar-fixed';
		}

		// Sidebar mode
		if (!$sidebar_mode){
			$sidebar_mode = 'fixed';
		} 
		$cs_classes[] = 'cs-plus-sidebar-mode--'.$sidebar_mode;

		// Sidebar Autofold
		if ($sidebar_autofold){
			$cs_classes[] = 'cs-plus-sidebar-autofold';
		}
		if ($sidebar_autofold_bp){
			$cs_classes[] = 'cs-plus-sidebar-autofold--'.$sidebar_autofold_bp;
		}

		// Sidebar Current Visibility Status
		$sidebar_current_status = get_user_setting('plusSidebar');

		$sidebar_visibility = 'visible';
		if ($sidebar_current_status){
			$sidebar_visibility = ($sidebar_current_status == 'hidden') ? 'hidden' : 'visible';
		}
		$cs_classes[] = 'cs-plus-sidebar-visibility--'.$sidebar_visibility;

		// Replace Sidebar Core Item Icons
		// --------------------------------------------------------
		if (Plus_admin::gs('sidebar_replace_core_icons')){
			$cs_classes[] = 'cs-plus-sidebar-material-icons';
		}

		// Body Content Layout
		// --------------------------------------------------------
		if (Plus_admin::gs('body_layout_type') == 'fixed'){
			$cs_classes[] = 'cs-plus-body-layout--fixed';
		} else if (Plus_admin::gs('body_layout_type') == 'boxed'){
			$cs_classes[] = 'cs-plus-body-layout--boxed';
		}

		// Fixed Footer
		// --------------------------------------------------------
		if (Plus_admin::gs('footer_fixed_status')){
			$cs_classes[] = 'cs-plus-footer-fixed';
		}


		// Filter and Return Admin Classes
		// --------------------------------------------------------
		$cs_classes = apply_filters('cst_plusadmin/body_class', $cs_classes);
		return $classes . implode(' ',$cs_classes);
	}


	/**
	 * Admin ads on dashboard widget
	 *
	 * @hook [filter] update_right_now_text
	 * @since 1.0.0
	 */
	function dashboard_widget_right_now($content){
		if (!Plus_admin::gs('rightnowwidget_status')){
			$version = $this->version;
			$content .= '<br>'.sprintf( esc_attr__('Your WordPress Admin Dashboard looks awesome thanks to %s','plus_admin'), '<a href="'.CS_PLUS_PLUGIN_URL.'" title="PLUS Admin Official Website" target="_blank">PLUS Admin '.$version.'</a>');
		}
		return $content;
	}


	/**
	 * Inject Admin Bar
	 * 
	 * Our custom top bar is injected into the page main content wrapper
	 *
	 * @hook [action] in_admin_header
	 * @since 1.0.0
	 */
	function inject_admin_bar(){
		Plus_admin_Navbar::init();
	}


	/**
	 * Inject Adminmenu Brand
	 * 
	 * The brand is injected into the adminmenu and then by javascript it is dynamically changed to its final position
	 * outside the admin menu
	 *
	 * @hook [action] adminmenu
	 * @since 1.0.2
	 */
	function inject_adminmenu_brand(){
		$brand_wrapper = Plus_admin_Navbar::get_brand_logo();
		echo "
			<div class='thisisthebrand'>
				<div class='cs-plus-header-toolbar-item cs-plus-header-toolbar-item_sidebartoggle cs-plus-header-toolbar-item-type--sidebartoggle'>
					<a title='Collapse Sidebar'>
						<span class='cs-plus-header-toolbar-item_toggle-icon-primary'>
							<i class='mdi-menu1'></i>
						</span>
						<span class='cs-plus-header-toolbar-item_toggle-icon-secondary'>
							<i class='mdi-close'></i>
						</span>
					</a>
				</div>
				{$brand_wrapper}
			</div>
		";
	}


	/**
	 * Network Sites Sidebar
	 * @since 1.0.0
	 */
	function network_sites_sidebar(){
		if (is_multisite()){
			global $wp_admin_bar;
			
			$sidebar_position 	= Plus_admin::gs('network_sidebar_position');
			$sidebar_position	= ($sidebar_position) ? $sidebar_position : 'left';
			$blog_names 		= array();
			$output_sidebar		= false;
			$sites 				= $wp_admin_bar->user->blogs;
			
			foreach ($sites as $site_id => $site){
				// $site = (object) $site; // For DEMO ONLY
				$blog_names[$site_id] = strtoupper( $site->blogname );
			}
	
			$sites_order = Plus_admin::gs('network_sidebar_sorting');
			if ($sites_order != 'none'){
				$is_excluded = (Plus_admin::gs('network_sidebar_sorting_mainsite') === true) ? true : false;
	
				if ($is_excluded){
					// Remove main blog from list
					unset($blog_names[1]);
				}
		
				// Sort menu by site name
				if ($sites_order == 'asc'){
					asort($blog_names);
				} else if ($sites_order == 'desc'){
					arsort($blog_names);
				}
				
				if ($is_excluded){
					// Add main blog back in to list
					if ($sites[1]){
						$_sites[1] = strtoupper( $sites[1]->blogname );
					}
				}
	
				foreach ($blog_names as $site_id => $site_name){
					$_sites[$site_id] = $site_name;
				}
			} else {
				$_sites = $blog_names;
			}
			
			foreach ($_sites as $site_id => $site_name){
				$current_site = (object) $sites[$site_id];
	
				$site_id		= $current_site->userblog_id;
				$site_name 		= $current_site->blogname;
				$site_domain 	= $current_site->domain;
				$site_url		= $current_site->siteurl;
				$site_admin_url = $site_url ."/wp-admin/";
	
				$output_sidebar .= "
					<li id='wp-admin-bar-blog-{$site_id}' class='menupop wp-has-submenu cs-network-site' data-name='{$site_name}'>
						<a class='ab-item wp-has-submenu' aria-haspopup='true' href='{$site_admin_url}'>
							<div class='blavatar'></div> {$site_name}
						</a>
						<ul id='wp-admin-bar-blog-{$site_id}-default' class='wp-submenu'>
							<li id='wp-admin-bar-blog-{$site_id}-d'><a class='ab-item' href='{$site_admin_url}'>Dashboard</a></li>
							<li id='wp-admin-bar-blog-{$site_id}-n'><a class='ab-item' href='{$site_admin_url}post-new.php'>New Post</a></li>
							<li id='wp-admin-bar-blog-{$site_id}-c'><a class='ab-item' href='{$site_admin_url}edit-comments.php'>Manage Comments</a></li>
							<li id='wp-admin-bar-blog-{$site_id}-v'><a class='ab-item' href='{$site_url}'>Visit Site</a></li>
						</ul>
					</li>
				";
			}
	
			$sidebar_brand = "
				<div class='sidebar-brand-wrapper'>
					<a href='#'>
						<div class='sidebar-brand_brand sidebar-brand_brand--visible'>
							<div class='sidebar-brand_icon'>
								<i class='cli cli-network-alt1'></i>
							</div>
							<div class='sidebar-brand_text'>Network Sites</div>
						</div>
					</a>
				</div>
			";
	
			$sidebar_search_field = false;
			if (Plus_admin::gs('network_sidebar_searchfield_status')){
				$sidebar_search_field = "
					<div class='cs-plus-network-sites-search-wrapper'>
						<input placeholder='".esc_attr__('Search a site','plus_admin')."' class='cs-network-site-search' type='text'>
					</div>
				";
			}
	
			$output_sidebar = "
				<div id='cs-plus-network-sites-sidebar' class='cs-plus-network-sidebar_{$sidebar_position}'>
					<div class='cs-plus-network-sites-sidebar-wrapper'>
						{$sidebar_brand}
						{$sidebar_search_field}
						<div id='cs_wp-admin-bar-my-sites-list-wrapper'>
							<ul id='cs_wp-admin-bar-my-sites-list' class='ab-sub-secondary ab-submenu'>{$output_sidebar}</ul>
						</div>
					</div>
				</div>
			";
			echo $output_sidebar;
		}
	}


	/**
	 * Plugin row action links
	 */
	function plugin_row_action_links($actions,$file){
		$plus_basename = 'plus_admin/plus_admin.php';
		if ($plus_basename != $file){ return $actions; }

		$settings = array('settings' => '<a href="admin.php?page=cs-plus-admin-settings">' . esc_attr__('Settings','plus_admin') . '</a>');
		$site_link = array('support' => '<a href="' . CS_PLUS_PLUGIN_URL . '/support/" target="_blank">'. esc_attr__('Support','plus_admin') .'</a>');

		$actions = array_merge($settings, $actions);
		$actions = array_merge($site_link, $actions);

		return $actions;
	}

	/**
	 * Plugin row meta links
	 */
	function plugin_row_meta_links( $input, $file ) {
		$plus_basename = 'plus_admin/plus_admin.php';
		if ($plus_basename != $file){ return $input; }

		$links = array(
			'<a href="' . admin_url( 'admin.php?page=cs-plus-admin-home' ) . '">' . esc_attr__( 'Getting Started','plus_admin' ) . '</a>',
			'<a href="' . CS_PLUS_PLUGIN_URL . '/docs/" target="_blank">' . esc_attr__( 'Documentation','plus_admin' ) . '</a>',
		);

		$output = array_merge( $input, $links );

		return $output;
	}


	/**
	 * On Plugin Settings Save Hook
	 *
	 * @since    1.0.0
	 */
	function save_plugin_settings($options,$framework_unique){
		// Generate Favicons
		if ($framework_unique == 'cs_plusadmin_settings'){
			$this->favicon_generate($options);
		}

		// Initial Run Check
		// If is first run, then update the plugin status
		if ($this->is_firstrun()){
			$this->update_plugin_status('initialized');
		}
	}


	/**
	 * Load Plugin Settings Config File
	 *
	 * @since 1.1.0
	 */
	function load_plugin_config(){
		require_once( 'config/plus-config.php'  );
	}


	/**
	 * Create Favicons - Apple Devices Icon - Android Devices Icon
	 * 
	 * The icons are generated by resizing the specified uploaded image
	 *
	 * @since    1.0.0
	 */
	private function favicon_generate($options){
		$favicon_status 	= $options['logo_favicon_status'];
		$apple_status 		= $options['logo_apple_status'];
		$android_status 	= $options['logo_android_status'];
		$devices			= $options['logo_devices_fs'];
		
		if ($favicon_status || $apple_status || $android_status){
			require_once CS_PLUS_PLUGIN_PATH . '/admin/includes/ImageResize.php';
	
			$site_id = false;
			if (is_multisite()) {
				$site_id 		= "/".get_current_blog_id();
			}

			$favicon_path 	= CS_PLUS_PLUGIN_PATH ."/favicons{$site_id}";

			if (!file_exists($favicon_path)) {
				mkdir($favicon_path, 0777, true);
			}
	
			// FAVICON
			if ($favicon_status){
				$favicon_id = $devices['logo_favicon'];

				if (Plus_admin::gs('logo_favicon') != $favicon_id) {
					$favicon 	= get_attached_file($favicon_id);
					$sizes		= array('16', '32', '96');
					
					if ($favicon){
						foreach ($sizes as $size){
							$image = new \Gumlet\ImageResize($favicon);
							$image
								->resizeToBestFit($size, $size)
								->save($favicon_path."/favicon-{$size}x{$size}.png");
						}
					} else {
						foreach ($sizes as $size){
							$file = $favicon_path."/favicon-{$size}x{$size}.png";
							unlink($file);
						}
					}
				}
			}
	
			// APPLE
			if ($apple_status){
				$apple_id = $devices['logo_apple'];

				if (Plus_admin::gs('logo_apple') != $apple_id) {
					$apple 	= get_attached_file($apple_id);
					$sizes 	= array('57', '60', '72', '76', '114', '120', '144', '152', '180');
		
					if ($apple){
						foreach ($sizes as $size){
							$image = new \Gumlet\ImageResize($apple);
							$image
								->resizeToBestFit($size, $size)
								->save($favicon_path."/apple-touch-icon-{$size}x{$size}.png");
						}
					} else {
						foreach ($sizes as $size){
							$file = $favicon_path."/apple-touch-icon-{$size}x{$size}.png";
							unlink($file);
						}
					}
				}
			}
	
			// ANDROID
			if ($android_status){
				$android_id = $devices['logo_android'];

				if (Plus_admin::gs('logo_android') != $android_id) {
					$android 	= get_attached_file($android_id);
					$sizes 		= array('36', '48', '72', '96', '144', '192');
		
					if ($android){
						foreach ($sizes as $size){
							$image = new \Gumlet\ImageResize($android);
							$image
								->resizeToBestFit($size, $size)
								->save($favicon_path."/android-chrome-{$size}x{$size}.png");
						}
					} else {
						foreach ($sizes as $size){
							$file = $favicon_path."/android-chrome-{$size}x{$size}.png";
							unlink($file);
						}
					}
				}
			}
		}
	}


	/**
	 * Generate Favicon/Apple/Android icons HTML code to be displayed on the admin area
	 *
	 * @since    1.0.0
	 */
	private function cs_plus_favicon_get_html(){
		$site_id = false;
		if (is_multisite()) {
			$site_id 		= "/".get_current_blog_id();
		}
		$favicon_path 	= CS_PLUS_PLUGIN_PATH ."/favicons{$site_id}";
		$favicon_uri	= CS_PLUS_PLUGIN_URI ."/favicons{$site_id}";
		$html = '';

		// FAVICON
		if (Plus_admin::gs('logo_favicon_status')){
			foreach (array('16', '32', '96') as $size) {
				$size = "{$size}x{$size}";
				if (file_exists("{$favicon_path}/favicon-{$size}.png")) {
					$html .= '<link rel="icon" type="image/png" href="'.$favicon_uri.'/favicon-'.$size.'.png" sizes="'.$size.'">';
					$html .= "\n";
				}
			}
		}

		// APPLE
		if (Plus_admin::gs('logo_favicon_status')){
			foreach (array('57', '60', '72', '76', '114', '120', '144', '152', '180') as $size){
				$size = "{$size}x{$size}";
				if (file_exists("{$favicon_path}/apple-touch-icon-{$size}.png")) {
					$html .= '<link rel="apple-touch-icon" sizes="'.$size.'" href="'.$favicon_uri.'/apple-touch-icon-'.$size.'.png">';
					$html .= "\n";
				}
			}
		}

		// ANDROID
		if (Plus_admin::gs('logo_android_status')){
			foreach (array('36', '48', '72', '96', '144', '192') as $size){
				$size = "{$size}x{$size}";
				if (file_exists("{$favicon_path}/android-chrome-{$size}.png")) {
					$html .= '<link rel="icon" type="image/png" href="'.$favicon_uri.'/android-chrome-'.$size.'.png" sizes="'.$size.'">';
					$html .= "\n";
				}
			}
		}

		return strlen($html) > 0 ? $html : false;
	}


	/**
	 * SET Admin Settings for the Admin Area [Hook: admin_footer]
	 *
	 * @since    1.0.0
	 */
	function getset_settings(){
		// General Settings
		// --------------------------------------------------------
		$logo_url = Plus_admin::gs('logo_url');
		if ($logo_url == 'admin_url') { $logo_url = str_replace('%admin_url%',admin_url(),$logo_url); }

		$navbar_elements = Plus_admin::gs('navbar_elements')['main'];
		$navbar_elements = (json_decode($navbar_elements)) ? json_decode($navbar_elements) : array();

		// Sidebar Settings
		$sidebar_status 					= (get_user_setting('mfold') == 'o') ? 'open' : 'folded';
		$sidebar_current_status 			= (get_user_setting('plusSidebar')) ? get_user_setting('plusSidebar') : 'visible';

		$output = array(
			'general'	=> array(
				'plugin_name'		=> $this->plugin_name,
				'plugin_version'	=> $this->version,
				'body_scrollbar'	=> Plus_admin::gs('bodyscrollbar_status'),
				'wp_is_mobile'		=> wp_is_mobile(),
				'is_multisite'		=> is_multisite(),
				'is_network_admin'	=> is_network_admin(),
				'is_super_admin'	=> is_super_admin(),
				'footer_fixed'		=> Plus_admin::gs('footer_fixed_status'),
			),
			'logo'		=> array(
				'status'	=> Plus_admin::gs('logo_status'),
				'url'		=> $logo_url,
				'type'		=> Plus_admin::gs('logo_type'),
				'image'		=> wp_get_attachment_url(Plus_admin::gs('logo_image')),
				'collapsed'	=> wp_get_attachment_url(Plus_admin::gs('logo_image_collapsed')),
				'icon'		=> Plus_admin::gs('logo_icon'),
				'text'		=> Plus_admin::gs('logo_text'),
			),
			'navbar' 	=> array(
				'help_title'			=> esc_attr__('Help','plus_admin'),
				'screen_title'			=> esc_attr__('Screen Options','plus_admin'),
				'sidebartoggle_button'	=> Plus_admin::gs('navbar_sidebar_toggle_button_action'),
			),
			'adminmenu' => array(
				'sidebar_status'			=> $sidebar_status,
				'status'					=> Plus_admin::gs('sidebar_status'),
				'accordion'					=> Plus_admin::gs('sidebar_accordion'),
				'scrollbar'					=> Plus_admin::gs('sidebar_scrollbar'),
				'brand_position'			=> Plus_admin::gs('sidebar_brand_position'),
				'position'					=> Plus_admin::gs('sidebar_position'),

				'autocollapse_submenu'		=> Plus_admin::gs('sidebar_autocollapse_submenu'),
				'mode'						=> Plus_admin::gs('sidebar_mode'),
				'autofold'					=> Plus_admin::gs('sidebar_autofold_status'),
				'autofold_breakpoint'		=> Plus_admin::gs('sidebar_autofold_breakpoint'),
				'current_status'			=> $sidebar_current_status,
				'remember_current_status'	=> Plus_admin::gs('sidebar_remember_current_status'),
			),
			'notifications'	=> array(
				'status'		=> Plus_admin::gs('notification_center_status'),
				'duration'		=> Plus_admin::gs('notification_center_notification_duration')['slider1'],
			),
		);

		$output = apply_filters('cst_plusadmin/getset_settings', $output);

		$output = json_encode($output);

		echo '<script type="text/javascript">$csj = jQuery.noConflict();_PLUS_ADMIN.settings = $csj.extend(true,_PLUS_ADMIN.settings, '.$output.');</script>';

	}


	/**
	 * Site "Generator" Replacement
	 * Remove "generator" tag from all the pages that use this information
	 * 
	 * 1. Completely hide site generator text
	 * 2. Replace site generator text
	 * 
	 * @since 1.0.0
	 */
	function version_remover(){
		if (Plus_admin::gs('site_generator_visibility')){
			remove_action('wp_head', 'wp_generator');   //remove inbuilt version
			remove_action('wp_head', 'woo_version');    //remove Woo-version (in case someone uses that)
		}
	}
	function generator_filter($html,$type){
		if (Plus_admin::gs('site_generator_status')){
			$generator_text		= Plus_admin::gs('site_generator_text');
			$generator_version 	= Plus_admin::gs('site_generator_version');
			$generator_url 		= Plus_admin::gs('site_generator_link');
			$generator_text 	= $generator_text ." ". $generator_version;
	
			switch($type){
				case 'html':
					$gen = '<meta name="generator" content="'.$generator_text.'">';
					break;
				case 'xhtml':
					$gen = '<meta name="generator" content="'.$generator_text.'" />';
					break;
				case 'atom':
					$gen = '<generator uri="'.$generator_url.'" version="'.$generator_version.'">'.$generator_text.'</generator>';
					break;
				case 'rss2':
					$gen = '<generator>'.$generator_text.'</generator>';
					break;
				case 'rdf':
					$gen = '<admin:generatorAgent rdf:resource="'.$generator_text.'" />';
					break;
				case 'comment':
					$gen = '<!-- generator="'.$generator_text.'" -->';
					break;
				case 'export':
					$gen = '<!-- generator="'.$generator_text.'" created="'. date('Y-m-d H:i') . '" -->';
					break;
				default:
					$gen = '';
			}
			return $gen;
		}
	}



	/**
	 * Replace Footer Text & Footer Version
	 * 
	 * @since 1.0.0
	 */
	function remove_footer_text($default){
		$status = Plus_admin::gs('footer_text_status');
		if ($status){
			$hidden = Plus_admin::gs('footer_text_visibility');
			$text 	= Plus_admin::gs('footer_text');

			echo ($hidden) ? '' : $text;
		} else {
			echo $default;
		}
	}
	function remove_footer_version($default){
		$status = Plus_admin::gs('footer_version_status');
		if ($status){
			$hidden = Plus_admin::gs('footer_version_visibility');
			$text 	= Plus_admin::gs('footer_version');
			
			echo ($hidden) ? '' : $text;
		} else {
			echo $default;
		}
	}


	/**
	 * Register the 'Admin Menu Manager Page' as a submenu page
	 * 
	 * @since 1.0.0
	 */
	function register_admin_pages($framework_unique){
		// if ($framework_unique == 'cs_plusadmin_settings'){
			$page_hook = add_submenu_page('cs-plus-admin-settings', 'PLUS Admin About', esc_attr__( 'About the Plugin','plus_admin'), 'manage_options', 'cs-plus-admin-about', 'cs_plus_admin_welcome_page' );
			add_action("load-{$page_hook}",array(&$this,'cs_plus_register_about_plugin_page'));
		// }
	}

	function cs_plus_register_about_plugin_page(){
		wp_register_style( $this->plugin_name .'_about', plugin_dir_url( __FILE__ ) . 'css/plus_admin-page-dashboard.css' );
		wp_enqueue_style( $this->plugin_name .'_about' );
	}


	/**
	 * Restrict Access to admin settings
	 * 
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @return void
	 */
	function show_admin_menu_(){
		$accessrights 	= Plus_admin::gs('accessrights');

		if ($accessrights === 'all' || !$accessrights){
			return true;
		} else if ($accessrights == 'superadmin'){
			if (is_super_admin()){
				return true;
			} else { 
				return false;
			}
		}
	}

	function show_admin_menu(){
		$accessrights 		= Plus_admin::gs('accessrights');
		$accessrights 		= Plus_admin::gs('accessrights');
		$access_current 	= Plus_admin::gs('accessrights_current');
		$current_user 		= wp_get_current_user();
		$current_username 	= $current_user->user_login;

		if (is_super_admin()){
			return true;
		}
		if ($current_username == $access_current) {
			return true;
		} else {
			if ($accessrights === 'all'){
				return true;
			} else if ($accessrights == 'superadmin'){
				if (is_super_admin()){
					return true;
				} else { 
					return false;
				}
			} else if ($accessrights == 'userrole'){
				$access_role 	= Plus_admin::gs('accessrights_role');
				if (Plus_admin::is_current_user_in_role($access_role)){
					return true;
				} else { 
					return false;
				}
			} else if ($accessrights == 'user'){
				$access_user 	= Plus_admin::gs('accessrights_user');
				$current_user 	= wp_get_current_user();
				if (!$current_user->exists()){ return false; }
				if (is_numeric($access_user)){
					$user 			= get_user_by('id', $access_user);
					$current_id 	= $current_user->ID;

					if ($user && $user->exists()){
						if ($current_id == $access_user) {
							return true;
						} else { 
							return false;
						}
					} else { 
						return true;
					}
				} else {
					$user_id = username_exists($access_user);
					if ($user_id){
						$current_user 		= wp_get_current_user();
						$current_username 	= $current_user->user_login;
						$current_userid 	= $current_user->ID;
						if (($current_username == $access_user) && ($current_userid == $user_id)) {
							return true;
						} else {
							return false;
						}
					} else { 
						return true;
					}
				}
			} else if ($accessrights == 'current'){
				$access_current = Plus_admin::gs('accessrights_current');
				// VALIDACION DEL USUARIO ACTUAL ALMACENADO EN DB
				return false;
			} else {
				return false;
			}
		}
	}


	/**
	 * Plugin Status
	 * 
	 * @since 1.0.0
	 */
	private static $plugin_status_var = 'cs_plusadmin_status';
	function get_plugin_status(){
		$status = get_option(self::$plugin_status_var);
		$status = ($status) ? $status : false;

		return $status;
	}
	function update_plugin_status($status){
		update_option(self::$plugin_status_var,$status);
	}
	function is_firstrun(){
		$status = $this->get_plugin_status();
		return ($status === false) ? true : false;
	}
}