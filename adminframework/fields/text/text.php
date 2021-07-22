<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Text
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_text extends CSSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output(){

    echo $this->element_before();

    if (isset( $this->field['settings'])){ extract( $this->field['settings'] ); }
    $size           = ( isset( $size  ) ) ? $size  : 'lg';
    $alignment      = ( isset( $alignment  ) ) ? $alignment  : 'left';

    $settings_class = "cssf-input--size_{$size} cssf-input--alignment_{$alignment}";

    echo '<input type="'. $this->element_type() .'" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_class($settings_class) . $this->element_attributes() .'/>';
    echo $this->element_after();

  }

}
