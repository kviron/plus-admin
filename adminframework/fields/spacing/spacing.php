<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Spacing
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_spacing extends CSSFramework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {
		$defaults_value = array(
			'all'		=> 0,
			'top'		=> 0,
			'right'		=> 0,
			'bottom'	=> 0,
			'left'		=> 0,
			'unit'		=> 'px',
		);
		$this->value 	= wp_parse_args( $this->element_value(), $defaults_value );

		$value = $this->value;

		$settings = array(
			'all'		=> (isset($this->field['settings']['all'])) ? $this->field['settings']['all'] : false,
			'top'		=> (isset($this->field['settings']['top'])) ? $this->field['settings']['top'] : true,
			'right'		=> (isset($this->field['settings']['right'])) ? $this->field['settings']['right'] : true,
			'bottom'	=> (isset($this->field['settings']['bottom'])) ? $this->field['settings']['bottom'] : true,
			'left'		=> (isset($this->field['settings']['left'])) ? $this->field['settings']['left'] : true,
			'unit'		=> (isset($this->field['settings']['unit'])) ? $this->field['settings']['unit'] : true,
		);


		$value_all		= $value['all'];
		$value_top		= $value['top'];
		$value_right	= $value['right'];
		$value_bottom	= $value['bottom'];
		$value_left		= $value['left'];
		$value_unit		= $value['unit'];
		$both 			= ($settings['unit'] == false) ? 'both' : 'prepend';
		$is_chosen		= ( isset( $this->field['chosen'] ) && $this->field['chosen'] === false ) ? '' : 'chosen ';
		$chosen_rtl		= ( is_rtl() && ! empty( $is_chosen ) ) ? 'chosen-rtl ' : '';

		echo $this->element_before();
		echo '<div class="cssf-spacing cssf-multifield">';

		if ($settings['all'] === true) {
			echo cssf_add_element( array(
				'pseudo'	=> true,
				'type'		=> 'text_addon',
				'name'		=> $this->element_name('[all]'),
				'settings'	=> array(
					'prepend_value'	=> '<i class="cli cli-arrows"></i>',
					'append_value'	=> $value_unit,
					'size'			=> 'xs',
					'type'			=> $both,
				),
				'value'		=> $value_all,
				'attributes' => array(
					'placeholder' => esc_attr__('All','cssf-framework'),
				),
				'class'	=> 'cssf-number',
			) );
		} else {
			if ($settings['top'] === true) {
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[top]'),
					'settings'	=> array(
						'prepend_value'	=> '<i class="cli cli-arrow-up"></i>',
						'append_value'	=> $value_unit,
						'size'			=> 'xs',
						'type'			=> $both,
					),
					'value'		=> $value_top,
					'attributes' => array(
						'placeholder' => esc_attr__('Top','cssf-framework'),
					),
					'class'	=> 'cssf-number',
				) );
			}
			if ($settings['right'] === true) {
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[right]'),
					'settings'	=> array(
						'prepend_value'	=> '<i class="cli cli-arrow-right"></i>',
						'append_value'	=> $value_unit,
						'size'			=> 'xs',
						'type'			=> $both,
					),
					'value'		=> $value_right,
					'attributes' => array(
						'placeholder' => esc_attr__('Right','cssf-framework'),
					),
					'class'	=> 'cssf-number',
				) );
			}
			if ($settings['bottom'] === true) {
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[bottom]'),
					'settings'	=> array(
						'prepend_value'	=> '<i class="cli cli-arrow-down"></i>',
						'append_value'	=> $value_unit,
						'size'			=> 'xs',
						'type'			=> $both,
					),
					'value'		=> $value_bottom,
					'attributes' => array(
						'placeholder' => esc_attr__('Bottom','cssf-framework'),
					),
					'class'	=> 'cssf-number',
				) );
			}
			if ($settings['left'] === true) {
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[left]'),
					'settings'	=> array(
						'prepend_value'	=> '<i class="cli cli-arrow-left"></i>',
						'append_value'	=> $value_unit,
						'size'			=> 'xs',
						'type'			=> $both,
					),
					'value'		=> $value_left,
					'attributes' => array(
						'placeholder' => esc_attr__('Left','cssf-framework'),
					),
					'class'	=> 'cssf-number',
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