<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
*
* Field: Sorter
*
* @since 1.0.0
* @version 1.0.1
*
*/
class CSSFramework_Option_Sorter extends CSSFramework_Options {
	
	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}
	
	public function output(){
		
		echo $this->element_before();
		
		$value          = $this->element_value();
		$value          = ( ! empty( $value ) ) ? $value : $this->field['default'];
		$enabled        = ( ! empty( $value['enabled'] ) ) ? $value['enabled'] : array();
		$disabled       = ( ! empty( $value['disabled'] ) ) ? $value['disabled'] : array();
		$enabled_title  = ( isset( $this->field['settings']['enabled_title'] ) ) ? $this->field['settings']['enabled_title'] : esc_attr__( 'Enabled', 'cssf-framework' );
		$disabled_title = ( isset( $this->field['settings']['disabled_title'] ) ) ? $this->field['settings']['disabled_title'] : esc_attr__( 'Disabled', 'cssf-framework' );
		
		echo '<div class="cssf-sorter-wrapper">';
		echo '<div class="cssf-sorter-block">';
		echo '<h3>'. $enabled_title .'</h3>';
		echo '<ul class="cssf-enabled">';
		if( ! empty( $enabled ) ) {
			foreach( $enabled as $en_id => $en_name ) {
				echo '<li><input type="hidden" name="'. $this->element_name( '[enabled]['. $en_id .']' ) .'" value="'. $en_name .'" data-element-id="'.$en_id.'"/><label>'. $en_name .'</label></li>';
			}
		}
		echo '</ul>';
		echo '</div>';
		
		echo '<div class="cssf-sorter-block">';
		echo '<h3>'. $disabled_title .'</h3>';
		echo '<ul class="cssf-disabled">';
		if( ! empty( $disabled ) ) {
			foreach( $disabled as $dis_id => $dis_name ) {
				echo '<li><input type="hidden" name="'. $this->element_name( '[disabled]['. $dis_id .']' ) .'" value="'. $dis_name .'" data-element-id="'.$dis_id.'"/><label>'. $dis_name .'</label></li>';
			}
		}
		echo '</ul>';
		echo '</div>';
		echo '</div>';
		echo '<div class="clear"></div>';
		
		echo $this->element_after();
		
	}
	
}
