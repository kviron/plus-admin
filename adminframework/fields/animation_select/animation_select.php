<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: CSS Animation Select
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_animation_select extends CSSFramework_Options {

    public function __construct( $field, $value = '', $unique = '' ) {
        parent::__construct( $field, $value, $unique );
    }

    public function output() {

        echo $this->element_before();
        
        $options    = [
            esc_attr__('Fade FX')		=> [
                'fade_in'			=> esc_attr__('Fade In'),
                'fade_in--top'		=> esc_attr__('Fade In Top'),
                'fade_in--bottom'	=> esc_attr__('Fade In Bottom'),
                'fade_in--left'		=> esc_attr__('Fade In Left'),
                'fade_in--right'	=> esc_attr__('Fade In Right'),
                'otro'              => 'otro'
            ],
            // esc_attr__('Flip FX')			=> [
            //     'flipInX'			=> esc_attr__('Flip In Horizontal'),
            //     'flipInY'			=> esc_attr__('Flip In Vertical'),
            // ],
            // esc_attr__('Rotate FX')			=> [
            //     'rotateIn'			=> esc_attr__('Rotate In'),
            //     'rotateInDownLeft'	=> esc_attr__('Rotate In Down-Left'),
            //     'rotateInDownRight'	=> esc_attr__('Rotate In Down-Right'),
            //     'rotateInUpLeft'	=> esc_attr__('Rotate In Up-Left'),
            //     'rotateInUpRight'	=> esc_attr__('Rotate In Up-Right'),
            // ],
            // esc_attr__('Slide FX')			=> [
            //     'slideIn'			=> esc_attr__('Slide In'),
            //     'slideInUp'			=> esc_attr__('Slide In Up'),
            //     'slideInRight'		=> esc_attr__('Slide In Right'),
            //     'slideInDown'		=> esc_attr__('Slide In Down'),
            //     'slideInLeft'		=> esc_attr__('Slide In Left'),
            // ],
            // esc_attr__('Zoom FX')			=> [
            //     'zoomIn'			=> esc_attr__('Zoom In'),
            //     'zoomInUp'			=> esc_attr__('Zoom In Up'),
            //     'zoomInRight'		=> esc_attr__('Zoom In Right'),
            //     'zoomInDown'		=> esc_attr__('Zoom In Down'),
            //     'zoomInLeft'		=> esc_attr__('Zoom In Left'),
            // ]
        ];
        if( isset( $options ) ) {

            $class      = $this->element_class();
            $options    = ( is_array( $options ) ) ? $options : array_filter( $this->element_data( $options ) );
            $extra_name = ( isset( $this->field['attributes']['multiple'] ) ) ? '[]' : '';
            $chosen_rtl = ( is_rtl() && strpos( $class, 'chosen' ) ) ? 'chosen-rtl' : '';

            echo '<select name="'. $this->element_name( $extra_name ) .'"'. $this->element_class( $chosen_rtl ) . $this->element_attributes() .'>';

            echo ( isset( $this->field['default_option'] ) ) ? '<option value="">'.$this->field['default_option'].'</option>' : '';

            if( !empty( $options ) ){
                foreach ( $options as $key => $value ) {
                    if ( is_array($value) ) {
                        echo '<optgroup label="'.$key.'">';
                        foreach ($value as $key => $value) {
                            echo '<option value="'. $key .'" '. $this->checked( $this->element_value(), $key, 'selected' ) .'>'. $value .'</option>';
                        }
                        echo '</optgroup>';
                    } else {
                        echo '<option value="'. $key .'" '. $this->checked( $this->element_value(), $key, 'selected' ) .'>'. $value .'</option>';
                    }
                }
            }
            echo '</select>';
        }
        echo $this->element_after();
    }
}