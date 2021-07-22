<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
*
* Field: navbar_builder
*
* @since 1.0.0
* @version 1.0.0
*
*/
class CSSFramework_Option_navbar_builder extends CSSFramework_Options {
	
	public function __construct( $field = '', $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	static function init(){
		/**
		 * Register Hooks
		 */

		// Register Field Assets - Styles and Scripts
		add_filter('cssf_register_framework_assets',array( 'CSSFramework_Option_navbar_builder', 'register_assets' ),10);
	}

	static public function register_assets($styles_scripts){
		$styles = $styles_scripts[0];
		$scripts = $styles_scripts[1];

		$url = CSSF_URI . '/fields/navbar_builder/';

		$styles['cssf-field-navbar_builder'] = array(
			$url . 'css/styles.css',
			array(),
			'1.0.0',
			false,
		);

		$scripts['cssf-field-navbar_builder'] = array(
			$url . 'js/scripts.js',
			array( 'cssf-plugins' ),
			'1.0.0',
			false,
		);

		return array($styles,$scripts);
	}

	private function isJson($string) {
		return !empty($string) && is_string($string) && is_array(json_decode($string, true)) && json_last_error() == 0;
	}

	private function parse_element($section,$elements){
		$tpl = '';
		
		if ($section){
			foreach($section as $key){
				$eData 			= $elements->$key;
				$eType 			= $eData->type;
				$eName 			= $eData->name;
				$eIcon 			= $eData->icon;
				$eIconSecondary = $eData->icon_secondary;
				$elementText 	= "<i class='{$eIcon}'></i>";
				
				// Element Types
				if ($eType == 'pagetitle' || $eType == 'flexiblespace' || $eType == 'sitebrand' || $eType == 'custom'){
					// Just for better display
					$default_icons = array(
						'pagetitle' 	=> 'cli cli-type',
						'flexiblespace'	=> 'cli cli-separator-vertical',
						'sitebrand'		=> 'cli cli-rocket',
						'custom'		=> 'cli cli-square-dashed',
					);
					$eIcon 			= ($eIcon) ? $eIcon : (($eIconSecondary) ? $eIconSecondary : $default_icons[$eType]);
					$eIcon 			= "<i class='{$eIcon}'></i>";
					$elementText 	= "{$eIcon} <span>{$eName}</span>";
				}

				$tpl .= "
					<div class='cssf-layout-builder_element layout-element__{$key} cssf-layout-builder_element-type--{$eType}' data-layout-element-name='{$key}' title='{$eName}'>
						<div class='cssf-layout-builder_element-toolbar'>
							<a class='cssf-lb-btn--edit' title='Edit Element' draggable='false'></a>
							<a class='cssf-lb-btn--delete' title='Delete Element' draggable='false'></a>
						</div>
						<div class='cssf-layout-builder_element-content'>
							{$elementText}
						</div>
					</div>
				";
			}
		}
		return ($tpl) ? $tpl : false;
	}

	public function output() {

		$value          = $this->element_value();
    	$value          = ( ! empty( $value ) ) ? $value : $this->field['default'];

        $elements_default 	= array();

		$elements_data		= ($this->isJson($value['data'])) ? json_decode($value['data']) : array();
        
        $defaults_value = array(
			'main'	    => '',
            'elements'  => '',
		);

		$value			= wp_parse_args( $this->element_value(), $defaults_value );
        $value_main		= ($this->isJson($value['main'])) ? json_decode($value['main']) : $value['main'];
		$value_elements = ($this->isJson($value['elements'])) ? json_decode($value['elements']) : $value['elements'];
		if (is_array($value_main) && is_array($value_elements)){
			$value_elements = array_diff($value_elements,$value_main);
		}
		
		$text_placeholder = __('Drag & Drop some elements here','plus_admin');
		
		echo $this->element_before();

		echo '
            <div class="cssf-layout-builder cssf-layout-builder--navbar">
				<div class="cssf-layout-builder__enabled">
					<div class="cssf-layout-title">Navbar</div>
					<div class="cssf-layout-placeholder cssf-pseudo-hidden">'.$text_placeholder.'</div>
					<div class="cssf-layout-builder-elements-wrapper">
						<div class="cssf-layout-builder_section cssf-layout-builder_section--main" data-layout-section="main">'.$this->parse_element($value_main,$elements_data).'</div>
					</div>
                </div>
				<div class="cssf-layout-builder__disabled">
					<div class="cssf-layout-title">Available Elements</div>
					<div class="cssf-layout-placeholder cssf-pseudo-hidden">'.$text_placeholder.'</div>
					<div class="cssf-layout-builder-elements-wrapper">
						'.$this->parse_element($value_elements,$elements_data).'
					</div>
				</div>
            </div>
        ';

		echo cssf_add_element( array(
			'pseudo'	=> true,
			'type'		=> 'fieldset',
			'name'		=> $this->element_name( '[editor]' ),
			'class'		=> 'cssf-navbar_builder-editor',
			'fields'	=> array(
				array(
					'type'			=> 'content',
					'content'		=> __('Add New Element','plus_admin'),
					'wrap_class'	=> 'editor_action_title',
				),
				array(
					'id'		=> 'editor_action',
					'type'		=> 'select',
					'options'	=> array(
						'add' => 'Agregar nuevo',
						'edit' => 'Editar',
					),
					'class'		=> 'editor_action',
					'wrap_class'	=> 'hidden',
				),

				// Element Type
				array(
                    'id'        => 'element_type',
                    'type'      => 'select',
                    'title'     => __('Element Type','plus_admin'),
					'options'   => array(
						__('Customizable Elements','plus_admin') => array(
							'useraccount'     	=> __('User Account','plus_admin'),
							'comments'			=> __('Comments','plus_admin'),
							'updates'			=> __('Updates','plus_admin'),
							'newcontent'		=> __('New Content','plus_admin'),
							'notifications'		=> __('Notifications','plus_admin'),
							'viewsite'			=> __('View Site','plus_admin'),
							'help'				=> __('Help','plus_admin'),
							'screenoptions'		=> __('Screen Options','plus_admin'),
							'sidebartoggle'		=> __('Sidebar Toggle','plus_admin'),
							'networksites'		=> __('Network Sites','plus_admin'),
							'customlink'		=> __('Custom Link','plus_admin'),
							'custom'			=> __('Customizable Element','plus_admin'),
						),	
						__('Pre-defined Elements','plus_admin') => array(
							'pagetitle'			=> __('Page Title','plus_admin'),
							'flexiblespace'    	=> __('Flexible Space','plus_admin'),
							'sitebrand'			=> __('Site Brand','plus_admin'),
						),
					),
					'default_option'	=> __('-- Select an element type --','plus-admin'),
					'class' => 'element_type',
				),

				// Element Visibility
				array(
					'dependency'	=> array('element_type','!=',''),
					'id'			=> 'element_visibility_device',
					'type'			=> 'select',
					'title'			=> __('Element Visibility','plus_admin'),
					'subtitle'		=> __('Set the visibility of the item according to different devices','plus_admin'),
					'desc'			=> __('If you leave blank, the item will be hidden in all devices','plus_admin'),
					'chosen'		=> true,
					'multiple'		=> true,
					'sortable'		=> true,
					'placeholder' 	=> __('Select a device','plus_admin'),
					'options'     	=> array(
						'desktop'	=> __('Desktop','plus_admin'),
						'tablet'	=> __('Tablet','plus_admin'),
						'mobile'	=> __('Mobile','plus_admin'),
						'small'		=> __('Small','plus_admin'),
					),
					'class'			=> 'element_visibility_device',
				),

				// User Avatar
				array(
					'dependency'	=> array('element_type','==','useraccount'),
                    'id'            => 'useravatar_status',
                    'type'          => 'switcher',
                    'title'         => __('User Avatar','plus_admin'),
                    'label'         => __('Use user avatar image','plus_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
					),
					'class' 		=> 'element_useravatar_status',
                ),
				
				// Element Icon
				array(
					'dependency'	=> array('element_type','any','useraccount,comments,updates,newcontent,notifications,viewsite,help,screenoptions,sidebartoggle,networksites,customlink,custom'),
					'id'            => 'element_icon',
					'type'          => 'icon',
					'title'         => __('Icon','plus_admin'),
					'class' 		=> 'element_icon',
				),
				array(
					'dependency'	=> array('element_type','any','sidebartoggle,networksites,custom'),
					'id'            => 'element_icon_secondary',
					'type'          => 'icon',
					'title'         => __('Secondary Icon','plus_admin'),
					'subtitle'		=> __('This icon is displayed to close the sidebar','plus_admin'),
					'default'       => 'cli cli-x',
					'class' 		=> 'element_icon_secondary',
				),

				// Custom Link
				array(
					'dependency'	=> array('element_type','any','customlink,custom'),
                    'id'            => 'customlink_type',
                    'type'          => 'select',
					'title'         => __('Custom Link Type','plus_admin'),
					'info'			=> __('Choose the type of link you want to use in this element','plus_admin'),
                    'options'        => array(
                        'admin'   	=> __('Admin Page','plus_admin'),
						'internal'	=> __('Internal Link','plus_admin'),
						'external'	=> __('External Link','plus_admin'),
					),
					'class' 		=> 'element_customlink_type',
                ),
				array(
					'dependency'		=> array('element_type|customlink_type','any|==','customlink,custom|admin'),
                    'id'            	=> 'customlink_admin_uri',
                    'type'          	=> 'select',
                    'title'         	=> __('Custom Admin Page Link','plus_admin'),
					'info'          	=> __('Choose the administration page to link to','plus_admin'),
					'options'			=> 'admin_page',
					'default_option'	=> __('-- Choose an admin page --','plus_admin'),
					'class' 			=> 'element_customlink_adminUri',
				),
				
				// Custom Link
				array(
					'dependency'	=> array('element_type|customlink_type','any|any','customlink,custom|internal,external'),
                    'id'            => 'customlink_custom_uri',
                    'type'          => 'text',
                    'title'         => __('Custom URL','plus_admin'),
					'info'          => __('Insert the URL where this element points','plus_admin'),
					'class' 		=> 'element_customlink_customUri',
				),

				// Tooltip
				array(
					'dependency'	=> array('element_type','any','useraccount,comments,updates,newcontent,notifications,viewsite,help,screenoptions,sidebartoggle,networksites,customlink,custom'),
                    'id'            => 'tooltip_status',
                    'type'          => 'switcher',
                    'title'         => __('Tooltip','plus_admin'),
                    'label'         => __('Use custom tooltip','plus_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
					),
					'class' 		=> 'element_tooltip_status',
                ),
				array(
					'dependency'	=> array('tooltip_status|element_type','==|any','true|useraccount,comments,updates,newcontent,notifications,viewsite,help,screenoptions,sidebartoggle,networksites,customlink,custom'),
                    'id'            => 'tooltip_text',
                    'type'          => 'text',
                    'title'         => __('Tooltip Text','plus_admin'),
					'info'          => __('Leave blank to use the default tooltip','plus_admin'),
					'class' 		=> 'element_tooltip_text',
				),
				
				// Notifications Bubble
				array(
					'dependency'	=> array('element_type','any','comments,updates,newcontent,notifications,custom'),
                    'id'            => 'notifications_status',
                    'type'          => 'switcher',
                    'title'         => __('Notifications','plus_admin'),
                    'label'         => __('Show notifications bubble','plus_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
					),
					'class'			=> 'element_notifications_status',
				),

				// Custom Element
				array(
					'dependency'	=> array('element_type','==','custom'),
                    'id'            => 'label_status',
                    'type'          => 'switcher',
                    'title'         => __('Show Label','plus_admin'),
                    'label'         => __('Show text label','plus_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','plus_admin'),
                        'off'   => __('No','plus_admin'),
					),
					'class' 		=> 'element_label_status',
				),
				array(
					'dependency'	=> array('element_type|label_status','==|==','custom|true'),
                    'id'            => 'label_text',
                    'type'          => 'text',
                    'title'         => __('Element Label','plus_admin'),
					'class' 		=> 'element_label_text',
				),
				array(
					'dependency'	=> array('element_type','==','custom'),
                    'id'            => 'container_id',
                    'type'          => 'text',
                    'title'         => __('Container ID','plus_admin'),
					'class' 		=> 'element_container_id',
				),
				array(
					'dependency'	=> array('element_type','==','custom'),
                    'id'            => 'container_class',
                    'type'          => 'text',
                    'title'         => __('Container Class','plus_admin'),
					'class' 		=> 'element_container_class',
				),
				array(
					'dependency'	=> array('element_type','==','custom'),
                    'id'            => 'wrapper_type',
                    'type'          => 'select',
                    'title'         => __('Wrapper Type','plus_admin'),
					'class' 		=> 'element_wrapper_type',
					'options'		=> array(
						'a'		=> 'a',
						'div'	=> 'div',
					),
					'default'	=> 'a',
				),
				array(
					'dependency'	=> array('element_type','==','custom'),
                    'id'            => 'wrapper_id',
                    'type'          => 'text',
                    'title'         => __('Wrapper ID','plus_admin'),
					'class' 		=> 'element_wrapper_id',
				),
				array(
					'dependency'	=> array('element_type','==','custom'),
                    'id'            => 'wrapper_class',
                    'type'          => 'text',
                    'title'         => __('Wrapper Class','plus_admin'),
					'class' 		=> 'element_wrapper_class',
				),
				array(
					'dependency'	=> array('element_type','==','custom'),
                    'id'            => 'wrapper_attributes',
                    'type'          => 'text',
                    'title'         => __('Wrapper Additional Attributes','plus_admin'),
					'class' 		=> 'element_wrapper_attributes',
				),


				// Editor Action Buttons
				array(
					'dependency'	=> array('editor_action','==','add'),
					'id'        	=> 'btn_add',
					'type'     		=> 'button',
					'value'     	=> __('<i class="cli cli-plus-circle"></i> Add New Element','plus_admin'),
					'class'			=> 'cssf-button cssf-button-success cssf-navbar-builder-add_element',
				),
				array(
					'dependency'	=> array('editor_action','==','edit'),
					'id'        	=> 'btn_cancel',
					'type'     		=> 'button',
					'value'     	=> __('<i class="cli cli-x"></i> Cancel Edition','plus_admin'),
					'class'			=> 'cssf-button cssf-navbar-builder-edit_cancel',
					'columns' 		=> '6',
				),
				array(
					'dependency'	=> array('editor_action','==','edit'),
					'id'        	=> 'btn_edit',
					'type'     		=> 'button',
					'value'     	=> __('<i class="cli cli-edit-2"></i> Update Element','plus_admin'),
					'class'			=> 'cssf-button cssf-button-primary cssf-navbar-builder-edit_element',
					'columns' 		=> '6',
				),
			)
		));

		$value_main		= ($value_main) ? json_encode($value_main) : false;
		$value_elements = ($value_elements) ? json_encode($value_elements) : false;
		$value_data 	= ($elements_data) ? json_encode($elements_data) : false;
		
		echo "
			<input type='hidden' name='{$this->element_name("[main]")}' class='section__main' value='{$value_main}'>
			<input type='hidden' name='{$this->element_name("[elements]")}' class='section__elements' value='{$value_elements}'>
			<input type='hidden' name='{$this->element_name("[data]")}' class='elements__data' value='{$value_data}'>
		";

		echo $this->element_after();
		
	}
	
}


CSSFramework_Option_navbar_builder::init();