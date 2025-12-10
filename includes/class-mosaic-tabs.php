<?php
/**
 * Mosaic Design System - Tabs Builder
 *
 * Fluent interface for building tab navigation.
 *
 * @package Mosaic
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Mosaic Tabs Class
 */
class Mosaic_Tabs {

    /**
     * Tabs configuration
     *
     * @var array
     */
    private $tabs = array();

    /**
     * Tabs options
     *
     * @var array
     */
    private $options = array();

    /**
     * Active tab key
     *
     * @var string
     */
    private $active_tab = '';

    /**
     * Constructor
     *
     * @param array $options Tabs options
     */
    public function __construct( $options = array() ) {
        $this->options = wp_parse_args( $options, array(
            'id'              => '',
            'class'           => '',
            'style'           => '', // default, pills, vertical
            'mobile_select'   => true,
            'overflow_menu'   => true,
            'hash_navigation' => false,
        ) );
    }

    /**
     * Add a tab
     *
     * @param string $key     Tab key
     * @param string $label   Tab label
     * @param string $content Tab content HTML
     * @param array  $options Tab options
     * @return self
     */
    public function add_tab( $key, $label, $content = '', $options = array() ) {
        $this->tabs[ $key ] = wp_parse_args( $options, array(
            'label'   => $label,
            'content' => $content,
            'icon'    => '',
            'count'   => null,
            'active'  => false,
        ) );

        if ( $options['active'] ?? false ) {
            $this->active_tab = $key;
        }

        return $this;
    }

    /**
     * Set the active tab
     *
     * @param string $key Tab key
     * @return self
     */
    public function set_active( $key ) {
        $this->active_tab = $key;

        return $this;
    }

    /**
     * Set tab content
     *
     * @param string $key     Tab key
     * @param string $content Tab content HTML
     * @return self
     */
    public function set_content( $key, $content ) {
        if ( isset( $this->tabs[ $key ] ) ) {
            $this->tabs[ $key ]['content'] = $content;
        }

        return $this;
    }

    /**
     * Render the tabs
     *
     * @return string HTML
     */
    public function render() {
        if ( empty( $this->tabs ) ) {
            return '';
        }

        // Set first tab as active if none specified
        if ( empty( $this->active_tab ) ) {
            $this->active_tab = array_key_first( $this->tabs );
        }

        $classes = array( 'mosaic-tabs-container' );

        if ( $this->options['style'] ) {
            $classes[] = 'mosaic-tabs-' . $this->options['style'];
        }

        if ( $this->options['class'] ) {
            $classes[] = $this->options['class'];
        }

        $attrs = array(
            'class'            => implode( ' ', $classes ),
            'data-mosaic-tabs' => 'true',
        );

        if ( $this->options['hash_navigation'] ) {
            $attrs['data-mosaic-tabs-hash'] = 'true';
        }

        if ( $this->options['id'] ) {
            $attrs['id'] = $this->options['id'];
        }

        $html = sprintf( '<div%s>', $this->build_attrs( $attrs ) );

        // Render mobile select
        if ( $this->options['mobile_select'] ) {
            $html .= $this->render_mobile_select();
        }

        // Render tab navigation
        $html .= $this->render_navigation();

        // Render tab content panels
        $html .= $this->render_content();

        $html .= '</div>';

        return $html;
    }

    /**
     * Render tab navigation
     *
     * @return string HTML
     */
    private function render_navigation() {
        $html = '<div class="mosaic-tabs-wrapper">';
        $html .= '<div class="mosaic-tabs">';

        foreach ( $this->tabs as $key => $tab ) {
            $is_active = ( $key === $this->active_tab );
            $classes = array( 'mosaic-tab' );

            if ( $is_active ) {
                $classes[] = 'mosaic-tab-active';
            }

            $html .= sprintf(
                '<button type="button" class="%s" data-tab="%s" data-label="%s">',
                esc_attr( implode( ' ', $classes ) ),
                esc_attr( $key ),
                esc_attr( $tab['label'] )
            );

            // Icon
            if ( ! empty( $tab['icon'] ) ) {
                $html .= sprintf(
                    '<span class="dashicons dashicons-%s"></span>',
                    esc_attr( $tab['icon'] )
                );
            }

            // Label
            $html .= esc_html( $tab['label'] );

            // Count badge
            if ( $tab['count'] !== null ) {
                $html .= sprintf(
                    ' <span class="mosaic-tab-count">%s</span>',
                    esc_html( $tab['count'] )
                );
            }

            $html .= '</button>';
        }

        $html .= '</div>';

        // Overflow menu container (populated by JS)
        if ( $this->options['overflow_menu'] ) {
            $html .= '<div class="mosaic-tabs-overflow" style="display: none;"></div>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Render mobile select
     *
     * @return string HTML
     */
    private function render_mobile_select() {
        $html = '<div class="mosaic-tabs-mobile-select">';
        $html .= '<select class="mosaic-select" id="mosaic-mobile-tab-selector">';

        foreach ( $this->tabs as $key => $tab ) {
            $selected = ( $key === $this->active_tab ) ? ' selected' : '';
            $html .= sprintf(
                '<option value="%s"%s>%s</option>',
                esc_attr( $key ),
                $selected,
                esc_html( $tab['label'] )
            );
        }

        $html .= '</select></div>';

        return $html;
    }

    /**
     * Render tab content panels
     *
     * @return string HTML
     */
    private function render_content() {
        $html = '';

        foreach ( $this->tabs as $key => $tab ) {
            $is_active = ( $key === $this->active_tab );
            $classes = array( 'mosaic-tab-content' );

            if ( $is_active ) {
                $classes[] = 'mosaic-tab-content-active';
            }

            $html .= sprintf(
                '<div class="%s" data-tab="%s">%s</div>',
                esc_attr( implode( ' ', $classes ) ),
                esc_attr( $key ),
                $tab['content']
            );
        }

        return $html;
    }

    /**
     * Render only the navigation (for custom layouts)
     *
     * @return string HTML
     */
    public function render_nav_only() {
        if ( empty( $this->active_tab ) && ! empty( $this->tabs ) ) {
            $this->active_tab = array_key_first( $this->tabs );
        }

        return $this->render_navigation();
    }

    /**
     * Output the tabs
     */
    public function display() {
        echo $this->render(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Build attributes string
     *
     * @param array $attrs Attributes array
     * @return string Attributes string
     */
    private function build_attrs( $attrs ) {
        $str = '';
        foreach ( $attrs as $key => $value ) {
            if ( $value !== '' && $value !== null ) {
                $str .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
            }
        }
        return $str;
    }
}
