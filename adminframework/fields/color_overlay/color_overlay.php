<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
*
* Field: Color Overlay
*
* @since 1.0.0
* @version 1.0.0
*
*/
class CSSFramework_Option_color_overlay extends CSSFramework_Options {
	
	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}
	
	public function output() {
		
		echo $this->element_before();
		
		$defaults_value = array(
			'color'		=> 'transparent',
			'mode'		=> 'initial',
			'amount'	=> array(
				'slider1'	=> 0,
				'slider2'	=> 0,
			),
		);
		
		$blendmodes = array(
			'initial'		=> 'Initial',
			'inherit'		=> 'Inherit',
			'unset'			=> 'Unset',
			'normal' 		=> 'Normal',
			'multiply'		=> 'Multiply',
			'screen' 		=> 'Screen',
			'overlay' 		=> 'Overlay', 
			'darken' 		=> 'Darken',
			'lighten' 		=> 'Lighten',
			'color-dodge' 	=> 'Color Dodge',
			'color-burn' 	=> 'Color Burn',
			'hard-light' 	=> 'Hard Light',
			'soft-light' 	=> 'Soft Light',
			'difference' 	=> 'Difference',
			'exclusion' 	=> 'Exclusion',
			'hue' 			=> 'Hue',
			'saturation' 	=> 'Saturation',
			'color' 		=> 'Color',
			'luminosity' 	=> 'Luminosity',
		);

		$this->value		= wp_parse_args( $this->element_value(), $defaults_value );

		$amount = ($this->value['amount']['slider1']) ? $this->value['amount']['slider1'] : $this->value['amount'];
		
		echo '<div class="cssf-color_overlay cssf-multifield">';
		
		echo cssf_add_element(array(
			'pseudo'		=> true,
			'id'			=> $this->field['id'].'_color',
			'type'			=> 'color_picker',
			'name'			=> $this->element_name('[color]'),
			'attributes'	=> array(
				'data-atts'		=> 'bgcolor',
			),
			'value'			=> $this->value['color'],
			'default'		=> ( isset( $this->field['default']['color'] ) ) ? $this->field['default']['color'] : '',
			'rgba'			=> ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
			'palettes'		=> $this->field['palettes'],
			'before'		=> '<label>'.esc_attr__('Color','cssf-framework').'</label>',
		));
		echo cssf_add_element(array(
			'pseudo'        => true,
			'type'          => 'select',
			'name'          => $this->element_name( '[mode]' ),
			'options'       => $blendmodes,
			'attributes'    => array(
				'data-atts'     => 'mode',
			),
			'value'         => $this->value['mode'],
			'before'		=> '<label>'.esc_attr__('Blend Mode','cssf-framework').'</label>',
		));

		echo cssf_add_element(array(
			'pseudo'        => true,
			'type'          => 'slider',
			'name'          => $this->element_name( '[amount]' ),
			'attributes'    => array(
				'data-atts'     => 'amount',
			),
			'value'         => array(
				'slider1' => $amount,
				'slider2' => 0,
			),
			'before'		=> '<label>'.esc_attr__('Blend Amount','cssf-framework').'</label>',
			'settings'		=> array(
				'step'		=> 1,
				'min'		=> 0,
				'max'		=> 100,
				'unit'		=> esc_attr__('%','cssf-framework'),
				'input'		=> true,
				'round'		=> true,
			),
		));

		
		echo '</div>';
		echo $this->element_after();
		
	}
	
}