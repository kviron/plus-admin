<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Text Addon
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_text_addon extends CSSFramework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output(){

		$settings = array(
			'type'			=> ( empty( $this->field['settings']['type'] ) ) ? 'prepend' : $this->field['settings']['type'],
			'icon'			=> ( empty( $this->field['settings']['icon'] ) ) ? false : $this->field['settings']['icon'],
			'prepend_value'	=> ( empty( $this->field['settings']['prepend_value'] ) ) ? '' : $this->field['settings']['prepend_value'],
			'append_value'	=> ( empty( $this->field['settings']['append_value'] ) ) ? '' : $this->field['settings']['append_value'],
			'size'			=> ( isset( $this->field['settings']['size'])) ? $this->field['settings']['size'] : 'sm',
			'alignment'		=> ( isset( $this->field['settings']['alignment'])) ? $this->field['settings']['alignment'] : 'center',
		);

		$size 		= $settings['size'];
		$alignment 	= $settings['alignment'];

    	$settings_class = "cssf-input--size_{$size} cssf-input--alignment_{$alignment}";
		$addon_icon = ($settings['icon']) ? 'cssf-input-addon-icon' : '';

		echo $this->element_before();

		if ($settings['type'] === 'prepend'){
			echo '<div class="cssf-input-addon-field cssf-field-text cssf-input-addon--style_prepend '.$settings_class.'">';
			echo '<span class="cssf-input-addon '.$addon_icon.'">'.$settings['prepend_value'].'</span>';
			echo '<input type="text" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_class() . $this->element_attributes() .'/>';
			echo '</div>';
		} else if ($settings['type'] === 'append'){
			echo '<div class="cssf-input-addon-field cssf-field-text cssf-input-addon--style_append '.$settings_class.'">';
			echo '<input type="text" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_class() . $this->element_attributes() .'/>';
			echo '<span class="cssf-input-addon '.$addon_icon.'">'.$settings['append_value'].'</span>';
			echo '</div>';
		} else if ($settings['type'] === 'both'){
			echo '<div class="cssf-input-addon-field cssf-field-text cssf-input-addon--style_both '.$settings_class.'">';
			echo '<span class="cssf-input-addon '.$addon_icon.'">'.$settings['prepend_value'].'</span>';
			echo '<input type="text" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_class() . $this->element_attributes() .'/>';
			echo '<span class="cssf-input-addon '.$addon_icon.'">'.$settings['append_value'].'</span>';
			echo '</div>';
		}
		echo $this->element_after();

	}

}