<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
*
* Field: Button
*
* @since 1.0.0
* @version 1.0.0
*
*/
class CSSFramework_Option_button extends CSSFramework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output(){
		$value = ( empty( $this->element_value() ) ) ? esc_attr__('Button Name','cssf-framework') : $this->element_value();

		$options = array(
			'type'	=> ( empty( $this->field['options']['type'] ) ) ? '' : 'button-'.$this->field['options']['type'],
		);

		echo $this->element_before();
		echo '<a href="#" name="'. $this->element_name() .'" '. $this->element_class($options['type']) . $this->element_attributes() .'>'. $value .'</a>';
		echo $this->element_after();

	}

}
