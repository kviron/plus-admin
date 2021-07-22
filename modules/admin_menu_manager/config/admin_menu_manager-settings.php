<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

// Framework Page Settings
// ===============================================================================================
$settings = array(
    'menu_type'             => 'submenu', // menu, submenu, options, theme, etc.
    'menu_parent'           => 'cs-plus-admin-settings',
    'menu_title'            => __('Admin Menu Manager','plus_admin'),
    'menu_slug'             => 'cs-plus-admin-admin-menu-manager-settings',
    'menu_capability'       => 'manage_options',
    'menu_icon'             => 'dashicon-shield',
    'menu_position'         => null,
    'show_submenus'         => true,
    'framework_title'       => __('Admin Menu Manager','plus_admin'),
    'framework_subtitle'    => __('v1.0.0','plus_admin'),
    'ajax_save'             => true,
    'buttons'               => array('reset' => false),
    'option_name'           => 'cs_plus_admin_amm_settings',
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
class CS_Admin_Module_AMM_settings{

    public function set_options(){
        $options        = array();

        /* ===============================================================================================
            CUSTOMIZATION
           =============================================================================================== */
        $options[]      = array(
            'name'        => 'sidebar_customization',
            'title'       => __('Admin Menu Customization','plus_admin'),
            'icon'        => 'cli cli-sidebar',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('Admin Menu Customization','plus_admin'),
                ),
                array(
                    'id'        => 'sidebar_status',
                    'type'      => 'switcher',
                    'title'     => __('Custom Admin Menu','plus_admin'),
                    'label'     => __('Use custom admin menu','plus_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                ),
                array(
                    'type'    		=> 'content',
                    'content'		=> Plus_admin_Module_Admin_Menu_Manager::cs_menumng_settings_page(),
                    // 'wrap_class'	=> 'cs-uls-custom-content'
                ),
                
            ),
        );

        return $options;

    }

}

// Create new settings framework options page
// ===============================================================================================
cssf_new_options_page($settings,'CS_Admin_Module_AMM_settings',1000);