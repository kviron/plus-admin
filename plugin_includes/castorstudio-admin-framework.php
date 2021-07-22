<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/* ===============================================================================================
    CastorStudio Admin Settings Framework
   =============================================================================================== */
if (!function_exists('cssf_framework_init_check')){
	function cssf_framework_init_check() {
		if (!function_exists( 'cssf_framework_init' ) && !class_exists('CSSFramework')){
			// Plugin location of cssf-framework.php
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'adminframework/cssf-framework.php';
		}
	}
	add_action( 'plugins_loaded', 'cssf_framework_init_check' );
}