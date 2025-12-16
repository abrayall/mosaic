<?php
/**
 * Main Mosaic Demo class
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Mosaic_Demo {

    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->init();
    }

    private function init() {
        // Initialize admin
        if ( is_admin() ) {
            Mosaic_Demo_Admin::instance();
        }
    }
}
