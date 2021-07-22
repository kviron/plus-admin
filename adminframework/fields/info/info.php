<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Info
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_info extends CSSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output() {

  	$title 		= (isset($this->field['title'])) ? $this->field['title'] : false;
  	$content 	= $this->field['content'];

    // Field Settings
  	$settings = ( isset($this->field['settings']) ) ? $this->field['settings'] : false;
  	$icon 		= ( isset($settings['icon']) ) ? $settings['icon'] : false;
  	$type 		= ( isset($settings['type']) ) ? $settings['type'] : 'notice';
  	$style 		= ( isset($settings['style']) ) ? $settings['style'] : 'success';

    
    echo $this->element_before();
    echo '<div class="cssf-field-info--type_'.$type.' cssf-field-info--style_'.$style.'">';
    if ($icon){
    	echo '<div class="cssf-field-info__icon">';
    	echo '<i class="'.$icon.'"></i>';
    	echo '</div>';	
    }
    echo '<div class="cssf-field-info__content">';
    if ($title) {
    	echo '<h4>'.$title.'</h4>';
    }
    echo '<p>'.$content.'</p>';
    echo '</div>';
    echo '</div>';
    echo $this->element_after();

  }

}
