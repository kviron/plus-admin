<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Border
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_border extends CSSFramework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		echo $this->element_before();

		$settings = array(
			'all'		=> ( $this->field['settings']['all'] === true ) ? true : false,
			'top'		=> ( $this->field['settings']['top'] === false ) ? false : true,
			'right'		=> ( $this->field['settings']['right'] === false ) ? false : true,
			'bottom'	=> ( $this->field['settings']['bottom'] === false ) ? false : true,
			'left'		=> ( $this->field['settings']['left'] === false ) ? false : true,
			'style'		=> ( $this->field['settings']['style'] === false ) ? false : true,
			'color'		=> ( $this->field['settings']['color'] === false ) ? false : true,
		);

		$defaults_value = array(
			'all'		=> '',
			'top'		=> '',
			'right'		=> '',
			'bottom'	=> '',
			'left'		=> '',
			'style'		=> '',
			'color'		=> '',
		);

		$value			= wp_parse_args( $this->element_value(), $defaults_value );
		$value_all		= $value['all'];
		$value_top		= $value['top'];
		$value_right	= $value['right'];
		$value_bottom	= $value['bottom'];
		$value_left		= $value['left'];
		$value_unit		= $value['unit'];
		$value_style 	= $value['style'];
		$is_chosen		= ( isset( $this->field['chosen'] ) && $this->field['chosen'] === false ) ? '' : 'chosen ';
		$chosen_rtl		= ( is_rtl() && ! empty( $is_chosen ) ) ? 'chosen-rtl ' : '';

		echo '<div class="cssf-border cssf-multifield">';

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
				],
				'before'		=> '<label>'.esc_attr__('All borders').'</label>',
			) );
		} else {
			if ($settings['top'] === true) {
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[top]'),
					'settings'	=> array(
						'addon_value'	=> '<i class="fa fa-long-arrow-up"></i>',
					),
					'value'		=> $value_top,
					'attributes' => [
						'placeholder' => 'top'
					],
					'before'		=> '<label>'.esc_attr__('Top Border').'</label>',
				) );
			}
			if ($settings['right'] === true) {
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[right]'),
					'settings'	=> array(
						'addon_value'	=> '<i class="fa fa-long-arrow-right"></i>',
					),
					'value'		=> $value_right,
					'attributes' => [
						'placeholder' => 'right'
					],
					'before'		=> '<label>'.esc_attr__('Right Border').'</label>',
				) );
			}
			if ($settings['bottom'] === true) {
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[bottom]'),
					'settings'	=> array(
						'addon_value'	=> '<i class="fa fa-long-arrow-down"></i>',
					),
					'value'		=> $value_bottom,
					'attributes' => [
						'placeholder' => 'bottom'
					],
					'before'		=> '<label>'.esc_attr__('Bottom Border').'</label>',
				) );
			}
			if ($settings['left'] === true) {
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[left]'),
					'settings'	=> array(
						'addon_value'	=> '<i class="fa fa-long-arrow-left"></i>',
					),
					'value'		=> $value_left,
					'attributes' => [
						'placeholder' => 'left'
					],
					'before'		=> '<label>'.esc_attr__('Left Border').'</label>',
				) );
			}
		}
		if ($settings['style'] === true) {
			echo cssf_add_element( array(
				'pseudo'	=> true,
				'type'		=> 'select',
				'name'		=> $this->element_name('[style]'),
				'options'	=> array(
					'none'		=> 'none',
					'solid'		=> 'solid',
					'dashed'	=> 'dashed',
					'dotted'	=> 'dotted',
					'double'	=> 'double',
				),
				'value'		=> $value_style,
				'before'		=> '<label>'.esc_attr__('Border Style').'</label>',
			) );
		}
		if ($settings['color'] === true) {
			echo cssf_add_element( array(
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
				'before'		=> '<label>'.esc_attr__('Border Color').'</label>',
			) );
		}

		echo '</div>';
		echo $this->element_after();

	}

}