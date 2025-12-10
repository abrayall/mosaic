<?php
/**
 * Mosaic Design System - Accordion Builder
 *
 * Fluent interface for building accordions.
 *
 * @package Mosaic
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Mosaic Accordion Class
 */
class Mosaic_Accordion {

    /**
     * Accordion options
     *
     * @var array
     */
    private $options = array();

    /**
     * Accordion items
     *
     * @var array
     */
    private $items = array();

    /**
     * Constructor
     *
     * @param array $options Accordion options
     */
    public function __construct( $options = array() ) {
        $this->options = wp_parse_args( $options, array(
            'id'       => '',
            'class'    => '',
            'multiple' => false, // Allow multiple items open
            'flush'    => false, // No outer border
        ) );
    }

    /**
     * Add an accordion item
     *
     * @param string $title   Item title
     * @param string $content Item content
     * @param array  $options Item options
     * @return self
     */
    public function add_item( $title, $content, $options = array() ) {
        $this->items[] = wp_parse_args( $options, array(
            'title'   => $title,
            'content' => $content,
            'icon'    => '',
            'open'    => false,
        ) );

        return $this;
    }

    /**
     * Render the accordion
     *
     * @return string HTML
     */
    public function render() {
        if ( empty( $this->items ) ) {
            return '';
        }

        $classes = array( 'mosaic-accordion' );

        if ( $this->options['flush'] ) {
            $classes[] = 'mosaic-accordion-flush';
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

        if ( $this->options['multiple'] ) {
            $attrs['data-multiple'] = 'true';
        }

        $html = sprintf( '<div%s>', $this->build_attrs( $attrs ) );

        foreach ( $this->items as $item ) {
            $item_classes = array( 'mosaic-accordion-item' );
            if ( $item['open'] ) {
                $item_classes[] = 'mosaic-accordion-item-open';
            }

            $html .= sprintf( '<div class="%s">', implode( ' ', $item_classes ) );

            // Header
            $html .= '<div class="mosaic-accordion-header">';
            $html .= '<h3 class="mosaic-accordion-title">';
            if ( $item['icon'] ) {
                $html .= sprintf(
                    '<span class="dashicons dashicons-%s"></span>',
                    esc_attr( $item['icon'] )
                );
            }
            $html .= esc_html( $item['title'] );
            $html .= '</h3>';
            $html .= '<span class="mosaic-accordion-icon"><span class="dashicons dashicons-arrow-down-alt2"></span></span>';
            $html .= '</div>';

            // Content
            $html .= '<div class="mosaic-accordion-content">';
            $html .= '<div class="mosaic-accordion-body">';
            $html .= $item['content'];
            $html .= '</div>';
            $html .= '</div>';

            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Output the accordion
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
