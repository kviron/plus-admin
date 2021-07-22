<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Dimension
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_dimension extends CSSFramework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		$settings = array(
			'all'		=> ( isset($this->field['settings']['all'])) ? true : '',
			'width'		=> ( $this->field['settings']['width'] === false ) ? false : true,
			'height'	=> ( $this->field['settings']['height'] === false ) ? false : true,
			'unit'		=> ( $this->field['settings']['unit'] === false ) ? false : true,
		);

		$defaults_value = array(
			'all'		=> '',
			'width'		=> '',
			'height'	=> '',
			'unit'		=> ''
		);

		$value 			= wp_parse_args( $this->element_value(), $defaults_value );
		$value_all		= $value['all'];
		$value_width	= $value['width'];
		$value_height	= $value['height'];
		$value_unit 	= $value['unit'];
		$is_chosen		= ( isset( $this->field['chosen'] ) && $this->field['chosen'] === false ) ? '' : 'chosen ';
		$chosen_rtl		= ( is_rtl() && ! empty( $is_chosen ) ) ? 'chosen-rtl ' : '';

		echo $this->element_before();
		echo '<div class="cssf-dimension cssf-multifield">';

		if ($settings['all'] === true) {
			echo cssf_add_element( array(
				'pseudo'	=> true,
				'type'		=> 'text_addon',
				'name'		=> $this->element_name('[all]'),
				'settings'	=> array(
					'addon_value'	=> '<i class="fa fa-arrows"></i>',
				),
				'value'		=> $value_all,
				'attributes' => [
					'placeholder' => 'all'
				]
			) );
		} else {
			if ($settings['width'] === true) {
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[width]'),
					'settings'	=> array(
						'addon_value'	=> '<i class="fa fa-arrows-h"></i>',
					),
					'value'		=> $value_width,
					'attributes' => [
						'placeholder' => 'width'
					]
				) );
			}
			if ($settings['height'] === true) {
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[height]'),
					'settings'	=> array(
						'addon_value'	=> '<i class="fa fa-arrows-v"></i>',
					),
					'value'		=> $value_height,
					'attributes' => [
						'placeholder' => 'height'
					]
				) );
			}
		}
		if ($settings['unit'] === true) {
			echo cssf_add_element( array(
				'pseudo'	=> true,
				'type'		=> 'select',
				'name'		=> $this->element_name('[unit]'),
				'options'	=> array(
					'em'	=> 'em',
					'px'	=> 'px',
					'%'		=> '%',
				),
				'value'		=> $value_unit,
			) );
		}

		echo '</div>';
		echo $this->element_after();
	}

}




// Add 
add_action( 'cssf_enqueque_fields_scripts', 'cssf_enqueue_scripts');

function cssf_enqueue_scripts(){
	// wp_enqueue_script( 'cssf-framework',  CSSF_URI .'/laruta/js/cssf-dimension.js',  array( 'cssf-plugins' ), '1.0.0', true );
}