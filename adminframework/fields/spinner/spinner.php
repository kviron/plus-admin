<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
*
* Field: spinner
*
* @since 1.0.0
* @version 1.0.0
*
*/
if (!class_exists( 'CSSFramework_Option_spinner' )){
	class CSSFramework_Option_spinner extends CSSFramework_Options {
		
		public function __construct( $field, $value = '', $unique = ''){
			parent::__construct( $field, $value, $unique);
		}

		static function init(){
			/**
			 * Register Hooks
			 */
	
			// Register Field Assets - Styles and Scripts
			add_filter('cssf_register_framework_assets',array( 'CSSFramework_Option_spinner', 'register_assets' ),100);
		}

		static public function register_assets($styles_scripts){
			$styles = $styles_scripts[0];
			$scripts = $styles_scripts[1];

			wp_enqueue_script( 'jquery-ui-spinner' );

			$url = CSSF_URI . '/fields/spinner/';

			$styles['cssf-field-spinner'] = array(
				$url . 'css/styles.css',
				array(),
				'1.0.0',
				false,
			);
	
			$scripts['cssf-field-spinner'] = array(
				$url . 'js/scripts.js',
				array( 'cssf-plugins' ),
				'1.0.0',
				false,
			);
	
			return array($styles,$scripts);
		}
		
		/**
		 * Field Output
		 */
		public function output(){
			$settings = array(
				'max'  => 100,
				'min'  => 0,
				'step' => 1,
				'unit' => '',
			);

			$settings = wp_parse_args($this->field['settings'], $settings);
			
			echo $this->element_before();
			echo '<div class="cssf--spin">';
			echo '<input type="text" name="'. $this->element_name() .'" value="'. $this->value .'"'. $this->element_class() . $this->element_attributes( array( 'class' => 'cssf-number' ) ) .' data-max="'. $settings['max'] .'" data-min="'. $settings['min'] .'" data-step="'. $settings['step'] .'"/>';
			echo ( ! empty( $settings['unit'] ) ) ? '<div class="cssf--unit">'. $settings['unit'] .'</div>' : '';
			echo '</div>';
			echo '<div class="clear"></div>';
			echo $this->element_after();
			
		}
		
	}
}


CSSFramework_Option_spinner::init();