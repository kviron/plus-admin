<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Image
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_Image extends CSSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output(){

    echo $this->element_before();

    if( isset( $this->field['settings'] ) ) { extract( $this->field['settings'] ); }
    $upload_type  = ( isset( $upload_type  ) ) ? $upload_type  : '*';
    $button_title = ( isset( $button_title ) ) ? $button_title : esc_attr__( 'Add Image', 'cssf-framework' );
    $frame_title  = ( isset( $frame_title  ) ) ? $frame_title  : esc_attr__( 'Upload Image', 'cssf-framework' );
    $insert_title = ( isset( $insert_title ) ) ? $insert_title : esc_attr__( 'Use Image', 'cssf-framework' );

    $preview = '';
    $value   = $this->element_value();
    $add     = ( ! empty( $this->field['add_title'] ) ) ? $this->field['add_title'] : esc_attr__( 'Add Image', 'cssf-framework' );
    $hidden  = ( empty( $value ) ) ? ' hidden' : '';

    // Preview Size
    $preview_size = ( isset( $preview_size ) ) ? $preview_size : null;
    $preview_size_attr = null;

    if ($preview_size){
      if (!is_array($preview_size)){
        $preview_size_attr = "data-preview-size='{$preview_size}'";
      } else {
        $width  = $preview_size['width'];
        $height = $preview_size['height'];
        $fit    = $preview_size['fit'];
        $preview_size_attr = "data-preview-size='custom' style='--cssf-image-preview-size-width:{$width};--cssf-image-preview-size-height:{$height};--cssf-image-preview-size-fit:{$fit};'";
      }
    }

    if (!empty( $value )){
      if (isset($preview_size)){
        if (!is_array($preview_size)){
          $attachment_size = $preview_size;
        } else {
          $attachment_size = true;
        }
      } else {
        $attachment_size = 'thumbnail';
      }
      $attachment       = wp_get_attachment_image_src( $value, $attachment_size );
      $preview          = $attachment[0];
    }

    echo '<div class="cssf-image-select">';
    echo '<div class="cssf-image-preview'. $hidden .'" '.$preview_size_attr.'><div class="cssf-preview"><img src="'. $preview .'" alt="preview" /></div></div>';
    echo '<a href="#" class="cssf-button cssf-button-primary cssf-add" data-frame-title="'. $frame_title .'" data-upload-type="'. $upload_type .'" data-insert-title="'. $insert_title .'">'. $button_title .'</a>';
    echo '<a href="#" class="cssf-button cssf-button-warning cssf-remove'. $hidden .'">'. esc_attr__( 'Remove', 'cssf-framework' ) .'</a>';
    echo '<input type="text" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_class() . $this->element_attributes() .'/>';
    echo '</div>';

    echo $this->element_after();
  }

}
