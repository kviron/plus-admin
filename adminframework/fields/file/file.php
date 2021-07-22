<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: File
 *
 * @since 2.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_File extends CSSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output(){

    echo $this->element_before();

    if( isset( $this->field['settings'] ) ) { extract( $this->field['settings'] ); }
    $upload_type  = ( isset( $upload_type  ) ) ? $upload_type  : '*';
    $button_title = ( isset( $button_title ) ) ? $button_title : esc_attr__( 'Add File', 'cssf-framework' );
    $frame_title  = ( isset( $frame_title  ) ) ? $frame_title  : esc_attr__( 'Upload File', 'cssf-framework' );
    $insert_title = ( isset( $insert_title ) ) ? $insert_title : esc_attr__( 'Use File', 'cssf-framework' );

	
	// Field Value
    $value   = $this->element_value();
    $add     = ( ! empty( $this->field['add_title'] ) ) ? $this->field['add_title'] : esc_attr__( 'Add File', 'cssf-framework' );
	$hidden  = ( empty( $value ) ) ? ' hidden' : '';
	
	// Preview Data
	$preview = '';
	$preview_type = ''; $preview_title = ''; $preview_name = ''; $preview_size = ''; $preview_link = '';
	if ($value){
		$data = ($value) ? cssf_get_attachment($value) : false;
		$preview_type = 'cssf-file-preview--type_'. $data['subtype'];
		$preview_title 	= $data['title'];
		$preview_name 	= $data['filename'];
		$preview_size 	= $data['filesize_humanreadable'];
		$preview_link	= $data['url'];
	}

	echo "
		<div class='cssf-file-select'>
			<div class='cssf-file-preview {$hidden}'>
				<div class='cssf-preview {$preview_type}'></div>
				<div class='cssf-preview-data'>
					<div class='cssf-preview-data-file_title'><span>{$preview_title}</span></div>
					<div class='cssf-preview-data-file_name'>File Name: <span>{$preview_name}</span></div>
					<div class='cssf-preview-data-file_size'>File Size: <span>{$preview_size}</span></div>
					<div class='cssf-preview-data-file_link'>File Link: <span>{$preview_link}</span></div>
				</div>
			</div>
			<a href='#' class='cssf-button cssf-button-primary cssf-add' data-frame-title='{$frame_title}' data-upload-type='{$upload_type}' data-insert-title='{$insert_title}'>{$button_title}</a>
			<a href='#' class='cssf-button cssf-button-warning cssf-remove {$hidden}'>". esc_attr__( 'Remove', 'cssf-framework' ) ."</a>
			<input type='text' name='". $this->element_name() ."' value='". $this->element_value() ."' ". $this->element_class() . $this->element_attributes() ."/>
		</div>
	";

    echo $this->element_after();
  }

}
