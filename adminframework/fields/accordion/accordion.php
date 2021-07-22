<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Accordion
 *
 * @since 1.0.0
 * @version 1.0.1
 *
 */
class CSSFramework_Option_accordion extends CSSFramework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		echo $this->element_before();

		$title 		= $this->field['accordion_title'];

		echo '<div class="cssf-accordion-inner-section">';

		foreach($this->field['accordion_content'] as $accordion){

			$accordion_id 			= $accordion['id'];
			$accordion_title 		= (isset($accordion['title'])) ? $accordion['title'] : 'Accordion Section';
			$accordion_description 	= (isset($accordion['description'])) ? $accordion['description'] : '';
			echo "
				<div class='cssf-accordion-section'>
					<div class='cssf-accordion-title'>
						<h4>{$accordion_title}</h4>
						<p>{$accordion_description}</p>
					</div>
					<div class='cssf-accordion-content'>
				";

			$accordion_value = isset($this->value[$accordion_id]) ? $this->value[$accordion_id] : array();
			// $accordion_value = isset($this->value[$accordion_id]) ? $this->value[$accordion_id] : $this->field[$accordion_id];

			// echo "------------------------------------------------";
			// echo "This VALUE";
			// echo "<pre>";
			// print_r($this->value);
			// echo "</pre>";
			// echo "<br>ACCORDION VALUE";
			// echo "<pre>";
			// print_r($accordion_value);
			// echo "</pre>";
			// echo "------------------------------------------------";

			foreach ( $accordion['fields'] as $field ) {
				$field_default  = ( isset( $field['default'] ) ) ? $field['default'] : '';
				// $field_id    	= ( isset( $field['id'] ) ) ? $field['id'] : '';
				// $field_value 	= ( isset( $accordion_value[$field_id] ) ) ? $accordion_value[$field_id] : $default;

				$field['sub']   = true;
				$unique_id   	= $this->unique .'['. $this->field['id'] .']['. $accordion_id .']';

				if ($this->value){
					$value = (isset( $field['id'] ) && isset($accordion_value[$field['id']])) ? $accordion_value[$field['id']] : '';
				} else {
					$value = $field_default;
				}
				
				if ( ! empty( $this->field['un_array'] ) ) {
					// echo cssf_add_element( $field, cssf_get_option( $field_id ), $this->unique );
				} else {
					echo cssf_add_element( $field, $value, $unique_id, true );
				}

				// echo "<pre>";
				// print_r($field);
				// echo "</pre>";
			}
			echo '</div>'; // .cssf-accordion-content
			echo '</div>'; // .cssf-accordion-section
		}

		echo '</div>';

		echo $this->element_after();

	}

}
