<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Image Size
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_image_size extends CSSFramework_Options {
    /**
     * CSSFramework_Option_image_size constructor.
     * @param        $field
     * @param string $value
     * @param string $unique
     */
    public function __construct($field, $value = '', $unique = '') {
        parent::__construct($field, $value, $unique);
    }

    public function output() {
        echo $this->element_before();

        $default_width = ( isset($this->field['default']) && isset($this->field['default']['width']) ) ? $this->field['default']['width'] : '';
        $default_height = ( isset($this->field['default']) && isset($this->field['default']['height']) ) ? $this->field['default']['height'] : '';
        $default_crop = ( isset($this->field['default']) && isset($this->field['default']['crop']) ) ? $this->field['default']['crop'] : '';

        $width = ( isset($this->value['width']) ) ? $this->value['width'] : $default_width;
        $height = ( isset($this->value['height']) ) ? $this->value['height'] : $default_height;
        $crop = ( isset($this->value['crop']) ) ? $this->value['crop'] : $default_crop;

        echo cssf_add_element(array(
            'id'         => $this->field['id'] . '_width',
            'pseudo'     => FALSE,
            'type'       => 'text',
            'name'       => $this->element_name('[width]'),
            'value'      => $width,
            'attributes' => array(
                'placeholder' => esc_attr__('Width','cssf-framework'),
                'style'       => 'width:50px;',
                'size'        => 3,
            ),
        ));

        echo ' x ';

        echo cssf_add_element(array(
            'id'         => $this->field['id'] . '_height',
            'pseudo'     => FALSE,
            'type'       => 'text',
            'name'       => $this->element_name('[height]'),
            'value'      => $height,
            'attributes' => array(
                'placeholder' => esc_attr__('Height','cssf-framework'),
                'style'       => 'width:50px;',
                'size'        => 3,
            ),
        ));

        echo cssf_add_element(array(
            'id'     => $this->field['id'] . '_crop',
            'pseudo' => FALSE,
            'type'   => 'checkbox',
            'name'   => $this->element_name('[crop]'),
            'value'  => $crop,
            'label'  => esc_attr__('Hard Crop ?','cssf-framework'),
        ));

        echo $this->element_after();
    }
}
