<?php
/**
 * Mosaic Design System - Form Builder
 *
 * Fluent interface for building forms with consistent styling.
 *
 * @package Mosaic
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Mosaic Form Class
 */
class Mosaic_Form {

    /**
     * Form fields
     *
     * @var array
     */
    private $fields = array();

    /**
     * Form options
     *
     * @var array
     */
    private $options = array();

    /**
     * Constructor
     *
     * @param array $options Form options
     */
    public function __construct( $options = array() ) {
        $this->options = wp_parse_args( $options, array(
            'id'      => '',
            'class'   => '',
            'action'  => '',
            'method'  => 'post',
            'enctype' => '',
            'wide'    => false,
            'nonce'   => '',
            'nonce_action' => '',
        ) );
    }

    /**
     * Add a text input
     *
     * @param string $name    Field name
     * @param string $label   Field label
     * @param array  $options Field options
     * @return self
     */
    public function add_text( $name, $label, $options = array() ) {
        return $this->add_field( 'text', $name, $label, $options );
    }

    /**
     * Add an email input
     *
     * @param string $name    Field name
     * @param string $label   Field label
     * @param array  $options Field options
     * @return self
     */
    public function add_email( $name, $label, $options = array() ) {
        return $this->add_field( 'email', $name, $label, $options );
    }

    /**
     * Add a password input
     *
     * @param string $name    Field name
     * @param string $label   Field label
     * @param array  $options Field options
     * @return self
     */
    public function add_password( $name, $label, $options = array() ) {
        return $this->add_field( 'password', $name, $label, $options );
    }

    /**
     * Add a number input
     *
     * @param string $name    Field name
     * @param string $label   Field label
     * @param array  $options Field options
     * @return self
     */
    public function add_number( $name, $label, $options = array() ) {
        return $this->add_field( 'number', $name, $label, $options );
    }

    /**
     * Add a textarea
     *
     * @param string $name    Field name
     * @param string $label   Field label
     * @param array  $options Field options
     * @return self
     */
    public function add_textarea( $name, $label, $options = array() ) {
        return $this->add_field( 'textarea', $name, $label, $options );
    }

    /**
     * Add a select dropdown
     *
     * @param string $name    Field name
     * @param string $label   Field label
     * @param array  $choices Select options (value => label)
     * @param array  $options Field options
     * @return self
     */
    public function add_select( $name, $label, $choices, $options = array() ) {
        $options['choices'] = $choices;
        return $this->add_field( 'select', $name, $label, $options );
    }

    /**
     * Add a checkbox
     *
     * @param string $name    Field name
     * @param string $label   Field label
     * @param array  $options Field options
     * @return self
     */
    public function add_checkbox( $name, $label, $options = array() ) {
        return $this->add_field( 'checkbox', $name, $label, $options );
    }

    /**
     * Add a toggle switch
     *
     * @param string $name    Field name
     * @param string $label   Field label
     * @param array  $options Field options
     * @return self
     */
    public function add_toggle( $name, $label, $options = array() ) {
        return $this->add_field( 'toggle', $name, $label, $options );
    }

    /**
     * Add radio buttons
     *
     * @param string $name    Field name
     * @param string $label   Field label
     * @param array  $choices Radio options (value => label)
     * @param array  $options Field options
     * @return self
     */
    public function add_radio( $name, $label, $choices, $options = array() ) {
        $options['choices'] = $choices;
        return $this->add_field( 'radio', $name, $label, $options );
    }

    /**
     * Add a hidden field
     *
     * @param string $name  Field name
     * @param string $value Field value
     * @return self
     */
    public function add_hidden( $name, $value ) {
        return $this->add_field( 'hidden', $name, '', array( 'value' => $value ) );
    }

    /**
     * Add custom HTML
     *
     * @param string $html Custom HTML content
     * @return self
     */
    public function add_html( $html ) {
        $this->fields[] = array(
            'type' => 'html',
            'html' => $html,
        );

        return $this;
    }

    /**
     * Add a field
     *
     * @param string $type    Field type
     * @param string $name    Field name
     * @param string $label   Field label
     * @param array  $options Field options
     * @return self
     */
    public function add_field( $type, $name, $label, $options = array() ) {
        $this->fields[] = wp_parse_args( $options, array(
            'type'        => $type,
            'name'        => $name,
            'label'       => $label,
            'id'          => $name,
            'class'       => '',
            'value'       => '',
            'placeholder' => '',
            'required'    => false,
            'disabled'    => false,
            'readonly'    => false,
            'help'        => '',
            'error'       => '',
            'choices'     => array(),
            'attrs'       => array(),
            'size'        => '', // sm, lg
            'variant'     => '', // For toggles: warning, danger
            'inline'      => false, // For checkboxes/radios
        ) );

        return $this;
    }

    /**
     * Render the form
     *
     * @return string HTML
     */
    public function render() {
        $classes = array( 'mosaic-form' );

        if ( $this->options['wide'] ) {
            $classes[] = 'mosaic-form-wide';
        }

        if ( $this->options['class'] ) {
            $classes[] = $this->options['class'];
        }

        $attrs = array(
            'class'  => implode( ' ', $classes ),
            'method' => $this->options['method'],
        );

        if ( $this->options['id'] ) {
            $attrs['id'] = $this->options['id'];
        }

        if ( $this->options['action'] ) {
            $attrs['action'] = $this->options['action'];
        }

        if ( $this->options['enctype'] ) {
            $attrs['enctype'] = $this->options['enctype'];
        }

        $html = sprintf( '<form%s>', $this->build_attrs( $attrs ) );

        // Add nonce field
        if ( $this->options['nonce'] || $this->options['nonce_action'] ) {
            $html .= wp_nonce_field(
                $this->options['nonce_action'] ?: 'mosaic_form',
                $this->options['nonce'] ?: '_mosaic_nonce',
                true,
                false
            );
        }

        // Render fields
        foreach ( $this->fields as $field ) {
            $html .= $this->render_field( $field );
        }

        $html .= '</form>';

        return $html;
    }

    /**
     * Render a single field
     *
     * @param array $field Field configuration
     * @return string HTML
     */
    private function render_field( $field ) {
        if ( $field['type'] === 'html' ) {
            return $field['html'];
        }

        if ( $field['type'] === 'hidden' ) {
            return sprintf(
                '<input type="hidden" name="%s" value="%s">',
                esc_attr( $field['name'] ),
                esc_attr( $field['value'] )
            );
        }

        $html = '<div class="mosaic-form-group">';

        // Label (except for checkbox/toggle which have inline labels)
        if ( ! in_array( $field['type'], array( 'checkbox', 'toggle' ), true ) && $field['label'] ) {
            $required_marker = $field['required'] ? ' <span class="mosaic-required">*</span>' : '';
            $html .= sprintf(
                '<label class="mosaic-label" for="%s">%s%s</label>',
                esc_attr( $field['id'] ),
                esc_html( $field['label'] ),
                $required_marker
            );
        }

        // Render the input
        $html .= $this->render_input( $field );

        // Help text
        if ( $field['help'] ) {
            $html .= sprintf(
                '<span class="mosaic-help-text">%s</span>',
                esc_html( $field['help'] )
            );
        }

        // Error message
        if ( $field['error'] ) {
            $html .= sprintf(
                '<span class="mosaic-error-message">%s</span>',
                esc_html( $field['error'] )
            );
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Render an input element
     *
     * @param array $field Field configuration
     * @return string HTML
     */
    private function render_input( $field ) {
        $input_class = 'mosaic-input';

        if ( $field['size'] ) {
            $input_class .= ' mosaic-input-' . $field['size'];
        }

        if ( $field['error'] ) {
            $input_class .= ' mosaic-input-error';
        }

        if ( $field['class'] ) {
            $input_class .= ' ' . $field['class'];
        }

        $base_attrs = array(
            'name'     => $field['name'],
            'id'       => $field['id'],
            'disabled' => $field['disabled'] ? true : false,
            'readonly' => $field['readonly'] ? true : false,
            'required' => $field['required'] ? true : false,
        );

        $base_attrs = array_merge( $base_attrs, $field['attrs'] );

        switch ( $field['type'] ) {
            case 'textarea':
                $base_attrs['class'] = 'mosaic-textarea' . ( $field['class'] ? ' ' . $field['class'] : '' );
                $base_attrs['placeholder'] = $field['placeholder'];
                return sprintf(
                    '<textarea%s>%s</textarea>',
                    $this->build_attrs( $base_attrs ),
                    esc_textarea( $field['value'] )
                );

            case 'select':
                $base_attrs['class'] = 'mosaic-select' . ( $field['class'] ? ' ' . $field['class'] : '' );
                $html = sprintf( '<select%s>', $this->build_attrs( $base_attrs ) );
                foreach ( $field['choices'] as $value => $label ) {
                    $selected = selected( $field['value'], $value, false );
                    $html .= sprintf(
                        '<option value="%s"%s>%s</option>',
                        esc_attr( $value ),
                        $selected,
                        esc_html( $label )
                    );
                }
                $html .= '</select>';
                return $html;

            case 'checkbox':
                $checked = checked( $field['value'], true, false );
                return sprintf(
                    '<label class="mosaic-label-inline">
                        <input type="checkbox" class="mosaic-checkbox" name="%s" id="%s" value="1"%s%s>
                        %s
                    </label>',
                    esc_attr( $field['name'] ),
                    esc_attr( $field['id'] ),
                    $checked,
                    $this->build_attrs( $field['attrs'] ),
                    esc_html( $field['label'] )
                );

            case 'toggle':
                $checked = checked( $field['value'], true, false );
                $toggle_class = 'mosaic-toggle';
                if ( $field['variant'] ) {
                    $toggle_class .= ' mosaic-toggle-' . $field['variant'];
                }
                return sprintf(
                    '<label class="%s">
                        <input type="checkbox" class="mosaic-toggle-input" name="%s" id="%s" value="1"%s%s>
                        <span class="mosaic-toggle-switch">
                            <span class="mosaic-toggle-slider"></span>
                        </span>
                        <span class="mosaic-toggle-label">%s</span>
                    </label>',
                    esc_attr( $toggle_class ),
                    esc_attr( $field['name'] ),
                    esc_attr( $field['id'] ),
                    $checked,
                    $this->build_attrs( $field['attrs'] ),
                    esc_html( $field['label'] )
                );

            case 'radio':
                $group_class = 'mosaic-radio-group';
                if ( $field['inline'] ) {
                    $group_class .= ' mosaic-radio-group-inline';
                }
                $html = sprintf( '<div class="%s">', esc_attr( $group_class ) );
                foreach ( $field['choices'] as $value => $label ) {
                    $checked = checked( $field['value'], $value, false );
                    $radio_id = $field['id'] . '_' . sanitize_key( $value );
                    $html .= sprintf(
                        '<label class="mosaic-label-inline">
                            <input type="radio" class="mosaic-radio" name="%s" id="%s" value="%s"%s>
                            %s
                        </label>',
                        esc_attr( $field['name'] ),
                        esc_attr( $radio_id ),
                        esc_attr( $value ),
                        $checked,
                        esc_html( $label )
                    );
                }
                $html .= '</div>';
                return $html;

            default: // text, email, password, number, etc.
                $base_attrs['type'] = $field['type'];
                $base_attrs['class'] = $input_class;
                $base_attrs['value'] = $field['value'];
                $base_attrs['placeholder'] = $field['placeholder'];
                return sprintf( '<input%s>', $this->build_attrs( $base_attrs ) );
        }
    }

    /**
     * Output the form
     */
    public function display() {
        echo $this->render(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Render form actions (submit button area)
     *
     * @param string $submit_label Submit button label
     * @param array  $options Options
     * @return string HTML
     */
    public static function actions( $submit_label = 'Save Changes', $options = array() ) {
        $defaults = array(
            'cancel_label' => '',
            'cancel_url'   => '',
            'align'        => 'right', // left, right, between
            'loading'      => false,
        );

        $options = wp_parse_args( $options, $defaults );

        $classes = array( 'mosaic-form-actions' );
        if ( $options['align'] !== 'left' ) {
            $classes[] = 'mosaic-form-actions-' . $options['align'];
        }

        $html = sprintf( '<div class="%s">', esc_attr( implode( ' ', $classes ) ) );

        if ( $options['cancel_label'] && $options['cancel_url'] ) {
            $html .= sprintf(
                '<a href="%s" class="mosaic-btn mosaic-btn-secondary">%s</a>',
                esc_url( $options['cancel_url'] ),
                esc_html( $options['cancel_label'] )
            );
        }

        $btn_class = 'mosaic-btn mosaic-btn-primary';
        if ( $options['loading'] ) {
            $btn_class .= ' mosaic-btn-loading';
        }

        $html .= sprintf(
            '<button type="submit" class="%s">%s</button>',
            esc_attr( $btn_class ),
            esc_html( $submit_label )
        );

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
            if ( $value === true ) {
                $str .= ' ' . esc_attr( $key );
            } elseif ( $value !== false && $value !== null && $value !== '' ) {
                $str .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
            }
        }
        return $str;
    }
}
