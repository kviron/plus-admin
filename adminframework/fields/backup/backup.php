<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Backup
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_backup extends CSSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output() {

	$default_options 	= array($this->unique);
	$options 			= (isset($this->field['settings']['options'])) ? $this->field['settings']['options'] : $default_options;

	$options 			= apply_filters('cssf-framework/import-options/', $options);

	$to_export_list 	= cssf_encode_string(implode(',',$options));
	$to_export_data 	= array();
	foreach($options as $option){
		$to_export_data[$option] = get_option($option);
	}
	$to_export_data = cssf_encode_string( $to_export_data );

    echo $this->element_before();

    // echo '<textarea name="'. $this->unique .'[import]"'. $this->element_class() . $this->element_attributes() .'></textarea>';
	echo '<textarea name="import"'. $this->element_class('cssf-import-backup_data') . $this->element_attributes() .'></textarea>';
	echo '<a href="'. admin_url( 'admin-ajax.php?action=cssf-import-options') .'" id="cssf-import-backup" class="cssf-button cssf-button-primary cssf-import-backup" target="_blank">'. esc_attr__( 'Import a Backup', 'cssf-framework' ) .'</a>'; 
    // submit_button( esc_attr__( 'Import a Backup', 'cssf-framework' ), 'primary cssf-import-backup', 'backup', false );
    echo '<small>( '. esc_attr__( 'copy-paste your backup string here', 'cssf-framework' ).' )</small>';

    echo '<hr />';

    echo '<textarea name="_nonce"'. $this->element_class() . $this->element_attributes() .' disabled="disabled">'. $to_export_data .'</textarea>';
	
	// echo '<a href="'. admin_url( 'admin-ajax.php?action=cssf-export-options' ) .'" class="button button-primary" target="_blank">'. esc_attr__( 'Export and Download Backup', 'cssf-framework' ) .'</a>';
    // echo '<a href="'. admin_url( 'admin-ajax.php?action=cssf-export-options&option_array=' . $this->unique ) .'" class="button button-primary" target="_blank">'. esc_attr__( 'Export and Download Backup', 'cssf-framework' ) .'</a>'; 
    echo '<a href="'. admin_url( 'admin-ajax.php?action=cssf-export-options&export=' . $to_export_list) .'" class="cssf-button cssf-button-primary" target="_blank">'. esc_attr__( 'Export and Download Backup', 'cssf-framework' ) .'</a>'; 
    echo '<small>-( '. esc_attr__( 'or', 'cssf-framework' ) .' )-</small>';
	// submit_button( esc_attr__( 'Reset All Options', 'cssf-framework' ), 'cssf-button cssf-button-warning cssf-reset-confirm', $this->unique . '[resetall]', false );
	echo '<input type="submit" name="'.$this->unique . '[resetall]'.'" id="'.$this->unique . '[resetall]'.'" value="'.esc_attr__( 'Reset All Options', 'cssf-framework' ).'" class="cssf-button cssf-button-warning cssf-reset-confirm">';
    echo '<small class="cssf-text-warning">'. esc_attr__( 'Please be sure for reset all of framework options.', 'cssf-framework' ) .'</small>';

    echo $this->element_after();

  }

}
