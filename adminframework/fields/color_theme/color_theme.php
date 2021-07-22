<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
*
* Field: Color Theme
*
* @since 1.0.0
* @version 1.0.1
*
*/
class CSSFramework_Option_color_theme extends CSSFramework_Options {
	
	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}
	
	public function output() {
		
		echo $this->element_before();
		
		$field_unique 	= $this->unique ."[".$this->field['id']."]";
		$options 		= $this->field['options'];
		$sections 		= $options['sections'];
		$colors 		= $options['colors'];		
		$schemes 		= $this->field['schemes'];
		$settings 		= $this->field['settings'];
		$preview_colors = $settings['preview_colors'];

		// Default Colors
		$defaults = null;
		foreach($colors as $section){
			foreach($section as $color){
				if (isset($color['type']) && $color['type'] == 'group'){
					foreach($color['colors'] as $color){
						$color_id 	= (isset($color['id'])) ? $color['id'] : null;
						$color_code = (isset($color['color'])) ? $color['color'] : null;
						
						$defaults[$color_id] = $color_code;		
					}
				} else {
					$color_id 	= (isset($color['id'])) ? $color['id'] : null;
					$color_code = (isset($color['color'])) ? $color['color'] : null;
					
					$defaults[$color_id] = $color_code;
				}
			}
		}
		$this->value  = wp_parse_args( $this->element_value(), $defaults);


		// Get Current Selected Scheme
		$current_scheme_id 		= (isset($this->value['current_scheme_id'])) ? $this->value['current_scheme_id'] : null;
		$current_scheme_type 	= (isset($this->value['current_scheme_type'])) ? $this->value['current_scheme_type'] : null;


		// Predefined & Custom Schemes
		$predefined_schemes_json 	= ($schemes) ? (($this->isJSON($schemes)) ? $schemes : json_encode($schemes)) : null;
		$custom_schemes 			= (isset($this->value['custom_schemes'])) ? $this->value['custom_schemes'] : null;
		// $custom_schemes				= ($this->isJSON($custom_schemes)) ? json_decode($custom_schemes) : $custom_schemes;
		$custom_schemes_json 		= ($custom_schemes) ? (($this->isJSON($custom_schemes)) ? $custom_schemes : json_encode($custom_schemes)) : null;


		// Get Predefined & Custom Color Schemes for preview and select them
		$schemes_list_output = null;
		foreach($schemes as $key => $scheme){
			$scheme_name 			= (isset($scheme['name'])) ? $this->make_title($scheme['name']) : null;
			$scheme_scheme 			= (isset($scheme['scheme'])) ? $scheme['scheme'] : null;
			$scheme_preview_colors 	= array();
			
			// Check Current Scheme
			$is_current = null;
			if ($current_scheme_id == $key && $current_scheme_type == 'predefined'){
				$is_current = true;
			}

			// Set Preview Colors
			foreach($preview_colors as $color){
				$scheme_preview_colors[] = (isset($scheme_scheme[$color])) ? $scheme_scheme[$color] : null;
			}
			
			// Add Preview
			$schemes_list_output .= $this->get_preview_template($key,$scheme_name,$scheme_preview_colors,false,$is_current);
		}
		if ($custom_schemes){
			if ($this->isJSON($custom_schemes)) {
				$custom_schemes = json_decode($custom_schemes,true);
			}
			foreach($custom_schemes as $key => $scheme){
				$scheme_name 			= (isset($scheme['name'])) ? $this->make_title($scheme['name']) : null;
				$scheme_scheme 			= (isset($scheme['scheme'])) ? $scheme['scheme'] : null;
				$scheme_preview_colors 	= array();

				// Check Current Scheme
				$is_current = null;
				if ($current_scheme_id == $key && $current_scheme_type == 'custom'){
					$is_current = true;
				}

				// Set Preview Colors
				foreach($preview_colors as $color){
					$scheme_preview_colors[] = (isset($scheme_scheme[$color])) ? $scheme_scheme[$color] : null;
				}
				
				// Add Preview
				$schemes_list_output .= $this->get_preview_template($key,$scheme_name,$scheme_preview_colors,true,$is_current);
			}
		}
		
		
		// Get Color Scheme Builder Controls
		$output_schemes_sections = null;
		foreach($sections as $slug => $section){
			if (is_array($section)){
				$section_name = $section['title'];
				$section_desc = $section['desc'];
			} else {
				$section_name = $section;
				$section_desc = null;
			}
			
			$output_colors = null;
			
			$section_colors = (isset($colors[$slug])) ? $colors[$slug] : false;
			if ($section_colors){
				foreach($section_colors as $color){
					if (isset($color['type']) && $color['type'] == 'group'){
						$group_title 	= (isset($color['title'])) ? $color['title'] : null;
						$group_colors 	= null;
						foreach($color['colors'] as $color){
							$group_colors .= $this->color_picker($color);
						}
						$output_colors .= "<div class='cssf-scheme-section-group'><h5>{$group_title}</h5><div class='cssf-multifield'>{$group_colors}</div></div>";
					} else {
						$output_colors .= $this->color_picker($color);
					}
				}
			}
			
			$output_schemes_sections .= "
				<div class='cssf-scheme-section'>
					<div class='cssf-accordion-title'>
						<h4>{$section_name}</h4>
						<p>{$section_desc}</p>
					</div>
					<div class='cssf-accordion-content'>
						<div class='cssf-multifield'>
							{$output_colors}
						</div>
					</div>
				</div>
			";
		}


		// Get User Roles
		global $wp_roles;
		$roles 				= $wp_roles->roles;
		$user_roles_ui 		= '';
		$user_roles_input 	= '';


		// Prepend the Global Default Style
		$default_theme = array(
			'cssf_default_scheme' => array(
				'name'	=> esc_attr__('Default Theme','cssf-framework'),
			),
		);
		$roles = $default_theme + $roles;

		foreach($roles as $key => $value){
			$role_name 				= $value['name'];
			$role_value_scheme_id 	= 'default_theme';

			if ($key == 'cssf_default_scheme'){
				$role_scheme_is_erasable 	= false;
				$role_scheme_is_active		= true;
				$role_scheme_icon			= 'cli-user-check';
				$role_field_name 			= "{$key}";
				$role_value_scheme_id 		= (isset($this->value['userrole'][$role_field_name]['scheme_id'])) ? $this->value['userrole'][$role_field_name]['scheme_id'] : $role_value_scheme_id;
				$_role_value 				= explode(":",$role_value_scheme_id);
				$role_scheme_id 			= (isset($_role_value[0])) ? $_role_value[0] : $role_value_scheme_id;
				$role_scheme_type			= (isset($_role_value[1])) ? $_role_value[1] : '';
			} else {
				$role_scheme_is_erasable 	= true;
				$role_scheme_is_active		= false;
				$role_scheme_icon			= 'cli-user';
				$role_field_name 			= "cssf__{$key}";
				$role_value_scheme_id 		= (isset($this->value['userrole'][$role_field_name]['scheme_id'])) ? $this->value['userrole'][$role_field_name]['scheme_id'] : $role_value_scheme_id;
				$_role_value 				= explode(":",$role_value_scheme_id);
				$role_scheme_id 			= (isset($_role_value[0])) ? $_role_value[0] : $role_value_scheme_id;
				$role_scheme_type			= (isset($_role_value[1])) ? $_role_value[1] : '';

				if ($role_value_scheme_id == 'default_theme'){
					$role_scheme_is_erasable 	= false;
				}
			}

			$role_scheme_class 			= ($role_scheme_is_active) ? 'cssf-schemes-user-role__active' : '';
			$role_value_scheme		 	= (isset($this->value['userrole'][$role_field_name]['scheme'])) ? $this->value['userrole'][$role_field_name]['scheme'] : $role_value_scheme_id;

			$role_scheme_name 			= $this->make_title($role_scheme_id);
			
			$role_input_name_scheme_id 	= $this->element_name("[userrole][{$role_field_name}][scheme_id]");
			$role_input_name_scheme 	= $this->element_name("[userrole][{$role_field_name}][scheme]");

			$options_class = (!$role_scheme_is_erasable) ? 'cssf-schemes-user-role_options__hidden' : '';
			$new_role_options = "
				<div class='cssf-schemes-user-role_options {$options_class}'>
					<div class='cssf-schemes-user-role_options-item cssf-schemes-user-role_options-item--delete'>
						<i class='cli cli-trash'></i>
					</div>
				</div>
			";
			$spinner = "<div class='cssf-schemes-loader'><div class='cssf-spinner'></div></div>";

			$new_role_ui = "
				<div class='cssf-schemes-user-role {$role_scheme_class}' data-role='{$key}' data-current-scheme-id='{$role_scheme_id}' data-current-scheme-type='{$role_scheme_type}'>
					<div class='cssf-schemes-user-role_icon'>
						<i class='cli {$role_scheme_icon}'></i>
					</div>
					<div class='cssf-schemes-user-role_name'>
						{$role_name}
					</div>
					<div class='cssf-schemes-user-role_scheme'>
						<i class='cli cli-droplet'></i> <span>{$role_scheme_name}</span>
					</div>
					{$new_role_options}
					{$spinner}
				</div>
			";
			$new_role_input = "
				<input type='hidden' name='{$role_input_name_scheme_id}' class='cssf-userrole-input-field cssf-userrole_{$key}_scheme_id' value='{$role_value_scheme_id}'>
				<input type='hidden' name='{$role_input_name_scheme}' class='cssf-userrole-input-field cssf-userrole_{$key}_scheme' value='{$role_value_scheme}'>
			";


			$user_roles_ui .= $new_role_ui;
			$user_roles_input .= $new_role_input;
		}



		// Get Preview Template
		$preview_template 				= $this->get_preview_template('0','Demo',array('rgb(0,0,0)','rgb(0,0,0)','rgb(0,0,0)','rgb(0,0,0)','rgb(0,0,0)'),true);
		$preview_template_scheme_colors = json_encode($preview_colors);


		// Export URL
		$_field_unique 		= cssf_encode_string($field_unique);
		$_export_nonce 		= wp_create_nonce('cssf-framework-nonce');
		$export_admin_url 	= admin_url("admin-ajax.php?action=cssf-color-scheme_export&field_unique={$_field_unique}&nonce={$_export_nonce}");
		

		// Output HTML
		$text_intro_title 			= (isset($this->field['intro_title'])) ? $this->field['intro_title'] : __('Select User Role','cssf-framework');
		$text_intro_desc			= (isset($this->field['intro_desc'])) ? $this->field['intro_desc'] : __('Select a user role to customize. If you do not select any, the <strong>Default Theme</strong> style will be applied to all user roles.','cssf-framework');
		$text_listing_title 		= (isset($this->field['listing_title'])) ? $this->field['listing_title'] : __('Choose Color Scheme','cssf-framework');
		$text_listing_desc			= (isset($this->field['listing_desc'])) ? $this->field['listing_desc'] : __('Choose the color scheme you want to use for this user role. Don\'t forget that you can customize it completely!','cssf-framework');
		$text_scheme_builder_title 	= esc_attr__('Color Scheme Customizer','cssf-framework');
		$text_scheme_builder_desc 	= esc_attr__('Here you can fully customize the selected color scheme or use it as a template for your new preferred scheme. Customize it and use it or save it as a new one!','cssf-framework');
		$text_custom_scheme_name_placeholder	= esc_attr__('Color Scheme Name','cssf-framework');
		$text_import_area_placeholder			= esc_attr__('Paste your color schemes backup file content here','cssf-framework');
		$text_overwrite_schemes					= esc_attr__('Overwrite all my custom color schemes with the imported schemes','cssf-framework');
		$text_save_color_scheme 	= esc_attr__('Save Color Scheme','cssf-framework');
		$text_export_color_scheme	= esc_attr__('Export','cssf-framework');
		$text_import_color_scheme	= esc_attr__('Import','cssf-framework');	
		$name_current_scheme_id 	= $this->element_name("[current_scheme_id]");
		$name_current_scheme_type 	= $this->element_name("[current_scheme_type]");
		$name_scheme_unique 		= $this->element_name("[scheme_unique]");
		$name_predefined_schemes 	= $this->element_name("[predefined_schemes]");
		$name_custom_schemes 		= $this->element_name("[custom_schemes]");



		echo "
			<div class='cssf-schemes'>
				<div class='cssf-schemes-user-roles-outer-wrapper'>
					<div class='cssf-schemes-title'>
						<h3>{$text_intro_title}</h3>
						<p>{$text_intro_desc}</p>
					</div>

					<div class='cssf-schemes-user-roles-wrapper' data-current-role='cssf_default_scheme'>
						{$user_roles_ui}
						{$user_roles_input}
					</div>
				</div>


				<div class='cssf-schemes-list-outer-wrapper'>
					<div class='cssf-schemes-title'>
						<h3>{$text_listing_title}</h3>
						<p>{$text_listing_desc}</p>
					</div>
					
					<ul class='cssf-schemes-list'>
						{$schemes_list_output}
					</ul>
					<input type='hidden' name='{$name_current_scheme_id}' class='cssf-color-scheme-current_id' value='{$current_scheme_id}'>
					<input type='hidden' name='{$name_current_scheme_type}' class='cssf-color-scheme-current_type' value='{$current_scheme_type}'>
					<input type='hidden' name='{$name_predefined_schemes}' class='cssf-color-scheme-predefined_schemes' value='{$predefined_schemes_json}'>
					<input type='hidden' name='{$name_custom_schemes}' class='cssf-color-scheme-custom_schemes' value='{$custom_schemes_json}'>
					<input type='hidden' name='{$name_scheme_unique}' class='cssf-color-scheme-unique' value='{$field_unique}'>
					<div class='cssf-schemes-controls'>
						<div class='cssf-schemes-controls-buttons-row'>
							<div class='cssf-element cssf-field-text'>
								<input type='text' class='cssf-color-scheme-scheme_name' name='' value='' placeholder='{$text_custom_scheme_name_placeholder}'>
							</div>
							<button class='cssf-color-scheme-save_scheme cssf-button cssf-button-primary'><i class='cli cli-save'></i>{$text_save_color_scheme}</button>
							<a href='{$export_admin_url}' class='cssf-color-scheme-export_scheme cssf-button' target='_blank'><i class='cli cli-download-cloud'></i>{$text_export_color_scheme}</a>
							<button class='cssf-color-scheme-import_scheme cssf-button'><i class='cli cli-file-plus'></i>{$text_import_color_scheme}</button>
						</div>
						<div class='cssf-schemes-import'>
							<div class='cssf-element cssf-field-textarea'>
								<textarea class='cssf-schemes-import_data' placeholder='{$text_import_area_placeholder}'></textarea>
								<label>
									<div class='cssf-field-checkbox'>
										<input type='checkbox' class='cssf-schemes-import_overwrite cssf-checkbox-icheck' value=''> {$text_overwrite_schemes}
									</div>
								</label>
							</div>
							<button class='cssf-schemes-import_submit cssf-button cssf-button-primary'><i class='cli cli-file-plus'></i>{$text_import_color_scheme}</button>
						</div>
					</div>
				</div>
				<div class='cssf-scheme-builder-outer-wrapper'>
					<div class='cssf-schemes-title'>
						<h3>{$text_scheme_builder_title}</h3>
						<p>{$text_scheme_builder_desc}</p>
					</div>
					<div class='cssf-scheme-builder'>
						$output_schemes_sections
					</div>
				</div>
				<div class='cssf-scheme-preview-template' data-scheme-colors='{$preview_template_scheme_colors}'>
					{$preview_template}
				</div>
				</div>
		";
		
		echo $this->element_after();
		
	}

	function get_preview_template($key,$scheme_name,$colors = array('rgb(0,0,0)','rgb(0,0,0)','rgb(0,0,0)','rgb(0,0,0)','rgb(0,0,0)'),$is_custom = false,$is_current = false){
		$is_current 	= ($is_current) ? 'cssf-schemes-item-current' : null;
		$data_type 		= ($is_custom) ? 'custom' : 'predefined';
		$delete_button 	= ($is_custom) ? "<div class='cssf-schemes-item_delete' data-scheme-id='{$key}'><i class='cli cli-trash'></i></div>" : null;
		$spinner 		= "<div class='cssf-schemes-loader'><div class='cssf-spinner'></div></div>";
		$color_vars 	= '';
		foreach($colors as $ckey => $cvalue){
			$ckey++;
			$color_vars .= "--color{$ckey}: $cvalue;";
		}
		return "
			<li class='cssf-schemes-item {$is_current}' data-scheme-id='{$key}' data-scheme-type='{$data_type}' style='{$color_vars}'>
				<div class='cssf-schemes-item-preview' style='background-color:var(--color5);'>
					<span class='preview_header_brand' style='background-color:var(--color1);'></span>
					<span class='preview_header' style='background-color:var(--color2);'></span>
					<span class='preview_primary' style='background-color:var(--color3);'></span>
					<span class='preview_secondary' style='background-color:var(--color4);'></span>
					<span class='preview_text' style='color:var(--color6);'>{$scheme_name}</span>
				</div>
				{$spinner}
				{$delete_button}
			</li>
		";
	}
	
	
	private function color_picker($color){
		$color_id 		= (isset($color['id'])) ? $color['id'] : null;
		$color_code 	= (isset($color['color'])) ? $color['color'] : null;
		$color_title 	= (isset($color['title'])) ? $color['title'] : null;
		$color_palette 	= (isset($color['palette'])) ? $color['palette'] : null;
		
		return cssf_add_element( array(
			'pseudo'		=> true,
			'id'			=> $this->field['id'].'_color_'.$color_id,
			'type'			=> 'color_picker',
			'name'			=> $this->element_name("[{$color_id}]"),
			'attributes'	=> array(
				'data-field-name'	=> $color_id,
			),
			'value'			=> $this->value[$color_id],
			'default'		=> ( isset( $color_code ) ) ? $color_code : '',
			'rgba'			=> ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
			'palettes'		=> ( isset( $color_palette ) ) ? $color_palette : false,
			'before'		=> "<label>{$color_title}</label>",
		), $this->value[$color_id] );
	}



	/**
	 * Helper Function to Check if is valid JSON object
	 */
	private function isJSON($string){
		return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}
	private function make_title($string){
		$string = str_replace("-"," ",$string);
		$string = str_replace("_"," ",$string);
		return ucwords($string);
	}
	
}