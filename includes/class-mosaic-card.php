<?php
/**
 * Mosaic Design System - Card Builder
 *
 * Fluent interface for building cards and stat cards.
 *
 * @package Mosaic
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Mosaic Card Class
 */
class Mosaic_Card {

    /**
     * Card options
     *
     * @var array
     */
    private $options = array();

    /**
     * Card header
     *
     * @var array
     */
    private $header = array();

    /**
     * Card body content
     *
     * @var string
     */
    private $body = '';

    /**
     * Card footer
     *
     * @var string
     */
    private $footer = '';

    /**
     * Constructor
     *
     * @param array $options Card options
     */
    public function __construct( $options = array() ) {
        $this->options = wp_parse_args( $options, array(
            'id'          => '',
            'class'       => '',
            'variant'     => '', // flat, hover, interactive
            'active'      => false,
        ) );
    }

    /**
     * Set card header
     *
     * @param string $title   Header title
     * @param string $actions Header actions HTML
     * @param string $icon    Optional icon
     * @return self
     */
    public function set_header( $title, $actions = '', $icon = '' ) {
        $this->header = array(
            'title'   => $title,
            'actions' => $actions,
            'icon'    => $icon,
        );

        return $this;
    }

    /**
     * Set card body
     *
     * @param string $content Body HTML content
     * @return self
     */
    public function set_body( $content ) {
        $this->body = $content;

        return $this;
    }

    /**
     * Set card footer
     *
     * @param string $content Footer HTML content
     * @return self
     */
    public function set_footer( $content ) {
        $this->footer = $content;

        return $this;
    }

    /**
     * Render the card
     *
     * @return string HTML
     */
    public function render() {
        $classes = array( 'mosaic-card' );

        if ( $this->options['variant'] ) {
            $classes[] = 'mosaic-card-' . $this->options['variant'];
        }

        if ( $this->options['active'] ) {
            $classes[] = 'mosaic-card-active';
        }

        if ( $this->options['class'] ) {
            $classes[] = $this->options['class'];
        }

        $attrs = array(
            'class' => implode( ' ', $classes ),
        );

        if ( $this->options['id'] ) {
            $attrs['id'] = $this->options['id'];
        }

        $html = sprintf( '<div%s>', $this->build_attrs( $attrs ) );

        // Header
        if ( ! empty( $this->header ) ) {
            $html .= '<div class="mosaic-card-header">';
            $html .= '<h3 class="mosaic-card-header-title">';
            if ( $this->header['icon'] ) {
                $html .= sprintf(
                    '<span class="dashicons dashicons-%s"></span>',
                    esc_attr( $this->header['icon'] )
                );
            }
            $html .= esc_html( $this->header['title'] );
            $html .= '</h3>';
            if ( $this->header['actions'] ) {
                $html .= $this->header['actions'];
            }
            $html .= '</div>';
        }

        // Body
        if ( $this->body ) {
            $html .= '<div class="mosaic-card-body">' . $this->body . '</div>';
        }

        // Footer
        if ( $this->footer ) {
            $html .= '<div class="mosaic-card-footer">' . $this->footer . '</div>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Output the card
     */
    public function display() {
        echo $this->render(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Create a stat card
     *
     * @param string $label   Stat label
     * @param mixed  $value   Stat value
     * @param array  $options Stat card options
     * @return string HTML
     */
    public static function stat( $label, $value, $options = array() ) {
        $defaults = array(
            'id'        => '',
            'class'     => '',
            'variant'   => 'primary', // primary, success, warning, critical
            'icon'      => '',
            'meta'      => '',
            'clickable' => false,
            'active'    => false,
            'filter'    => '',
            'attrs'     => array(),
        );

        $options = wp_parse_args( $options, $defaults );

        $classes = array( 'mosaic-stat-card', 'mosaic-stat-card-' . $options['variant'] );

        if ( $options['clickable'] ) {
            $classes[] = 'mosaic-stat-card-clickable';
        }

        if ( $options['active'] ) {
            $classes[] = 'mosaic-stat-card-active';
        }

        if ( $options['class'] ) {
            $classes[] = $options['class'];
        }

        $attrs = array_merge( $options['attrs'], array(
            'class' => implode( ' ', $classes ),
        ) );

        if ( $options['id'] ) {
            $attrs['id'] = $options['id'];
        }

        if ( $options['filter'] ) {
            $attrs['data-filter'] = $options['filter'];
        }

        $html = sprintf( '<div%s>', self::build_attrs_static( $attrs ) );

        // Label with optional icon
        $html .= '<h3 class="mosaic-stat-card-label">';
        $html .= esc_html( $label );
        if ( $options['icon'] ) {
            $html .= sprintf(
                ' <span class="dashicons dashicons-%s"></span>',
                esc_attr( $options['icon'] )
            );
        }
        $html .= '</h3>';

        // Value
        $html .= sprintf(
            '<div class="mosaic-stat-card-value">%s</div>',
            esc_html( $value )
        );

        // Meta/description
        if ( $options['meta'] ) {
            $html .= sprintf(
                '<div class="mosaic-stat-card-meta">%s</div>',
                esc_html( $options['meta'] )
            );
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Create a status card
     *
     * @param string $status  Status (healthy, warning, critical)
     * @param string $title   Card title
     * @param string $message Card message
     * @param array  $options Additional options
     * @return string HTML
     */
    public static function status( $status, $title, $message = '', $options = array() ) {
        $defaults = array(
            'id'    => '',
            'class' => '',
        );

        $options = wp_parse_args( $options, $defaults );

        $icons = array(
            'healthy'  => 'yes-alt',
            'warning'  => 'warning',
            'critical' => 'dismiss',
        );

        $icon = isset( $icons[ $status ] ) ? $icons[ $status ] : 'info';

        $classes = array( 'mosaic-status-card', 'mosaic-status-card-' . $status );

        if ( $options['class'] ) {
            $classes[] = $options['class'];
        }

        $attrs = array( 'class' => implode( ' ', $classes ) );

        if ( $options['id'] ) {
            $attrs['id'] = $options['id'];
        }

        $html = sprintf( '<div%s>', self::build_attrs_static( $attrs ) );

        $html .= sprintf(
            '<div class="mosaic-status-card-icon"><span class="dashicons dashicons-%s"></span></div>',
            esc_attr( $icon )
        );

        $html .= '<div class="mosaic-status-card-content">';
        $html .= sprintf( '<h4 class="mosaic-status-card-title">%s</h4>', esc_html( $title ) );

        if ( $message ) {
            $html .= sprintf( '<p class="mosaic-status-card-message">%s</p>', esc_html( $message ) );
        }

        $html .= '</div></div>';

        return $html;
    }

    /**
     * Create a metric tile
     *
     * @param string $label Metric label
     * @param mixed  $value Metric value
     * @param string $unit  Optional unit
     * @param array  $options Additional options
     * @return string HTML
     */
    public static function metric( $label, $value, $unit = '', $options = array() ) {
        $defaults = array(
            'id'    => '',
            'class' => '',
        );

        $options = wp_parse_args( $options, $defaults );

        $classes = array( 'mosaic-metric-tile' );

        if ( $options['class'] ) {
            $classes[] = $options['class'];
        }

        $attrs = array( 'class' => implode( ' ', $classes ) );

        if ( $options['id'] ) {
            $attrs['id'] = $options['id'];
        }

        $html = sprintf( '<div%s>', self::build_attrs_static( $attrs ) );

        $html .= sprintf(
            '<div class="mosaic-metric-tile-label">%s</div>',
            esc_html( $label )
        );

        $html .= '<div class="mosaic-metric-tile-value">';
        $html .= esc_html( $value );

        if ( $unit ) {
            $html .= sprintf( '<span class="mosaic-metric-tile-unit">%s</span>', esc_html( $unit ) );
        }

        $html .= '</div></div>';

        return $html;
    }

    /**
     * Create a stats grid
     *
     * @param array $stats Array of stat card configurations
     * @return string HTML
     */
    public static function stats_grid( $stats ) {
        $html = '<div class="mosaic-stats-grid">';

        foreach ( $stats as $stat ) {
            $html .= self::stat(
                $stat['label'],
                $stat['value'],
                $stat
            );
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Build attributes string (instance method)
     *
     * @param array $attrs Attributes array
     * @return string Attributes string
     */
    private function build_attrs( $attrs ) {
        return self::build_attrs_static( $attrs );
    }

    /**
     * Build attributes string (static method)
     *
     * @param array $attrs Attributes array
     * @return string Attributes string
     */
    private static function build_attrs_static( $attrs ) {
        $str = '';
        foreach ( $attrs as $key => $value ) {
            if ( $value !== '' && $value !== null ) {
                $str .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
            }
        }
        return $str;
    }
}
