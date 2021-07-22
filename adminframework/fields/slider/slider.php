<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Slider
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_slider extends CSSFramework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		$defaults_value = array(
			'slider1'	=> 0,
			'slider2'	=> null,
		);

		$this->value 	= wp_parse_args( $this->element_value(), $defaults_value );
		// $value_slider1 	= (isset($value['slider1'])) ? $value['slider1'] : '';
		// $value_slider2 	= (isset($value['slider2'])) ? $value['slider2'] : '';

		$settings = array(
			'step'  	=> ( empty( $this->field['settings']['step'] ) ) ? 1 : $this->field['settings']['step'],
			'unit'  	=> ( empty( $this->field['settings']['unit'] ) ) ? '' : $this->field['settings']['unit'],
			'min'   	=> ( empty( $this->field['settings']['min'] ) ) ? 0 : $this->field['settings']['min'],
			'max'   	=> ( empty( $this->field['settings']['max'] ) ) ? 100 : $this->field['settings']['max'],
			'round' 	=> ( empty( $this->field['settings']['round'] ) ) ? false : true,
			'tooltip'	=> ( empty( $this->field['settings']['tooltip'] ) ) ? false : true,
			'input'		=> ( empty( $this->field['settings']['input'] ) ) ? false : true,
			'handles'	=> ( empty( $this->field['settings']['handles'] ) ) ? false : true,
			'size'		=> ( isset( $this->field['settings']['size'])) ? $this->field['settings']['size'] : 'sm',
			'alignment'	=> ( isset( $this->field['settings']['alignment'])) ? $this->field['settings']['alignment'] : 'center',
			'slider1'	=> $this->value['slider1'],
			'slider2'	=> $this->value['slider2'],
		);

		$input_type 	= ($settings['input']) ? 'text' : 'hidden';

		$the_value 		= (isset($this->value['slider2'])) ? $this->value['slider2'] : $this->value['slider1'];
		if (isset($this->value['slider1']) && isset($this->value['slider2'])) {
			$the_value 		= json_encode(array($this->value['slider1'],$this->value['slider2']));
		}

		echo $this->element_before();

		echo "<input type='hidden' name='". $this->element_name() ."' value='{$the_value}' ". $this->element_class('cssf-slider_value') . $this->element_attributes() ."/>";

		echo '<div class="cssf-slider" data-slider-options=\'' . json_encode( $settings ) . '\'>';

		echo cssf_add_element( array(
			'pseudo'		=> true,
			'type'			=> 'text_addon',
			'name'			=> $this->element_name('[slider1]'),
			'value'			=> $this->value['slider1'],
			'default'		=> $this->value['slider1'],
			'class'			=> 'cssf-slider_handler1',
			'attributes' 	=> [
				'placeholder' 	=> $settings['min'],
				'type'			=> $input_type
			],
			'settings'		=> [
				'type'			=> 'append',
				'append_value'	=> $settings['unit'],
				'size'			=> $settings['size'],
				'alignment'		=> $settings['alignment'],
			]
		) );
		
		echo '<div class="cssf-slider-wrapper"></div>';
		
		if ($settings['handles']) { 

			echo cssf_add_element( array(
				'pseudo'		=> true,
				'type'			=> 'text',
				'name'			=> $this->element_name('[slider2]'),
				'value'			=> $this->value['slider2'],
				'default'		=> $this->value['slider2'],
				'class'			=> 'cssf-slider_handler2',
				'attributes' 	=> [
					'placeholder' 	=> $settings['max'],
					'type'			=> $input_type
				]
			) );
		}
		
		echo '</div>';

		echo $this->element_after();

	}

}