<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Layout Builder - Navbar
 *
 * @since 1.2.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_builder_navbar extends CSSFramework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output(){

        $elements       = (isset($this->field['elements'])) ? $this->field['elements'] : array();

        $elements_default = array(
            'help' => [
                'name'  => esc_attr__('Help','plus_admin'),
                'slug'  => 'help'
            ],
            'screen' => [
                'name'  => esc_attr__('Screen Options','plus_admin'),
                'slug'  => 'screen'
            ],
            'notifications' => [
                'name'  => esc_attr__('Notifications','plus_admin'),
                'slug'  => 'notifications'
            ],
            'site' => [
                'name'  => esc_attr__('View Site','plus_admin'),
                'slug'  => 'site'
            ],
            'updates' => [
                'name'  => esc_attr__('Updates','plus_admin'),
                'slug'  => 'updates'
            ],
            'comments' => [
                'name'  => esc_attr__('Comments','plus_admin'),
                'slug'  => 'comments'
            ],
            'newcontent' => [
                'name'  => esc_attr__('New Content','plus_admin'),
                'slug'  => 'newcontent'
            ],
            'account' => [
                'name'  => esc_attr__('User Profile','plus_admin'),
                'slug'  => 'account'
            ],
        );

        $elements       = wp_parse_args( $elements, $elements_default );
        
        $defaults_value = array(
            'main'	    => '',
            'elements'  => array_keys($elements)
		);
        $is_json = function($string) {
            return !empty($string) && is_string($string) && is_array(json_decode($string, true)) && json_last_error() == 0;
        };

		$value			= wp_parse_args( $this->element_value(), $defaults_value );
        $value_main		= ($is_json($value['main'])) ? json_decode($value['main']) : array();
        $value_elements = ($is_json($value['elements'])) ? json_decode($value['elements']) : $value['elements'];
        $value_elements = array_diff($value_elements,$value_main);
        
        $parse_value = function($value) use($elements) {
            $tpl = '';
            foreach ( $value as $key ) {
                $tpl .= '<div class="cssf-uls-layout-element layout-element__'.$key.'" data-layout-element-name="'.$key.'">'.$elements[$key]['name'].'</div>';
            }
            return ($tpl) ? $tpl : false;
        };

		echo $this->element_before();

        echo '
            <div class="cssf-uls-layout-builder">
                <div class="cssf-uls-layout__design uls-layout-detailsview">
                    <div class="cssf-uls-layout-section layout-section__main" data-layout-section="main">'.$parse_value($value_main).'</div>
                </div>
                <div class="cssf-uls-layout__elements">'.$parse_value($value_elements).'</div>
            </div>
        ';
        $value_main = (isset($this->value['main'])) ? $this->value['main'] : '';
        echo cssf_add_element( array(
			'pseudo'	=> true,
			'type'		=> 'text',
			'name'		=> $this->element_name('[main]'),
            'value'		=> $value_main,
            'class'		=> 'section__main',
			'attributes'	=> [
				'type'	=> 'hidden',
			]
        ) );

		// echo '</div>';
		echo $this->element_after();

	}

}