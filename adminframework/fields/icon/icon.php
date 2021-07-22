<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Icon
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_icon extends CSSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output() {

    echo $this->element_before();

    $value  = $this->element_value();
    $hidden = ( empty( $value ) ) ? ' hidden' : '';

    echo '<div class="cssf-icon-select">';
    echo '<span class="cssf-icon-preview'. $hidden .'"><i class="'. $value .'"></i></span>';
    echo '<a href="#" class="cssf-button cssf-button-primary cssf-icon-add">'. esc_attr__( 'Add Icon', 'cssf-framework' ) .'</a>';
    echo '<a href="#" class="cssf-button cssf-button-warning cssf-icon-remove'. $hidden .'">'. esc_attr__( 'Remove Icon', 'cssf-framework' ) .'</a>';
    echo '<input type="text" name="'. $this->element_name() .'" value="'. $value .'"'. $this->element_class( 'cssf-icon-value' ) . $this->element_attributes() .' />';
    echo '</div>';

    echo $this->element_after();

  }

}
