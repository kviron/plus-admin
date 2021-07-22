<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Email validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'cssf_validate_email' ) ) {
  function cssf_validate_email( $value, $field ) {

    if ( ! sanitize_email( $value ) ) {
      return esc_attr__( 'Please write a valid email address!', 'cssf-framework' );
    }

  }
  add_filter( 'cssf_validate_email', 'cssf_validate_email', 10, 2 );
}

/**
 *
 * Numeric validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'cssf_validate_numeric' ) ) {
  function cssf_validate_numeric( $value, $field ) {

    if ( ! is_numeric( $value ) ) {
      return esc_attr__( 'Please write a numeric data!', 'cssf-framework' );
    }

  }
  add_filter( 'cssf_validate_numeric', 'cssf_validate_numeric', 10, 2 );
}

/**
 *
 * Required validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'cssf_validate_required' ) ) {
  function cssf_validate_required( $value ) {
    if ( empty( $value ) ) {
      return esc_attr__( 'Fatal Error! This field is required!', 'cssf-framework' );
    }
  }
  add_filter( 'cssf_validate_required', 'cssf_validate_required' );
}
