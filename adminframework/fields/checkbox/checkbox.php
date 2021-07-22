<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
*
* Field: Checkbox
*
* @since 1.0.0
* @version 1.0.0
*
*/
class CSSFramework_Option_checkbox extends CSSFramework_Options {
	
	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	private function do_checkbox($options,$depth){
		$style_attrs  = null;
		$settings     = $this->field['settings'];
		$style        = (isset($settings['style'])) ? $settings['style'] : false;
		$type         = (isset($settings['type'])) ? $settings['type'] : 'normal';

		$check_fields = '';

		foreach ($options as $key => $value){
			$re_do = false;
			if ($style == 'labeled'){
				if (is_array($value)){
					$labels = $value['unchecked'];
					$labels .= '|'.$value['checked'];
				} else {
					$labels = $value;
				}
				$value = null;
				$style_attrs = "class='cssf-checkbox-labeled' data-labelauty='{$labels}'";
			} else if ($style == 'icheck'){
				$style_attrs = "class='cssf-checkbox-icheck cssf-checkbox-{$type}'";
			}


			if (is_array($value)){
				if (isset($value['desc'])){
					$desc 	= '<p class="cssf-text-field-desc">'.$value['desc'].'</p>';
					$value 	= $value['label'];
				} else {
					$re_do = true;
				}
			} else {
				$desc 	= null;
			}

			if ($re_do){
				$depth++;
				$subfields  = $this->do_checkbox($value,$depth);
				$check_fields .= "<li class='checkbox-sublevel checkbox-sublevel-{$depth}'><strong>{$key}</strong>{$subfields}</li>";
			} else {
				$check_fields .= '
					<li>
						<label>
							<div><input type="checkbox" name="'. $this->element_name( '[]' ) .'" value="'. $key .'"'. $this->element_attributes( $key ) . $this->checked( $this->element_value(), $key ) . $style_attrs .'/> '.$value.'</div>
							'.$desc.'
						</label>
					</li>
				';
			}

		}

		$output = '
			<ul'. $this->element_class() .'>
				'.$check_fields.'
			</ul>
		';

		return $output;
	}
	
	public function output() {
		
		echo $this->element_before();
		
		if( isset( $this->field['options'] ) ) {
			
			$options  = $this->field['options'];
			$options  = ( is_array( $options ) ) ? $options : array_filter( $this->element_data( $options ) );
			
			if (!empty($options)){
				// $style_attrs  = null;
				// $settings     = $this->field['settings'];
				// $style        = (isset($settings['style'])) ? $settings['style'] : false;
				// $type         = (isset($settings['type'])) ? $settings['type'] : 'normal';
				



				echo $this->do_checkbox($options,0);




				// echo '<ul'. $this->element_class() .'>';
				// foreach ( $options as $key => $value ) {
				// 	if ($style == 'labeled'){
				// 		if (is_array($value)){
				// 			$labels = $value['unchecked'];
				// 			$labels .= '|'.$value['checked'];
				// 		} else {
				// 			$labels = $value;
				// 		}
				// 		$value = null;
				// 		$style_attrs = "class='cssf-checkbox-labeled' data-labelauty='{$labels}'";
				// 	} else if ($style == 'icheck'){
				// 		$style_attrs = "class='cssf-checkbox-icheck cssf-checkbox-{$type}'";
				// 	} else if ($style == 'multilevel'){
						
				// 	}


				// 	if (is_array($value)){
				// 		$desc 	= '<p class="cssf-text-field-desc">'.$value['desc'].'</p>';
				// 		$value 	= $value['label'];
				// 	} else {
				// 		$desc 	= null;
				// 	}
				// 	echo '
				// 		<li>
				// 			<label>
				// 				<div><input type="checkbox" name="'. $this->element_name( '[]' ) .'" value="'. $key .'"'. $this->element_attributes( $key ) . $this->checked( $this->element_value(), $key ) . $style_attrs .'/> '.$value.'</div>
				// 				'.$desc.'
				// 			</label>
				// 		</li>
				// 	';
				// }
				// echo '</ul>';
			}
			
		} else {
			$label = ( isset( $this->field['label'] ) ) ? $this->field['label'] : '';
			echo '<label><input type="checkbox" name="'. $this->element_name() .'" value="1"'. $this->element_class() . $this->element_attributes() . checked( $this->element_value(), 1, false ) .'/> '. $label .'</label>';
		}
		
		echo $this->element_after();
		
	}
	
}
