<?php
/**
 * Mosaic Design System
 *
 * Main class for configuration and UI components.
 *
 * @package Mosaic
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Mosaic Class
 */
class Mosaic {

    /**
     * Cached config data
     *
     * @var array|null
     */
    private static $config = null;

    /**
     * Mosaic directory path
     *
     * @var string
     */
    private static $path = '';

    /* =========================================================================
       Configuration Methods
       ========================================================================= */

    /**
     * Load configuration from mosaic.properties
     *
     * @param string $mosaic_path Path to mosaic directory
     * @return array Configuration array
     */
    public static function load( $mosaic_path = '' ) {
        if ( self::$config !== null && ( empty( $mosaic_path ) || $mosaic_path === self::$path ) ) {
            return self::$config;
        }

        if ( empty( $mosaic_path ) ) {
            $mosaic_path = dirname( __DIR__ );
        }

        self::$path = rtrim( $mosaic_path, '/' );
        $properties_file = self::$path . '/mosaic.properties';

        if ( file_exists( $properties_file ) ) {
            self::$config = parse_ini_file( $properties_file );
        } else {
            self::$config = self::defaults();
        }

        return self::$config;
    }

    /**
     * Get default configuration
     *
     * @return array
     */
    private static function defaults() {
        return array(
            'name'     => 'Mosaic',
            'main-css' => 'assets/css/mosaic.css',
            'main-js'  => 'assets/js/mosaic.js',
            'css-path' => 'assets/css',
            'js-path'  => 'assets/js',
            'php-path' => 'includes',
        );
    }

    /**
     * Get a config value
     *
     * @param string $key     Config key
     * @param mixed  $default Default value if not found
     * @return mixed
     */
    public static function config( $key, $default = null ) {
        $config = self::load();
        return isset( $config[ $key ] ) ? $config[ $key ] : $default;
    }

    /**
     * Get the main CSS file path
     *
     * @return string Relative path to main CSS file
     */
    public static function css() {
        return self::config( 'main-css', 'assets/css/mosaic.css' );
    }

    /**
     * Get the main JS file path
     *
     * @return string Relative path to main JS file
     */
    public static function js() {
        return self::config( 'main-js', 'assets/js/mosaic.js' );
    }

    /**
     * Get the version
     *
     * @return string
     */
    public static function version() {
        // Check for version.properties first (created at build time)
        $version_file = self::$path . '/version.properties';
        if ( file_exists( $version_file ) ) {
            $version_config = parse_ini_file( $version_file );
            if ( isset( $version_config['version'] ) ) {
                return $version_config['version'];
            }
        }

        return self::config( 'version', '1.0.0' );
    }

    /**
     * Get the mosaic directory path
     *
     * @return string
     */
    public static function path() {
        if ( empty( self::$path ) ) {
            self::load();
        }
        return self::$path;
    }

    /* =========================================================================
       UI Component Methods
       ========================================================================= */

    /**
     * Render a button
     *
     * @param string $label   Button label
     * @param array  $options Button options
     * @return string HTML
     */
    public static function button( $label, $options = array() ) {
        $defaults = array(
            'type'     => 'button',
            'variant'  => 'secondary',
            'size'     => '',
            'icon'     => '',
            'icon_pos' => 'left',
            'class'    => '',
            'id'       => '',
            'disabled' => false,
            'loading'  => false,
            'href'     => '',
            'target'   => '',
            'attrs'    => array(),
        );

        $options = wp_parse_args( $options, $defaults );

        $classes = array( 'mosaic-btn', 'mosaic-btn-' . $options['variant'] );

        if ( $options['size'] ) {
            $classes[] = 'mosaic-btn-' . $options['size'];
        }

        if ( $options['loading'] ) {
            $classes[] = 'mosaic-btn-loading';
        }

        if ( $options['class'] ) {
            $classes[] = $options['class'];
        }

        $attrs = array( 'class' => implode( ' ', $classes ) );

        if ( $options['id'] ) {
            $attrs['id'] = $options['id'];
        }

        if ( $options['disabled'] ) {
            $attrs['disabled'] = 'disabled';
        }

        $attrs = array_merge( $attrs, $options['attrs'] );

        $icon_html = '';
        if ( $options['icon'] ) {
            $icon_html = sprintf( '<span class="dashicons dashicons-%s"></span>', esc_attr( $options['icon'] ) );
        }

        $content = $options['icon_pos'] === 'left'
            ? $icon_html . esc_html( $label )
            : esc_html( $label ) . $icon_html;

        if ( $options['href'] ) {
            $attrs['href'] = esc_url( $options['href'] );
            if ( $options['target'] ) {
                $attrs['target'] = $options['target'];
            }
            return self::tag( 'a', $attrs, $content );
        } else {
            $attrs['type'] = $options['type'];
            return self::tag( 'button', $attrs, $content );
        }
    }

    /**
     * Render a badge
     *
     * @param string $label   Badge label
     * @param string $variant Badge variant
     * @param array  $options Additional options
     * @return string HTML
     */
    public static function badge( $label, $variant = 'default', $options = array() ) {
        $defaults = array(
            'icon'  => '',
            'solid' => false,
            'class' => '',
        );

        $options = wp_parse_args( $options, $defaults );

        $classes = array( 'mosaic-badge', 'mosaic-badge-' . $variant );

        if ( $options['solid'] ) {
            $classes[] = 'mosaic-badge-solid';
        }

        if ( $options['class'] ) {
            $classes[] = $options['class'];
        }

        $content = '';
        if ( $options['icon'] ) {
            $content .= sprintf( '<span class="dashicons dashicons-%s"></span>', esc_attr( $options['icon'] ) );
        }
        $content .= esc_html( $label );

        return sprintf( '<span class="%s">%s</span>', esc_attr( implode( ' ', $classes ) ), $content );
    }

    /**
     * Render a health badge
     *
     * @param string $status Status (healthy, warning, critical, unknown)
     * @param string $label  Optional custom label
     * @return string HTML
     */
    public static function health_badge( $status, $label = '' ) {
        $icons = array(
            'healthy'  => 'yes-alt',
            'warning'  => 'warning',
            'critical' => 'dismiss',
            'unknown'  => 'minus',
        );

        $labels = array(
            'healthy'  => 'Healthy',
            'warning'  => 'Warning',
            'critical' => 'Critical',
            'unknown'  => 'Unknown',
        );

        $icon = isset( $icons[ $status ] ) ? $icons[ $status ] : 'minus';
        $label = $label ?: ( isset( $labels[ $status ] ) ? $labels[ $status ] : ucfirst( $status ) );

        return sprintf(
            '<span class="mosaic-health-badge mosaic-health-badge-%s"><span class="dashicons dashicons-%s"></span> %s</span>',
            esc_attr( $status ),
            esc_attr( $icon ),
            esc_html( $label )
        );
    }

    /**
     * Render a status dot
     *
     * @param string $status Status (success, warning, critical, inactive)
     * @param bool   $pulse  Whether to animate
     * @return string HTML
     */
    public static function status_dot( $status, $pulse = false ) {
        $classes = array( 'mosaic-status-dot', 'mosaic-status-dot-' . $status );

        if ( $pulse ) {
            $classes[] = 'mosaic-status-dot-pulse';
        }

        return sprintf( '<span class="%s"></span>', esc_attr( implode( ' ', $classes ) ) );
    }

    /**
     * Render a spinner
     *
     * @param array $options Spinner options
     * @return string HTML
     */
    public static function spinner( $options = array() ) {
        $defaults = array(
            'size'  => '',
            'color' => '',
            'class' => '',
        );

        $options = wp_parse_args( $options, $defaults );

        $classes = array( 'mosaic-spinner' );

        if ( $options['size'] ) {
            $classes[] = 'mosaic-spinner-' . $options['size'];
        }

        if ( $options['color'] ) {
            $classes[] = 'mosaic-spinner-' . $options['color'];
        }

        if ( $options['class'] ) {
            $classes[] = $options['class'];
        }

        return sprintf( '<span class="%s"></span>', esc_attr( implode( ' ', $classes ) ) );
    }

    /**
     * Render a progress bar
     *
     * @param int   $value   Progress value (0-100)
     * @param array $options Progress options
     * @return string HTML
     */
    public static function progress( $value, $options = array() ) {
        $defaults = array(
            'variant'    => '',
            'size'       => '',
            'striped'    => false,
            'animated'   => false,
            'label'      => '',
            'show_value' => false,
            'class'      => '',
        );

        $options = wp_parse_args( $options, $defaults );

        $classes = array( 'mosaic-progress' );

        if ( $options['variant'] ) {
            $classes[] = 'mosaic-progress-' . $options['variant'];
        }

        if ( $options['size'] ) {
            $classes[] = 'mosaic-progress-' . $options['size'];
        }

        if ( $options['striped'] ) {
            $classes[] = 'mosaic-progress-striped';
        }

        if ( $options['animated'] ) {
            $classes[] = 'mosaic-progress-animated';
        }

        if ( $options['class'] ) {
            $classes[] = $options['class'];
        }

        $value = max( 0, min( 100, intval( $value ) ) );

        $html = '';

        if ( $options['label'] || $options['show_value'] ) {
            $html .= '<div class="mosaic-progress-wrapper">';
            $html .= '<div class="mosaic-progress-header">';
            if ( $options['label'] ) {
                $html .= sprintf( '<span class="mosaic-progress-title">%s</span>', esc_html( $options['label'] ) );
            }
            if ( $options['show_value'] ) {
                $html .= sprintf( '<span class="mosaic-progress-value">%d%%</span>', $value );
            }
            $html .= '</div>';
        }

        $html .= sprintf(
            '<div class="%s"><div class="mosaic-progress-bar" style="width: %d%%;"></div></div>',
            esc_attr( implode( ' ', $classes ) ),
            $value
        );

        if ( $options['label'] || $options['show_value'] ) {
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * Render an icon
     *
     * @param string $name    Dashicon name (without 'dashicons-' prefix)
     * @param array  $options Icon options
     * @return string HTML
     */
    public static function icon( $name, $options = array() ) {
        $defaults = array(
            'size'  => '',
            'color' => '',
            'class' => '',
        );

        $options = wp_parse_args( $options, $defaults );

        $classes = array( 'dashicons', 'dashicons-' . $name, 'mosaic-icon' );

        if ( $options['size'] ) {
            $classes[] = 'mosaic-icon-' . $options['size'];
        }

        if ( $options['color'] ) {
            $classes[] = 'mosaic-icon-' . $options['color'];
        }

        if ( $options['class'] ) {
            $classes[] = $options['class'];
        }

        return sprintf( '<span class="%s"></span>', esc_attr( implode( ' ', $classes ) ) );
    }

    /**
     * Render an alert/notice
     *
     * @param string $message Message content
     * @param string $type    Alert type (info, success, warning, error)
     * @param array  $options Alert options
     * @return string HTML
     */
    public static function alert( $message, $type = 'info', $options = array() ) {
        $defaults = array(
            'dismissible' => false,
            'icon'        => true,
            'class'       => '',
        );

        $options = wp_parse_args( $options, $defaults );

        $icons = array(
            'info'    => 'info',
            'success' => 'yes-alt',
            'warning' => 'warning',
            'error'   => 'dismiss',
        );

        $classes = array( 'mosaic-info-card' );
        if ( $options['class'] ) {
            $classes[] = $options['class'];
        }

        $icon_html = '';
        if ( $options['icon'] && isset( $icons[ $type ] ) ) {
            $icon_html = sprintf( '<span class="dashicons dashicons-%s"></span>', esc_attr( $icons[ $type ] ) );
        }

        return sprintf(
            '<div class="%s">%s<div class="mosaic-info-card-content">%s</div></div>',
            esc_attr( implode( ' ', $classes ) ),
            $icon_html,
            wp_kses_post( $message )
        );
    }

    /**
     * Start a page wrapper
     *
     * @param string $title   Page title
     * @param array  $options Options
     * @return string HTML
     */
    public static function page_start( $title = '', $options = array() ) {
        $defaults = array(
            'class'   => '',
            'actions' => '',
        );

        $options = wp_parse_args( $options, $defaults );

        $html = '<div class="wrap mosaic-wrap">';

        if ( $title ) {
            $html .= '<div class="mosaic-page-header">';
            $html .= '<div class="mosaic-page-header-title">';
            $html .= sprintf( '<h1 class="wp-heading-inline mosaic-heading-1">%s</h1>', esc_html( $title ) );
            $html .= '</div>';
            if ( $options['actions'] ) {
                $html .= '<div class="mosaic-page-header-actions">' . $options['actions'] . '</div>';
            }
            $html .= '</div>';
            $html .= '<hr class="wp-header-end">';
        }

        return $html;
    }

    /**
     * End a page wrapper
     *
     * @return string HTML
     */
    public static function page_end() {
        return '</div>';
    }

    /* =========================================================================
       Helper Methods
       ========================================================================= */

    /**
     * Build an HTML tag
     *
     * @param string $tag     Tag name
     * @param array  $attrs   Attributes
     * @param string $content Inner content
     * @return string HTML
     */
    private static function tag( $tag, $attrs, $content = '' ) {
        $attr_str = '';
        foreach ( $attrs as $key => $value ) {
            if ( $value === true ) {
                $attr_str .= ' ' . esc_attr( $key );
            } elseif ( $value !== false && $value !== null ) {
                $attr_str .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
            }
        }

        return sprintf( '<%s%s>%s</%s>', $tag, $attr_str, $content, $tag );
    }
}
