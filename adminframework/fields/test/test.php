<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
*
* Field: Test
*
* @since 1.0.0
* @version 1.0.0
*
*/
class CSSFramework_Option_test extends CSSFramework_Options {
	
	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
		
		$this->enqueue_scripts();
	}
	
	public function enqueue_scripts(){
		$url = CSSF_URI . '/fields/test/';
		wp_enqueue_script( 'testfield', $url . 'js/test.js', array( ), '', false);
	}
	
	public function output() {
		
		echo $this->element_before();
		echo "ESTE ES EL ELEMENTO DE PRUEBA CTM";
		echo $this->element_after();
		
	}
	
}
