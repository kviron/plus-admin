<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

class Plus_admin_Module_Admin_Menu_Manager extends Plus_admin_Module{
	private static $instance = null;
	
	function __construct(){
		parent::__construct();

		$this->name     	= 'login_page_manager';
		$this->version  	= '1.0.0';
		$this->plugin_name 	= 'plus_admin_menu_manager';
		$this->unique 		= 'cs_plus_admin_amm_settings';

		return $this;
	}

    public static function getInstance() {
        if (is_null(self::$instance) || !(self::$instance instanceof Plus_admin_Module_Admin_Menu_Manager))
            self::$instance = new Plus_admin_Module_Admin_Menu_Manager();
        return self::$instance;
    }
	
    public function init(){
        $this->load_dependencies();
        $this->define_admin_hooks();
		$this->define_public_hooks();
    }

    private function load_dependencies(){
		require_once( 'includes/cs-wp-menu-output.php' );
    }

    private function define_admin_hooks(){
        /**
         * Load Module Config Settings Options
         * 
         * @since 2.0.0
         */
        $this->add_action('cssf_framework_load_config', $this, 'load_module_config');


        /**
         * Enqueue Scripts
         * 
         * @since 2.0.0
         */
        $this->add_action( 'admin_enqueue_scripts', $this, 'enqueue_scripts' );


        /**
		 * AJAX CALLS
		 * 
		 * 1. Menu Save
		 * 2. Menu Reset
		 * 3. Menu User Role Change
		 * 
		 * @since 2.0.0
		 */
		$this->add_action('wp_ajax_ipido_menu_save', $this, 'menu_save_callback');
        $this->add_action('wp_ajax_ipido_menu_reset', $this, 'menu_reset_callback');
        $this->add_action('wp_ajax_ipido_menu_change', $this, 'menu_change_callback');


        /**
		 * Custom Admin Menu Manager
		 * 
		 * @since 1.2.0
		 */

		if ($this->gs('sidebar_status')){
            $this->add_filter( 'custom_menu_order', $this, 'maybe_custom_adminmenu', 100, 1 );
			$this->add_filter( 'menu_order', $this, 'custom_admin_menu_manager', 100, 1 );
		}
    }

    private function define_public_hooks(){

    }



    /**
     * ------------------------------------------------------------------------------------------------
     * 
     * Module Specific Functionality
     * 
     * ------------------------------------------------------------------------------------------------
     */


    
    /**
	 * Load Config Settings - CSSFRAMEWORK
     * 
     * @since 2.0.0
	 */
	public function load_module_config(){
		require_once( 'config/admin_menu_manager-settings.php'  );
    }



    /**
     * Enqueue Scripts
     * 
     * @since 2.0.0
     */
    public function enqueue_scripts(){
        wp_enqueue_script('plus_admin_tippy', CS_PLUS_PLUGIN_URI . '/admin/js/jquery.tippy.min.js', array( 'jquery' ), '', false );
        wp_enqueue_script('plus_admin_menumanager', plugin_dir_url( __FILE__ ) . 'js/admin_menu_manager.js', array('plus_admin'), '', false );
    }


	
	/**
	 * Get current user role
	 * 
	 * @since 1.2.0
	 */
	private function cs_get_current_user_role(){
		if (is_user_logged_in()){
			$user = wp_get_current_user();
			$role = ( array ) $user->roles;
			return $role[0];
		} else {
			return 'administrator';
		}
	}



	public function maybe_custom_adminmenu(){
		// echo "<b>Si custom menu...</b>";
		return true;
	}


	/**
	 * Change/replace the admin menu based on active user role
	 *
	 * @hook ACTION admin_menu
	 * @since 1.2.0
	 */
	public function custom_admin_menu_manager($menu_order){
        global $menu;
        global $submenu;

		$GLOBALS['menu_rearrange'] 				= true;
		$GLOBALS['cs_ipido_original_menu'] 		= $menu;
        $GLOBALS['cs_ipido_original_submenu'] 	= $submenu;
        
        // Set Temporal Original Admin Menu
        update_option('cs_plusadmin_adminmenu_temporal',$menu);
        update_option('cs_plusadmin_adminsubmenu_temporal',$submenu);

		$current_user_role = $this->cs_get_current_user_role();

		$_adminmenu = $this->cs_menumanager_parser($menu,$submenu,$current_user_role);
		$newmenu 	= $this->cs_menumanager_visibility_parser($_adminmenu);

		$menu 		= $newmenu['menu'];
		$submenu 	= $newmenu['submenu'];
		
		return $menu_order;
    }
    
    

    /**
     * Admin Menu Parser
     * Rename and Reorder the Admin Menu
     * 
     * @since 1.2.0
     */
    private function cs_menumanager_parser($the_menu,$the_submenu,$current_user_role){
        $output_menu 	= $the_menu;
        $output_submenu = $the_submenu;
		$_adminmenu 	= Plus_admin::helper()->get_option("cs_plusadmin_adminmenu");
		
		// Encontrar el menu personalizado para el rol de usuario $current_user_role
        if (is_array($_adminmenu)){
            $_adminmenu = (isset($_adminmenu[$current_user_role])) ? $_adminmenu[$current_user_role] : null;
		}
		
		// Si existe el menu personalizado para el rol
        if ($_adminmenu){
			// echo "<h3>Admin Menu: {$current_user_role}</h3>";
            // Rename Menu and Submenu
            foreach ( $the_menu as $key => $item ) {
				/**
				 * The elements in each item array are:
				 * 0: Menu title
				 * 1: Minimum level or capability required.
				 * 2: The URL of the item's file
				 * 3: Page Title
				 * 4: Classes
				 * 5: ID
				 * 6: Icon for top level menu
				 */
                $menu_name          = (isset($item[0])) ? $item[0] : null;
                $menu_capability    = (isset($item[1])) ? $item[1] : null;
				$menu_slug          = (isset($item[2])) ? urldecode($item[2]) : null;
				$menu_slug          = (isset($item[2])) ? $item[2] : null;
				$menu_pagetitle 	= (isset($item[3])) ? $item[3] : null;
				$menu_classes 		= (isset($item[4])) ? $item[4] : null;
                $menu_id			= (isset($item[5])) ? $item[5] : null;
                $menu_icon			= (isset($item[6])) ? $item[6] : null;
                $menu_index			= $key;
                $menu_submenu       = (isset($the_submenu[$menu_slug])) ? $the_submenu[$menu_slug] : null;

				// echo "<br>Menu: {$key}";
				// echo "<pre>";
				// print_r($item);
				// print_r($menu_submenu);
				// echo "</pre>";

                $new_menu_item = null;
                if (!isset($_adminmenu[$menu_index])){
                    $new_menu_item = array(
                        'id'    => $menu_id,
                        'slug'  => $menu_slug,
                        'index' => $menu_index,
                        'state' => 'enabled',
                        'name'  => $menu_name,
                        'icon'  => $menu_icon,
                        'submenu'   => null,
                    );
                    $_adminmenu[$menu_index] = $new_menu_item;
                }

                $_item              = (isset($_adminmenu[$menu_index])) ? $_adminmenu[$menu_index] : $item; // Get custom menu item data
                $_menu_state        = (isset($_item['state'])) ? $_item['state'] : 'enabled';

                // Menu Item Notifications Bubble
                $_menu_name = null;
                if (isset($_item['name'])){
                    $_menu_name = htmlspecialchars_decode($_item['name'],ENT_QUOTES);
                    $_menu_name = $_item['name'];
                    $menu_notifications         = $this->get_notifications_count($menu_name);
                    $menu_notifications_bubble  = "<span class='update-plugins awaiting-mod count-{$menu_notifications}'><span class='plugin-count'>{$menu_notifications}</span></span>";
                    $_menu_name                 = str_replace('%bubble%',$menu_notifications_bubble,$_item['name']);
                    $output_menu[$key]['has_notifications'] = $_item['name'];
                }

                $output_menu[$key][0] = (isset($_item['name'])) ? $_menu_name : $menu_name;
                $output_menu[$key][6] = (isset($_item['icon'])) ? $_item['icon'] : $menu_icon;
                $output_menu[$key]['name_original']    = $menu_name;
                $output_menu[$key]['icon_original']    = $menu_icon;
                $output_menu[$key]['state']            = $_menu_state;

                if ($menu_submenu){
                    $_item_submenu = (isset($_item['submenu'])) ? $_item['submenu'] : null;
        
                    foreach($menu_submenu as $key => $item){
                        $submenu_name       = (isset($item[0])) ? $item[0] : null;
                        $submenu_slug       = (isset($item[2])) ? (new self)->cs_menumanager_removesp($item[2]) : null;
                        $submenu_capability = (isset($item[1])) ? $item[1] : null;
                        $submenu_id         = (isset($_item_submenu['id'])) ? $_item_submenu['id'] : $key;
                        
                        // Search current original submenu item
                        $_submenu_index = $key;
    
                        $new_submenu_item   = null;
                        $_submenu_name      = null;
                        if (!isset($_item_submenu[$_submenu_index])){
                            $new_submenu_item = array(
                                'id'    => $submenu_id,
                                'slug'  => $submenu_slug,
                                'state' => 'enabled',
                                'name'  => $submenu_name,
                            );

                            $_item = $new_submenu_item;
                            $_adminmenu[$menu_index]['submenu'][$_submenu_index] = $new_submenu_item;
                        } 
                        
                        $_item              = (isset($_item_submenu[$_submenu_index])) ? $_item_submenu[$_submenu_index] : $item; // Get custom menu item data
                        $_submenu_name      = (isset($_item['name'])) ? $_item['name'] : $submenu_name;
                        $_submenu_state     = (isset($_item['state'])) ? $_item['state'] : 'enabled';

                        // SubMenu Item Notifications Bubble
                        if (isset($_item['name'])){
                            if (!$_submenu_name){
                                $_submenu_name = htmlspecialchars_decode($_item['name'],ENT_QUOTES);
                                $_submenu_name = $_item['name'];
                            }
                            $menu_notifications         = $this->get_notifications_count($submenu_name);
                            $menu_notifications_bubble  = "<span class='update-plugins awaiting-mod count-{$menu_notifications}'><span class='plugin-count'>{$menu_notifications}</span></span>";
                            $_submenu_name              = str_replace('%bubble%',$menu_notifications_bubble,$_submenu_name);

                            $output_submenu[$menu_slug][$key]['has_notifications'] = $_item['name'];
                        }

                        $output_submenu[$menu_slug][$key][0]               = $_submenu_name;
                        $output_submenu[$menu_slug][$key]['name_original'] = $submenu_name;
                        $output_submenu[$menu_slug][$key]['id']            = $submenu_id;
                        $output_submenu[$menu_slug][$key]['state']         = $_submenu_state;
                    }
                }
            }

            // Reorder
            $reorder_state = false;
            if (isset($_adminmenu) && $reorder_state){
                $menu_reorder = array();
                $submenu_reorder = array();
                
                foreach ( $_adminmenu as $key => $item ) {
                    $menu_slug  = (isset($item['slug'])) ? $item['slug'] : null;
                    $menu_id    = (isset($item['id'])) ? $item['id'] : null;
                    $menu_index = (isset($item['index'])) ? $item['index'] : null;
                    $_submenu   = (isset($item['submenu'])) ? $item['submenu'] : null;
            
                    $_index = $key;

                    if (isset($the_menu[$_index])){
                        $menu_reorder[$_index] = $output_menu[$_index];
                        
                        if (isset($_submenu)){
                            $_submenuorder = array();
                            foreach ($_submenu as $key => $item){
                                $menu_id = $item['id'];
                                if (isset($output_submenu[$menu_slug][$menu_id])){
                                    $__submenu = $output_submenu[$menu_slug][$menu_id];
                                    $_submenuorder[$key] = $__submenu;
                                }
                            }

                            $submenu_reorder[$menu_slug] = $_submenuorder;
                        }
                    }
                }

                $output_menu       = $menu_reorder;
                $output_submenu    = $submenu_reorder;
            }
        }

        return array(
            'menu'      => $output_menu,
            'submenu'   => $output_submenu,
        );
    }

    private function cs_menumanager_visibility_parser($adminmenu){
        $menu       = $adminmenu['menu'];
        $submenu    = $adminmenu['submenu'];

        $_menu      = array();
        $_submenu   = array();
        foreach($menu as $key => $item){
            if (isset($item['state'])){
                if ($item['state'] == 'enabled'){
                    $_menu[$key] = $item;
                }
            } else {
                $_menu[$key] = $item;
            }
        }
        
        foreach($submenu as $key => $item){
            if (is_array($item)){
                foreach($item as $_key => $_item){
                    if (isset($_item['state'])){
                        if ($_item['state'] == 'enabled'){
                            $_submenu[$key][$_key] = $_item;
                        }
                    } else {
                        $_submenu[$key][$_key] = $_item;
                    }
                }
            }
        }
        return array(
            'menu'      => $_menu,
            'submenu'   => $_submenu
        );
    }

    static public function cs_menumng_settings_page($current_user_role = 'administrator', $is_ajax_request = false){
        /**
         * Custom Admin Menu by User Role
         * 
         * @since 1.2.0
         */
        
        global $wp_roles;
        global $menu_rearrange;
        global $menu;
        global $submenu;


        $the_menu = $menu;
        $the_submenu = $submenu;
        if ($menu || $submenu){
            $temporal_menu = Plus_admin::helper()->get_option('cs_plusadmin_adminmenu_temporal');
            $temporal_submenu = Plus_admin::helper()->get_option('cs_plusadmin_adminsubmenu_temporal');

            if ($temporal_menu == false || $temporal_submenu == false){
                Plus_admin::helper()->update_option('cs_plusadmin_adminmenu_temporal',$menu);
                Plus_admin::helper()->update_option('cs_plusadmin_adminsubmenu_temporal',$submenu);
            }
        }
        if ($is_ajax_request){
            $temporal_menu = Plus_admin::helper()->get_option('cs_plusadmin_adminmenu_temporal');
            $temporal_submenu = Plus_admin::helper()->get_option('cs_plusadmin_adminsubmenu_temporal');

            if ($temporal_menu && $temporal_submenu){
                $the_menu = Plus_admin::helper()->get_option('cs_plusadmin_adminmenu_temporal');
                $the_submenu = Plus_admin::helper()->get_option('cs_plusadmin_adminsubmenu_temporal');
            }
        }

        if ($menu_rearrange){
            $temporal_menu = Plus_admin::helper()->get_option('cs_plusadmin_adminmenu_temporal');
            $temporal_submenu = Plus_admin::helper()->get_option('cs_plusadmin_adminsubmenu_temporal');

            if ($temporal_menu && $temporal_submenu){
                $the_menu = Plus_admin::helper()->get_option('cs_plusadmin_adminmenu_temporal');
                $the_submenu = Plus_admin::helper()->get_option('cs_plusadmin_adminsubmenu_temporal');
            } else {
                global $cs_ipido_original_menu;
                global $cs_ipido_original_submenu;

                $the_menu       = $cs_ipido_original_menu;
                $the_submenu    = $cs_ipido_original_submenu;
            }
        } 

        $default_user_role  = 'administrator';
        if ($is_ajax_request){
            $current_user_role  = (isset($current_user_role)) ? $current_user_role : $default_user_role;
        } else {
            $current_user_role  = (isset($_GET['cs_plus_admin_user_role'])) ? $_GET['cs_plus_admin_user_role'] : $default_user_role;
        }

        $roles              = $wp_roles->get_names();
        $user_roles_options = '';
        $html_output        = '';
        
        if( !empty( $roles ) ){
            foreach ( $roles as $key => $value ) {
                $selected = ($current_user_role == $key) ? 'selected="selected"' : '';
                $user_roles_options .= "<option value='{$key}' {$selected}>{$value}</option>";
            }
        }

        $role = get_role( $current_user_role );
        $capabilities = $role->capabilities;

        if (!$capabilities){
            $html_output = '<h2>No hay permisos para este rol de usuario...</h2>';
        }

        // i18n support
        $text_menumanager_title         = __('PLUS Admin Menu Manager','plus_admin');
        $text_toolbar_order_initial     = __('Restore Initial Order','plus_admin');
        $text_toolbar_order_asc         = __('Sort Asc','plus_admin');
        $text_toolbar_order_desc        = __('Sort Desc','plus_admin');
        $text_toolbar_expand_all        = __('Expand All','plus_admin');
        $text_toolbar_collapse_all      = __('Collapse All','plus_admin');
        $text_toolbar_menu_move         = __('Move Menu','plus_admin');
        $text_toolbar_menu_show         = __('Show Menu','plus_admin');
        $text_toolbar_menu_hide         = __('Hide Menu','plus_admin');
        $text_toolbar_menu_edit         = __('Edit Menu','plus_admin');
        $text_toolbar_submenu_expand    = __('Expand Submenu','plus_admin');
        $text_toolbar_submenu_collapse  = __('Collapse Submenu','plus_admin');
        $text_toolbar_submenu_move      = __('Move Submenu','plus_admin');
        $text_toolbar_submenu_show      = __('Show Submenu','plus_admin');
        $text_toolbar_submenu_hide      = __('Hide Submenu','plus_admin');
        $text_toolbar_submenu_edit      = __('Edit Submenu','plus_admin');
        $text_menu_original             = __('Original','plus_admin');
        $text_menu_rename               = __('Rename to','plus_admin');
        $text_menu_rename_help          = __('Use %bubble% to display dynamic bubble notifications for this menu item','plus_admin');
        $text_menu_icon                 = __('Menu Icon','plus_admin');
        $text_instructions              = __('Instructions','plus_admin');
        $text_instructions_list         = sprintf(__('<li>Choose a user role from the select box, then press "Re/Load Menu" button.</li><li>Drag and Drop %s to rearrange menu and sub menu items.</li><li>Click on %s icon to show or hide the menu or submenu item.</li><li>Click on %s icon to edit menu name and icon, and submenu name.</li><li>Click on %s icon to collapse or %s icon to expand the submenu items of the selected menu.</li>','plus_admin'),'<span><i class="cli cli-move"></i></span>','<span><i class="cli cli-eye"></i></span>','<span><i class="cli cli-edit-3"></i></span>','<span><i class="cli cli-unfold-less"></i></span>','<span><i class="cli cli-unfold-more"></i></span>');
        $text_btn_reset                 = __('Reset to Original','plus_admin');
        $text_btn_save                  = __('Save Menu','plus_admin');
        $text_userroles_title           = sprintf(__('Select user role','plus_admin'),$current_user_role);
        $text_userroles_btn             = __('Re/Load Menu','plus_admin');

        
        // Definir y actualizar el $menu y $submenu
        $_adminmenu = (new self)->cs_menumanager_parser($the_menu,$the_submenu,$current_user_role);
        $_menu      = $_adminmenu['menu'];
        $_submenu   = $_adminmenu['submenu'];

        foreach ( $_menu as $key => $item ) {
            /**
             * The elements in each item array are :
             * 0: Menu title
             * 1: Minimum level or capability required.
             * 2: The URL of the item's file
             * 3: Page Title
             * 4: Classes
             * 5: ID
             * 6: Icon for top level menu
             * 
             * Aditional elements, added by cs_menumanager_parser()
             * name_original: Original menu title/name
             * icon_original: Original menu icon
             * state        : Visibility state
             */

            $menu_name          = (isset($item[0])) ? $item[0] : null;
            if (isset($item['has_notifications'])){
                $menu_name      = $item['has_notifications'];
            }
            $menu_name_encoded  = htmlspecialchars($menu_name,ENT_QUOTES);
            $menu_icon          = (isset($item[6])) ? $item[6] : null;
            $menu_slug          = (isset($item[2])) ? urldecode($item[2]) : null;
            $menu_slug          = (isset($item[2])) ? $item[2] : null;
            $menu_capability    = (isset($item[1])) ? $item[1] : null;
            $menu_id            = (isset($item[5])) ? $item[5] : null;
            $menu_name_original = (isset($item['name_original'])) ? wp_strip_all_tags($item['name_original']) : $menu_name;
            $menu_icon_original = (isset($item['icon_original'])) ? $item['icon_original'] : $menu_icon;
            $menu_index         = $key;

            $menu_state         = (isset($item['state'])) ? $item['state'] : 'enabled';
            $menu_state_icon    = ($menu_state == 'enabled') ? 'cli-eye' : 'cli-eye-off';
            $menu_class         = 'cs-menu-'.$menu_state;


            // Get Notification Bubbles
            $_name              = $item[0];
            $_bubbles           = (new self)->get_notifications_count($_name);
            $menu_notifications = false;
            if ($_bubbles !== false){
                $menu_notifications = $_bubbles;
            }


            $menu_icons_panel   = (new self)->cs_menuicons_list();

            $_has_cap = (new self)->cs_menumanager_has_cap($current_user_role,$menu_capability);

            if ($_has_cap){
                if ($menu_slug == 'separator1' || $menu_slug == 'separator2' || $menu_slug == 'separator-last'){
                    $html_output .= "
                                    <div class='cs-menu-manager_menu-wrapper cs-admin-menu-item enabled' data-menu-state='enabled' data-menu-id='menu-separator' data-menu-slug='{$menu_slug}' data-menu-index='{$menu_index}'>
                                        <div class='cs-menu-manager_item'>
                                            <div class='cs-menu-manager_item-heading'>
                                                <div class='cs-menu-manager_item-heading-title'>
                                                    <span class='cs-menu-title-icon dashicons-before'><i class='cli cli-separator-horizontal'></i></span>
                                                    <span class='cs-menu-title'>Separator</span>
                                                </div>
                                                <div class='cs-menu-manager_item-heading-toolbar'>
                                                    <div class='cs-menu-manager_item-heading-toolbar_action cs-mm-action-move'><i class='cli cli-move'></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                } else {
                    $item_submenu           = (isset($_submenu[$menu_slug])) ? $_submenu[$menu_slug] : false;
                    $toolbar_icon_submenu   = ($item_submenu) ? "<div class='cs-menu-manager_item-heading-toolbar_action cs-mm-action-toggle-submenu cs-menu-submenu-open' title='{$text_toolbar_submenu_collapse}' data-title-collapse='{$text_toolbar_submenu_collapse}' data-title-expand='{$text_toolbar_submenu_expand}'><i class='cli cli-unfold-less'></i></div>" : false;

                    $html_output .= "<div class='cs-menu-manager_menu-wrapper cs-admin-menu-item {$menu_state}' data-menu-state='{$menu_state}' data-menu-id='{$menu_id}' data-menu-index='{$menu_index}' data-menu-slug='{$menu_slug}' data-menu-icon='{$menu_icon}' data-menu-name='{$menu_name_encoded}' data-menu-notifications='{$menu_notifications}'>";
                    $html_output .= "
                                <div class='cs-menu-manager_item' data-menu-notifications='{$menu_notifications}'>
                                    <div class='cs-menu-manager_item-heading'>
                                        <div class='cs-menu-manager_item-heading-title'>
                                            <span class='cs-menu-title-icon dashicons-before {$menu_icon}'></span>
                                            <span class='cs-menu-title'>{$menu_name}</span>
                                        </div>
                                        <div class='cs-menu-manager_item-heading-toolbar'>
                                            <div class='cs-menu-manager_item-heading-toolbar_action cs-mm-action-move' title='{$text_toolbar_menu_move}'><i class='cli cli-move'></i></div>
                                            <div class='cs-menu-manager_item-heading-toolbar_action cs-mm-action-display {$menu_class}' title='{$text_toolbar_menu_hide}' data-title-show='{$text_toolbar_menu_hide}' data-title-hide='{$text_toolbar_menu_show}''><i class='cli {$menu_state_icon}'></i></div>
                                            <div class='cs-menu-manager_item-heading-toolbar_action cs-mm-action-editpanel' title='{$text_toolbar_menu_edit}'><i class='cli cli-edit-3'></i></div>
                                            {$toolbar_icon_submenu}
                                        </div>
                                    </div>
                                    <div class='cs-menu-manager_item-edit-panel'>
                                        <div class='cs-edit-field-row'>
                                            <div class='cs-edit-field'>{$text_menu_original}:</div>
                                            <div class='cs-edit-value'><span class='dashicons-before {$menu_icon_original}'></span> {$menu_name_original}</div>
                                        </div>
                                        <div class='cs-edit-field-row'>
                                            <div class='cs-edit-field'>{$text_menu_rename}:</div>
                                            <div class='cs-edit-value'>
                                                <div class='cs-edit-value-inner'>
                                                    <input type='text' class='cs-admin-menu-rename' value='{$menu_name_encoded}'>
                                                    <div class='cs-edit-field-help'>{$text_menu_rename_help}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='cs-edit-field-row'>
                                            <div class='cs-edit-field'>{$text_menu_icon}:</div>
                                            <div class='cs-edit-value'>
                                                <span class='cs-menu-icon-panel_toggle dashicons-before {$menu_icon}'></span>
                                                {$menu_icons_panel}
                                            </div>
                                        </div>
                                    </div>
                                </div>";

                    if ($item_submenu){
                        $html_output .= "<div class='cs-menu-manager_submenu-wrapper' data-state='open'>";
                        foreach($item_submenu as $key => $item){
                            $submenu_name       = (isset($item[0])) ? $item[0] : null;
                            if (isset($item['has_notifications'])){
                                $submenu_name   = $item['has_notifications'];
                            }
                            $submenu_name_encoded  = htmlspecialchars($submenu_name,ENT_QUOTES);

                            $submenu_slug       = (isset($item[2])) ? (new self)->cs_menumanager_removesp($item[2]) : null;
                            $submenu_capability = (isset($item[1])) ? $item[1] : null;
                            $submenu_id         = (isset($item['id'])) ? $item['id'] : $key;

                            $submenu_state      = (isset($item['state'])) ? $item['state'] : 'enabled';
                            $submenu_state_icon = ($submenu_state == 'enabled') ? 'cli-eye' : 'cli-eye-off';
                            $submenu_class      = 'cs-menu-'.$submenu_state;
                            
                            $submenu_name_original = (isset($item['name_original'])) ? wp_strip_all_tags($item['name_original']) : $submenu_name;
                            
                            $_has_cap = (new self)->cs_menumanager_has_cap($current_user_role,$submenu_capability);

                            // Get Notification Bubbles
                            $_name              = $item[0];
                            $_bubbles           = (new self)->get_notifications_count($_name);
                            $menu_notifications = false;
                            if ($_bubbles !== false){
                                $menu_notifications = $_bubbles;
                            }

                            if ($_has_cap){
                                $html_output .= "
                                            <div class='cs-menu-manager_menu-wrapper cs-admin-submenu-item {$submenu_state}' data-menu-state='{$submenu_state}' data-menu-id='{$submenu_id}' data-menu-slug='{$submenu_slug}' data-menu-name='{$submenu_name_encoded}' data-parent-id='{$menu_id}' data-menu-notifications='{$menu_notifications}'>
                                                <div class='cs-menu-manager_item' data-menu-notifications='{$menu_notifications}'>	
                                                    <div class='cs-menu-manager_item-heading'>
                                                        <div class='cs-menu-manager_item-heading-title'>
                                                            <span class='cs-menu-title'>{$submenu_name}</span>
                                                        </div>
                                                        <div class='cs-menu-manager_item-heading-toolbar'>
                                                            <div class='cs-menu-manager_item-heading-toolbar_action cs-mm-action-move' title='$text_toolbar_submenu_move'><i class='cli cli-move'></i></div>
                                                            <div class='cs-menu-manager_item-heading-toolbar_action cs-mm-action-display {$submenu_class}' title='{$text_toolbar_submenu_hide}' data-title-show='{$text_toolbar_submenu_hide}' data-title-hide='{$text_toolbar_submenu_show}'><i class='cli {$submenu_state_icon}'></i></div>
                                                            <div class='cs-menu-manager_item-heading-toolbar_action cs-mm-action-editpanel' title='{$text_toolbar_submenu_edit}'><i class='cli cli-edit-3'></i></div>
                                                        </div>
                                                    </div>
                    
                                                    <div class='cs-menu-manager_item-edit-panel'>
                                                        <div class='cs-edit-field-row'>
                                                            <div class='cs-edit-field'>{$text_menu_original}:</div>
                                                            <div class='cs-edit-value'>{$submenu_name_original}</div>
                                                        </div>
                                                        <div class='cs-edit-field-row'>
                                                            <div class='cs-edit-field'>{$text_menu_rename}:</div>
                                                            <div class='cs-edit-value'>
                                                                <div class='cs-edit-value-inner'>
                                                                    <input type='text' class='cs-admin-submenu-rename' value='{$submenu_name_encoded}'>
                                                                    <div class='cs-edit-field-help'>{$text_menu_rename_help}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>    
                                            </div>";
                            }
                        }
                        $html_output .= "</div>";
                    }
                    $html_output .= "</div>"; // /.cs-amdin-menu-item
                }
            }
        }

        $form_nonce = wp_nonce_field( 'cs-plus-admin-menu-manager', 'cs-plus-admin-menu-manager-form-nonce','',false );



        if ($is_ajax_request){
            return $html_output;
        } else {
            return "
                <div id='cs-plus-admin-menu-management'>
                    <div id='cs-menu-manager'>
                        <div class='cs-menu-manager-order-toolbar'>
                            <div class='cs-menu-manager_item-order-toolbar_action cs-mm-action--order-original' title='{$text_toolbar_order_initial}'><i class='cli cli-sort-vertical'></i></div>
                            <div class='cs-menu-manager_item-order-toolbar_action cs-mm-action--order-asc' title='{$text_toolbar_order_asc}'><i class='cli cli-sort-asc'></i></div>
                            <div class='cs-menu-manager_item-order-toolbar_action cs-mm-action--order-desc' title='{$text_toolbar_order_desc}'><i class='cli cli-sort-desc'></i></div>
                            <div class='cs-menu-manager_item-order-toolbar_action cs-mm-action--expand' title='{$text_toolbar_expand_all}'><i class='cli cli-unfold-more'></i></div>
                            <div class='cs-menu-manager_item-order-toolbar_action cs-mm-action--collapse' title='{$text_toolbar_collapse_all}'><i class='cli cli-unfold-less'></i></div>
                        </div>
                        <div class='cs-menu-manager-full-menu-wrapper'>
                            {$html_output}
                        </div>
                        <div class='cs-menu-manager-spinner cs-spinner'></div>
                    </div>
                    <div class='cs-menu-manager-side-wrapper'>
                        <div id='cs-menu-manager_user-role-selector' class='cs-menu-manager_postbox'>
                            <div class='postbox'>
                                <h2 class='hndle ui-sortable-handle'><span>{$text_userroles_title}</span></h2>
                                <div class='inside'>
                                    <div class='postbox-inner-wrapper'>
                                        <select id='cs-menu-manager-roles' name='cs-menu-manager-roles'>
                                            {$user_roles_options}
                                        </select>
                                        <input type='hidden' id='cs-admin-current-user-role' name='cs-admin-current-user-role' value='{$current_user_role}'>
                                        <input id='cs-admin-menu_change' name='cs-admin-user-role_change' class='cs-mm-btn button-secondary' value='{$text_userroles_btn}' type='submit'>
                                    </div>
                                    <div id='major-publishing-actions'>
                                        <div id='delete-action'>
                                            <a id='cs-admin-menu_reset' class='cs-mm-btn submitdelete deletion' href='#'>{$text_btn_reset}</a>
                                        </div>
                                        <div id='publishing-action'>
                                            <div class='cs-plus-spinner spinner'></div>
                                            <input id='cs-admin-menu_save' name='cs-admin-menu_save' class='cs-mm-btn button-primary' value='{$text_btn_save}' type='button'>
                                        </div>
                                        <div class='clear'></div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- user-role-selector -->
                        <div id='cs-menu-manager_actions-wrapper' class='cs-menu-manager_postbox'>
                            <div class='postbox'>
                                <h2 class='hndle ui-sortable-handle'><span>{$text_instructions}</span></h2>
                                <div class='inside'>
                                    <ul>
                                        {$text_instructions_list}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ";
        }


    }


    /**
     * Dynamic Notifications bubbles
     * 
     * Used to display dynamic notification bubbles, instead of plain text number
     * 
     * @since 2.0.0
     */
    static public function get_notifications_count($string){
        // $pattern = <span class=("|')(update-plugins|awaiting-mod)(("|')|\s([^("|')]*)("|'))>(.*\s?)</span>
        // preg_match('/<span class=("|\')(update-plugins|awaiting-mod)(("|\')|\s([^("|\')]*)("|\'))>(.*\s?)<\/span>/', $string, $output_array);
        preg_match('/(update-plugins|awaiting-mod)*>(.*\s?)/', $string, $output_array);

        if ($output_array){
            $count      = $output_array[2];
            // $count      = $output_array[7];
            $_bubbles   = strip_tags($count);
            return ($_bubbles !== false) ? $_bubbles : false;
        }
        return false;
    }


    /**
	 * AJAX CALLS CALLBACKS
	 *
	 * @since 	1.0.0
	 */
	function menu_save_callback() {
		// check the nonce
		if (check_ajax_referer( 'cs-plus-admin-nonce', 'nonce', false ) == false ) {
			wp_send_json_error();
			die('Permissions check failed. Please login or refresh (if already logged in) the page, then try Again.');
        }

		// Request Vars
		$user_role 			= $_POST['userrole'];
        $newadminmenu 		= $_POST['adminmenu'];
        $newadminmenuorder  = $_POST['adminmenuorder'];

        // Reorder
        $_newmenu = array();
        foreach ($newadminmenuorder as $item){
            $menu_id    = $item['menu'];
            $menu       = $newadminmenu[$menu_id];
            
            $submenu_ids = $item['submenu'];
            $menu_submenu = $menu['submenu'];

            $_submenu = array();
            foreach($submenu_ids as $submenu_id){
                $__submenu = $menu['submenu'][$submenu_id];
                $_submenu[$submenu_id] = $__submenu;
                // echo "<br>MENU $menu_id --> SUBMENU: $submenu_id<br>";
                // print_r($__submenu);
            }

            $menu['submenu'] = $_submenu;

            $_newmenu[$menu_id] = $menu;
        }
        // Update local var
        $newadminmenu   = $_newmenu;


		// Get current menus data
		$adminmenu      = (Plus_admin::helper()->get_option("cs_plusadmin_adminmenu")) ? Plus_admin::helper()->get_option("cs_plusadmin_adminmenu") : array();

		// Update menu data by requested user role
		$adminmenu[$user_role]  = $newadminmenu;

		// Update menu data on DB
		Plus_admin::helper()->update_option("cs_plusadmin_adminmenu", $adminmenu);

	
		/* Output Response
		   ========================================================================== */
		$response = array(
			'status'    => 'OK',
            'message'   => 'Settings saved',
            'menu'      => $newadminmenu,
		);
		echo json_encode($response);
		die();
    }
    
	function menu_reset_callback() {
        // check the nonce
		if (check_ajax_referer( 'cs-plus-admin-nonce', 'nonce', false ) == false ) {
			wp_send_json_error();
			die('Permissions check failed. Please login or refresh (if already logged in) the page, then try Again.');
        }

		// Request Vars
		$user_role 		= $_POST['userrole'];

		// Get current menus data
		$adminmenu 		= Plus_admin::helper()->get_option("cs_plusadmin_adminmenu");

	
		// Remove menu data by requested user role
		if (isset($adminmenu[$user_role])){
			unset($adminmenu[$user_role]);
		}
		
		// Update menu data on DB
		Plus_admin::helper()->update_option("cs_plusadmin_adminmenu", $adminmenu);


		/* Output Response
		   ========================================================================== */
		//    $response = array(
		// 	'status'    => 'OK',
		// 	'message'   => 'Settings updated',
		// 	'user_role'	=> $user_role,
		// );
        // echo json_encode($response);

        $response = self::cs_menumng_settings_page($user_role,true);

        wp_send_json_success( $response );
		die();
    }

    function menu_change_callback(){
        // check the nonce
		if (check_ajax_referer( 'cs-plus-admin-nonce', 'nonce', false ) == false ) {
			wp_send_json_error();
			die('Permissions check failed. Please login or refresh (if already logged in) the page, then try Again.');
        }

        // Request Vars
        $user_role 	= $_POST['userrole'];

        $response   = self::cs_menumng_settings_page($user_role,true);

        wp_send_json_success( $response );

        die();
    }



    /**
     * Helper Functions
     * 
     * @since 2.0.0
     */
    function _search($array,$key,$target){
        $output = false;
        if (is_array($array) && isset($key) && isset($target)){
            $output = array_search(
                $target,
                array_filter(
                    array_combine(
                        array_keys($array),
                        array_column(
                            $array, $key
                        )
                    )
                )
            );
        }
        return $output;
    }

    function cs_menumanager_has_cap($requested_role,$capability){
        $role           = get_role( $requested_role );
        $capabilities   = $role->capabilities;

        $current_user   = wp_get_current_user();
        $current_role   = $current_user->roles;
        $current_role   = $current_role[0];

        $_has_cap       = (isset($capabilities[$capability])) ? true : false;
        $_user_can      = current_user_can($capability);
        
        if ($_has_cap){
            return true;
        } else if (($_user_can) && ($current_role == $requested_role)) {
            return true;
        } else {
            return false;
        }
    }

    static function cs_menumanager_removesp($string){
        return preg_replace("/[^A-Za-z0-9 ]/", '', $string);
    }

    function cs_menuicons_list() {
        $ret = "";
        $ret .= "<div class='cs-menu-manager_icons-panel'>";

        $str = Plus_admin::helper()->dashiconscsv();
        $exp = explode(",", $str);

        foreach ($exp as $key => $value) {
            $valexp = explode(":", $value);
            $class = trim($valexp[0]);
            $code = trim($valexp[1]);

            $ret .= "<span data-class = 'dashicons-{$class}' class='cs-menu-manager_icons-panel-icon dashicons-before dashicons-{$class}'></span>";
        }

        $ret .= "</div>";
        return $ret;
    }
    

}