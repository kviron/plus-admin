<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

class Plus_admin_login_theme_Minimal extends Plus_admin_Login_Theme{

	public function __construct(){
		parent::__construct();

        $this->theme_name   = 'minimal';
		$this->theme_prefix = 'plus_theme-'.$this->theme_name;

		$this->settings = (object) array(
			'show_outside_logo'		=> false,
			'show_side_logo'		=> false,
			'show_links_outside'	=> false,
		);
	}

	public function init(){
		// $this->load_dependencies();
		$this->define_hooks();
		$this->run();
    }

	public function get_settings(){
		$theme_id 		= $this->theme_prefix;
		$theme_prefix 	= $theme_id .'__';
		$theme_path 	= CS_PLUS_PLUGIN_URI .'/modules/login_page_manager/themes/'.$this->theme_name;

		$settings 		= array(
			'dependency'	=> array('theme_'.$this->theme_name,'==','true'),
			'id'			=> $theme_id,
			'type'			=> 'fieldset',
			'fields'		=> array(
                array(
					'type'			=> 'subheading',
					'content'		=> __('Minimal Theme Settings'),
				),
				array(
                    'id'        => $theme_prefix.'background_type',
                    'type'      => 'image_select',
                    'title'     => __('Background Type','plus_admin'),
                    'options'   => array(
						'gallery' 	=> CS_PLUS_PLUGIN_URI .'/admin/config/images/background-gallery.png',
						'custom' 	=> CS_PLUS_PLUGIN_URI .'/admin/config/images/background-custom.png',
						'external' 	=> CS_PLUS_PLUGIN_URI .'/admin/config/images/background-custom-external.png',
						'youtube' 	=> CS_PLUS_PLUGIN_URI .'/admin/config/images/background-video-youtube.png',
						'vimeo' 	=> CS_PLUS_PLUGIN_URI .'/admin/config/images/background-video-vimeo.png',
                    ),
                    'radio'     => true,
                    'default'   => 'external',
				),
				array(
					'dependency'    => array($theme_prefix.'background_type_gallery','==','true'),
                    'id'        	=> $theme_prefix.'background_gallery',
                    'type'      	=> 'image_gallery_custom',
					'title'     	=> __('Background Gallery','plus_admin'),
					'settings'		=> array(
						'path'			=> CS_PLUS_PLUGIN_PATH,
						'uri'			=> CS_PLUS_PLUGIN_URI,
						'images_path'	=> '/images/csbggallery/',
					),
				),
				array(
                    'dependency'	=> array($theme_prefix.'background_type_custom','==','true'),
                    'id'        	=> $theme_prefix.'background',
                    'type'      	=> 'background',
                    'title'     	=> __('Custom Background Image','plus_admin'),
                    'desc'      	=> __('Background image, color and settings etc. for login page (Eg: http://www.yourdomain.com/wp-login.php)','plus_admin'),
                    'settings'   	=> array(
                        'button_title' 	=> __('Choose Background','plus_admin'),
                        'frame_title'  	=> __('Choose an image to use as a login background','plus_admin'),
						'insert_title' 	=> __('Use this background image','plus_admin'),
						'preview_size'  => 'medium',
                        'repeat'        => true,
                        'position'      => true,
                        'attachment'    => false,
						'size'          => true,
						'color'			=> true,
                    ),
                    'default'       => array(
                        'repeat'     	=> 'no-repeat',
                        'position'   	=> 'center center',
						'attachment' 	=> 'fixed',
						'size'			=> 'cover',
                        'color'      	=> '#c4d2d3',
					),
				),
				array(
                    'dependency'    	=> array($theme_prefix.'background_type_external','==','true'),
                    'id'            	=> $theme_prefix.'background_external',
                    'type'          	=> 'background',
                    'title'         	=> __('Custom External Background Image','plus_admin'),
                    'desc'          	=> __('Background image, color and settings etc. for login page','plus_admin'),
                    'settings'       	=> array(
                        'button_title' 		=> __('Choose Background','plus_admin'),
                        'frame_title'  		=> __('Choose an image to use as a login background','plus_admin'),
						'insert_title' 		=> __('Use this background image','plus_admin'),
						'preview_size'  	=> 'medium',
                        'repeat'        	=> true,
                        'position'      	=> true,
                        'attachment'    	=> false,
						'size'          	=> true,
						'color'				=> true,
						'external_image'	=> array(
							'attributes'	=> array(
								'placeholder' => __('Paste your external image url here','plus_admin'),
							),
						),
                    ),
                    'default'       => array(
						'external_image'	=> $theme_path . '/minimal-bg.jpg',
                        'repeat'     		=> 'no-repeat',
                        'position'   		=> 'center center',
						'attachment' 		=> 'fixed',
						'size'				=> 'cover',
                        'color'      		=> '#E0E0E0',
					),
				),
				array(
					'dependency'    => array($theme_prefix.'background_type_youtube','==','true'),
					'id'			=> $theme_prefix . 'background_youtube',
					'type'			=> 'oembed',
					'title'			=> __('YouTube Video URL','plus_admin'),
					'settings'		=> array(
						'placeholder'	=> __('Insert any YouTube video url here'),
					),
					'default'	=> 'https://www.youtube.com/watch?v=3YC6RfLxsIA',
				),
				array(
					'dependency'    => array($theme_prefix.'background_type_vimeo','==','true'),
					'id'			=> $theme_prefix . 'background_vimeo',
					'type'			=> 'oembed',
					'title'			=> __('Vimeo Video URL','plus_admin'),
					'settings'		=> array(
						'placeholder'	=> __('Insert any Vimeo video url here'),
					),
					'default'	=> 'https://vimeo.com/152051763',
				),
				array(
                    'type'      => 'subheading',
                    'content'   => __('General Styling','plus_admin'),
				),
				array(
					'id'			=> $theme_prefix.'login_form_bg',
					'type'			=> 'color_picker',
					'title'			=> __('Login Form Background Color','plus_admin'),
					'default'		=> 'rgba(245,245,245,0.95)',
				),
				array(
					'id'            => $theme_prefix.'login_button_bg',
					'type'          => 'color_link',
					'title'         => __('Login Button Background Color','plus_admin'),
					'settings'		=> array(
						'regular'   => true,
						'hover'     => true,
						'active'    => true,
						'palettes'	=> array(
							// '#F44336',// red
							'#E91E63',// pink
							'#9C27B0',// purple
							'#673AB7',// deep purple
							'#3F51B5',// indigo
							// '#2196F3',// blue
							'#03A9F4',// light blue
							// '#00BCD4',// cyan
							'#009688',// teal
							// '#4CAF50',// green
							'#8BC34A',// light green
							'#CDDC39',// lime
							// '#FFEB3B',// yellow
							'#FFC107',// amber
							'#FF9800',// orange
							// '#FF5722',// deep orange
							// '#795548',// brown
							// '#9E9E9E',// grey
							// '#607D8B',// blue grey
						),
					),
					'default'       => array(
						'regular'   => '#8BC34A',
						'hover'     => '#cddc39',
						'active'    => '#6eaa40',
					),
				),
				array(
					'id'            => $theme_prefix.'login_button_color',
					'type'          => 'color_link',
					'title'         => __('Login Button Text Color','plus_admin'),
					'settings' 		=> array(
						'regular'   => true,
						'hover'     => true,
						'active'    => true,
						'palettes'	=> array(
							// '#F44336',// red
							'#E91E63',// pink
							'#9C27B0',// purple
							'#673AB7',// deep purple
							'#3F51B5',// indigo
							// '#2196F3',// blue
							'#03A9F4',// light blue
							// '#00BCD4',// cyan
							'#009688',// teal
							// '#4CAF50',// green
							'#8BC34A',// light green
							'#CDDC39',// lime
							// '#FFEB3B',// yellow
							'#FFC107',// amber
							'#FF9800',// orange
							// '#FF5722',// deep orange
							// '#795548',// brown
							// '#9E9E9E',// grey
							// '#607D8B',// blue grey
						),
					),
					'default'       => array(
						'regular'   => 'rgba(255,255,255,0.8)',
						'hover'     => 'rgba(255,255,255,1)',
						'active'    => 'rgba(255,255,255,0.8)',
					),
				),
			),
		);
		return $settings;
	}

	public function parse_settings($settings){
		$option = function($option) use ($settings){
			$theme_prefix = $this->theme_prefix.'__';
			return (isset($settings[$theme_prefix.$option])) ? $settings[$theme_prefix.$option] : false;
		};

		// Parse Settings
		// ==========================================================================

		// Login Logo
		$login_logo 	= $this->_gs('logo_image_login');
		$login_logo 	= wp_get_attachment_url($login_logo);
		$login_logo 	= "url({$login_logo})";

		// Login Background Custom
		$bg_type = $option('background_type');
		// Login Background Defaults
		$background_image_url 		= '';
		$background_image_repeat	= '';
		$background_image_position	= '';
		$background_image_size		= '';
		$background_image_color		= '';
		
		if ($bg_type){
			if ($bg_type == 'gallery'){
				$bg = $option('background_gallery');

				// Find BG File
				$bg_file_name 	= "/images/csbggallery/full/{$bg}";
				$path 			= CS_PLUS_PLUGIN_PATH . $bg_file_name;
				$uri 			= CS_PLUS_PLUGIN_URI . $bg_file_name;

				$image_url 					= "{$uri}";
				$background_image_url 		= "url($image_url)";
				$background_image_repeat	= 'no-repeat';
				$background_image_position	= 'center center';
				$background_image_size		= 'cover';
				$background_image_color		= 'transparent';
			} else if ($bg_type == 'custom'){
				$bg = $option('background');
				
				$image_url 					= wp_get_attachment_url($bg['image']);
				$background_image_url		= "url({$image_url})";
				$background_image_repeat	= $bg['repeat'];
				$background_image_position	= $bg['position'];
				$background_image_size		= $bg['size'];
				$background_image_color		= $bg['color'];
			} else if ($bg_type == 'external'){
				$bg = $option('background_external');
				
				$image_url 					= $bg['external_image'];
				$background_image_url		= "url({$image_url})";
				$background_image_repeat	= $bg['repeat'];
				$background_image_position	= $bg['position'];
				$background_image_size		= $bg['size'];
				$background_image_color		= $bg['color'];
			}
		}

		// Login Button
		$_lb_bg		= $option('login_button_bg');
		$_lb_color	= $option('login_button_color');
		$login_button_bg 			= $_lb_bg['regular'];
		$login_button_bg_hover 		= $_lb_bg['hover'];
		$login_button_bg_active 	= $_lb_bg['active'];
		$login_button_color 		= $_lb_color['regular'];
		$login_button_color_hover 	= $_lb_color['hover'];
		$login_button_color_active 	= $_lb_color['active'];

		// Login Form Background Color
		$login_form_bg = $option('login_form_bg');

		// Theme Colors
		$theme_primary 			= $login_button_bg; // #cb3002
		$theme_primary_dark 	= $login_button_bg_hover;
		$theme_primary_light 	= $login_button_bg_active;

		$theme_accent 	= $option('accent');

		// Grays
		$gray 			= '#585858';
		$gray_light		= '#b8b8b8';
		$gray_lighter	= '#e5e5e5';

		// Link
		$link_general	= $theme_primary;
		$link_accent	= $theme_accent;


		// Output Theme CSS Vars
		// ==========================================================================
		$output = "
		:root{
			%s_login-logo:						$login_logo;

			%s_login-background:				$background_image_url $background_image_repeat $background_image_position $background_image_color;
			%s_login-background-size: 			$background_image_size;

			%s_login-form-background:			$login_form_bg;

			%s_login-button-background:			$login_button_bg;
			%s_login-button-background-hover:	$login_button_bg_hover;
			%s_login-button-background-active:	$login_button_bg_active;
			%s_login-button-color:				$login_button_color;
			%s_login-button-color-hover:		$login_button_color_hover;
			%s_login-button-color-active:		$login_button_color_active;

			%s_login-theme-primary:				$theme_primary;
			%s_login-theme-primary-light:		$theme_primary_light;
			%s_login-theme-primary-dark:		$theme_primary_dark;
			%s_login-theme-accent:				$theme_accent;
			%s_login-theme-gray:				$gray;
			%s_login-theme-gray-light:			$gray_light;
			%s_login-theme-gray-lighter:		$gray_lighter;
		}
		";
		$prefix = CS_PLUS_CSS_THEME_SLUG;
		$output = str_replace('%s',$prefix,$output);
		return $output;
	}

	private function define_hooks(){
		/**
		 * Enqueue Scripts
		 */
		$this->add_action( 'login_enqueue_scripts', $this,'enqueue_scripts') ;

		/**
		 * Render HTML Fields
		 */
		$this->add_filter('login_message',$this,'render_header_title_html');
		$this->add_action('login_header',$this,'render_header_html');
		$this->add_action('login_footer',$this,'render_footer_html');
		$this->add_action('login_form',$this,'render_form_html');
		$this->add_action('lostpassword_form',$this,'render_lostpwform_html');
		$this->add_action('register_form',$this,'render_registerform_html');
	}

	function enqueue_scripts(){
		// wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/style-login.css' );
		// wp_enqueue_script( 'custom-login', get_stylesheet_directory_uri() . '/style-login.js' );
		wp_enqueue_style('cs-plus-admin-google-fonts','https://fonts.googleapis.com/css?family=Maven+Pro', false ); 
	}
	function render_header_title_html($message){
		// $pattern = "/<p ?.*>(.*)<\/p>/";
    	// preg_match($pattern, $message, $matches);
		// $_message = $matches[1];
		
		$current_page = $this->get_current_page();
		$output_message = '';
		if ($current_page == 'register'){
			$output_message .= "
				<div class='cs-plus-admin-login-title'>
					<h2>Register on our site</h2>
				</div>
			";
		} else if ($current_page == 'lostpassword'){
			$output_message .= "
				<div class='cs-plus-admin-login-title'>
					<h2>Recover your password</h2>
				</div>
			";
		} else if ($current_page == 'login'){
			$output_message .= "
				<div class='cs-plus-admin-login-title'>
					<h2>Welcome back!</h2>
				</div>
			";
		}

		$output_message .= $message;

		return $output_message;

		// return "
		// 	<div class='cs-plus-admin-login-title'>
		// 		<h2>Welcome back to Plus Admin!</h2>
		// 	</div>
		// 	{$message}
		// ";
	}
	function render_header_html(){
		$is_frozen 	= ($this->_gst('loginbox_background_style') == 'frozen-glass') ? 'cs-frozen-style' : false;
		$is_video 	= ($this->_gst('background_type') == 'youtube' || $this->_gst('background_type') == 'vimeo') ? 'cs-video-background' : false;
		$classes 	= "$is_frozen $is_video";

		$this->the_header('layout1',$classes);
	}
	function render_footer_html(){
		$this->the_footer('layout1');
	}
	function render_form_html(){
		$forgetmenot 	= $this->get_forgetmenot();
		$lostpw 		= $this->get_link_lostpassword();
		$register 		= $this->get_link_register('','<div class="or-signup">Or create a new account</div>');
		echo "
			<div class='cs-plus-admin-login-links'>
				{$forgetmenot}
				{$lostpw}
			</div>
			{$register}
		";
	}
	function render_lostpwform_html(){
		$login 			= $this->get_link_login();
		echo "
			<div class='cs-plus-admin-login-links'>
				{$login}
			</div>
		";
	}
	function render_registerform_html(){
		$login 			= $this->get_link_login();
		echo "
			<div class='cs-plus-admin-login-links'>
				{$login}
			</div>
		";
	}
	function background_content(){
		$video_type = $this->_gst('background_type');

		if ($video_type == 'youtube'){
			$video_url 	= $this->_gst('background_youtube');
			$video_id 	= $this->get_youtube_id($video_url);
			$video_obj 	= "<iframe id='player' type='text/html' width='100%' height='100%' src='https://www.youtube.com/embed/{$video_id}?autoplay=1&mute=1&controls=0&enablejsapi=1&fs=0&color=white&loop=1&showinfo=0&iv_load_policy=3&playlist={$video_id}' frameborder='0' allowfullscreen></iframe>";
		} else if ($video_type == 'vimeo'){
			$video_url 	= $this->_gst('background_vimeo');
			$video_id 	= $this->get_vimeo_id($video_url);
			$video_obj 	= "<iframe src='https://player.vimeo.com/video/{$video_id}?background=1' width='100%' height='100%' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
		}

		if (isset($video_id)){
			// return "<div id='player'></div>";
			return "<div id='cs-plus-admin-video-background-player'>{$video_obj}</div>";
		}
	}
	function footer_form(){}
	function footer_page(){}
	function additional_content(){}

}