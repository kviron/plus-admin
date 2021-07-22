<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

// Framework Page Settings
// ===============================================================================================
$settings = array(
	'menu_type'             => 'menu', // menu, submenu, options, theme, etc.
	'menu_parent'           => '',
	'menu_title'            => 'PLUS Admin',
	'menu_slug'             => 'cs-plus-admin-settings',
	'menu_capability'       => 'manage_options',
	'menu_icon'             => 'dashicons-shield',
	'menu_position'         => 1113.12,
	'show_submenus'         => true,
	'framework_title'       => __('PLUS Admin Settings','plus_admin'),
	'framework_subtitle'    => __('by CastorStudio','plus_admin'),
	'ajax_save'             => true,
	'buttons'               => array('reset' => false),
	'option_name'           => 'cs_plusadmin_settings',
	'override_location'     => '',
	'extra_css'             => array(),
	'extra_js'              => array(),
	'is_single_page'        => true,
	'is_sticky_header'      => false,
	'style'                 => 'modern',
	'help_tabs'             => array(),
);

// Config Options
// ===============================================================================================
class CS_Plusadmin_settings_options{
	public function set_options(){
		$options        = array();
		
		/* ===============================================================================================
		PICK THEME
		=============================================================================================== */
		$options['theme'] = array(
			'name'        => 'theme',
			'title'       => __('Themes','plus_admin'),
			'icon'        => 'cli cli-droplet',
			
			// begin: fields
			'fields'      => array(
				array(
					'type'    => 'heading',
					'content' => __('Choose a Theme','plus_admin'),
				),
				array(
					'type'    => 'content',
					'content' => __('Here you can choose the theme you want, customize it or create a totally new one!','plus_admin'),
				),
				array(
					'id'			=> 'theme',
					'type'			=> 'image_select',
					// 'title'			=> __('Theme','plus_admin'),
					'radio'			=> true,
					'options'		=> Plus_admin_Admin::get_admin_themes(),
					'default'   	=> 'custom',
				),
				array(
					'id'			=> 'theme_settings',
					'type'			=> 'fieldset',
					'fields'		=> Plus_admin_Admin::get_admin_themes_settings(),
				),
				
			), // end: fields
		);
		
		
		/* ===============================================================================================
		LOGO SETTINGS
		=============================================================================================== */
		$options['logo'] = array(
			'name'        => 'logo',
			'title'       => __('Logo Settings','plus_admin'),
			'icon'        => 'cli cli-image',
			
			// begin: fields
			'fields'      => array(
				array(
					'type'    => 'heading',
					'content' => __('Logo Settings','plus_admin'),
				),
				array(
					'id'            => 'logo_url',
					'type'          => 'text',
					'title'         => __('Logo URL','plus_admin'),
					'subtitle'      => __('User will be redirected to this mentioned url when clicking the logo.','plus_admin'),
					'info'          => __('If you need to redirect to the wordpress admin area, use the <code>%admin_url%</code> tag.','plus_admin'),
					'default'       => 'admin_url',
				),
				array(
					'id'            => 'logo_type',
					'type'          => 'image_select',
					'title'         => __('Admin Logo Type','plus_admin'),
					'subtitle'      => __('Choose a logo style to use in the admin area','plus_admin'),
					'options'       => array(
						'image'     => CS_PLUS_PLUGIN_URI .'/admin/config/images/logo-type-image.png',
						'text'      => CS_PLUS_PLUGIN_URI .'/admin/config/images/logo-type-text.png',
					),
					'radio'         => true,
					'default'       => 'text'
				),
				
				// Logo Image
				// -----------------------------------------------------------------
				array(
					'dependency'    => array('logo_type_image','==','true'),
					'id'            => 'logo_type_image_fs',
					'type'          => 'fieldset',
					'fields'        => array(
						array(
							'id'            => 'logo_image',
							'type'          => 'image',
							'title'         => __('Logo Image','plus_admin'),
							'subtitle'      => __('Upload your own logo of 200px * 44px (width*height)','plus_admin'),
							'settings'      => array(
								'button_title' 	=> __('Choose Logo','plus_admin'),
								'frame_title'  	=> __('Choose an image','plus_admin'),
								'insert_title' 	=> __('Use this logo','plus_admin'),
								'preview_size'  => 'fullsize',
							),
						),
						array(
							'id'            => 'logo_image_collapsed',
							'type'          => 'image',
							'title'         => __('Logo Image Collapsed Menu','plus_admin'),
							'subtitle'      => __('Upload your own logo of 44px * 44px (width*height)','plus_admin'),
							'settings'      => array(
								'button_title' 	=> __('Choose Logo','plus_admin'),
								'frame_title'  	=> __('Choose an image','plus_admin'),
								'insert_title' 	=> __('Use this logo','plus_admin'),
								'preview_size'  => 'medium',
							),
						),
					),
				),
				
				// Logo Text
				// -----------------------------------------------------------------
				array(
					'dependency'    => array('logo_type_text','==','true'),
					'id'            => 'logo_type_text_fs',
					'type'          => 'fieldset',
					'fields'        => array(
						array(
							'id'            => 'logo_icon',
							'type'          => 'icon',
							'title'         => __('Logo Icon','plus_admin'),
							'subtitle'      => __('Choose an icon for the logo','plus_admin'),
							'default'       => 'cli cli-diamond',
						),
						array(
							'id'            => 'logo_text',
							'type'          => 'text',
							'title'         => __('Logo Text','plus_admin'),
							'subtitle'      => __('Enter the text to use in the logo','plus_admin'),
							'desc'			=> __('Ex: &ltstrong&gtPlus&lt/strong&gt Admin [subtitle]White label WordPress Admin Theme[/subtitle]'),
							'default'       => __('<strong>Plus</strong> Admin [subtitle]White label WordPress Admin Theme[/subtitle]','plus_admin'),
							'sanitize'      => false,
						),
					),
				),
				
				array(
					'id'            => 'logo_favicon_status',
					'type'          => 'switcher',
					'title'         => __('Favicon Logo','plus_admin'),
					'label'         => __('Use custom favicon for admin area','plus_admin'),
					'labels'        => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
				),
				array(
					'dependency'    => array('logo_favicon_status','==','true'),
					'id'            => 'logo_devices_fs',
					'type'          => 'fieldset',
					'fields'        => array(
						array(
							'type'    		=> 'info',
							'title'         => __('Notice','plus_admin'),
							'content'		=> __('We\'ll automatically generate 3 different favicon sizes: 16x16px, 32x32px, 96x96px. <br> To get the best results, upload an image of at least 96x96 pixels.','plus_admin'),
						),
						array(
							'id'            => 'logo_favicon',
							'type'          => 'image',
							'title'         => __('Favicon Logo Image','plus_admin'),
							'subtitle'      => __('Upload an image to use as a favicon','plus_admin'),
							'settings'       => array(
								'button_title' 	=> __('Choose Logo','plus_admin'),
								'frame_title'  	=> __('Choose an image to use as a Favicon','plus_admin'),
								'insert_title' 	=> __('Use this logo','plus_admin'),
								'preview_size'  => 'medium',
							),
						),
					),
				),
				
				array(
					'id'            => 'logo_apple_status',
					'type'          => 'switcher',
					'title'         => __('Apple Devices Logo','plus_admin'),
					'label'         => __('Use custom logo for Apple devices','plus_admin'),
					'labels'        => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
				),
				array(
					'dependency'    => array('logo_apple_status','==','true'),
					'id'            => 'logo_devices_fs',
					'type'          => 'fieldset',
					'fields'        => array(
						array(
							'type'    		=> 'info',
							'title'         => __('Notice','plus_admin'),
							'content'		=> __('We\'ll automatically generate 9 different device icon sizes: 57x57px, 60x60px, 72x72px, 76x76px, 114x114px, 120x120px, 144x144px, 152x152px, 180x180px. <br> To get the best results, upload an image of at least 180x180 pixels.','plus_admin'),
						),
						array(
							'id'            => 'logo_apple',
							'type'          => 'image',
							'title'         => __('Apple Devices Logo Image','plus_admin'),
							'subtitle'      => __('Upload an image to use as a logo for Apple devices','plus_admin'),
							'settings'       => array(
								'button_title'	=> __('Choose Logo','plus_admin'),
								'frame_title'  	=> __('Choose an image to use as a Apple devices logo','plus_admin'),
								'insert_title' 	=> __('Use this logo','plus_admin'),
								'preview_size'  => 'medium',
							),
						),
					),
				),
				
				array(
					'id'            => 'logo_android_status',
					'type'          => 'switcher',
					'title'         => __('Android Devices Logo','plus_admin'),
					'label'         => __('Use custom logo for Android devices','plus_admin'),
					'labels'        => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
				),
				array(
					'dependency'    => array('logo_android_status','==','true'),
					'id'            => 'logo_devices_fs',
					'type'          => 'fieldset',
					'fields'        => array(
						array(
							'type'    		=> 'info',
							'title'         => __('Notice','plus_admin'),
							'content'		=> __('We\'ll automatically generate 6 different device icon sizes: 36x36px, 48x48px, 72x72px, 96x96px, 144x144px, 192x192px. <br> To get the best results, upload an image of at least 192x192 pixels.','plus_admin'),
						),
						array(
							'id'            => 'logo_android',
							'type'          => 'image',
							'title'         => __('Android Devices Logo Image','plus_admin'),
							'subtitle'      => __('Upload an image to use as a logo for Android devices','plus_admin'),
							'settings'       => array(
								'button_title' 	=> __('Choose Logo','plus_admin'),
								'frame_title'  	=> __('Choose an image to use as a Android devices logo','plus_admin'),
								'insert_title' 	=> __('Use this logo','plus_admin'),
								'preview_size'  => 'medium',
							),
						),
					),
				),
			), // end: fields
		);
		
		
		/* ===============================================================================================
		SITE GENERATOR REPLACEMENT SECURITY
		=============================================================================================== */
		$options['site_generator_security'] = array(
			'name'        => 'site_generator_security',
			'title'       => __('Site Generator Replacement','plus_admin'),
			'icon'        => 'cli cli-shield',
			
			// begin: fields
			'fields'      => array(
				array(
					'type'    => 'heading',
					'content' => __('Site "Generator" Replacement','plus_admin'),
				),
				array(
					'id'        => 'site_generator_status',
					'type'      => 'switcher',
					'title'     => __('Site Generator Replacement','plus_admin'),
					'label'     => __('Use custom site generator replacement','plus_admin'),
					'labels'    => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
					'default'   => true,
				),
				array(
					'id'        => 'site_generator_visibility',
					'type'      => 'switcher',
					'title'     => __('Site Generator Visibility','plus_admin'),
					'label'     => __('Completely hide site generator text','plus_admin'),
					'labels'    => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
				),
				array(
					'id'        => 'site_generator_text',
					'type'      => 'text',
					'title'     => __('Custom Site Generator Text','plus_admin'),
					'subtitle'  => __('Enter the "generator" information text from WordPress to something you prefer.','plus_admin'),
					'default'   => __('CastorStudio.com','plus_admin'),
				),
				array(
					'id'        => 'site_generator_version',
					'type'      => 'text',
					'title'     => __('Custom Site Generator Version Text','plus_admin'),
					'subtitle'  => __('Enter the "generator version" text.','plus_admin'),
					'default'   => __('1.2.0','plus_admin'),
				),
				array(
					'id'        => 'site_generator_link',
					'type'      => 'text',
					'title'     => __('Custom Site Generator URL','plus_admin'),
					'subtitle'  => __('Enter the "generator url" from WordPress to something you prefer.','plus_admin'),
					'default'   => __('http://www.castorstudio.com','plus_admin'),
				),
			),
		);
		
		
		/* ===============================================================================================
		PAGE LOADER
		=============================================================================================== */
		$options['page_loader'] = array(
			'name'        => 'page_loader',
			'title'       => __('Page Loader','plus_admin'),
			'icon'        => 'cli cli-loader',
			
			// begin: fields
			'fields'      => array(
				array(
					'type'    => 'heading',
					'content' => __('Page Loader','plus_admin'),
				),
				array(
					'id'        => 'page_loader_status',
					'type'      => 'switcher',
					'title'     => __('Page Loader','plus_admin'),
					'label'     => __('Use custom page load progress indicator','plus_admin'),
					'labels'    => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
				),
				
				array(
					'id'        => 'page_loader_custom_colors_status',
					'type'      => 'switcher',
					'title'     => __('Custom Colors','plus_admin'),
					'label'     => __('Use custom progress loader colors','plus_admin'),
					'info'      => __('Important: By default, the progress bar uses the theme primary and primary light colors.','plus_admin'),
					'labels'    => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
				),
				array(
					'dependency'    => array('page_loader_custom_colors_status','==','true'),
					'id'            => 'page_loader_custom_colors_fs',
					'type'          => 'fieldset',
					'fields'        => array(
						array(
							'id'        => 'page_loader_color_primary',
							'type'      => 'color_picker',
							'title'     => __('Bar Primary Color','plus_admin'),
							'default'   => '#9C27B0',
						),
						array(
							'id'        => 'page_loader_color_secondary',
							'type'      => 'color_picker',
							'title'     => __('Bar Secondary Color','plus_admin'),
							'default'   => '#E1BEE7',
						),
					),
				),
				array(
					'id'        => 'page_loader_theme',
					'type'      => 'image_select',
					'title'     => __('Choose a Theme','plus_admin'),
					'options'   => array(
						'theme-1'       => CS_PLUS_PLUGIN_URI .'/admin/config/images/theme-pace-1.png',
						'theme-2'       => CS_PLUS_PLUGIN_URI .'/admin/config/images/theme-pace-2.png',
						'theme-3'       => CS_PLUS_PLUGIN_URI .'/admin/config/images/theme-pace-3.png',
						'theme-4'       => CS_PLUS_PLUGIN_URI .'/admin/config/images/theme-pace-4.png',
						'theme-5'       => CS_PLUS_PLUGIN_URI .'/admin/config/images/theme-pace-5.png',
						'theme-6'       => CS_PLUS_PLUGIN_URI .'/admin/config/images/theme-pace-6.png',
						'theme-7'       => CS_PLUS_PLUGIN_URI .'/admin/config/images/theme-pace-7.png',
						'theme-8'       => CS_PLUS_PLUGIN_URI .'/admin/config/images/theme-pace-8.png',
						'theme-9'       => CS_PLUS_PLUGIN_URI .'/admin/config/images/theme-pace-9.png',
						'theme-10'      => CS_PLUS_PLUGIN_URI .'/admin/config/images/theme-pace-10.png',
						'theme-11'      => CS_PLUS_PLUGIN_URI .'/admin/config/images/theme-pace-11.png',
						'theme-12'      => CS_PLUS_PLUGIN_URI .'/admin/config/images/theme-pace-12.png',
						'theme-13'      => CS_PLUS_PLUGIN_URI .'/admin/config/images/theme-pace-13.png',
						'theme-14'      => CS_PLUS_PLUGIN_URI .'/admin/config/images/theme-pace-14.png',
						'theme-15'      => CS_PLUS_PLUGIN_URI .'/admin/config/images/theme-pace-15.png',
					),
					'radio'     => true,
					'default'   => 'theme-15',
				),
				
			), // end: fields
		);
		
		
		/* ===============================================================================================
		USER PROFILE
		=============================================================================================== */
		$options['user_profile'] = array(
			'name'        => 'user_profile',
			'title'       => __('User Profile','plus_admin'),
			'icon'        => 'cli cli-users',
			
			// begin: fields
			'fields'      => array(
				array(
					'type'    => 'heading',
					'content' => __('User Profile','plus_admin'),
				),
				array(
					'id'        => 'user_profile_status',
					'type'      => 'switcher',
					'title'     => __('Personal Settings','plus_admin'),
					'label'     => __('Hide all user profile Personal Settings section','plus_admin'),
					'labels'    => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
				),
				array(
					'id'        => 'user_profile_options',
					'type'      => 'checkbox',
					'title'     => __('User Profile Personal Settings','plus_admin'),
					'subtitle'  => __('Check to hide the section','plus_admin'),
					'options'   => array(
						'editor'    => __('Hide Visual editor','plus_admin'),
						'syntaxis'  => __('Hide Syntaxis Highlighting','plus_admin'),
						'colors'    => __('Hide Admin colors schemes','plus_admin'),
						'shortcuts' => __('Hide Keyboard shortcuts','plus_admin'),
						'adminbar'  => __('Hide Admin Bar','plus_admin'),
						'language'  => __('Hide Language selector','plus_admin'),
					),
					'settings'  => array(
						'style'  => 'icheck',
					),
				),
				
			), // end: fields
		);
		
		
		/* 	===============================================================================================
		ADMIN TOP NAVBAR BUILDER
		=============================================================================================== */
		$options['top_navbar_builder'] = array(
			'name'        => 'top_navbar_builder',
			'title'       => __('Top Navbar Builder','plus_admin'),
			'icon'        => 'cli cli-header',
			
			// begin: fields
			'fields'      => array(
				array(
					'type'    => 'heading',
					'content' => __('Top Navbar Builder','plus_admin'),
				),
				array(
					'type'    => 'content',
					'content' => __('Create your own navbar layout, add the elements you want and rearrange them with drag&drop.','plus_admin'),
				),
				array(
					'id'        => 'navbar_elements_builder',
					'type'      => 'navbar_builder',
				),
			), // end: fields
		);
		
		
		
		/* 	===============================================================================================
		SIDEBAR ADMIN MENU
		=============================================================================================== */
		$options[]      = array(
			'name'        => 'sidebar_general',
			'title'       => __('Admin Menu','plus_admin'),
			'icon'        => 'cli cli-sidebar',
			
			// begin: fields
			'fields'      => array(
				array(
					'type'    => 'heading',
					'content' => __('Admin Menu','plus_admin'),
				),
				array(
					'id'        => 'sidebar_scrollbar',
					'type'      => 'switcher',
					'title'     => __('Custom Scrollbar','plus_admin'),
					'label'     => __('Use custom scrollbar style','plus_admin'),
					'labels'    => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
				),
				array(
					'id'        => 'sidebar_replace_core_icons',
					'type'      => 'switcher',
					'title'     => __('Replace Core Icons','plus_admin'),
					'label'     => __('Replace default dashicons for menu core items','plus_admin'),
					'labels'    => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
				),
				array(
					'id'        => 'sidebar_accordion',
					'type'      => 'switcher',
					'title'     => __('Accordion Submenu','plus_admin'),
					'label'     => __('Collapse submenu as an accordion menu','plus_admin'),
					'labels'    => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
				),
				array(
					'id'        => 'sidebar_autocollapse_submenu',
					'type'      => 'switcher',
					'title'     => __('Autocollapse Submenu','plus_admin'),
					'label'     => __('Automatically collapse the submenu when collapsing the sidebar','plus_admin'),
					'labels'    => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
				),
				array(
					'id'         	=> 'sidebar_mode',
					'type'       	=> 'select',
					'title'      	=> __('Sidebar Mode','plus_admin'),
					'options'    	=> array(
						'fixed'			=> __('Fixed Sidebar Menu','plus_admin'),
						// 'collapsible'	=> __('Collapsible Sidebar Menu','plus_admin'),
						'offcanvas'		=> __('Off Canvas Sidebar Menu','plus_admin'),
					),
					'default'    	=> 'fixed',
				),
				array(
					'id'        => 'sidebar_autofold_status',
					'type'      => 'switcher',
					'title'     => __('Sidebar Autofold','plus_admin'),
					'label'     => __('Auto collapse/hide the sidebar on mobile devices','plus_admin'),
					'labels'    => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
				),
				array(
					'dependency'	=> array('sidebar_autofold_status','==','true'),
					'id'         	=> 'sidebar_autofold_breakpoint',
					'type'       	=> 'select',
					'title'      	=> __('Sidebar Autofold Breakpoint','plus_admin'),
					'options'    	=> array(
						'960' => __('960 or less pixels width','plus_admin'),
						'782' => __('782 or less pixels width','plus_admin'),
						'600' => __('600 or less pixels width','plus_admin'),
						'400' => __('400 or less pixels width','plus_admin'),
					),
					'default'    	=> '960',
				),
				array(
					'id'		=> 'sidebar_remember_current_status',
					'type'		=> 'select',
					'title'		=> __('Remember Current Visibility Status','plus_admin'),
					'subtitle'	=> __('Maintains the visibility status of the sidebar through different pages','plus_admin'),
					'options'	=> array(
						'always'	=> __('Yes, always remember the current status','plus_admin'),
						'fixed'		=> __('Remember only on Fixed sidebar mode','plus_admin'),
						'offcanvas'	=> __('Remember only on Off Canvas sidebar mode','plus_admin'),
						'never'		=> __('No, never remember the current state','plus_admin'),
					),
					'default'	=> 'always',
				),
			), // end: fields
		);
		
		
		/* ===============================================================================================
		FOOTER
		=============================================================================================== */
		$options['footer'] = array(
			'name'        => 'footer',
			'title'       => __('Footer','plus_admin'),
			'icon'        => 'cli cli-footer',
			
			// begin: fields
			'fields'      => array(
				array(
					'type'    => 'heading',
					'content' => __('Footer','plus_admin'),
				),
				array(
					'id'        => 'footer_fixed_status',
					'type'      => 'switcher',
					'title'     => __('Fixed Footer','plus_admin'),
					'subtitle'	=> __('Choose whether to keep the footer fixed at the bottom of the page','plus_admin'),
					'label'     => __('Use fixed footer','plus_admin'),
					'labels'    => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
					'default'   => false,
				),
				array(
					'id'        => 'footer_text_status',
					'type'      => 'switcher',
					'title'     => __('Footer','plus_admin'),
					'label'     => __('Use custom footer text','plus_admin'),
					'labels'    => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
					'default'   => true,
				),
				array(
					'dependency'    => array('footer_text_status','==','true'),
					'id'            => 'footer_text_fs',
					'type'          => 'fieldset',
					'fields'        => array(
						array(
							'id'        => 'footer_text_visibility',
							'type'      => 'switcher',
							'title'     => __('Footer Text Visibility','plus_admin'),
							'label'     => __('Hide footer text','plus_admin'),
							'labels'    => array(
								'on'    => __('Yes','plus_admin'),
								'off'   => __('No','plus_admin'),
							),
						),
						array(
							'id'        => 'footer_text',
							'type'      => 'wysiwyg',
							'title'     => __('Custom Footer Text','plus_admin'),
							'subtitle'  => __('Enter the text that displays in the footer bar. HTML markup can be used.','plus_admin'),
							'default'   => __('PLUS Admin Powered by <a href="http://www.castorstudio.com" target="_blank">CastorStudio</a>','plus_admin'),
							'settings'  => array(
								'textarea_rows' => 5,
								'tinymce'       => true,
								'media_buttons' => false,
								'quicktags'     => false,
								'teeny'         => true,
							),
						),
					),
				),
				array(
					'id'            => 'footer_version_status',
					'type'          => 'switcher',
					'title'         => __('Footer Version','plus_admin'),
					'label'         => __('Use custom footer version text','plus_admin'),
					'labels'        => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
					'default'       => true,
				),
				array(
					'dependency'    => array('footer_version_status','==','true'),
					'id'            => 'footer_version_fs',
					'type'          => 'fieldset',
					'fields'        => array(
						array(
							'id'        => 'footer_version_visibility',
							'type'      => 'switcher',
							'title'     => __('Footer Version Text Visibility','plus_admin'),
							'label'     => __('Hide footer version text','plus_admin'),
							'labels'    => array(
								'on'    => __('Yes','plus_admin'),
								'off'   => __('No','plus_admin'),
							),
						),
						array(
							'id'        => 'footer_version',
							'type'      => 'wysiwyg',
							'title'     => __('Custom Version Text','plus_admin'),
							'subtitle'  => __('Enter the text that displays in the footer version bar. HTML markup can be used.','plus_admin'),
							'default'   => sprintf(__('<a href="http://www.castorstudio.com/plus-admin-wordpress-white-label-admin-theme" target="_blank">%s</a>','plus_admin'),PLUS_ADMIN_VERSION),
							'settings'  => array(
								'textarea_rows' => 5,
								'tinymce'       => true,
								'media_buttons' => false,
								'quicktags'     => false,
								'teeny'         => true,
							),
						),
					),
				),
			), // end: fields
		);
		
		
		/* ===============================================================================================
		CUSTOM CSS
		=============================================================================================== */
		$options['customcss'] = array(
			'name'        => 'customcss',
			'title'       => __('Custom CSS','plus_admin'),
			'icon'        => 'cli cli-code',
			
			// begin: fields
			'fields'      => array(
				array(
					'type'    => 'heading',
					'content' => __('Custom CSS','plus_admin'),
				),
				array(
					'id'        => 'customcss_status',
					'type'      => 'switcher',
					'title'     => __('Custom CSS','plus_admin'),
					'label'     => __('Use custom CSS code','plus_admin'),
					'labels'    => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
				),
				
				array(
					'id'              => 'customcss',
					'type'            => 'group',
					'button_title'    		=> __('Add new custom CSS code','plus_admin'),
					'accordion_title_new' 	=> __('New custom CSS code','plus_admin'),
					'accordion_title'		=> __('Custom CSS Code','plus_admin'),
					'accordion_title_field'	=> 'customcss_userrole',
					'fields'          => array(
						array(
							'id'        => 'customcss_status',
							'type'      => 'switcher',
							'title'     => __('Code block status','plus_admin'),
							'label'     => __('Enable use of this custom CSS code','plus_admin'),
							'labels'    => array(
								'on'    => __('Yes','plus_admin'),
								'off'   => __('No','plus_admin'),
							),
						),
						array(
							'id'        => 'customcss_userrole',
							'type'      => 'select',
							'title'     => __('User Role','plus_admin'),
							'options'   => 'user_role',
						),
						array(
							'id'        => 'customcss_code',
							'type'      => 'code_editor',
							'title'     => __('CSS Code','plus_admin'),
							'subtitle'  => __('The code you paste here will be applied in all your admin and login area.','plus_admin'),
							'info'      => __('Information: If you need to overwrite any CSS setting, you can add !important at the end of CSS property. eg: margin: 10px !important;','plus_admin'),
							'attributes'  => array(
								'data-theme'    => 'monokai',  // the theme for ACE Editor
								'data-mode'     => 'css',     // the language for ACE Editor
							),
						),
					),
				),
				
			),
		);
		
		
		/* ===============================================================================================
		GENERAL SETTINGS
		=============================================================================================== */
		$options['generalsettings'] = array(
			'name'        => 'generalsettings',
			'title'       => __('General Settings','plus_admin'),
			'icon'        => 'cli cli-settings',
			
			// begin: fields
			'fields'      => array(
				array(
					'type'    => 'heading',
					'content' => __('General Settings','plus_admin'),
				),

				array(
					'type'			=> 'subheading',
					'content'		=> __('General Settings','plus_admin'),
				),
				array(
					'id'        => 'resetsettings_status',
					'type'      => 'switcher',
					'title'     => __('Reset Admin Settings','plus_admin'),
					'subtitle'  => __('When you deactivate the plugin all your preferences will be deleted or reset to their default value.','plus_admin'),
					'label'     => __('Reset admin settings on plugin deactivation','plus_admin'),
					'labels'    => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
				),
				array(
					'id'        => 'rightnowwidget_status',
					'type'      => 'switcher',
					'title'     => __('Admin Name on Right Now Dashboard Widget','plus_admin'),
					'label'     => __('Hide the PLUS Admin version on Dashboard','plus_admin'),
					'labels'    => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
					'default'   => false,
				),
				// accessrights
				array(
					'id'         	=> 'accessrights',
					'type'       	=> 'button_set',
					'title'      	=> __('Plugin Settings access rights','plus_admin'),
					'subtitle'		=> __('Allow access to plugin settings to this specific users','plus_admin'),
					'options'    	=> array(
						'all'			=> __('All Users','plus_admin'),
						'superadmin'	=> __('Super Admin','plus_admin'),
						'userrole'		=> __('User Role','plus_admin'),
					),
					'default'    	=> 'all',
				),
				array(
					'dependency'		=> array('accessrights','==','user'),
					'id'            	=> 'accessrights_user',
					'type'          	=> 'text',
					'title'         	=> __('Allow Access by User','plus_admin'),
					'subtitle'      	=> __('Allow access only to this specific user','plus_admin'),
					'desc'          	=> __('Enter the username or user ID','plus_admin'),
					'default'       	=> '',
					'wrap_class'		=> 'cssf-field-subfield',
				),
				array(
					'dependency'		=> array('accessrights','==','userrole'),
					'id'            	=> 'accessrights_role',
					'type'          	=> 'select',
					'title'         	=> __('Allow Access by Userrole','plus_admin'),
					'subtitle'      	=> __('Allow access only to this specific userrole','plus_admin'),
					'options'			=> 'user_role',
					'default'       	=> 'administrator',
					'wrap_class'		=> 'cssf-field-subfield',
				),

				
				array(
					'type'			=> 'subheading',
					'content'		=> __('User Interface Settings','plus_admin'),
				),
				array(
					'id'        => 'bodyscrollbar_status',
					'type'      => 'switcher',
					'title'     => __('Custom Body Scrollbars','plus_admin'),
					'subtitle'  => __('Same as sidebar scrollbar','plus_admin'),
					'label'     => __('Use custom body scrollbar','plus_admin'),
					'labels'    => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
				),
				array(
					'id'        => 'navbar_titlebar_hidepagetitle',
					'type'      => 'switcher',
					'title'     => __('Hide Page Title','plus_admin'),
					'subtitle'	=> __('Useful when displaying the page title in the top navbar','plus_admin'),
					'label'     => __('Hide the original page title','plus_admin'),
					'labels'    => array(
						'on'    => __('Yes','plus_admin'),
						'off'   => __('No','plus_admin'),
					),
				),
				array(
					'id'            => 'custom_title_tag',
					'type'          => 'text',
					'title'         => __('Custom Title Tag','plus_admin'),
					'subtitle'      => __('Use custom text for the page title tag','plus_admin'),
					'desc'          => __('Use the <code>%full_title%</code> tag if you need to keep the page title with extra content. <br>Use the <code>%title%</code> tag if you need just the page title without extra content. <br>Use the <code>%site_name%</code> tag to refer your site name. <br> Leave blank to use the default page title','plus_admin'),
					'default'       => '%title% - Powered by Plus Admin',
				),
				array(
					'id'        => 'body_layout_type',
					'type'      => 'select',
					'title'     => __('Body Content Layout Type','plus_admin'),
					'subtitle'	=> __('Choose the layout type that will have the body content of the site','plus_admin'),
					'options'   => array(
						'fluid'	=> __('Fluid Layout','plus_admin'),
						'boxed'	=> __('Boxed Layout','plus_admin'),
					),
					'default'	=> 'fluid',
				),
				array(
					'dependency'	=> array('body_layout_type','==','boxed'),
					'id'			=> 'body_layout_boxed_width',
					'type'          => 'slider',
					'title'			=> __('Body Content: Boxed Layout Width','plus_admin'),
					'subtitle'		=> __('The body content max-width will be adjusted to the value you choose here','plus_admin'),
					'info'			=> __('Keep in mind that many plugins may have a minimum width set to work properly. If you experience any problem you can add a custom CSS rule to solve it.','plus_admin'),
					'attributes'    => array(
						'data-atts'     => 'maxwidth',
					),
					'value'         => array(
						'slider1' => 1040,
						'slider2' => 0,
					),
					'before'		=> '<label>'.esc_attr__('Max Width','plus_admin').'</label>',
					'settings'		=> array(
						'step'		=> 10,
						'min'		=> 560,
						'max'		=> 1920,
						'unit'		=> esc_attr__('px','cssf-framework'),
						'input'		=> true,
						'round'		=> true,
					),
				)

			),
		);
		
		
		/* ===============================================================================================
		NETWORK ADMIN
		=============================================================================================== */
		if (is_super_admin() && is_multisite()){
			$options['network_settings'] = array(
				'name'        => 'network_settings',
				'title'       => __('Network Settings','plus_admin'),
				'icon'        => 'cli cli-network-alt2',
				
				// begin: fields
				'fields'      => array(
					array(
						'type'    => 'heading',
						'content' => __('Network Settings','plus_admin'),
					),
					
					array(
						'type'      => 'subheading',
						'content'   => __('Network Sites Sidebar','plus_admin'),
					),
					array(
						'id'        => 'network_sidebar_position',
						'type'      => 'image_select',
						'title'     => __('Sidebar Position','plus_admin'),
						'options'   => array(
							'left'      => CS_PLUS_PLUGIN_URI .'/admin/config/images/network-sidebar-left.png',
							'right'     => CS_PLUS_PLUGIN_URI .'/admin/config/images/network-sidebar-right.png',
						),
						'default'   => 'left',
					),
					array(
						'id'        => 'network_sidebar_sorting',
						'type'      => 'select',
						'title'     => __('Sites Sorting','plus_admin'),
						'options'   => array(
							'none'  => __('None','plus_admin'),
							'asc'   => __('Ascending Sort','plus_admin'),
							'desc'  => __('Descending Sort','plus_admin'),
						),
					),
					array(
						'id'        => 'network_sidebar_sorting_mainsite',
						'type'      => 'switcher',
						'title'     => __('Main Site Sort','plus_admin'),
						'label'     => __('Exclude main site from sites sorting','plus_admin'),
						'labels'    => array(
							'on'    => __('Yes','plus_admin'),
							'off'   => __('No','plus_admin'),
						),
					),
					array(
						'id'        => 'network_sidebar_searchfield_status',
						'type'      => 'switcher',
						'title'     => __('Search Field','plus_admin'),
						'label'     => __('Use search field on the sidebar','plus_admin'),
						'labels'    => array(
							'on'    => __('Yes','plus_admin'),
							'off'   => __('No','plus_admin'),
						),
					),
				),
			);
		}
		
		
		/* ===============================================================================================
		MODULES
		=============================================================================================== */
		$options['modules'] = array(
			'name'        => 'modules',
			'title'       => __('Modules','plus_admin'),
			'icon'        => 'cli cli-package',
			
			// begin: fields
			'fields'      => array(
				array(
					'type'    => 'heading',
					'content' => __('Modules','plus_admin'),
				),
				array(
					'type'    => 'content',
					'content' => __('With modules you can extend the functionality of PLUS Admin','plus_admin'),
				),
				array(
					'id'            => 'modules',
					'type'          => 'image_select',
					'options'       => Plus_admin_Admin::get_modules(),
					'multi_select'  => true,
					'wrap_class'    => 'csf-flex-row',
				),
				
			),
		);
		
		
		/* ===============================================================================================
		BACKUP
		=============================================================================================== */
		$options[]   = array(
			'name'     => 'backup_section',
			'title'    => 'Backup',
			'icon'     => 'cli cli-shield',
			'fields'   => array(
				array(
					'type'    => 'heading',
					'content' => __('Backup','plus_admin'),
				),
				array(
					'type'    => 'notice',
					'class'   => 'warning',
					'content' => __('You can save your current options. Download a Backup and Import.','plus_admin'),
				),
				array(
					'type'    	=> 'backup',
					'settings'	=> array(
						'options'	=> array(
								'cs_plusadmin_settings',
								'cs_plus_admin_lpm_settings',
								'cs_plus_admin_amm_settings',
								'cs_plusadmin_adminmenu',
								'cs_plusadmin_adminsubmenu',
							),
						),
					),
				),
			);
			return $options;
		}
	}
	
	// Create new settings framework options page
	// ===============================================================================================
	cssf_new_options_page($settings,'CS_Plusadmin_settings_options');