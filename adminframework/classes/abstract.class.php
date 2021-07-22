<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
} // Cannot access pages directly.

/**
 *
 * Abstract Class
 * A helper class for action and filter hooks
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 */
abstract class CSSFramework_Abstract {
	/**
	 * options
	 *
	 * @var array
	 */
	public $options = array();

	/**
	 * type
	 *
	 * @var string
	 */
	protected $type = '';

	/**
	 * unique
	 *
	 * @var string
	 */
	// protected $unique = CSSF_OPTION;
	protected $unique = null;

	/**
	 * plugin_id
	 *
	 * @var null
	 */
	protected $plugin_id = null;

	/**
	 * raw_options
	 *
	 * @var array
	 */
	protected $raw_options = array();

	/**
	 * settings
	 *
	 * @var array
	 */
	protected $settings = array();

	/**
	 * cache
	 *
	 * @var array
	 */
	protected $cache = array();

	/**
	 * db_options
	 *
	 * @var array
	 */
	protected $db_options = array();

	/**
	 * override_location
	 *
	 * @var null
	 */
	protected $override_location = null;

	/**
	 * CSSFramework_Abstract constructor.
	 */
	public function __construct() {
	}

	/**
	 * @return null
	 */
	public function id() {
		return $this->plugin_id;
	}

	/**
	 * @return mixed
	 */
	public function get_unique() {
		return $this->unique;
	}

	/**
	 * Returns Override Location Path.
	 *
	 * @return null
	 */
	public function override_location() {
		return $this->override_location;
	}

	/**
	 * Abstract Function get_cache();
	 */
	public function get_cache() {
	}

	/**
	 * Abstract Function get_db_options();
	 */
	public function get_db_options() {
	}

	/**
	 * Gets Field's Value from $this->db_values.
	 *
	 * @param $field
	 * @param $values
	 *
	 * @return array|bool
	 */
	public function get_field_values( $field, $values ) {
		$value       = ( isset( $field['id'] ) && isset( $values[ $field['id'] ] ) ) ? $values[ $field['id'] ] : ( isset( $field['default'] ) ? $field['default'] : false );
		$is_in_array = in_array( $field['type'], array( 'fieldset', 'accordion' ) );

		if ( isset( $field['un_array'] ) && true === $field['un_array'] && true === $is_in_array ) {
			$value = array();
			foreach ( $field['fields'] as $_field ) {
				if ( ! isset( $_field['id'] ) ) {
					continue;
				}
				$value[ $_field['id'] ] = $this->get_field_values( $_field, $values );
			}
		} elseif ( 'tab' === $field['type'] ) {
			$_tab_values = array();
			$_tab_vals   = ( isset( $field['id'] ) && isset( $values[ $field['id'] ] ) ) ? $values[ $field['id'] ] : '';
			if ( ( isset( $field['un_array'] ) && true === $field['un_array'] ) ) {
				$_tab_vals = $values;
			}
			foreach ( $field['sections'] as $section ) {
				$_section_vals   = ( isset( $section['name'] ) && isset( $_tab_vals[ $section['name'] ] ) ) ? $_tab_vals[ $section['name'] ] : $_tab_vals;
				$_section_values = array();
				foreach ( $section['fields'] as $_field ) {
					$_section_values[ $_field['id'] ] = $this->get_field_values( $_field, $_section_vals );
				}
				if ( isset( $section['un_array'] ) && true === $section['un_array'] ) {
					$_tab_values = array_merge( $_section_values, $_tab_values );
				} else {
					$_tab_values[ $section['name'] ] = $_section_values;
				}
			}
			$value = $_tab_values;
		}
		return $value;
	}

	/**
	 * Checks if current request is ajax & its hearbeat
	 *
	 * @return bool
	 */
	public function is_not_ajax() {
		if ( isset( $_POST ) && isset( $_POST['action'] ) && 'heartbeat' === $_POST['action'] ) {
			return false;
		}
		return true;
	}

	/**
	 * Checks if current ajax request is done by CSSF
	 *
	 * @return bool
	 */
	public function is_cssf_ajax() {
		if ( isset( $_POST ) && isset( $_POST['action'] ) && 'CSSF-ajax' === $_POST['action'] ) {
			return true;
		}
		return false;
	}

	/**
	 * Custom Wrapper For wp AddAction
	 *
	 * @uses \add_action()
	 *
	 * @param     $hook
	 * @param     $function_to_add
	 * @param int $priority
	 * @param int $accepted_args
	 */
	public function addAction( $hook, $function_to_add, $priority = 30, $accepted_args = 1 ) {
		add_action( $hook, array( &$this, $function_to_add ), $priority, $accepted_args );
	}

	/**
	 * Custom Wrapper for wp ApplyFilters
	 *
	 * @uses \apply_filters()
	 *
	 * @param     $tag
	 * @param     $function_to_add
	 * @param int $priority
	 * @param int $accepted_args
	 */
	public function addFilter( $tag, $function_to_add, $priority = 30, $accepted_args = 1 ) {
		add_action( $tag, array( &$this, $function_to_add ), $priority, $accepted_args );
	}

	/**
	 * Returns Setting Value from $this->settings array
	 *
	 * @param string $key
	 * @param bool   $defaults
	 *
	 * @return bool|mixed
	 */
	public function _option( $key = '', $defaults = false ) {
		if ( isset( $this->settings[ $key ] ) ) {
			return $this->settings[ $key ];
		}
		return $defaults;
	}

	/**
	 * Maps Dyanmic Error id for each and every field.
	 *
	 * @param array  $array
	 * @param string $parent_id
	 *
	 * @return array
	 */
	protected function map_error_id( $array = array(), $parent_id = '' ) {
		$s = empty( $array ) ? $this->options : $array;
		if ( isset( $s['sections'] ) ) {
			$fname = '';
			if ( isset( $s['type'] ) && 'tab' === $s['type'] ) {
				$fname = $this->type . '_' . $parent_id . '_' . $s['id'] . '_';
			}
			foreach ( $s['sections'] as $b => $a ) {
				if ( isset( $a['fields'] ) ) {
					$fname .= ( isset( $a['name'] ) ) ? $a['name'] : '';

					$s['sections'][ $b ] = $this->map_error_id( $a, $fname );
				}
			}
		} elseif ( isset( $s['fields'] ) ) {
			foreach ( $s['fields'] as $f => $e ) {
				$field_id                      = isset( $e['id'] ) ? $e['id'] : '';
				$pid                           = $parent_id . '_' . $field_id;
				$s['fields'][ $f ]['error_id'] = $pid;
				if ( isset( $e['fields'] ) || isset( $e['sections'] ) ) {
					$s['fields'][ $f ] = $this->map_error_id( $s['fields'][ $f ], $pid );
				}
			}
		} else {
			foreach ( $s as $i => $v ) {
				if ( isset( $v['fields'] ) || isset( $v['sections'] ) ) {
					$s[ $i ] = $this->map_error_id( $v, $parent_id );
				}
			}
		}
		return $s;
	}

	/**
	 * Custom Wrapper for both do_action & apply_filters
	 *
	 * @uses \do_action()
	 * @uses \apply_filters()
	 *
	 * @param string $type
	 * @param array  $args
	 *
	 * @return mixed
	 */
	private function action_filter( $type = '', $args = array() ) {
		return call_user_func_array( $type, $args );
	}

	/**
	 * Triggers Do Action for the given slug
	 *
	 * @return mixed
	 */
	protected function action() {
		return $this->action_filter( 'do_action', func_get_args() );
	}

	/**
	 * Runs apply_filters
	 *
	 * @uses \apply_filters()
	 * @return mixed
	 */
	protected function filter() {
		return $this->action_filter( 'apply_filters', func_get_args() );
	}

	/**
	 * Triggers doaction for the given slug
	 * with global & plugin Specific slugs
	 *
	 * @return mixed
	 */
	protected function _action() {
		return $this->cssf_action_filter( 'do_action', func_get_args() );
	}

	/**
	 * Triggers apply filter for the given slug
	 * with global & plugin Specific slugs
	 *
	 * @return mixed
	 */
	protected function _filter() {
		return $this->cssf_action_filter( 'apply_filters', func_get_args() );
	}

	/**
	 * Triggers doaction & apply filter for the given slug
	 * with global & plugin Specific slugs
	 *
	 * @param string $type
	 *
	 * @return mixed
	 */
	protected function cssf_action_filter( $type = 'apply_filters' ) {
		$_args   = func_get_args();
		$args    = $_args[1];
		$args[0] = $this->get_action_filter_slugs( true ) . $_args[1][0];
		$data    = $this->action_filter( $type, $args );

		$args[0] = $this->get_action_filter_slugs( false ) . $_args[1][0];
		$args[1] = $data;

		return $this->action_filter( $type, $args );
	}

	/**
	 * Returns Custom Prefix For Every Action & Filter Applied By CSSF
	 *
	 * @param bool $plugin_id
	 *
	 * @return string
	 */
	protected function get_action_filter_slugs( $plugin_id = false ) {
		if ( false === $plugin_id ) {
			return 'cssf_' . $this->type . '_';
		}
		return 'cssf_' . $this->type . '_' . $this->plugin_id . '_';
	}



	/**
	 * Catchs Output.
	 *
	 * @param string $status
	 *
	 * @return string
	 */
	protected function catch_output( $status = 'start' ) {
		$data = '';
		if ( 'start' === $status ) {
			ob_start();
		} else {
			$data = ob_get_clean();
			ob_flush();
		}
		return $data;
	}

	/**
	 * Returns Custom Cache KEy.
	 *
	 * @param array $data
	 *
	 * @return bool|mixed|string
	 */
	protected function get_cache_key( $data = array() ) {
		if ( empty( $data ) ) {
			$data = $this->settings;
		}
		if ( isset( $data['uid'] ) ) {
			return $data['uid'];
		} elseif ( isset( $data['id'] ) ) {
			return $data['id'];
		} elseif ( isset( $data['title'] ) ) {
			return sanitize_title( $data['title'] );
		} elseif ( isset( $data['menu_title'] ) ) {
			return sanitize_title( $data['menu_title'] );
		}
		return false;
	}
}