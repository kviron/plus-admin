<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Border Radius
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_border_radius extends CSSFramework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		echo $this->element_before();

		$settings = array(
			'all'			=> ( isset($this->field['settings']['all'])) ? true : '',
			'topleft'		=> ( empty($this->field['settings']['topleft'])) ? true : '',
			'topright'		=> ( empty($this->field['settings']['topright'])) ? true : '',
			'bottomleft'	=> ( empty($this->field['settings']['bottomleft'])) ? true : '',
			'bottomright'	=> ( empty($this->field['settings']['bottomright'])) ? true : '',
			'unit'			=> ( empty($this->field['settings']['unit'])) ? true : '',
		);

		$defaults_value = array(
			'all'			=> '',
			'topleft'		=> '',
			'topright'		=> '',
			'bottomleft'	=> '',
			'bottomright'	=> '',
			'unit'			=> '',
		);

		$value				= wp_parse_args( $this->element_value(), $defaults_value );
		$value_all			= $value['all'];
		$value_topleft		= $value['topleft'];
		$value_topright		= $value['topright'];
		$value_bottomleft	= $value['bottomleft'];
		$value_bottomright	= $value['bottomright'];
		$value_unit		= $value['unit'];
		$is_chosen		= ( isset( $this->field['chosen'] ) && $this->field['chosen'] === false ) ? '' : 'chosen ';
		$chosen_rtl		= ( is_rtl() && ! empty( $is_chosen ) ) ? 'chosen-rtl ' : '';

		echo '<div class="cssf-border_radius cssf-multifield">';

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

			if ($settings['topleft'] === true) {
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[topleft]'),
					'settings'	=> array(
						'addon_value'	=> '<i class="im im-arrow-up-left"></i>',
					),
					'value'		=> $value_topleft,
					'attributes' => [
						'placeholder' => 'top left'
					]
				) );
			}
			if ($settings['topright'] === true) {
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[topright]'),
					'settings'	=> array(
						'addon_value'	=> '<i class="im im-arrow-up-right"></i>',
					),
					'value'		=> $value_topright,
					'attributes' => [
						'placeholder' => 'top right'
					]
				) );
			}
			if ($settings['bottomleft'] === true) {
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[bottomleft]'),
					'settings'	=> array(
						'addon_value'	=> '<i class="im im-arrow-down-left"></i>',
					),
					'value'		=> $value_bottomleft,
					'attributes' => [
						'placeholder' => 'bottom left'
					]
				) );
			}
			if ($settings['bottomright'] === true) {
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[bottomright]'),
					'settings'	=> array(
						'addon_value'	=> '<i class="im im-arrow-down-right"></i>',
					),
					'value'		=> $value_bottomright,
					'attributes' => [
						'placeholder' => 'bottom right'
					]
				) );
			}
			
		}

		if ($settings['unit'] === true) {
			echo cssf_add_element( array(
				'pseudo'	=> true,
				'type'		=> 'select',
				'name'		=> $this->element_name('[unit]'),
				'settings'	=> array(
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