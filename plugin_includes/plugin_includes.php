<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/* ===============================================================================================
    Plugin Specific Includes
   =============================================================================================== */
// Init other actions before everything
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'plugin_includes/class-plus_admin-before-activator.php';
Plus_admin_Before_Activator::activate();

// Maybe load CastorStudio Settings Framework
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'plugin_includes/castorstudio-admin-framework.php';

// Plugin Constants
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'plugin_includes/constants.php';

// Modules
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'plugin_includes/class-plus_admin-modules.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'plugin_includes/class-plus_admin-module.php';

// Themes
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'plugin_includes/class-plus_admin-themes.php';

// Navbar Class
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'plugin_includes/class-plus_admin-navbar.php';

// Admin Pages
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'plugin_includes/page-dashboard.php';   // Admin Page