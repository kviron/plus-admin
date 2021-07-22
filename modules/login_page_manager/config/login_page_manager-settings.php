<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

// Framework Page Settings
// ===============================================================================================
$settings = array(
    'menu_type'             => 'submenu', // menu, submenu, options, theme, etc.
    'menu_parent'           => 'cs-plus-admin-settings',
    'menu_title'            => __('Login Page Manager','plus_admin'),
    'menu_slug'             => 'cs-plus-admin-login-page-manager-settings',
    'menu_capability'       => 'manage_options',
    'menu_icon'             => 'dashicon-shield',
    'menu_position'         => null,
    'show_submenus'         => true,
    'framework_title'       => __('Login Page Manager','plus_admin'),
    'framework_subtitle'    => __('v1.0.0','plus_admin'),
    'ajax_save'             => true,
    'buttons'               => array('reset' => false),
    'option_name'           => 'cs_plus_admin_lpm_settings',
    'override_location'     => '',
    'extra_css'             => array(),
    'extra_js'              => array(),
    'is_single_page'        => true,
    'is_sticky_header'      => false,
    'style'                 => 'modern',
    'help_tabs'             => array(),
    'show_all_options_link'	=> false,
);

// Config Options
// ===============================================================================================
class CS_Admin_Module_LPM_settings{

    public function set_options(){
        $options        = array();

        /* ===============================================================================================
            LOGIN PAGE GENERAL SETTINGS
        =============================================================================================== */
        $options['general'] = array(
            'name'        => 'general',
            'title'       => __('General','plus_admin'),
            'icon'        => 'cli cli-log-in',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('General Settings','plus_admin'),
                ),

                array(
                    'id'        => 'login_page_status',
                    'type'      => 'switcher',
                    'title'     => __('Login Page','plus_admin'),
                    'label'     => __('Use custom login page theme','plus_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                    'default'   => true
                ),
                
                // Login Screen Logo
                // -----------------------------------------------------------------
                array(
                    'id'            => 'logo_image_login',
                    'type'          => 'image',
                    'title'         => __('Login Page Logo','plus_admin'),
                    'desc'          => __('Upload your own logo of 350px * 100px (width * height).','plus_admin'),
                    'settings'      => array(
                        'button_title'  => __('Choose Logo','plus_admin'),
                        'frame_title'   => __('Choose an image','plus_admin'),
                        'insert_title'  => __('Use this logo','plus_admin'),
                        'preview_size'  => 'medium',
                    ),
                ),
                array(
                    'id'            => 'login_page_title_status',
                    'type'          => 'switcher',
                    'title'         => __('Page Title','plus_admin'),
                    'label'         => __('Use custom login page title','plus_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                    'default'       => true
                ),
                array(
                    'dependency'    => array('login_page_title_status','==','true'),
                    'id'            => 'login_page_title',
                    'type'          => 'text',
                    'title'         => __('Page Title Text','plus_admin'),
                    'desc'          => __('This is the "title" meta tag.','plus_admin'),
                    'default'       => __('PLUS Admin - Whitelabel WordPress Admin Theme','plus_admin'),
                    'wrap_class'	=> 'cssf-field-subfield',
                ),

                array(
                    'id'            => 'login_logo_url_status',
                    'type'          => 'switcher',
                    'title'         => __('Logo URL','plus_admin'),
                    'label'         => __('Use custom login logo url','plus_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('login_logo_url_status','==','true'),
                    'id'            => 'login_logo_url',
                    'type'          => 'text',
                    'title'         => __('Logo URL','plus_admin'),
                    'desc'          => __('This is the URL to which the logo points','plus_admin'),
                    'after'         => __('<p class="cssf-text-muted">By default this url is your bloginfo url.</p>','plus_admin'),
                    'wrap_class'	=> 'cssf-field-subfield',
                ),

                array(
                    'id'            => 'login_logo_url_title_status',
                    'type'          => 'switcher',
                    'title'         => __('Logo Title','plus_admin'),
                    'label'         => __('Use custom logo title','plus_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('login_logo_url_title_status','==','true'),
                    'id'            => 'login_logo_url_title',
                    'type'          => 'text',
                    'title'         => __('Logo Title Text','plus_admin'),
                    'desc'          => __('This is simply ALT text for the logo.','plus_admin'),
                    'wrap_class'	=> 'cssf-field-subfield',
                ),

                array(
                    'id'        => 'login_page_error_shake',
                    'type'      => 'switcher',
                    'title'     => __('Login Error Shake','plus_admin'),
                    'desc'      => __('When you enter an incorrect username or password, the login form shakes to alert the user they need to try again.','plus_admin'),
                    'label'     => __('Remove the error shake effect','plus_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                ),



                array(
                    'type'      => 'subheading',
                    'content'   => __('Login Page Links','plus_admin'),
                ),

                array(
                    'id'            => 'login_page_rememberme_status',
                    'type'          => 'switcher',
                    'title'         => __('Remember Me','plus_admin'),
                    'label'         => __('Use custom "Remember Me" text','plus_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                    'help'          => array(
                        'type'      => 'image',
			            'position'  => 'top',
                        'content'   => CS_PLUS_PLUGIN_URI .'/modules/login_page_manager/images/login-rememberme.png',
                    ),
                ),
                array(
                    'dependency'    => array('login_page_rememberme_status','==','true'),
                    'id'            => 'login_page_rememberme_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'            => 'login_page_rememberme_visibility',
                            'type'          => 'switcher',
                            'title'         => __('Link Visibility','plus_admin'),
                            'label'         => __('Hide "Remember me" link','plus_admin'),
                            'labels'        => array(
                                'on'    => __('Yes','plus_admin'),
                                'off'   => __('No','plus_admin'),
                            ),
                        ),
                        array(
                            'id'            => 'login_page_rememberme',
                            'type'          => 'text',
                            'title'         => __('Remember Me Text','plus_admin'),
                            'default'       => __('Keep session active','plus_admin'),
                        ),
                    ),
                ),
                array(
                    'id'            => 'login_page_link_back_status',
                    'type'          => 'switcher',
                    'title'         => __('Back to main site','plus_admin'),
                    'label'         => __('Use custom link options','plus_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                    'help'          => array(
                        'type'      => 'image',
			            'position'  => 'top',
                        'content'   => CS_PLUS_PLUGIN_URI .'/modules/login_page_manager/images/login-backtosite.png',
                    ),
                ),
                array(
                    'dependency'    => array('login_page_link_back_status','==','true'),
                    'id'            => 'login_page_link_back_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'            => 'login_page_link_back_visibility',
                            'type'          => 'switcher',
                            'title'         => __('Link Visibility','plus_admin'),
                            'label'         => __('Hide "Back to main site" link','plus_admin'),
                            'labels'        => array(
                                'on'    => __('Yes','plus_admin'),
                                'off'   => __('No','plus_admin'),
                            ),
                        ),
                        array(
                            'id'            => 'login_page_link_back',
                            'type'          => 'text',
                            'title'         => __('Link Text','plus_admin'),
                            'after'         => __('<p class="cssf-text-muted">Use %s as a site name wildcard</p>','plus_admin'),
                            'default'       => __('Go to Homepage','plus_admin'),
                        ),
                    )
                ),
                
                array(
                    'id'            => 'login_page_link_lostpassword_status',
                    'type'          => 'switcher',
                    'title'         => __('Lost your password?','plus_admin'),
                    'label'         => __('Use custom link options','plus_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                    'help'          => array(
                        'type'      => 'image',
			            'position'  => 'top',
                        'content'   => CS_PLUS_PLUGIN_URI .'/modules/login_page_manager/images/login-lostpassword.png',
                    ),
                ),
                array(
                    'dependency'    => array('login_page_link_lostpassword_status','==','true'),
                    'id'            => 'login_page_link_lostpassword_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'            => 'login_page_link_lostpassword_visibility',
                            'type'          => 'switcher',
                            'title'         => __('Link Visibility','plus_admin'),
                            'label'         => __('Hide "Lost your password?" link','plus_admin'),
                            'labels'        => array(
                                'on'    => __('Yes','plus_admin'),
                                'off'   => __('No','plus_admin'),
                            ),
                        ),
                        array(
                            'id'            => 'login_page_link_lostpassword',
                            'type'          => 'text',
                            'title'         => __('Link Text','plus_admin'),
                            'default'       => __('Lost your password? Click here','plus_admin'),
                        ),
                    )
                ),

                array(
                    'id'            => 'login_page_link_register_status',
                    'type'          => 'switcher',
                    'title'         => __('Register','plus_admin'),
                    'label'         => __('Use custom link text','plus_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                    'help'          => array(
                        'type'      => 'image',
			            'position'  => 'top',
                        'content'   => CS_PLUS_PLUGIN_URI .'/modules/login_page_manager/images/login-register.png',
                    ),
                ),
                array(
                    'dependency'    => array('login_page_link_register_status','==','true'),
                    'id'            => 'login_page_link_register_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'type'          => 'info',
                            'content'       => __('To change the visibility of this link, you must enable/disable "Anyone can register" Membership option under the <a href="wp-admin/options-general.php">General Settings</a> page'),
                        ),
                        array(
                            'id'            => 'login_page_link_register',
                            'type'          => 'text',
                            'title'         => __('Link Text','plus_admin'),
                            'default'       => __('Sign Up Now','plus_admin'),
                        ),
                    ),
                ),

                array(
                    'id'            => 'login_page_link_login_status',
                    'type'          => 'switcher',
                    'title'         => __('Login','plus_admin'),
                    'label'         => __('Use custom link text','plus_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                    'help'          => array(
                        'type'      => 'image',
			            'position'  => 'top',
                        'content'   => CS_PLUS_PLUGIN_URI .'/modules/login_page_manager/images/login-log-in.png',
                    ),
                ),
                array(
                    'dependency'    => array('login_page_link_login_status','==','true'),
                    'id'            => 'login_page_link_login_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'            => 'login_page_link_login_visibility',
                            'type'          => 'switcher',
                            'title'         => __('Link Visibility','plus_admin'),
                            'label'         => __('Hide "Login" link','plus_admin'),
                            'labels'        => array(
                                'on'    => __('Yes','plus_admin'),
                                'off'   => __('No','plus_admin'),
                            ),
                        ),
                        array(
                            'id'            => 'login_page_link_login',
                            'type'          => 'text',
                            'title'         => __('Link Text','plus_admin'),
                            'default'       => __('Back to Sign in','plus_admin'),
                        ),
                    ),
                ),

                array(
                    'id'            => 'login_page_button_login_status',
                    'type'          => 'switcher',
                    'title'         => __('Login Button','plus_admin'),
                    'label'         => __('Use custom login button style','plus_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                    'help'          => array(
                        'type'      => 'image',
			            'position'  => 'top',
                        'content'   => CS_PLUS_PLUGIN_URI .'/modules/login_page_manager/images/login-loginbtn.png',
                    ),
                ),
                array(
                    'dependency'    => array('login_page_button_login_status','==','true'),
                    'id'            => 'login_page_button_login',
                    'type'          => 'text',
                    'title'         => __('Button Text','plus_admin'),
                    'default'       => __('Test PLUS Admin now!','plus_admin'),
                    'wrap_class'	=> 'cssf-field-subfield',
                ),


                array(
                    'id'            => 'login_page_button_getnewpassword_status',
                    'type'          => 'switcher',
                    'title'         => __('Get New Password Button','plus_admin'),
                    'label'         => __('Use custom button text','plus_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                    'help'          => array(
                        'type'      => 'image',
			            'position'  => 'top',
                        'content'   => CS_PLUS_PLUGIN_URI .'/modules/login_page_manager/images/login-getnewpasswordbtn.png',
                    ),
                ),
                array(
                    'dependency'    => array('login_page_button_getnewpassword_status','==','true'),
                    'id'            => 'login_page_button_getnewpassword',
                    'type'          => 'text',
                    'title'         => __('Button Text','plus_admin'),
                    'default'       => __('Get New Password','plus_admin'),
                    'wrap_class'	=> 'cssf-field-subfield',
                ),







                array(
                    'type'      => 'subheading',
                    'content'   => __('Messages','plus_admin'),
                ),

                array(
                    'id'            => 'login_page_login_message_status',
                    'type'          => 'switcher',
                    'title'         => __('Login Message','plus_admin'),
                    'label'         => __('Use custom login message','plus_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('login_page_login_message_status','==','true'),
                    'id'            => 'login_page_login_message_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'            => 'login_page_login_message_color',
                            'type'          => 'color_picker',
                            'title'         => __('Login Message Text Color','plus_admin'),
                            'default'       => 'rgba(255,255,255,0.8)',
                        ),
                        array(
                            'id'            => 'login_page_login_message',
                            'type'          => 'wysiwyg',
                            'title'         => __('Login Message Text','plus_admin'),
                            'desc'          => __('Enter a custom text to show on the login screen.','plus_admin'),
                            'default'       => __('Welcome back to PLUS Admin. Please login using the user credentials below:<br><strong>Username:</strong> demo <strong>Password:</strong> demo','plus_admin'),
                            'settings'      => array(
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
                    'id'            => 'login_page_logout_message_status',
                    'type'          => 'switcher',
                    'title'         => __('Logout Message','plus_admin'),
                    'label'         => __('Use custom logout message','plus_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('login_page_logout_message_status','==','true'),
                    'id'            => 'login_page_button_login_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'            => 'login_page_logout_message_visibility',
                            'type'          => 'switcher',
                            'title'         => __('Message Visibility','plus_admin'),
                            'label'         => __('Hide loggedout message','plus_admin'),
                            'labels'        => array(
                                'on'    => __('Yes','plus_admin'),
                                'off'   => __('No','plus_admin'),
                            ),
                        ),
                        array(
                            'id'            => 'login_page_logout_message',
                            'type'          => 'wysiwyg',
                            'title'         => __('Logout Message Text','plus_admin'),
                            'desc'          => __('Enter a text to show on the logout screen.','plus_admin'),
                            'default'       => __('Now you\'re out','plus_admin'),
                            'settings'      => array(
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
                    'id'            => 'login_page_invalid_username_status',
                    'type'          => 'switcher',
                    'title'         => __('Invalid Username Message','plus_admin'),
                    'label'         => __('Use custom invalid username message','plus_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('login_page_invalid_username_status','==','true'),
                    'id'            => 'login_page_invalid_username',
                    'type'          => 'wysiwyg',
                    'title'         => __('Invalid Username Message Text','plus_admin'),
                    'desc'          => __('Enter a text to show when entering an incorrect username.','plus_admin'),
                    'default'       => __('<strong>ERROR</strong>: Invalid username.','plus_admin'),
                    'settings'      => array(
                        'textarea_rows' => 5,
                        'tinymce'       => true,
                        'media_buttons' => false,
                        'quicktags'     => false,
                        'teeny'         => true,
                    ),
                    'wrap_class'	=> 'cssf-field-subfield',
                ),

                array(
                    'id'            => 'login_page_invalid_password_status',
                    'type'          => 'switcher',
                    'title'         => __('Invalid Password Message','plus_admin'),
                    'label'         => __('Use custom invalid password message','plus_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('login_page_invalid_password_status','==','true'),
                    'id'            => 'login_page_invalid_password',
                    'type'          => 'wysiwyg',
                    'title'         => __('Invalid Password Message Text','plus_admin'),
                    'desc'          => __('Enter a text to show when entering an incorrect password.','plus_admin'),
                    'default'       => __('<strong>ERROR</strong>: The password you entered is incorrect.','plus_admin'),
                    'settings'      => array(
                        'textarea_rows' => 5,
                        'tinymce'       => true,
                        'media_buttons' => false,
                        'quicktags'     => false,
                        'teeny'         => true,
                    ),
                    'wrap_class'	=> 'cssf-field-subfield',
                ),

                array(
                    'id'            => 'login_page_invalid_captcha_status',
                    'type'          => 'switcher',
                    'title'         => __('Invalid Captcha Message','plus_admin'),
                    'label'         => __('Use custom invalid captcha message','plus_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('login_page_invalid_captcha_status','==','true'),
                    'id'            => 'login_page_invalid_captcha',
                    'type'          => 'wysiwyg',
                    'title'         => __('Invalid Captcha Message Text','plus_admin'),
                    'desc'          => __('Enter a text to show when entering an incorrect captcha.','plus_admin'),
                    'default'       => __('<strong>ERROR</strong>: The captcha you entered is incorrect.','plus_admin'),
                    'settings'      => array(
                        'textarea_rows' => 5,
                        'tinymce'       => true,
                        'media_buttons' => false,
                        'quicktags'     => false,
                        'teeny'         => true,
                    ),
                    'wrap_class'	=> 'cssf-field-subfield',
                ),
                
            ), // end: fields
        );


        /* ===============================================================================================
            LOGIN PAGE THEMES
        =============================================================================================== */
        $options['themes'] = array(
            'name'        => 'themes',
            'title'       => __('Themes','plus_admin'),
            'icon'        => 'cli cli-droplet',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('Login Page Themes','plus_admin'),
                ),
                array(
                    'type'    => 'content',
                    'content' => __('Choose a theme and customize your login screen as you want!','plus_admin'),
				),
				
				array(
					'type'			=> 'content',
					'content'		=> Plus_admin_Module_Login_Page_Manager::check_background_gallery(),
				),

                array(
                    'id'			=> 'theme',
                    'type'			=> 'image_select',
                    // 'title'			=> __('Theme','plus_admin'),
                    'radio'			=> true,
                    'options'		=> Plus_admin_Module_Login_Page_Manager::get_login_themes(),
                    'default'   	=> 'gplus',
                ),
                array(
                    'id'			=> 'theme_settings',
                    'type'			=> 'fieldset',
                    'fields'		=> Plus_admin_Module_Login_Page_Manager::get_login_themes_settings(),
                ),
            ),
        );


        /* ===============================================================================================
            LOGIN PAGE SECURITY
        =============================================================================================== */
        $options['login_page_security'] = array(
            'name'        => 'login_page_security',
            'title'       => __('Login & Logout Redirect','plus_admin'),
            'icon'        => 'cli cli-shield',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('Login & Logout Redirect','plus_admin'),
                ),

                array(
                    'id'        => 'login_security_custom_login_url_status',
                    'type'      => 'switcher',
                    'title'     => __('Custom Login URL','plus_admin'),
                    'label'     => __('Use custom login URL','plus_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                    'default'   => true
                ),
                array(
                    'dependency'    => array('login_security_custom_login_url_status','==','true'),
                    'id'            => 'login_security_custom_login_url_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'		=> 'login_security_custom_login_slug',
                            'type'		=> 'text',
                            'title' 	=> __('Login URL slug','plus_admin'),
                            'default'	=> 'plus-admin-login',
                            'info'		=> __('Important: Your new login url will be in this format: http://www.yoursite.com/your-new-login-url-slug/','plus_admin'),
                        ),
                    ),
                ),

                array(
                    'id'        => 'login_security_custom_login_redirect_status',
                    'type'      => 'switcher',
                    'title'     => __('Custom Login Redirect','plus_admin'),
                    'label'     => __('Redirect users to a custom page after login','plus_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('login_security_custom_login_redirect_status','==','true'),
                    'id'            => 'login_security_custom_login_redirect_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'            => 'login_security_custom_login_redirect_roles',
                            'type'          => 'custom_userrole',
                            'title'         => __('Redirect by User Role','plus_admin'),
                        ),
                    ),
                ),

                array(
                    'id'        => 'login_security_custom_logout_url_status',
                    'type'      => 'switcher',
                    'title'     => __('Custom Logout URL','plus_admin'),
                    'label'     => __('Use custom logout URL','plus_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                    'default'   => true
                ),
                array(
                    'dependency'    => array('login_security_custom_logout_url_status','==','true'),
                    'id'            => 'login_security_custom_logout_url_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'		=> 'login_security_custom_logout_slug',
                            'type'		=> 'text',
                            'title'		=> __('Logout URL slug','plus_admin'),
                            'default'	=> 'plus-admin-logout',
                            'info'		=> __('Important: Your new logout url will be in this format: http://www.yoursite.com/your-new-logout-url-slug/','plus_admin'),
                        ),
                    ),
                ),

                array(
                    'id'        => 'login_security_custom_logout_redirect_status',
                    'type'      => 'switcher',
                    'title'     => __('Custom Logout Redirect','plus_admin'),
                    'label'     => __('Redirect users to a custom page after logout','plus_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('login_security_custom_logout_redirect_status','==','true'),
                    'id'            => 'login_security_custom_logout_redirect_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'            => 'login_security_custom_logout_redirect_roles',
                            'type'          => 'custom_userrole',
                            'title'         => __('Redirect by User Role','plus_admin'),
                        ),
                    ),
                ),
            ),
        );


        /* ===============================================================================================
            LOGIN PAGE RECAPTCHA
        =============================================================================================== */
        $options['recaptcha'] = array(
            'name'        => 'recaptcha',
            'title'       => __('reCAPTCHA nocaptcha','plus_admin'),
            'icon'        => 'cli cli-lock',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('Login Page reCAPTCHA','plus_admin'),
                ),

                array(
                    'id'        => 'login_page_recaptcha_status',
                    'type'      => 'switcher',
                    'title'     => __('reCAPTCHA','plus_admin'),
                    'label'     => __('Use reCAPTCHA ','plus_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
                    ),
                ),
                array(
                    'type'      => 'info',
                    'content'   => __('With the default test keys, you will always get No CAPTCHA and all verification requests will pass. Please <a href="https://www.google.com/recaptcha/admin" target="_blank">register a new key</a>','plus_admin'),
                ),
                array(
                    'id'        => 'login_page_recaptcha_sitekey',
                    'type'      => 'text',
                    'title'     => __('Site Key','plus_admin'),
                    'desc'      => __('Used in the HTML code that shows your site to users.','plus_admin'),
                    'default'   => '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI',
                    // 'validate'  => 'required',
                ),
                array(
                    'id'        => 'login_page_recaptcha_secretkey',
                    'type'      => 'text',
                    'title'     => __('Secret Key','plus_admin'),
                    'desc'      => __('Used for communications between your site and Google. Be careful not to reveal it to anyone.','plus_admin'),
                    'default'   => '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe',
                    // 'validate'  => 'required',
                ),
                array(
                    'id'        => 'login_page_recaptcha_forms',
                    'type'      => 'checkbox',
                    'title'     => __('Forms to protect','plus_admin'),
                    'options'   => array(
                        'login'     => __('Login Form','plus_admin'),
                        'lostpw'    => __('Lost Password Form','plus_admin'),
                        'register'  => __('Register Form','plus_admin'),
                    ),
                    'settings'  => array(
                        'style' => 'labeled'
                    ),
                ),
                array(
                    'id'        => 'login_page_recaptcha_theme',
                    'type'      => 'image_select',
                    'title'     => __('Theme','plus_admin'),
                    'options'   => array(
                        'light' => CS_PLUS_PLUGIN_URI .'/modules/login_page_manager/images/theme-light.png',
                        'dark'  => CS_PLUS_PLUGIN_URI .'/modules/login_page_manager/images/theme-dark.png',
                    ),
                    'radio'     => true,
                    'default'   => 'light',
                ),
                array(
                    'id'        => 'login_page_recaptcha_size',
                    'type'      => 'image_select',
                    'title'     => __('Size','plus_admin'),
                    'options'   => array(
                        'normal'    => CS_PLUS_PLUGIN_URI .'/modules/login_page_manager/images/theme-light.png',
                        'compact'   => CS_PLUS_PLUGIN_URI .'/modules/login_page_manager/images/size-compact.png',
                    ),
                    'radio'     => true,
                    'default'   => 'normal',
                ),
                // To-do: Live test API Key
                // array(
                //     'type'      => 'content',
                //     'title'     => 'Test API Key',
                //     'content'   => 'here',
                // ),
            ),
        );


        return $options;

    }
}

// Create new settings framework options page
// ===============================================================================================
cssf_new_options_page($settings,'CS_Admin_Module_LPM_settings');