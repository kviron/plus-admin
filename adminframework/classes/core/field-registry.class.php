<?php
class CSSFramework_Field_Registry{
	/**
	 * _instance
	 *
	 * @var null
	 */
	private static $_instance = null;

	/**
	 * _instances
	 *
	 * @var array
	 */
	private static $_instances = array();

	/**
	 * @return null|\CSSFramework_Field_Registry
	 */
	public static function instance() {
		if ( null === self::$_instance ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * @param \CSSFramework_Options $instance
	 */
	public function add( CSSFramework_Options &$instance ) {
		if ( ! isset( self::$_instances[ $instance->id ] ) ) {
			self::$_instances[ $instance->id ] = $instance;
		}
	}

	/**
	 * @param string $field_id
	 *
	 * @return bool|mixed
	 */
	public function get( $field_id = '' ) {
		return ( isset( self::$_instances[ $field_id ] ) ) ? self::$_instances[ $field_id ] : false;
	}

	/**
	 * @return array
	 */
	public function all() {
		return self::$_instances;
	}
}

return CSSFramework_Field_Registry::instance();
