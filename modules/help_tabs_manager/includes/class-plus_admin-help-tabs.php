<?php
/**
* Custom Help Tabs Manager
*
* @link       http://www.castorstudio.com
* @since      1.0.0
*
* @package    Plus_admin
* @subpackage Plus_admin/admin/includes
*/

class Plus_admin_help_tabs{
	private $tabs;
	
	static public function init($tabs){
		$class = __CLASS__ ;
		new $class;
	}
	
	public function __construct($helptabs){
		// add_action( "load-toplevel_page_cs-plus-admin-settings", array( $this, 'add_tabs' ), 20 );
		$this->helptabs = $helptabs;
		if (isset($helptabs['page'])){
			$this->add_tabs($helptabs['page']);
		}
		if (isset($helptabs['all'])){
			$this->add_tabs($helptabs['all']);
		}
	}
	
	public function add_tabs($helptab_pages){
		if (is_array($helptab_pages)){
			$screen = get_current_screen();
			
			foreach($helptab_pages as $page){
				// Remove Previous Help Tabs
				if ($page['tabs_remove_all']){
					$screen->remove_help_tabs();
				}
				
				// Create Help Tabs
				if (is_array($page['tabs'])){
					foreach ($page['tabs'] as $id => $data){
						$screen->add_help_tab(
							array(
								'id'       => Plus_admin::helper()->sanitize($data['tab_title']),
								'title'    => $data['tab_title'],
								'content'  => $data['tab_content'],
								// 'callback' => array($this,'prepare'),
							)
						);
					}
				}
				
				// Create Help Sidebar
				if ($page['sidebar_state']){
					$screen->set_help_sidebar($page['sidebar']);
				}
			}
		}
	}
	
	public function prepare($screen,$tab){
		printf('<p>%s</p>',esc_attr__($tab['callback'][0]->tabs[ $tab['id'] ]['tab_content'],'plus_admin'));
	}
}