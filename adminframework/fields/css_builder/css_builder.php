<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: CSS Builder
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_css_builder extends CSSFramework_Options {
    /**
     * CSSFramework_Option_css_builder constructor.
     * @param        $field
     * @param string $value
     * @param string $unique
     */
    public function __construct($field, $value = '', $unique = '') {
        parent::__construct($field, $value, $unique);
    }

    public function output() {
        echo $this->element_before();

        $is_select2 = ( isset($this->field['select2']) && $this->field['select2'] === TRUE ) ? 'select2' : '';
        $is_chosen = ( isset($this->field['chosen']) && $this->field['chosen'] === TRUE ) ? 'chosen' : '';
        echo '<div class="cssf-css-builder-container cssf-multifield">';

        echo cssf_add_element(array(
            'pseudo'  => FALSE,
            'id'      => $this->field['id'] . '_content',
            'type'    => 'content',
            'content' => 'Note, that if you enter a value without a unit, the default unit <em>px</em> will automatically appended. If an invalid value is entered, it is replaced by the default value <em>0px</em>. Accepted units are: <em>px</em>, <em>%</em> and <em>em</em></p><p>Activate the lock <span class="dashicons dashicons-lock acf-css-checkall" style="margin:0"></span> to link all values.',
        ));


        echo '<div class="cssf-css-builder-margin">';
        echo '<div><span class="dashicons cssf-css-info dashicons-info"></span></div>';
        echo '<div class="cssf-css-margin-caption">' . esc_attr__("Margin", 'cssf-framework') . '<span class="dashicons dashicons-lock cssf-css-checkall cssf-margin-checkall" ></span></div>';
        $this->_css_fields('margin');

        echo '<div class="cssf-css-builder-border">';
        echo '<div class="cssf-css-border-caption">' . esc_attr__("Border", 'cssf-framework') . '<span class="dashicons dashicons-lock cssf-css-checkall cssf-border-checkall" ></span></div>';
        $this->_css_fields('border');
        echo '<div class="cssf-css-builder-padding">';
        echo '<div class="cssf-css-padding-caption">' . esc_attr__("Padding", 'cssf-framework') . '<span class="dashicons dashicons-lock cssf-css-checkall cssf-padding-checkall" ></span></div>';
        $this->_css_fields('padding');
        echo '<div class="cssf-css-builder-layout-center">';
        echo '<p>Lorem ipsum dolor sit amet, </p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';


        echo '<div class="cssf-css-builder-extra-options">';
        $id = $this->unique . '[' . $this->field['id'] . ']';
        echo cssf_add_element(array(
            'pseudo'    => true,
            'type'      => 'color_picker',
            'id'        => 'background-color',
            'before'	=> '<label>'.esc_attr__('Background Color','cssf-framework').'</label>',
        ), $this->field_val('background-color'), $id);
        echo cssf_add_element(array(
            'pseudo'    => true,
            'type'      => 'color_picker',
            'id'        => 'border-color',
            'before'	=> '<label>'.esc_attr__('Border Color','cssf-framework').'</label>',
        ), $this->field_val('border-color'), $id);
        echo cssf_add_element(array(
            'pseudo'    => true,
            'type'      => 'color_picker',
            'id'        => 'color',
            'before'	=> '<label>'.esc_attr__('Text Color','cssf-framework').'</label>',
        ), $this->field_val('color'), $id);
        echo cssf_add_element(array(
            'pseudo'    => true,
            'type'    => 'select',
            'id'      => 'border-style',
            'before'	=> '<label>'.esc_attr__('Border Style','cssf-framework').'</label>',
            'class'   => $is_select2 . ' ' . $is_chosen,
            'options' => array(
                ''       => esc_attr__("None", 'cssf-framework'),
                'solid'  => esc_attr__("Solid", 'cssf-framework'),
                'dashed' => esc_attr__("Dashed", 'cssf-framework'),
                'dotted' => esc_attr__("Dotted", 'cssf-framework'),
                'double' => esc_attr__("Double", 'cssf-framework'),
                'groove' => esc_attr__("Groove", 'cssf-framework'),
                'ridge'  => esc_attr__("Ridge", 'cssf-framework'),
                'inset'  => esc_attr__("Inset", 'cssf-framework'),
                'outset' => esc_attr__("Outset", 'cssf-framework'),

            ),
        ), $this->field_val('border-style'), $id);

        echo '<div class="cssf-css-builder-border-radius">';
        echo '<div class="cssf-css-border-radius-caption">' . esc_attr__("Border Radius", 'cssf-framework') . '<span class="dashicons dashicons-lock cssf-css-checkall cssf-border-radius-checkall" ></span></div>';

        echo cssf_add_element($this->carr(array(
            // 'title'      => esc_attr__('Top Left', 'cssf-framework'),
            'wrap_class' => 'cssf-border-radius cssf-border-radius-top-left',
            'id'         => 'border-radius-top-left',
            'before'      => '<label>'.esc_attr__('Top Left','cssf-framework').'</label>',
            'attributes' => array(
                'style' => 'width: 100px',
            ),
        )), $this->field_val('border-radius-top-left'), $id);

        echo cssf_add_element($this->carr(array(
            // 'title'      => esc_attr__('Top Right', 'cssf-framework'),
            'wrap_class' => 'cssf-border-radius cssf-border-radius-top-right',
            'id'         => 'border-radius-top-right',
            'before'      => '<label>'.esc_attr__('Top Right','cssf-framework').'</label>',
            'attributes' => array(
                'style' => 'width: 100px',
            ),
        )), $this->field_val('border-radius-top-right'), $id);
        echo cssf_add_element($this->carr(array(
            // 'title'      => esc_attr__('Bottom Left', 'cssf-framework'),
            'wrap_class' => 'cssf-border-radius cssf-border-radius-bottom-left',
            'id'         => 'border-radius-bottom-left',
            'before'      => '<label>'.esc_attr__('Bottom Left','cssf-framework').'</label>',
            'attributes' => array(
                'style' => 'width: 100px',
            ),
        )), $this->field_val('border-radius-bottom-left'), $id);
        echo cssf_add_element($this->carr(array(
            // 'title'      => esc_attr__('Bottom Right', 'cssf-framework'),
            'wrap_class' => 'cssf-border-radius cssf-border-radius-bottom-right',
            'id'         => 'border-radius-bottom-right',
            'before'      => '<label>'.esc_attr__('Bottom Left','cssf-framework').'</label>',
            'attributes' => array(
                'style' => 'width: 100px',
            ),
        )), $this->field_val('border-radius-bottom-right'), $id);

        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo $this->element_after();
    }

    /**
     * @param $type
     */
    private function _css_fields($type) {
        $id = $this->unique . '[' . $this->field['id'] . ']';
        echo cssf_add_element($this->carr(array(
            'wrap_class' => 'cssf-' . $type . ' cssf-' . $type . '-top',
            'id'         => $type . '-top',
        )), $this->field_val($type . '-top'), $id);
        echo cssf_add_element($this->carr(array(
            'wrap_class' => 'cssf-' . $type . ' cssf-' . $type . '-right',
            'id'         => $type . '-right',
        )), $this->field_val($type . '-right'), $id);
        echo cssf_add_element($this->carr(array(
            'wrap_class' => 'cssf-' . $type . ' cssf-' . $type . '-bottom',
            'id'         => $type . '-bottom',
        )), $this->field_val($type . '-bottom'), $id);
        echo cssf_add_element($this->carr(array(
            'wrap_class' => 'cssf-' . $type . ' cssf-' . $type . '-left',
            'id'         => $type . '-left',
        )), $this->field_val($type . '-left'), $id);
    }

    /**
     * @param        $new_arr
     * @param string $type
     * @return array
     */
    private function carr($new_arr, $type = '') {
        return array_merge(array(
            'pseudo'     => true,
            'type'       => 'text',
            'attributes' => array(
                'style' => 'width: 40px',
            ),
        ), $new_arr);
    }

    /**
     * @param string $type
     * @return null
     */
    private function field_val($type = '') {
        return ( isset($this->value[$type]) ) ? $this->value[$type] : NULL;
    }

}

