<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
*
* Field: button_set
*
* @since 1.0.0
* @version 1.0.0
*
*/

class CSSFramework_Option_button_set extends CSSFramework_Options {
	
	public function __construct( $field, $value = '', $unique = ''){
		parent::__construct( $field, $value, $unique);
	}
	
	public function output() {
		
		$args = wp_parse_args( $this->field, 
		array(
			'multiple' => false,
			'options'  => array(),
			)
		);
		
		$value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );
		
		echo $this->element_before();
		
		if( ! empty( $args['options'] ) ) {
			
			echo '<div class="cssf-siblings cssf--button-group" data-multiple="'. $args['multiple'] .'">';
			
			foreach( $args['options'] as $key => $option ) {
				
				$type    = ( $args['multiple'] ) ? 'checkbox' : 'radio';
				$extra   = ( $args['multiple'] ) ? '[]' : '';
				$active  = ( in_array( $key, $value ) ) ? ' cssf--active' : '';
				$checked = ( in_array( $key, $value ) ) ? ' checked' : '';
				
				echo '<div class="cssf--sibling cssf--button'. $active .'">';
				echo '<input type="'. $type .'" class="cssf-hidden" name="'. $this->element_name( $extra ) .'" value="'. $key .'"'. $this->element_attributes() . $checked .'/>';
				echo $option;
				echo '</div>';
				
			}
			
			echo '</div>';
			
		}
		
		echo '<div class="clear"></div>';
		
		echo $this->element_after();
		
	}
	
}