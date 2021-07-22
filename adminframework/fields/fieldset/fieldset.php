<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
*
* Field: Fieldset
*
* @since 1.0.0
* @version 1.0.0
*
*/
class CSSFramework_Option_fieldset extends CSSFramework_Options {
	
	public function __construct( $field, $value = '', $unique = '', $is_sub = false ) {
		parent::__construct( $field, $value, $unique, $is_sub );
	}
	
	public function output() {
		
		echo $this->element_before();
		
		echo '<div class="cssf-inner">';


		// Required Subfield Comprovation
		$maybe_sub = (isset($this->field['sub'])) ? true : false;

		foreach ( $this->field['fields'] as $field ) {
			$field['sub'] = ($maybe_sub) ? true : null;
			
			$default   	 = ( isset( $field['default'] ) ) ? $field['default'] : '';
			$field_id    = ( isset( $field['id'] ) ) ? $field['id'] : '';
			$field_value = ( isset( $this->value[$field_id] ) ) ? $this->value[$field_id] : $default;
			$unique_id   = $this->unique .'['. $this->field['id'] .']';

			
			if ( ! empty( $this->field['un_array'] ) ) {
				echo cssf_add_element( $field, cssf_get_option( $field_id ), $this->unique );
			} else {
				echo cssf_add_element( $field, $field_value, $unique_id );
			}
		}
		
		echo '</div>';
		
		echo $this->element_after();
		
	}
	
}
