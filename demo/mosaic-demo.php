<?php
/**
 * Plugin Name: Mosaic Demo
 * Description: Showcase of Mosaic Design System components
 * Version: 1.0.0
 * Author: JustFlow
 * License: GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'MOSAIC_DEMO_VERSION', '1.0.0' );
define( 'MOSAIC_DEMO_PATH', plugin_dir_path( __FILE__ ) );
define( 'MOSAIC_DEMO_URL', plugin_dir_url( __FILE__ ) );

// Load Mosaic
require_once MOSAIC_DEMO_PATH . 'mosaic/includes/class-mosaic.php';
require_once MOSAIC_DEMO_PATH . 'mosaic/includes/class-mosaic-table.php';
require_once MOSAIC_DEMO_PATH . 'mosaic/includes/class-mosaic-data-table.php';
require_once MOSAIC_DEMO_PATH . 'mosaic/includes/class-mosaic-card.php';
require_once MOSAIC_DEMO_PATH . 'mosaic/includes/class-mosaic-accordion.php';
require_once MOSAIC_DEMO_PATH . 'mosaic/includes/class-mosaic-tabs.php';
require_once MOSAIC_DEMO_PATH . 'mosaic/includes/class-mosaic-form.php';

Mosaic::load( MOSAIC_DEMO_PATH . 'mosaic' );

// Load plugin files
require_once MOSAIC_DEMO_PATH . 'includes/class-mosaic-demo.php';
require_once MOSAIC_DEMO_PATH . 'includes/class-mosaic-demo-admin.php';

// Initialize
add_action( 'plugins_loaded', function() {
    Mosaic_Demo::instance();
});
