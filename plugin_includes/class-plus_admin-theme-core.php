<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Theme: 	core
 *
 * @since 1.0.0
 * @version 1.0.1
 *
 */
class Plus_admin_Theme_core{
	public function __construct(){
        $this->theme_name   = 'core';
		$this->theme_prefix = 'plus_theme-'.$this->theme_name;
	}

	public function get_settings(){
		$theme_id 		= $this->theme_prefix;
		$theme_prefix 	= $theme_id .'__';

		$settings 		= array();
		return $settings;
	}

	public function parse_settings($settings = array()){
		$option = function($option) use ($settings){
			$theme_prefix = $this->theme_prefix.'__';
			return $settings[$theme_prefix.$option];
		};

		// Parse Settings
		// ==========================================================================
		// Body Layout Width
		if (Plus_admin::gs('body_layout_type') == 'boxed'){
			$body_boxed_layout_width = Plus_admin::gs('body_layout_boxed_width')['slider1'];
		} else {
			$body_boxed_layout_width = false;
		}

		// Page Loader
		$page_loader_custom_colors_status 	= Plus_admin::gs('page_loader_custom_colors_status');
		$page_loader_primary 	= null;
		$page_loader_secondary 	= null;
		if ($page_loader_custom_colors_status){
			$page_loader_primary 	= Plus_admin::gs('page_loader_color_primary');
			$page_loader_secondary 	= Plus_admin::gs('page_loader_color_secondary');
		}

		// Sidebar Brand Logo
		$brand_logo_normal 		= wp_get_attachment_url(Plus_admin::gs('logo_image'));
		$brand_logo_collapsed 	= wp_get_attachment_url(Plus_admin::gs('logo_image_collapsed'));

		// Image Placeholder
		$image_placeholder 		= CS_PLUS_PLUGIN_URI . '/images/castorstudio-placeholder.png';

		// Custom CSS
		$customcss_general_status 	= Plus_admin::gs('customcss_status');
		$custom_css_code_output 	= '';
		if ($customcss_general_status){
			$custom_css_code_output = $this->get_custom_css();
		}

		// Output Theme CSS Vars
		// ==========================================================================
		$output = "
			:root{
				%s_body-boxed-layout-width:		{$body_boxed_layout_width}px;

				%s_page-loader-primary:			$page_loader_primary;
				%s_page-loader-secondary:		$page_loader_secondary;

				%s_brand-logo-normal:			url($brand_logo_normal);
				%s_brand-logo-collapsed:		url($brand_logo_collapsed);

				%s_image_placeholder: 			url($image_placeholder);
			}
			$custom_css_code_output
		";
		$prefix = CS_PLUS_CSS_THEME_SLUG;
		$output = str_replace('%s',$prefix,$output);
		return $output;
	}


	/**
	 * Get Custom CSS Code based on User Roles
	 *
	 * @version 1.0.0
	 * @since 1.1.0
	 * 
	 * @return void
	 */
	private function get_custom_css(){
		// Custom CSS
		$customcss_general_status = Plus_admin::gs('customcss_status');
		$output = '';

		if ($customcss_general_status){
			$customcss			= Plus_admin::gs('customcss');

			foreach($customcss as $css_section){
				$customcss_section_status 	= isset($css_section['customcss_status']) ? $css_section['customcss_status'] : null;
				$customcss_section_userrole = isset($css_section['customcss_userrole']) ? $css_section['customcss_userrole'] : null;
				$customcss_section_code 	= isset($css_section['customcss_code']) ? $css_section['customcss_code'] : null;
				
				if ($customcss_section_status){
					if ($customcss_section_userrole && Plus_admin::helper()->is_current_user_in_role($customcss_section_userrole)){
						$output .= $customcss_section_code;
					}
				}
			}

			return $output;
		}
	}
}