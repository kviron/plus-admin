<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

// Config Options
// ===============================================================================================
class CS_Admin_Module_HTM_settings{

    public function set_options(){
        /* ===============================================================================================
            CUSTOM HELP TABS MANAGER
           =============================================================================================== */
        $options    = array(
            'name'        => 'customhelptabs',
            'title'       => __('Custom Help Tabs Manager','plus_admin'),
            'icon'        => 'cli cli-help-circle',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('Custom Help Tabs Manager','plus_admin'),
                ),
                array(
                    'type'    => 'content',
                    'content' => __('"Help Tabs" helps users on how to exactly use any settings page by giving them more information in the help tab.','plus_admin'),
                ),
                array(
                    'id'        => 'helptabs_status',
                    'type'      => 'switcher',
                    'title'     => __('Enable Help Tabs','plus_admin'),
                    'label'     => __('Enable the use of Custom Help Tabs','plus_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                ),
                array(
                    'id'              => 'helptabs_container',
                    'type'            => 'group',
                    // 'title'           => 'Group Field',
                    // 'desc'            => 'Accordion title using the ID of the field.',
                    'button_title'    		=> __('Add Help Tab to a new page','plus_admin'),
					'accordion_title_new' 	=> __('New Help Tab Page','plus_admin'),
					'accordion_title'		=> __('Help Tab Page','plus_admin'),
					'accordion_title_field'	=> 'helptab_page',
                    'fields'          		=> array(
						array(
                            'id'        => 'helptab_status',
                            'type'      => 'switcher',
                            'title'     => __('Disable Helptab Page','plus_admin'),
                            'label'     => __('Temporarily disable this page settings and help tabs instead of deleting it.','plus_admin'),
                            'labels'    => array(
                                'on'    => __('Yes','plus_admin'),
                                'off'   => __('No','plus_admin'),
                            ),
						),
						array(
                            'id'        		=> 'helptab_userrole',
                            'type'      		=> 'select',
                            'title'    		 	=> __('User Role','plus_admin'),
                            'options'   		=> 'user_role',
							'default_option'   	=> __('All Users','plus_admin'),
							'desc'				=> __('Choose an user role to show this help.','plus_admin'),
                        ),
                        array(
                            'id'        		=> 'helptab_page',
                            'type'      		=> 'select',
                            'title'     		=> __('Page','plus_admin'),
							'options'			=> 'admin_pages',
                            'default_option'	=> __('Choose a page','plus_admin'),
						),
                        array(
                            'id'        => 'helptab_remove_original',
                            'type'      => 'switcher',
                            'title'     => __('Remove Original Help','plus_admin'),
                            'label'     => __('Remove all original previous help tabs from this page','plus_admin'),
                            'labels'    => array(
                                'on'    => __('Yes','plus_admin'),
                                'off'   => __('No','plus_admin'),
                            ),
                        ),
                        array(
                            'id'        => 'helptab_custom_sidebar',
                            'type'      => 'switcher',
                            'title'     => __('Custom Sidebar','plus_admin'),
                            'label'     => __('Use custom sidebar info','plus_admin'),
                            'labels'    => array(
                                'on'    => __('Yes','plus_admin'),
                                'off'   => __('No','plus_admin'),
                            ),
                        ),
                        array(
                            'dependency'    => array('helptab_custom_sidebar','==','true'),
                            'id'        => 'helptab_sidebar_content',
                            'type'      => 'wysiwyg',
                            'title'     => 'Custom Sidebar Help Tab Content',
                            'settings'  => array(
                                'textarea_rows' => 5,
                                'tinymce'       => true,
                                'media_buttons' => false,
                                'quicktags'     => true,
                                'teeny'         => false,
							),
							'wrap_class'	=> 'cssf-field-subfield',
                        ),
                        array(
                            'id'              => 'helptab_items',
                            'type'            => 'group',
                            // 'title'           => 'Group Field',
                            // 'desc'            => 'Accordion title using the ID of the field.',
                            'button_title'    => __('Add New Help Tab','plus_admin'),
                            'accordion_title' => __('New Help Tab','plus_admin'),
                            'fields'          => array(
                                array(
                                    'id'            => 'tab_title',
                                    'type'          => 'text',
                                    'title'         => __('Help Tab Title','plus_admin'),
                                ),
                                array(
                                    'id'            => 'tab_content',
                                    'type'          => 'wysiwyg',
                                    'title'         => __('Help Tab Content','plus_admin'),
                                    'settings'      => array(
                                        'textarea_rows' => 5,
										'tinymce'       => true,
										'media_buttons' => true,
										'quicktags'     => true,
										'teeny'         => false,
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                
            ),
        );


        return $options;

    }
}

// Create new settings framework options tab
// ===============================================================================================
cssf_new_options_tab('cssf_settings_cs_plusadmin_settings_options','CS_Admin_Module_HTM_settings','customhelptabs');