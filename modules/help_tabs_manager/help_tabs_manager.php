<?php
class Plus_admin_Module_Help_Tabs_Manager extends Plus_admin_Module{
	private static $instance = null;
	
    public function __construct() {
        parent::__construct();

        $this->name     	= 'help_tabs_manager';
		$this->version  	= '1.0.3';
		$this->plugin_name 	= false;
		$this->unique 		= false; // use the main/core plugin unique id
    }

    public static function getInstance() {
        if (is_null(self::$instance) || !(self::$instance instanceof Plus_admin_Module_Help_Tabs_Manager))
            self::$instance = new Plus_admin_Module_Help_Tabs_Manager();
        return self::$instance;
    }

    public function init(){
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies(){

    }

    private function define_admin_hooks(){
        /**
         * Load Module Config Settings Options
         * 
         * @since 1.0.0
         */
        $this->add_action('cssf_framework_load_config', $this, 'load_module_config');


        /**
         * Enqueue Scripts
         * 
         * @since 1.0.0
         */
		$this->add_action( 'admin_enqueue_scripts', $this, 'enqueue_scripts' );
		

		/**
		 * Custom Contextual Help Tabs Manager
		 * 
		 * @since 1.0.0
		 */
		$this->add_action('admin_head', $this, 'custom_help_tabs');
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
     * @since 1.0.0
	 */
	public function load_module_config(){
		require_once( 'config/'.$this->name.'-settings.php'  );
    }
    


    /**
     * Enqueue Scripts
     * 
     * @since 1.0.0
     */
    public function enqueue_scripts(){
        wp_enqueue_script('plus_admin_'.$this->name, plugin_dir_url( __FILE__ ) . 'js/'.$this->name.'.js', array('plus_admin'), $this->version, false );
	}



	/**
	 * Custom Help Tabs
	 *
	 * @since 1.0.0
	 */


	/**
	 * Get Current Admin Screen identifier
	 * Devuelve el "id" de la pagina actual, en el mismo formato que el admin framework las muestra
	 * Se deve utilizar en conjunto con el admin framework
	 *
	 * @return void
	 */
	private function get_current_admin_page(){
		global $hook_suffix;
		global $pagenow;
		$hook = get_current_screen();

		$args = array();

		if ($hook->taxonomy){
			$args['taxonomy'] = $hook->taxonomy;
		}

		$pt = $hook->post_type;
		if ($pt){
			$core_pt = array('post','attachment');
			if (!in_array($pt,$core_pt)){
				$args['post_type'] = $hook->post_type;
			}
		}

		preg_match('/^(.*?)_page_/',$hook->base,$is_custom_page);
		if ($is_custom_page){
			$current_screen_id = preg_replace('/^(.*?)_page_/','', $hook->base);
			$pagenow = 'admin.php';
			$args = array();
			$args['page'] = $current_screen_id;
		}

		$output = add_query_arg( $args, $pagenow);
		return $output;
	}




	function custom_help_tabs(){
		if (Plus_admin::gs('helptabs_status')){
			// global $pagenow;
			// global $hook_suffix;
			require_once 'includes/class-plus_admin-help-tabs.php';
			
			// $current_screen 	= get_current_screen();
			// // $current_screen_id 	= $current_screen->id;
			// $current_screen_id 	= $hook_suffix;
			// // $current_screen_id 	= str_replace(array('toplevel_page_', 'settings_page_'),array('',''), $current_screen_id);
			// $current_screen_id = preg_replace('/^(.*?)_page_/','', $current_screen_id);
			$current_screen_id 	= $this->get_current_admin_page();

			$all_tabs 			= Plus_admin::gs('helptabs_container');


			// Current Page Help Data
			$page_index 		= Plus_admin::helper()->search_array_for_ids($all_tabs,'helptab_page',$current_screen_id);
			// $page_index 		= (!isset($page_index)) ? Plus_admin::helper()->search_array_for_ids($all_tabs,'helptab_page',$hook_suffix) : $page_index;
			$all_pages_index 	= Plus_admin::helper()->search_array_for_ids($all_tabs,'helptab_page','all_pages');
			$page_helptabs 		= array();
			$all_helptabs 		= array();

			if ($page_index){
				foreach ($page_index as $index) {
					$helptab_data 		= (isset($all_tabs[$index])) ? $all_tabs[$index] : null;
					$helptab_status 	= (isset($helptab_data['helptab_status'])) ? $helptab_data['helptab_status'] : null;
	
					if (!$helptab_status){
						$helptab_page 			= (isset($helptab_data['helptab_page'])) ? $helptab_data['helptab_page'] : null;
						$helptab_userrole		= (isset($helptab_data['helptab_userrole'])) ? $helptab_data['helptab_userrole'] : false;
						$helptab_remove			= (isset($helptab_data['helptab_remove_original'])) ? $helptab_data['helptab_remove_original'] : false;
						$helptab_sidebar_status	= (isset($helptab_data['helptab_custom_sidebar'])) ? $helptab_data['helptab_custom_sidebar'] : false;
						$helptab_sidebar 		= (isset($helptab_data['helptab_sidebar_content'])) ? $helptab_data['helptab_sidebar_content'] : false;
						$helptab_tabs			= (isset($helptab_data['helptab_items'])) ? $helptab_data['helptab_items'] : false;

						if (!$helptab_userrole || Plus_admin::helper()->is_current_user_in_role($helptab_userrole)){
							// Current Page Help Array
							$current_helptab = array(
								'tabs_remove_all'	=> $helptab_remove,
								'tabs'				=> $helptab_tabs,
								'sidebar_state'		=> $helptab_sidebar_status,
								'sidebar'			=> $helptab_sidebar,
							);
		
							$page_helptabs[] = $current_helptab;
						}
					}
				}
			}

			if ($all_pages_index){
				foreach ($all_pages_index as $index) {
					$helptab_data 		= (isset($all_tabs[$index])) ? $all_tabs[$index] : null;
					$helptab_status 	= (isset($helptab_data['helptab_status'])) ? $helptab_data['helptab_status'] : null;
	
					if (!$helptab_status){
						$helptab_page 			= (isset($helptab_data['helptab_page'])) ? $helptab_data['helptab_page'] : null;
						$helptab_userrole		= (isset($helptab_data['helptab_userrole'])) ? $helptab_data['helptab_userrole'] : false;
						$helptab_remove			= (isset($helptab_data['helptab_remove_original'])) ? $helptab_data['helptab_remove_original'] : false;
						$helptab_sidebar_status	= (isset($helptab_data['helptab_custom_sidebar'])) ? $helptab_data['helptab_custom_sidebar'] : false;
						$helptab_sidebar 		= (isset($helptab_data['helptab_sidebar_content'])) ? $helptab_data['helptab_sidebar_content'] : false;
						$helptab_tabs			= (isset($helptab_data['helptab_items'])) ? $helptab_data['helptab_items'] : false;

						if (!$helptab_userrole || Plus_admin::helper()->is_current_user_in_role($helptab_userrole)){
							// Current Page Help Array
							$current_helptab = array(
								'tabs_remove_all'	=> $helptab_remove,
								'tabs'				=> $helptab_tabs,
								'sidebar_state'		=> $helptab_sidebar_status,
								'sidebar'			=> $helptab_sidebar,
							);
		
							$page_helptabs[] = $current_helptab;
						}
					}
				}
			}

			// Helptabs to render
			$helptabs = null;
			if ($page_helptabs){
				$helptabs['page'] = $page_helptabs;
			}
			if ($all_helptabs){
				$helptabs['all'] = $all_helptabs;
			}

			if ($helptabs){
				new Plus_admin_help_tabs($helptabs);
			} else {

			}
		} else {

		}
	}
}