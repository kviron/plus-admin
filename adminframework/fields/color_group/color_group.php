<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
*
* Field: color_group
*
* @since 1.0.0
* @version 1.0.0
*
*/
if(!class_exists('CSSFramework_Option_color_group')){
	class CSSFramework_Option_color_group extends CSSFramework_Options {
		public function __construct( $field, $value = '', $unique = ''){
			parent::__construct( $field, $value, $unique);
		}
		
		public function output() {
			$options = (!empty($this->field['options'])) ? $this->field['options'] : array();

			$width_style = (!empty($this->field['settings']['width_style'])) ? $this->field['settings']['width_style'] : 'block';
			
			echo $this->element_before();
			echo "<div class='cssf-multifield cssf-multifield--style_{$width_style}'>";

			if(!empty($options)){
				$output_colors = null;
				foreach($options as $color_id => $color_title){

					if (is_array($color_title)){
						$color = $color_title;
						if (isset($color['type']) && $color['type'] == 'group'){
							$group_title 	= (isset($color['title'])) ? $color['title'] : null;
							$group_colors 	= null;
							foreach($color['colors'] as $color_id => $color_title){
								$color_value  	= (!empty( $this->value[$color_id])) ? $this->value[$color_id] : '';
								$color_default 	= (!empty( $this->field['default'][$color_id])) ? $this->field['default'][$color_id] : '';
								$group_colors 	.= $this->color_picker($color_id,$color_title,$color_default,$color_value);
							}
							$output_colors .= "<div class='cssf-color_group-group'><h5>{$group_title}</h5><div class='cssf-multifield'>{$group_colors}</div></div>";
						}
					} else {
						$color_value  	= (!empty( $this->value[$color_id])) ? $this->value[$color_id] : '';
						$color_default 	= (!empty( $this->field['default'][$color_id])) ? $this->field['default'][$color_id] : '';
						$output_colors .= $this->color_picker($color_id,$color_title,$color_default,$color_value);
					}
				}

				echo $output_colors;
				// foreach($options as $key => $option){
				// 	if (is_array($key)){
				// 		echo "<br>ES UN GRUPO!<br>";
						
				// 	}

				// 	$color_value  = (!empty( $this->value[$key])) ? $this->value[$key] : '';
				// 	$default = (!empty( $this->field['default'][$key])) ? $this->field['default'][$key] : '';

				// 	echo cssf_add_element( array(
				// 		'pseudo'		=> true,
				// 		'id'			=> $this->field['id'].'_'.$key,
				// 		'type'			=> 'color_picker',
				// 		'name'			=> $this->element_name('['.$key.']'),
				// 		'attributes'	=> array(
				// 			'data-atts'		=> 'bgcolor',
				// 		),
				// 		'value'			=> $color_value,
				// 		'default'		=> $default,
				// 		// 'rgba'			=> ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
				// 		// 'palettes'		=> $settings['palettes'],
				// 		'before'		=> "<label>{$option}</label>",
				// 	) );
					
				// }
			}
			
			echo '</div>';
			
			echo $this->element_after();
			
		}


		private function color_picker($color_id = false,$color_title = false,$color_default = false,$color_value = false){
			// $color_id 		= (isset($color['id'])) ? $color['id'] : null;
			// $color_code 	= (isset($color['color'])) ? $color['color'] : null;
			// $color_title 	= (isset($color['title'])) ? $color['title'] : null;

			return cssf_add_element( array(
				'pseudo'		=> true,
				'id'			=> $this->field['id'].'_color_'.$color_id,
				'type'			=> 'color_picker',
				'name'			=> $this->element_name("[{$color_id}]"),
				'attributes'	=> array(
					'data-field-name'	=> $color_id,
				),
				'value'			=> $color_value,
				'default'		=> $color_default,
				'rgba'			=> ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
				// 'palettes'		=> ( isset( $color_palette ) ) ? $color_palette : false,
				'before'		=> "<label>{$color_title}</label>",
			), $color_value );
		}
		
	}
}
