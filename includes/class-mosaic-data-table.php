<?php
/**
 * Mosaic Design System - Data Table Builder
 *
 * Extended table with built-in support for CRUD actions (add, edit, delete).
 *
 * @package Mosaic
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Mosaic Data Table Class
 */
class Mosaic_Data_Table extends Mosaic_Table {

    /**
     * Edit button callback or URL pattern
     *
     * @var callable|string|null
     */
    private $edit_action = null;

    /**
     * Delete button callback or URL pattern
     *
     * @var callable|string|null
     */
    private $delete_action = null;

    /**
     * Edit button label
     *
     * @var string
     */
    private $edit_label = 'Edit';

    /**
     * Delete button label
     *
     * @var string
     */
    private $delete_label = 'Delete';

    /**
     * Row ID key for action URLs
     *
     * @var string
     */
    private $id_key = 'id';

    /**
     * Set the table title
     *
     * @param string $title Table title
     * @return self
     */
    public function set_title( $title ) {
        $this->header_title = $title;
        return $this;
    }

    /**
     * Set the add button in header
     *
     * @param string $label   Button label
     * @param string $href    Button URL (or empty for JS handling)
     * @param array  $options Additional button options
     * @return self
     */
    public function set_add_button( $label = 'Add New', $href = '', $options = array() ) {
        $button_options = array_merge( array(
            'variant' => 'primary',
            'icon'    => 'plus-alt',
            'href'    => $href,
        ), $options );

        $this->header_actions = Mosaic::button( $label, $button_options );

        return $this;
    }

    /**
     * Enable edit action for rows
     *
     * @param callable|string $action URL pattern with {id} placeholder, or callable that receives row data
     * @param string          $label  Button label
     * @return self
     */
    public function enable_edit( $action, $label = 'Edit' ) {
        $this->edit_action = $action;
        $this->edit_label = $label;

        return $this;
    }

    /**
     * Enable delete action for rows
     *
     * @param callable|string $action URL pattern with {id} placeholder, or callable that receives row data
     * @param string          $label  Button label
     * @return self
     */
    public function enable_delete( $action, $label = 'Delete' ) {
        $this->delete_action = $action;
        $this->delete_label = $label;

        return $this;
    }

    /**
     * Set the key used for row IDs in action URLs
     *
     * @param string $key The key name in row data
     * @return self
     */
    public function set_id_key( $key ) {
        $this->id_key = $key;

        return $this;
    }

    /**
     * Add a row with automatic action buttons
     *
     * @param array $data    Row data (keyed by column key)
     * @param array $options Row options
     * @return self
     */
    public function add_row( $data, $options = array() ) {
        // Auto-generate actions column if edit or delete is enabled
        if ( ( $this->edit_action || $this->delete_action ) && ! isset( $data['actions'] ) ) {
            $data['actions'] = $this->render_row_actions( $data );
        }

        return parent::add_row( $data, $options );
    }

    /**
     * Render action buttons for a row
     *
     * @param array $row_data Row data
     * @return string HTML
     */
    private function render_row_actions( $row_data ) {
        $buttons = array();
        $id = isset( $row_data[ $this->id_key ] ) ? $row_data[ $this->id_key ] : '';

        // Edit button
        if ( $this->edit_action ) {
            $href = $this->resolve_action_url( $this->edit_action, $row_data, $id );
            $buttons[] = Mosaic::button( $this->edit_label, array(
                'variant' => 'primary',
                'href'    => $href,
            ) );
        }

        // Delete button
        if ( $this->delete_action ) {
            $href = $this->resolve_action_url( $this->delete_action, $row_data, $id );
            $buttons[] = Mosaic::button( $this->delete_label, array(
                'variant' => 'danger-outline',
                'href'    => $href,
                'class'   => 'mosaic-confirm-delete',
                'attrs'   => array(
                    'data-confirm' => 'Are you sure you want to delete this item?',
                ),
            ) );
        }

        if ( empty( $buttons ) ) {
            return '';
        }

        return '<div class="mosaic-table-actions">' . implode( '', $buttons ) . '</div>';
    }

    /**
     * Resolve action URL from pattern or callable
     *
     * @param callable|string $action   Action pattern or callable
     * @param array           $row_data Row data
     * @param mixed           $id       Row ID
     * @return string URL
     */
    private function resolve_action_url( $action, $row_data, $id ) {
        if ( is_callable( $action ) ) {
            return call_user_func( $action, $row_data, $id );
        }

        // Replace {id} placeholder
        return str_replace( '{id}', $id, $action );
    }

    /**
     * Render the table - ensure actions column exists if needed
     *
     * @return string HTML
     */
    public function render() {
        // Auto-add actions column if not present and actions are enabled
        if ( $this->edit_action || $this->delete_action ) {
            $this->add_column( 'actions', 'Actions' );
        }

        return parent::render();
    }
}
