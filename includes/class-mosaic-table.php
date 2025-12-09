<?php
/**
 * Mosaic Design System - Table Builder
 *
 * Fluent interface for building consistent tables.
 *
 * @package Mosaic
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Mosaic Table Class
 */
class Mosaic_Table {

    /**
     * Table columns
     *
     * @var array
     */
    private $columns = array();

    /**
     * Table rows
     *
     * @var array
     */
    private $rows = array();

    /**
     * Table options
     *
     * @var array
     */
    private $options = array();

    /**
     * Footer content
     *
     * @var string
     */
    private $footer = '';

    /**
     * Empty state content
     *
     * @var array
     */
    private $empty_state = array();

    /**
     * Constructor
     *
     * @param array $options Table options
     */
    public function __construct( $options = array() ) {
        $this->options = wp_parse_args( $options, array(
            'id'            => '',
            'class'         => '',
            'striped'       => true,
            'hover'         => true,
            'sticky_header' => true,
            'responsive'    => false,
            'compact'       => false,
            'bordered'      => false,
        ) );
    }

    /**
     * Add a column
     *
     * @param string $key     Column key
     * @param string $label   Column label
     * @param array  $options Column options
     * @return self
     */
    public function add_column( $key, $label, $options = array() ) {
        $this->columns[ $key ] = wp_parse_args( $options, array(
            'label'    => $label,
            'class'    => '',
            'sortable' => false,
            'align'    => 'left', // left, center, right
            'width'    => '',
        ) );

        return $this;
    }

    /**
     * Set columns in bulk
     *
     * @param array $columns Array of column definitions
     * @return self
     */
    public function set_columns( $columns ) {
        foreach ( $columns as $key => $column ) {
            if ( is_string( $column ) ) {
                $this->add_column( $key, $column );
            } else {
                $label = isset( $column['label'] ) ? $column['label'] : $key;
                $this->add_column( $key, $label, $column );
            }
        }

        return $this;
    }

    /**
     * Add a row
     *
     * @param array $data    Row data (keyed by column key)
     * @param array $options Row options
     * @return self
     */
    public function add_row( $data, $options = array() ) {
        $this->rows[] = array(
            'data'    => $data,
            'options' => wp_parse_args( $options, array(
                'class'  => '',
                'id'     => '',
                'status' => '', // selected, success, warning, critical, disabled
                'attrs'  => array(),
            ) ),
        );

        return $this;
    }

    /**
     * Set rows in bulk
     *
     * @param array $rows Array of row data
     * @return self
     */
    public function set_rows( $rows ) {
        foreach ( $rows as $row ) {
            if ( isset( $row['data'] ) ) {
                $this->add_row( $row['data'], $row['options'] ?? array() );
            } else {
                $this->add_row( $row );
            }
        }

        return $this;
    }

    /**
     * Set footer content
     *
     * @param string $content Footer HTML content
     * @return self
     */
    public function set_footer( $content ) {
        $this->footer = $content;

        return $this;
    }

    /**
     * Set empty state
     *
     * @param string $title   Empty state title
     * @param string $message Empty state message
     * @param string $icon    Dashicon name
     * @param string $action  Action button HTML
     * @return self
     */
    public function set_empty_state( $title, $message = '', $icon = 'admin-page', $action = '' ) {
        $this->empty_state = array(
            'title'   => $title,
            'message' => $message,
            'icon'    => $icon,
            'action'  => $action,
        );

        return $this;
    }

    /**
     * Render the table
     *
     * @return string HTML
     */
    public function render() {
        // Build table classes
        $classes = array( 'mosaic-table' );

        if ( $this->options['striped'] ) {
            $classes[] = 'mosaic-table-striped';
        }

        if ( $this->options['hover'] ) {
            $classes[] = 'mosaic-table-hover';
        }

        if ( $this->options['sticky_header'] ) {
            $classes[] = 'mosaic-table-sticky';
        }

        if ( $this->options['responsive'] ) {
            $classes[] = 'mosaic-table-responsive';
        }

        if ( $this->options['compact'] ) {
            $classes[] = 'mosaic-table-compact';
        }

        if ( $this->options['bordered'] ) {
            $classes[] = 'mosaic-table-bordered';
        }

        if ( $this->options['class'] ) {
            $classes[] = $this->options['class'];
        }

        // Build attributes
        $attrs = array(
            'class' => implode( ' ', $classes ),
        );

        if ( $this->options['id'] ) {
            $attrs['id'] = $this->options['id'];
        }

        $attr_str = $this->build_attrs( $attrs );

        // Start table
        $html = sprintf( '<table%s>', $attr_str );

        // Render header
        $html .= $this->render_header();

        // Render body
        $html .= $this->render_body();

        // Render footer
        if ( $this->footer ) {
            $html .= $this->render_footer();
        }

        $html .= '</table>';

        return $html;
    }

    /**
     * Render table header
     *
     * @return string HTML
     */
    private function render_header() {
        $html = '<thead><tr>';

        foreach ( $this->columns as $key => $column ) {
            $classes = array();

            if ( $column['class'] ) {
                $classes[] = $column['class'];
            }

            if ( $column['align'] !== 'left' ) {
                $classes[] = 'mosaic-text-' . $column['align'];
            }

            if ( $column['sortable'] ) {
                $classes[] = 'mosaic-sortable';
            }

            $style = '';
            if ( $column['width'] ) {
                $style = sprintf( 'width: %s;', esc_attr( $column['width'] ) );
            }

            $html .= sprintf(
                '<th class="%s" style="%s" data-column="%s">%s</th>',
                esc_attr( implode( ' ', $classes ) ),
                $style,
                esc_attr( $key ),
                esc_html( $column['label'] )
            );
        }

        $html .= '</tr></thead>';

        return $html;
    }

    /**
     * Render table body
     *
     * @return string HTML
     */
    private function render_body() {
        $html = '<tbody>';

        // Check for empty state
        if ( empty( $this->rows ) && ! empty( $this->empty_state ) ) {
            $colspan = count( $this->columns );
            $html .= sprintf(
                '<tr><td colspan="%d">%s</td></tr>',
                $colspan,
                $this->render_empty_state()
            );
        } else {
            foreach ( $this->rows as $row ) {
                $html .= $this->render_row( $row );
            }
        }

        $html .= '</tbody>';

        return $html;
    }

    /**
     * Render a single row
     *
     * @param array $row Row data and options
     * @return string HTML
     */
    private function render_row( $row ) {
        $options = $row['options'];
        $data = $row['data'];

        $classes = array();

        if ( $options['status'] ) {
            $classes[] = 'mosaic-row-' . $options['status'];
        }

        if ( $options['class'] ) {
            $classes[] = $options['class'];
        }

        $attrs = $options['attrs'];
        $attrs['class'] = implode( ' ', $classes );

        if ( $options['id'] ) {
            $attrs['id'] = $options['id'];
        }

        $html = sprintf( '<tr%s>', $this->build_attrs( $attrs ) );

        foreach ( $this->columns as $key => $column ) {
            $cell_value = isset( $data[ $key ] ) ? $data[ $key ] : '';
            $cell_classes = array();

            if ( $column['align'] !== 'left' ) {
                $cell_classes[] = 'mosaic-text-' . $column['align'];
            }

            // Add data-label for responsive tables
            $data_label = sprintf( 'data-label="%s"', esc_attr( $column['label'] ) );

            $html .= sprintf(
                '<td class="%s" %s>%s</td>',
                esc_attr( implode( ' ', $cell_classes ) ),
                $data_label,
                $cell_value // Allow HTML in cells
            );
        }

        $html .= '</tr>';

        return $html;
    }

    /**
     * Render table footer
     *
     * @return string HTML
     */
    private function render_footer() {
        $colspan = count( $this->columns );

        return sprintf(
            '<tfoot><tr><td colspan="%d">%s</td></tr></tfoot>',
            $colspan,
            $this->footer
        );
    }

    /**
     * Render empty state
     *
     * @return string HTML
     */
    private function render_empty_state() {
        $html = '<div class="mosaic-table-empty">';

        if ( ! empty( $this->empty_state['icon'] ) ) {
            $html .= sprintf(
                '<span class="dashicons dashicons-%s"></span>',
                esc_attr( $this->empty_state['icon'] )
            );
        }

        if ( ! empty( $this->empty_state['title'] ) ) {
            $html .= sprintf(
                '<div class="mosaic-table-empty-title">%s</div>',
                esc_html( $this->empty_state['title'] )
            );
        }

        if ( ! empty( $this->empty_state['message'] ) ) {
            $html .= sprintf(
                '<div class="mosaic-table-empty-message">%s</div>',
                esc_html( $this->empty_state['message'] )
            );
        }

        if ( ! empty( $this->empty_state['action'] ) ) {
            $html .= $this->empty_state['action'];
        }

        $html .= '</div>';

        return $html;
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

    /**
     * Output the table
     */
    public function display() {
        echo $this->render(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
