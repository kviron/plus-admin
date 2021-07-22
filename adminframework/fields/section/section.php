<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Section
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_section extends CSSFramework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		echo $this->element_before();

		echo '<div class="cssf-inner-section">';

		foreach ( $this->field['fields'] as $field ) {

			$default    = ( isset( $field['default'] ) ) ? $field['default'] : '';
			$field_id    = ( isset( $field['id'] ) ) ? $field['id'] : '';
			$field_value = ( isset( $this->value[$field_id] ) ) ? $this->value[$field_id] : $default;
			$unique_id   = $this->unique .'['. $this->field['id'] .']';

			if ( ! empty( $this->field['un_array'] ) ) {
				echo cssf_add_element( $field, cssf_get_option( $field_id ), $this->unique );
			} else {
				echo cssf_add_element( $field, $field_value, $unique_id );
			}

		}

		echo '</div>';

		echo $this->element_after();

	}

}
