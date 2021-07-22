<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Color Variant
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_color_variant extends CSSFramework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		echo $this->element_before();

		$settings = array(
			'darker'    => ( empty($this->field['settings']['darker']) ) ? false : true,
			'dark'      => ( empty($this->field['settings']['dark']) ) ? false : true,
			'normal'    => ( empty($this->field['settings']['normal']) ) ? false : true,
            'light'     => ( empty($this->field['settings']['light']) ) ? false : true,
			'lighter'   => ( empty($this->field['settings']['lighter']) ) ? false : true,
			'palettes'  => ( isset($this->field['settings']['palettes']) ) ? $this->field['settings']['palettes'] : false,
		);

		$defaults_value = array(
			'darker'    => '',
			'dark'      => '',
			'normal'    => '',
            'light'     => '',
			'lighter'   => '',
		);

		$this->value  = wp_parse_args( $this->element_value(), $defaults_value );

		echo '<div class="cssf-color_variant cssf-multifield">';

		if ($settings['darker'] === true) {
			echo cssf_add_element( array(
				'pseudo'		=> true,
				'id'			=> $this->field['id'].'_color_darker',
				'type'			=> 'color_picker',
				'name'			=> $this->element_name('[darker]'),
				'attributes'	=> array(
					'data-atts'		=> 'bgcolor',
				),
				'value'			=> $this->value['darker'],
				'default'		=> ( isset( $this->field['default']['darker'] ) ) ? $this->field['default']['darker'] : '',
				'rgba'			=> ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
				'palettes'		=> $settings['palettes'],
				'before'		=> '<label>'.esc_attr__('Darker','cssf-framework').'</label>',
			), $this->value['darker'] );
		}
        if ($settings['dark'] === true) {
			echo cssf_add_element( array(
				'pseudo'		=> true,
				'id'			=> $this->field['id'].'_color_dark',
				'type'			=> 'color_picker',
				'name'			=> $this->element_name('[dark]'),
				'attributes'	=> array(
					'data-atts'		=> 'bgcolor',
				),
				'value'			=> $this->value['dark'],
				'default'		=> ( isset( $this->field['default']['dark'] ) ) ? $this->field['default']['dark'] : '',
				'rgba'			=> ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
				'palettes'		=> $settings['palettes'],
				'before'		=> '<label>'.esc_attr__('Dark','cssf-framework').'</label>',
			), $this->value['dark'] );
        }
        if ($settings['normal'] === true) {
			echo cssf_add_element( array(
				'pseudo'		=> true,
				'id'			=> $this->field['id'].'_color_normal',
				'type'			=> 'color_picker',
				'name'			=> $this->element_name('[normal]'),
				'attributes'	=> array(
					'data-atts'		=> 'bgcolor',
				),
				'value'			=> $this->value['normal'],
				'default'		=> ( isset( $this->field['default']['normal'] ) ) ? $this->field['default']['normal'] : '',
				'rgba'			=> ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
				'palettes'		=> $settings['palettes'],
				'before'		=> '<label>'.esc_attr__('Normal','cssf-framework').'</label>',
			), $this->value['normal'] );
		}
        if ($settings['light'] === true) {
			echo cssf_add_element( array(
				'pseudo'		=> true,
				'id'			=> $this->field['id'].'_color_light',
				'type'			=> 'color_picker',
				'name'			=> $this->element_name('[light]'),
				'attributes'	=> array(
					'data-atts'		=> 'bgcolor',
				),
				'value'			=> $this->value['light'],
				'default'		=> ( isset( $this->field['default']['light'] ) ) ? $this->field['default']['light'] : '',
				'rgba'			=> ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
				'palettes'		=> $settings['palettes'],
				'before'		=> '<label>'.esc_attr__('Light','cssf-framework').'</label>',
			), $this->value['light'] );
        }
        if ($settings['lighter'] === true) {
			echo cssf_add_element( array(
				'pseudo'		=> true,
				'id'			=> $this->field['id'].'_color_lighter',
				'type'			=> 'color_picker',
				'name'			=> $this->element_name('[lighter]'),
				'attributes'	=> array(
					'data-atts'		=> 'bgcolor',
				),
				'value'			=> $this->value['lighter'],
				'default'		=> ( isset( $this->field['default']['lighter'] ) ) ? $this->field['default']['lighter'] : '',
				'rgba'			=> ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
				'palettes'		=> $settings['palettes'],
				'before'		=> '<label>'.esc_attr__('Lighter','cssf-framework').'</label>',
			), $this->value['lighter'] );
		}

		echo '</div>';
		echo $this->element_after();

	}

}