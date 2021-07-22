<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
*
* Field: Background
*
* @since 1.0.0
* @version 1.0.1
*
*/
class CSSFramework_Option_background extends CSSFramework_Options {
	
	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}
	
	public function output() {
		
		echo $this->element_before();
		
		$settings = array(
			'external_image'	=> ( isset($this->field['settings']['external_image'])) ? $this->field['settings']['external_image'] : false,
			'repeat'			=> ( empty($this->field['settings']['repeat']) || ($this->field['settings']['repeat'] === false) ) ? false : true,
			'position'			=> ( empty($this->field['settings']['position']) || ($this->field['settings']['position'] === false) ) ? false : true,
			'attachment'		=> ( empty($this->field['settings']['attachment']) || ($this->field['settings']['attachment'] === false) ) ? false : true,
			'size'		    	=> ( empty($this->field['settings']['size']) || ($this->field['settings']['size'] === false) ) ? false : true,
			'color'		    	=> ( empty($this->field['settings']['color']) || ($this->field['settings']['color'] === false) ) ? false : true,
			'palettes'   		=> ( isset($this->field['settings']['palettes']) ) ? $this->field['settings']['palettes'] : false,
		);
		
		$value_defaults = array(
			'external_image'	=> '',
			'image'       		=> '',
			'repeat'      		=> '',
			'position'    		=> '',
			'attachment'  		=> '',
			'size'        		=> '',
			'color'       		=> '',
		);
		
		$this->value  = wp_parse_args( $this->element_value(), $value_defaults );
		
		if( isset( $this->field['settings'] ) ) { extract( $this->field['settings'] ); }
		$external_image = ( isset( $external_image  ) ) ? $external_image  : null;
		$upload_type  	= ( isset( $upload_type  ) ) ? $upload_type  : 'image';
		$button_title 	= ( isset( $button_title ) ) ? $button_title : esc_attr__( 'Upload', 'cssf-framework' );
		$frame_title  	= ( isset( $frame_title  ) ) ? $frame_title  : esc_attr__( 'Upload', 'cssf-framework' );
		$insert_title 	= ( isset( $insert_title ) ) ? $insert_title : esc_attr__( 'Use Image', 'cssf-framework' );
		
		$preview = '';
		$value   = $this->value['image'];
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

		// Is Image From Media Gallery
		if (!isset($external_image)){
			$btns_from_media_gallery = "
				<a href='#' class='cssf-button cssf-button-primary cssf-add' data-frame-title='{$frame_title}' data-upload-type='{$upload_type}' data-insert-title='{$insert_title}'>{$button_title}</a>
				<a href='#' class='cssf-button cssf-button-warning cssf-remove {$hidden}'>". esc_attr__( 'Remove', 'cssf-framework' ) ."</a>
			";
			$hide_external_image_input = true;
		} else {
			$btns_from_media_gallery = '';
			$hide_external_image_input = false;
		}

		$hidden_external_image  = ($hide_external_image_input) ? ' hidden' : '';
		
		echo "
			<div class='cssf-image-preview {$hidden}' {$preview_size_attr}><div class='cssf-preview'><img src='{$preview}' alt='preview' /></div></div>
			<div class='cssf-field-upload-form'>
				<input type='text' name='". $this->element_name( '[image]' ) ."' value='". $this->value['image'] ."'". $this->element_class('cssf-hidden-input') . $this->element_attributes() ."/>
				<div class='cssf-field-text {$hidden_external_image}'>
					<input type='text' name='". $this->element_name( '[external_image]' ) ."' value='". $this->value['external_image'] ."'". $this->element_class() . $this->element_attributes($external_image['attributes']) ."/>
				</div>
				{$btns_from_media_gallery}
			</div>
		";
		
		
		// background attributes
		echo '<fieldset><div class="cssf-multifield">';
		if ($settings['repeat'] === true){
			echo cssf_add_element( array(
				'pseudo'          => true,
				'type'            => 'select',
				'name'            => $this->element_name( '[repeat]' ),
				'options'         => array(
					''              => 'repeat',
					'repeat-x'      => 'repeat-x',
					'repeat-y'      => 'repeat-y',
					'no-repeat'     => 'no-repeat',
					'inherit'       => 'inherit',
				),
				'attributes'      => array(
					'data-atts'     => 'repeat',
				),
				'value'           => $this->value['repeat'],
				'before'		      => '<label>'.esc_attr__('Repeat','cssf-framework').'</label>',
			));
		}
		if ($settings['position'] === true){
			echo cssf_add_element( array(
				'pseudo'          => true,
				'type'            => 'select',
				'name'            => $this->element_name( '[position]' ),
				'options'         => array(
					''              => 'left top',
					'left center'   => 'left center',
					'left bottom'   => 'left bottom',
					'right top'     => 'right top',
					'right center'  => 'right center',
					'right bottom'  => 'right bottom',
					'center top'    => 'center top',
					'center center' => 'center center',
					'center bottom' => 'center bottom'
				),
				'attributes'      => array(
					'data-atts'     => 'position',
				),
				'value'           => $this->value['position'],
				'before'		      => '<label>'.esc_attr__('Position','cssf-framework').'</label>',
			));
		}
		if ($settings['attachment'] === true){
			echo cssf_add_element( array(
				'pseudo'          => true,
				'type'            => 'select',
				'name'            => $this->element_name( '[attachment]' ),
				'options'         => array(
					''              => 'scroll',
					'fixed'         => 'fixed',
				),
				'attributes'      => array(
					'data-atts'     => 'attachment',
				),
				'value'           => $this->value['attachment'],
				'before'		      => '<label>'.esc_attr__('Attachment','cssf-framework').'</label>',
			));
		}
		if ($settings['size'] === true){
			echo cssf_add_element( array(
				'pseudo'          => true,
				'type'            => 'select',
				'name'            => $this->element_name( '[size]' ),
				'options'         => array(
					''              => 'size',
					'cover'         => 'cover',
					'contain'       => 'contain',
					'inherit'       => 'inherit',
					'initial'       => 'initial',
				),
				'attributes'      => array(
					'data-atts'     => 'size',
				),
				'value'           => $this->value['size'],
				'before'		      => '<label>'.esc_attr__('Size','cssf-framework').'</label>',
			));
		}
		if ($settings['color'] === true){
			echo cssf_add_element( array(
				'pseudo'          => true,
				'id'              => $this->field['id'].'_color',
				'type'            => 'color_picker',
				'name'            => $this->element_name('[color]'),
				'attributes'      => array(
					'data-atts'     => 'bgcolor',
				),
				'value'           => $this->value['color'],
				'default'         => ( isset( $this->field['default']['color'] ) ) ? $this->field['default']['color'] : '',
				'rgba'            => ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
				'palettes'		    => $settings['palettes'],
				'before'		      => '<label>'.esc_attr__('Color','cssf-framework').'</label>',
			));
		}
		echo '</div></fieldset>';
		
		echo $this->element_after();
		
	}
}
