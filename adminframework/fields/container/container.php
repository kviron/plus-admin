<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
*
* Field: Container
*
* @since 1.0.0
* @version 1.0.0
*
*/
class CSSFramework_Option_container extends CSSFramework_Options {
	
	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}
	
	public function output() {
		
		echo $this->element_before();

		// Required Subfield Comprovation
		$maybe_sub = (isset($this->field['sub'])) ? true : false;
		
		echo '<div class="cssf-content-wrapper">';
		
		foreach($this->field['fields'] as $field){
			$field['sub'] = ($maybe_sub) ? true : null;

			$default    	= (isset($field['default'])) ? $field['default'] : '';
			$field_id    	= (isset($field['id'])) ? $field['id'] : '';
			$field_value 	= (isset($this->value[$field_id])) ? $this->value[$field_id] : $default;
			$unique_id   	= $this->unique;
			$unique_id   	= $this->unique .'['. $this->field['id'] .']';
			
			echo cssf_add_element( $field, $field_value, $unique_id );
		}
		
		echo '</div>';
		
		echo $this->element_after();
		
	}
	
}
