<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Image Gallery Custom
 *
 * @since 1.0.0
 * @version 1.0.1
 *
 */
class CSSFramework_Option_image_gallery_custom extends CSSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output() {

    echo $this->element_before();

    $value  = $this->element_value();
    $hidden = ( empty( $value ) ) ? ' hidden' : '';
    $visible = ( !empty( $value ) ) ? ' hidden' : '';

    // Settings
    if( isset( $this->field['settings'] ) ) { 
	  $settings     = $this->field['settings'];
	  $main_path 	= (isset($settings['path'])) ? $settings['path'] : false;
	  $main_uri 	= (isset($settings['uri'])) ? $settings['uri'] : false;
      $images_path  = $settings['images_path'];
	}


	// Get Preview Image URL
	$main_path 		= ($main_path) ? $main_path : CSSF_DIR;
	$main_uri 		= ($main_uri) ? $main_uri : CSSF_URI;
	$path 			= $main_path . $images_path;
	$uri 			= $main_uri . $images_path;

	$image_full_path 	= "{$path}/full/{$value}";
	$image_thumb_path 	= "{$path}/thumbs/{$value}";
	
	$image_full_uri 	= "{$uri}/full/{$value}";
	$image_thumb_uri 	= "{$uri}/thumbs/{$value}";

	$_path 	= cssf_encode_string($main_path);
	$_uri	= cssf_encode_string($main_uri);

	echo "
		<div class='cssf-image-select'>
    		<div class='cssf-image-preview {$hidden}'><img src='{$image_thumb_uri}'></i></div>
    		<div class='cssf-field-wrapper-horizontal'>
    			<a href='#' class='cssf-button cssf-button-primary cssf-image-add' data-images-path='{$images_path}' data-path='{$_path}' data-uri='{$_uri}'>". esc_attr__( 'Add image', 'cssf-framework' ) ."</a>
    			<a href='#' class='cssf-button cssf-button-warning cssf-image-remove {$hidden}'>". esc_attr__( 'Remove image', 'cssf-framework' ) ."</a>
    			<input type='text' name='". $this->element_name() ."' value='{$value}' ". $this->element_class( 'cssf-image-value' ) . $this->element_attributes() ." />
    		</div>
		</div>
	";

    echo $this->element_after();

  }

}
