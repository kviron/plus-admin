<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
*
* Field: Group
*
* @since 1.0.0
* @version 1.0.0
*
*/
class CSSFramework_Option_group extends CSSFramework_Options {
	
	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}
	
	public function output() {
		
		echo $this->element_before();
		
		$fields      		= array_values( $this->field['fields'] );
		$last_id    		= ( is_array( $this->value ) ) ? max( array_keys( $this->value ) ) : 0;
		$last_id 			= ( is_array( $this->value ) ) ? count( array_keys( $this->value ) ) : 0;
		$acc_title_new 		= ( isset( $this->field['accordion_title_new'] ) ) ? $this->field['accordion_title_new'] : esc_attr__( 'Adding', 'cssf-framework' );
		$acc_title 			= ( isset( $this->field['accordion_title'] ) ) ? (($this->field['accordion_title'] === false) ? 'hidden_title' : $this->field['accordion_title'])  : false;
		$acc_title_field 	= ( isset( $this->field['accordion_title_field'] ) ) ? $this->field['accordion_title_field'] : false;
		$acc_title_format 	= ( isset( $this->field['accordion_title_format'] ) ) ? $this->field['accordion_title_format'] : false;
		$field_title 		= ( isset( $fields[0]['title'] ) ) ? $fields[0]['title'] : $fields[1]['title'];
		$field_id   		= ( isset( $fields[0]['id'] ) ) ? $fields[0]['id'] : $fields[1]['id'];

		$accordion_item_total = 0;
		
		//$el_class    		= ( isset( $this->field['title'] ) ) ? sanitize_title( $field_title ) : 'no-title';
		$el_class    		= ( $acc_title ) ? sanitize_title( $acc_title ) : 'no-title';

		

		// Nuevas Variables 2019
		$g_id = $this->field['id'];



		// First Base Item to be Cloned
		echo '<div class="cssf-group cssf-group-'. $el_class .'-adding hidden" data-field-id="'.$this->unique.'" data-unique-id="['.$g_id.']">';
		
		echo '<div class="cssf-group-title-wrapper">';
		echo '<h4 class="cssf-group-title">'. $acc_title_new .'</h4>';
		echo '<a href="#" class="cssf-button cssf-button-warning cssf-remove-group">'. esc_attr__( 'Remove', 'cssf-framework' ) .'</a>';
		echo '</div>';
		echo '<div class="cssf-group-content">';
		foreach ( $fields as $field ) {
			$field['sub']   = true;
			$unique         = $this->unique .'[_nonce]['. $this->field['id'] .']['. $last_id .']';
			// $unique         = $this->unique .'['. $this->field['id'] .']['. $last_id .']';
			$field_default  = ( isset( $field['default'] ) ) ? $field['default'] : '';

			// echo "<pre>";
			// print_r($field);
			// echo "</pre>";
			
			echo cssf_add_element( $field, $field_default, $unique );
		}
		// echo '<div class="cssf-element cssf-text-right cssf-remove"><a href="#" class="button cssf-warning-primary cssf-remove-group">'. esc_attr__( 'Remove', 'cssf-framework' ) .'</a></div>';
		echo '</div>';
		
		echo '</div>';
		
		
		// Items
		echo '<div class="cssf-groups cssf-accordion" data-field-id="'.$this->unique.'" data-unique-id="['.$g_id.']">';
		
		// // Custom Group Title
		// if (!empty($search_id)){
		// 	$acc_title_new = ( isset( $search_id[0]['title'] ) ) ? $search_id[0]['title'] : $acc_title_new;
		// 	$field_id  = ( isset( $search_id[0]['id'] ) ) ? $search_id[0]['id'] : $field_id;
		// }

		$item_count_id = null;
		if( ! empty( $this->value ) ) {
			$item_count_id = 0;
			// echo "<pre>";
			// print_r($this->value);
			// echo "</pre>";
			foreach ( $this->value as $key => $value ) {
			
				$title = ( isset( $this->value[$key][$field_id] ) ) ? $this->value[$key][$field_id] : '';

				if ( is_array( $title ) && isset( $this->multilang ) ) {
					$lang  = cssf_language_defaults();
					$title = $title[$lang['current']];
					$title = is_array( $title ) ? $title[0] : $title;
				}


				// echo "<pre>";
				// print_r($fields);
				// echo "</pre>";

				$_locked = isset($value['_locked']) ? true : false;
				
				/**
				 * Custom Group Title
				 */

				// Single field title
				$group_title_field 	= ($acc_title_field) ? cssf_search_multi_array($this->value[$key],$acc_title_field) : false;
				$group_title_single = ($group_title_field) ? $group_title_field : false;

				// Multivar field title
				$title_format 		= (isset($acc_title_format['format'])) ? $acc_title_format['format'] : false;
				$title_field_vars 	= (isset($acc_title_format['fields'])) ? $acc_title_format['fields'] : false;
				$title_vars 		= array(); $group_title_formated = '';
				if ($title_format && $title_field_vars){
					foreach ($title_field_vars as $var => $field) {
						$title_vars[$var] = cssf_search_multi_array($this->value[$key],$field);
					}
					$group_title_formated = strtr($title_format, $title_vars);
				}

				$group_title 		= ($acc_title) ? (($acc_title === 'hidden_title') ? '' : "{$acc_title}: ") : "{$field_title}: ";
				$group_title_value  = ($group_title_single) ? $group_title_single : (($group_title_formated) ? $group_title_formated : $title);

				// echo '<div class="cssf-group cssf-group-'. $el_class .'-'. ( $item_count_id + 1 ) .'" data-item-index="'.$item_count_id.'">';
				// echo '<div class="cssf-group-title-wrapper">';
				// // echo '<h4 class="cssf-group-title">'. $field_title .': '. $title .'</h4>';
				// echo '<h4 class="cssf-group-title">'. $group_title .': '. $group_title_value .'</h4>';
				$element_class 	= $el_class .'-'. ( $item_count_id + 1 );
				$remove_btn 	= (!$_locked) ? '<a href="#" class="cssf-button cssf-button-warning cssf-remove-group">'. esc_attr__( 'Remove', 'cssf-framework' ) .'</a>' : '';
				echo "
				<div class='cssf-group cssf-group-{$element_class}' data-item-index='{$item_count_id}'>
					<div class='cssf-group-title-wrapper'>
						<h4 class='cssf-group-title'>{$group_title}{$group_title_value}</h4>
						{$remove_btn}
					</div>
					<div class='cssf-group-content'>
				";
				
				foreach ( $fields as $field ) {
					$field['sub'] = true;
					$unique = $this->unique . '[' . $this->field['id'] . ']['.$item_count_id.']';
					$value  = ( isset( $field['id'] ) && isset( $this->value[$key][$field['id']] ) ) ? $this->value[$key][$field['id']] : '';
					echo cssf_add_element( $field, $value, $unique );
				}

				echo '</div>';
				echo '</div>';
				
				$item_count_id++;
			}
		}
		
		$accordion_item_total = ($item_count_id === null) ? 'initial' : $item_count_id - 1;

		echo '</div>';
		
		echo '<a href="#" class="cssf-button cssf-button-primary cssf-add-group" data-count="'.$accordion_item_total.'">'. $this->field['button_title'] .'</a>';
		
		echo $this->element_after();
		
	}
	
}
