<?php if ( !defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Custom Field for User Roles
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_custom_userrole extends CSSFramework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {
		global $wp_roles;
		$roles 			= $wp_roles->get_names();
		$defaults_value;
		foreach($roles as $key => $value){
			$defaults_value[$key] = '';
			$defaults_value[$key .'_status'] = false;
		}
		$value 			= wp_parse_args( $this->element_value(), $defaults_value );

		echo $this->element_before();
		echo '<div class="cssf-custom-userrole cssf-multifield">';
		
		foreach($roles as $key => $role){
			echo '<div class="cssf-custom-userrole-content">';
			echo cssf_add_element( array(
				'pseudo'	=> true,
				'type'      => 'switcher',
				'name'		=> $this->element_name('['.$key.'_status]'),
				'value'		=> $value[$key.'_status'],
				'label'     => sprintf(esc_attr__('Redirect %s users to:','plus_admin'), $role),
				'labels'    => array(
					'on'    => esc_attr__('Yes','plus_admin'),
					'off'   => esc_attr__('No','plus_admin'),
				),
			));

			echo cssf_add_element( array(
				'pseudo'	=> true,
				'type'		=> 'text',
				'name'		=> $this->element_name('['.$key.']'),
				'value'		=> $value[$key],
				'attributes' => [
					'placeholder' => $role
				],
				'info'		=> esc_attr__('Leave blank to use the default url.','plus_admin'),
			));
			echo '</div>';
		}





		echo '</div>';
		echo $this->element_after();
	}

}