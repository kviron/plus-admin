<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Image Select
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_image_select extends CSSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output() {

    $input_type  = ( ! empty( $this->field['radio'] ) ) ? 'radio' : 'checkbox';
    $input_attr  = ( ! empty( $this->field['multi_select'] ) ) ? '[]' : '';

    echo $this->element_before();
    echo ( empty( $input_attr ) ) ? '<div class="cssf-field-image-select">' : '';

    
    if( isset( $this->field['options'] ) ) {
      $options  = $this->field['options'];
      foreach ( $options as $key => $value ) {
        if (is_array($value)){
          $image            = $value['image'];
          $caption          = $key;
          $name             = $value['name'];
          $caption_wrapper  = "<div class='image-select-caption'>{$name}</div>";
          $class            = "image-select-has-caption";
        } else {
          $image            = $value;
          $caption          = $key;
          $name             = $key;
          $caption_wrapper  = null;
          $class            = "cssf-has-tooltip";
        }

        echo '
            <label>
              <input type="'. $input_type .'" name="'. $this->element_name( $input_attr ) .'" value="'. $caption .'"'. $this->element_class() . $this->element_attributes( $caption ) . $this->checked( $this->element_value(), $caption ) .'/>
              <div class="cssf-image-select-wrapper '.$class.'" data-title="'.$caption.'">
                <img src="'. $image .'" alt="'. $name .'" />
                '.$caption_wrapper.'
              </div>
            </label>';
      }
    }

    echo ( empty( $input_attr ) ) ? '</div>' : '';
    echo $this->element_after();

  }

}
