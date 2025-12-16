<?php
/**
 * Mosaic Demo Admin - Component Showcase
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Mosaic_Demo_Admin {

    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'admin_menu', array( $this, 'add_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
    }

    public function add_menu() {
        add_menu_page(
            'Mosaic',
            'Mosaic',
            'manage_options',
            'mosaic-demo',
            array( $this, 'render_page' ),
            'dashicons-art',
            30
        );
    }

    public function enqueue_assets( $hook ) {
        if ( 'toplevel_page_mosaic-demo' !== $hook ) {
            return;
        }

        wp_enqueue_style(
            'mosaic',
            MOSAIC_DEMO_URL . 'mosaic/' . Mosaic::css(),
            array(),
            Mosaic::version()
        );

        wp_enqueue_script(
            'mosaic',
            MOSAIC_DEMO_URL . 'mosaic/' . Mosaic::js(),
            array(),
            Mosaic::version(),
            true
        );

        // Load Mosaic modules
        $modules = array( 'dialog', 'tabs', 'clipboard', 'ajax', 'filters', 'media', 'theme', 'tooltip', 'dropdown', 'toast', 'tags' );
        foreach ( $modules as $module ) {
            wp_enqueue_script(
                'mosaic-' . $module,
                MOSAIC_DEMO_URL . 'mosaic/assets/js/modules/' . $module . '.js',
                array( 'mosaic' ),
                Mosaic::version(),
                true
            );
        }

        // Enqueue WordPress media for media selector
        wp_enqueue_media();

        wp_localize_script( 'mosaic', 'mosaicContext', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'mosaic_nonce' ),
        ) );
    }

    public function render_page() {
        $theme_selector = '
            <button type="button" class="mosaic-btn mosaic-btn-secondary-outline" id="mosaic-theme-toggle">Light</button>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    Mosaic.initTheme();
                    var btn = document.getElementById("mosaic-theme-toggle");

                    function updateButton() {
                        btn.textContent = Mosaic.getTheme() === "dark" ? "Dark" : "Light";
                    }

                    updateButton();
                    btn.addEventListener("click", function() {
                        Mosaic.toggleTheme();
                        updateButton();
                    });
                });
            </script>
        ';

        echo Mosaic::page_start( 'Mosaic Component Showcase', array(
            'icon' => 'art',
            'actions' => $theme_selector,
        ) );

        $this->render_tabs();

        echo Mosaic::page_end();
    }

    private function render_tabs() {
        $tabs = new Mosaic_Tabs( array( 'style' => 'default', 'hash_navigation' => true ) );

        $tabs->add_tab( 'buttons', 'Buttons', $this->render_buttons_section(), array( 'icon' => 'button', 'active' => true ) );
        $tabs->add_tab( 'badges', 'Badges & Status', $this->render_badges_section(), array( 'icon' => 'tag' ) );
        $tabs->add_tab( 'tables', 'Tables', $this->render_tables_section(), array( 'icon' => 'editor-table' ) );
        $tabs->add_tab( 'cards', 'Cards', $this->render_cards_section(), array( 'icon' => 'screenoptions' ) );
        $tabs->add_tab( 'tabs', 'Tabs', $this->render_tabs_section(), array( 'icon' => 'index-card' ) );
        $tabs->add_tab( 'accordions', 'Accordions', $this->render_accordions_section(), array( 'icon' => 'list-view' ) );
        $tabs->add_tab( 'forms', 'Forms', $this->render_forms_section(), array( 'icon' => 'editor-ul' ) );
        $tabs->add_tab( 'dialogs', 'Dialogs', $this->render_dialogs_section(), array( 'icon' => 'format-chat' ) );
        $tabs->add_tab( 'alerts', 'Alerts', $this->render_alerts_section(), array( 'icon' => 'warning' ) );
        $tabs->add_tab( 'progress', 'Progress & Loaders', $this->render_progress_section(), array( 'icon' => 'chart-bar' ) );
        $tabs->add_tab( 'tooltips', 'Tooltips', $this->render_tooltips_section(), array( 'icon' => 'info-outline' ) );
        $tabs->add_tab( 'dropdowns', 'Dropdowns', $this->render_dropdowns_section(), array( 'icon' => 'arrow-down-alt2' ) );
        $tabs->add_tab( 'pagination', 'Pagination', $this->render_pagination_section(), array( 'icon' => 'admin-page' ) );
        $tabs->add_tab( 'breadcrumbs', 'Breadcrumbs', $this->render_breadcrumbs_section(), array( 'icon' => 'arrow-right-alt' ) );
        $tabs->add_tab( 'avatars', 'Avatars', $this->render_avatars_section(), array( 'icon' => 'admin-users' ) );
        $tabs->add_tab( 'toasts', 'Toasts', $this->render_toasts_section(), array( 'icon' => 'megaphone' ) );
        $tabs->add_tab( 'tags', 'Tags', $this->render_tags_section(), array( 'icon' => 'tag' ) );
        $tabs->add_tab( 'utilities', 'Utilities', $this->render_utilities_section(), array( 'icon' => 'admin-tools' ) );

        $tabs->display();

        // Code toggle styles and script
        ?>
        <style>
        .mosaic-example { margin-bottom: 30px; }
        .mosaic-example-output { margin-bottom: 10px; }
        .mosaic-example-code { display: none; margin-top: 10px; }
        .mosaic-example-code.show { display: block; }
        .mosaic-example-code pre {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            margin: 0;
            font-size: 13px;
            line-height: 1.5;
            white-space: pre;
        }
        .mosaic-example-code pre code {
            display: block;
            padding: 0;
            margin: 0;
            white-space: pre;
        }
        .mosaic-example-toggle {
            font-size: 12px;
            color: #2271b1;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .mosaic-example-toggle:hover { color: #135e96; text-decoration: none; }
        .mosaic-example-toggle .dashicons { font-size: 14px; width: 14px; height: 14px; }
        .mosaic-example-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .mosaic-example-header h3 { margin: 0; }
        </style>
        <script>
        function toggleCode(id) {
            var codeBlock = document.getElementById(id);
            var btn = codeBlock.previousElementSibling.querySelector('.mosaic-example-toggle');
            if (codeBlock.classList.contains('show')) {
                codeBlock.classList.remove('show');
                btn.innerHTML = '<span class="dashicons dashicons-editor-code"></span> Show Code';
            } else {
                codeBlock.classList.add('show');
                btn.innerHTML = '<span class="dashicons dashicons-hidden"></span> Hide Code';
            }
        }
        </script>
        <?php
    }

    private function example( $title, $output, $code, $description = '' ) {
        static $example_id = 0;
        $example_id++;
        $id = 'code-example-' . $example_id;

        $html = '<div class="mosaic-example">';
        $html .= '<div class="mosaic-example-header">';
        $html .= '<h3>' . esc_html( $title ) . '</h3>';
        $html .= '<button type="button" class="mosaic-example-toggle" onclick="toggleCode(\'' . $id . '\')">';
        $html .= '<span class="dashicons dashicons-editor-code"></span> Show Code</button>';
        $html .= '</div>';
        if ( $description ) {
            $html .= '<p>' . $description . '</p>';
        }
        $html .= '<div class="mosaic-example-output">' . $output . '</div>';
        $html .= '<div class="mosaic-example-code" id="' . $id . '">';
        $html .= '<pre><code>' . esc_html( ltrim( rtrim( $code ), "\n\r\t " ) ) . '</code></pre>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    // =========================================================================
    // BUTTONS SECTION
    // =========================================================================
    private function render_buttons_section() {
        ob_start();
        echo '<div class="mosaic-showcase">';

        // Button Variants
        ob_start();
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
        echo Mosaic::button( 'Primary', array( 'variant' => 'primary' ) );
        echo Mosaic::button( 'Secondary', array( 'variant' => 'secondary' ) );
        echo Mosaic::button( 'Danger', array( 'variant' => 'danger' ) );
        echo Mosaic::button( 'Success', array( 'variant' => 'success' ) );
        echo Mosaic::button( 'Warning', array( 'variant' => 'warning' ) );
        echo Mosaic::button( 'Ghost', array( 'variant' => 'ghost' ) );
        echo Mosaic::button( 'Link', array( 'variant' => 'link' ) );
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic::button( 'Primary', array( 'variant' => 'primary' ) );
Mosaic::button( 'Secondary', array( 'variant' => 'secondary' ) );
Mosaic::button( 'Danger', array( 'variant' => 'danger' ) );
Mosaic::button( 'Success', array( 'variant' => 'success' ) );
Mosaic::button( 'Warning', array( 'variant' => 'warning' ) );
Mosaic::button( 'Ghost', array( 'variant' => 'ghost' ) );
Mosaic::button( 'Link', array( 'variant' => 'link' ) );
CODE;
        echo $this->example( 'Button Variants', $output, $code, 'All available button styles:' );

        // Button Sizes
        ob_start();
        echo '<div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap; margin-bottom: 10px;">';
        echo Mosaic::button( 'Small', array( 'variant' => 'primary', 'size' => 'sm' ) );
        echo Mosaic::button( 'Default', array( 'variant' => 'primary' ) );
        echo Mosaic::button( 'Large', array( 'variant' => 'primary', 'size' => 'lg' ) );
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic::button( 'Small', array( 'variant' => 'primary', 'size' => 'sm' ) );
Mosaic::button( 'Default', array( 'variant' => 'primary' ) );
Mosaic::button( 'Large', array( 'variant' => 'primary', 'size' => 'lg' ) );
CODE;
        echo $this->example( 'Button Sizes', $output, $code, 'Small, default, and large sizes:' );

        // Buttons with Icons
        ob_start();
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 10px;">';
        echo Mosaic::button( 'Add New', array( 'variant' => 'primary', 'icon' => 'plus-alt' ) );
        echo Mosaic::button( 'Edit', array( 'variant' => 'secondary', 'icon' => 'edit' ) );
        echo Mosaic::button( 'Delete', array( 'variant' => 'danger', 'icon' => 'trash' ) );
        echo Mosaic::button( 'Download', array( 'variant' => 'success', 'icon' => 'download' ) );
        echo '</div>';
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
        echo Mosaic::button( 'Next', array( 'variant' => 'primary', 'icon' => 'arrow-right-alt', 'icon_pos' => 'right' ) );
        echo Mosaic::button( 'Export', array( 'variant' => 'secondary', 'icon' => 'external', 'icon_pos' => 'right' ) );
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Icon on left (default)
Mosaic::button( 'Add New', array( 'variant' => 'primary', 'icon' => 'plus-alt' ) );
Mosaic::button( 'Edit', array( 'variant' => 'secondary', 'icon' => 'edit' ) );
Mosaic::button( 'Delete', array( 'variant' => 'danger', 'icon' => 'trash' ) );

// Icon on right
Mosaic::button( 'Next', array( 'variant' => 'primary', 'icon' => 'arrow-right-alt', 'icon_pos' => 'right' ) );
CODE;
        echo $this->example( 'Buttons with Icons', $output, $code, 'Icons on left and right positions:' );

        // Button States
        ob_start();
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 10px;">';
        echo Mosaic::button( 'Disabled', array( 'variant' => 'primary', 'disabled' => true ) );
        echo Mosaic::button( 'Disabled', array( 'variant' => 'secondary', 'disabled' => true ) );
        echo '</div>';
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
        echo Mosaic::button( 'Loading...', array( 'variant' => 'primary', 'loading' => true ) );
        echo Mosaic::button( 'Processing...', array( 'variant' => 'secondary', 'loading' => true ) );
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Disabled state
Mosaic::button( 'Disabled', array( 'variant' => 'primary', 'disabled' => true ) );

// Loading state
Mosaic::button( 'Loading...', array( 'variant' => 'primary', 'loading' => true ) );
CODE;
        echo $this->example( 'Button States', $output, $code, 'Disabled and loading states:' );

        // Link Buttons
        ob_start();
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
        echo Mosaic::button( 'Visit Website', array( 'variant' => 'primary', 'href' => 'https://example.com', 'icon' => 'external' ) );
        echo Mosaic::button( 'Documentation', array( 'variant' => 'secondary', 'href' => '#', 'icon' => 'book' ) );
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic::button( 'Visit Website', array(
    'variant' => 'primary',
    'href'    => 'https://example.com',
    'icon'    => 'external',
) );

Mosaic::button( 'Open in New Tab', array(
    'variant' => 'link',
    'href'    => 'https://example.com',
    'target'  => '_blank',
) );
CODE;
        echo $this->example( 'Link Buttons', $output, $code, 'Buttons that link to URLs:' );

        // Icon-Only Buttons
        ob_start();
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
        echo Mosaic::button( '', array( 'variant' => 'primary', 'icon' => 'plus-alt' ) );
        echo Mosaic::button( '', array( 'variant' => 'secondary', 'icon' => 'edit' ) );
        echo Mosaic::button( '', array( 'variant' => 'danger', 'icon' => 'trash' ) );
        echo Mosaic::button( '', array( 'variant' => 'success', 'icon' => 'yes' ) );
        echo Mosaic::button( '', array( 'variant' => 'ghost', 'icon' => 'admin-generic' ) );
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic::button( '', array( 'variant' => 'primary', 'icon' => 'plus-alt' ) );
Mosaic::button( '', array( 'variant' => 'secondary', 'icon' => 'edit' ) );
Mosaic::button( '', array( 'variant' => 'danger', 'icon' => 'trash' ) );
CODE;
        echo $this->example( 'Icon-Only Buttons', $output, $code, 'Compact buttons with just icons:' );

        echo '</div>';
        return ob_get_clean();
    }

    // =========================================================================
    // BADGES & STATUS SECTION
    // =========================================================================
    private function render_badges_section() {
        ob_start();
        echo '<div class="mosaic-showcase">';

        // Badge Variants
        ob_start();
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
        echo Mosaic::badge( 'Default', 'default' );
        echo Mosaic::badge( 'Primary', 'primary' );
        echo Mosaic::badge( 'Success', 'success' );
        echo Mosaic::badge( 'Warning', 'warning' );
        echo Mosaic::badge( 'Danger', 'danger' );
        echo Mosaic::badge( 'Info', 'info' );
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic::badge( 'Default', 'default' );
Mosaic::badge( 'Primary', 'primary' );
Mosaic::badge( 'Success', 'success' );
Mosaic::badge( 'Warning', 'warning' );
Mosaic::badge( 'Danger', 'danger' );
Mosaic::badge( 'Info', 'info' );
CODE;
        echo $this->example( 'Badge Variants', $output, $code, 'All available badge styles:' );

        // Health Badges
        ob_start();
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 10px;">';
        echo Mosaic::health_badge( 'healthy' );
        echo Mosaic::health_badge( 'warning' );
        echo Mosaic::health_badge( 'critical' );
        echo Mosaic::health_badge( 'unknown' );
        echo '</div>';
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
        echo Mosaic::health_badge( 'healthy', 'Online' );
        echo Mosaic::health_badge( 'warning', 'Degraded' );
        echo Mosaic::health_badge( 'critical', 'Offline' );
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Default labels
Mosaic::health_badge( 'healthy' );
Mosaic::health_badge( 'warning' );
Mosaic::health_badge( 'critical' );
Mosaic::health_badge( 'unknown' );

// Custom labels
Mosaic::health_badge( 'healthy', 'Online' );
Mosaic::health_badge( 'warning', 'Degraded' );
Mosaic::health_badge( 'critical', 'Offline' );
CODE;
        echo $this->example( 'Health Badges', $output, $code, 'Pre-configured status indicators:' );

        // Status Dots
        ob_start();
        echo '<div style="display: flex; gap: 20px; flex-wrap: wrap; align-items: center; margin-bottom: 10px;">';
        echo '<span>' . Mosaic::status_dot( 'healthy' ) . ' Healthy</span>';
        echo '<span>' . Mosaic::status_dot( 'warning' ) . ' Warning</span>';
        echo '<span>' . Mosaic::status_dot( 'critical' ) . ' Critical</span>';
        echo '</div>';
        echo '<div style="display: flex; gap: 20px; flex-wrap: wrap; align-items: center;">';
        echo '<span>' . Mosaic::status_dot( 'healthy', true ) . ' Active (pulse)</span>';
        echo '<span>' . Mosaic::status_dot( 'warning', true ) . ' Processing (pulse)</span>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Simple status dots
Mosaic::status_dot( 'healthy' );
Mosaic::status_dot( 'warning' );
Mosaic::status_dot( 'critical' );

// With pulse animation
Mosaic::status_dot( 'healthy', true );
Mosaic::status_dot( 'warning', true );
CODE;
        echo $this->example( 'Status Dots', $output, $code, 'Simple status indicators:' );

        // Badges with Icons
        ob_start();
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
        echo Mosaic::badge( 'Published', 'success', array( 'icon' => 'yes-alt' ) );
        echo Mosaic::badge( 'Draft', 'warning', array( 'icon' => 'edit' ) );
        echo Mosaic::badge( 'Deleted', 'danger', array( 'icon' => 'trash' ) );
        echo Mosaic::badge( 'Pending', 'info', array( 'icon' => 'clock' ) );
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic::badge( 'Published', 'success', array( 'icon' => 'yes-alt' ) );
Mosaic::badge( 'Draft', 'warning', array( 'icon' => 'edit' ) );
Mosaic::badge( 'Deleted', 'danger', array( 'icon' => 'trash' ) );
Mosaic::badge( 'Pending', 'info', array( 'icon' => 'clock' ) );
CODE;
        echo $this->example( 'Badges with Icons', $output, $code );

        echo '</div>';
        return ob_get_clean();
    }

    // =========================================================================
    // TABLES SECTION
    // =========================================================================
    private function render_tables_section() {
        ob_start();
        echo '<div class="mosaic-showcase">';

        // Basic Table
        ob_start();
        $table = new Mosaic_Table();
        $table->set_columns( array( 'id' => 'ID', 'name' => 'Name', 'email' => 'Email', 'status' => 'Status' ) );
        $table->add_row( array( 'id' => '1', 'name' => 'John Doe', 'email' => 'john@example.com', 'status' => Mosaic::health_badge( 'healthy', 'Active' ) ) );
        $table->add_row( array( 'id' => '2', 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'status' => Mosaic::health_badge( 'warning', 'Pending' ) ) );
        $table->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$table = new Mosaic_Table();
$table->set_columns( array(
    'id'     => 'ID',
    'name'   => 'Name',
    'email'  => 'Email',
    'status' => 'Status',
) );
$table->add_row( array(
    'id'     => '1',
    'name'   => 'John Doe',
    'email'  => 'john@example.com',
    'status' => Mosaic::health_badge( 'healthy', 'Active' ),
) );
$table->display();
CODE;
        echo $this->example( 'Basic Table', $output, $code );

        // Striped & Hover Table
        ob_start();
        $table = new Mosaic_Table( array( 'striped' => true, 'hover' => true ) );
        $table->set_columns( array( 'product' => 'Product', 'price' => 'Price', 'qty' => 'Qty' ) );
        $table->add_row( array( 'product' => 'Widget A', 'price' => '$10.00', 'qty' => '5' ) );
        $table->add_row( array( 'product' => 'Widget B', 'price' => '$25.00', 'qty' => '3' ) );
        $table->add_row( array( 'product' => 'Widget C', 'price' => '$15.00', 'qty' => '10' ) );
        $table->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$table = new Mosaic_Table( array(
    'striped' => true,
    'hover'   => true,
) );
$table->set_columns( array( ... ) );
$table->add_row( array( ... ) );
$table->display();
CODE;
        echo $this->example( 'Striped & Hover Table', $output, $code, 'Options: striped, hover, compact, bordered' );

        // Table with Row States
        ob_start();
        $table = new Mosaic_Table( array( 'hover' => true ) );
        $table->set_columns( array( 'task' => 'Task', 'status' => 'Status' ) );
        $table->add_row( array( 'task' => 'Complete docs', 'status' => 'Done' ), array( 'state' => 'success' ) );
        $table->add_row( array( 'task' => 'Review PR', 'status' => 'In Progress' ), array( 'state' => 'selected' ) );
        $table->add_row( array( 'task' => 'Fix bug', 'status' => 'Overdue' ), array( 'state' => 'critical' ) );
        $table->add_row( array( 'task' => 'Update deps', 'status' => 'At Risk' ), array( 'state' => 'warning' ) );
        $table->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$table->add_row( array( ... ), array( 'state' => 'success' ) );
$table->add_row( array( ... ), array( 'state' => 'selected' ) );
$table->add_row( array( ... ), array( 'state' => 'critical' ) );
$table->add_row( array( ... ), array( 'state' => 'warning' ) );
$table->add_row( array( ... ), array( 'state' => 'disabled' ) );
CODE;
        echo $this->example( 'Table with Row States', $output, $code, 'States: success, selected, critical, warning, disabled' );

        // Table with Empty State
        ob_start();
        $table = new Mosaic_Table();
        $table->set_columns( array( 'name' => 'Name', 'status' => 'Status' ) );
        $table->set_empty_state( 'No clients found', 'Get started by adding your first client.', 'groups' );
        $table->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$table = new Mosaic_Table();
$table->set_columns( array( ... ) );
$table->set_empty_state(
    'No clients found',
    'Get started by adding your first client.',
    'groups'  // dashicon name
);
$table->display();
CODE;
        echo $this->example( 'Table with Empty State', $output, $code );

        // Data Tables Section
        echo '<hr style="margin: 40px 0;"><h2>Data Tables</h2>';
        echo '<p>Data Tables extend the basic Table with built-in CRUD actions.</p>';

        // Data Table with Actions
        ob_start();
        $table = new Mosaic_Data_Table( array( 'striped' => true, 'hover' => true ) );
        $table->set_columns( array( 'id' => 'ID', 'name' => 'Name', 'email' => 'Email' ) );
        $table->set_id_key( 'id' );
        $table->enable_edit( '#edit/{id}' );
        $table->enable_delete( '#delete/{id}' );
        $table->add_row( array( 'id' => '1', 'name' => 'John Doe', 'email' => 'john@example.com' ) );
        $table->add_row( array( 'id' => '2', 'name' => 'Jane Smith', 'email' => 'jane@example.com' ) );
        $table->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$table = new Mosaic_Data_Table( array( 'striped' => true, 'hover' => true ) );
$table->set_columns( array(
    'id'    => 'ID',
    'name'  => 'Name',
    'email' => 'Email',
) );
$table->set_id_key( 'id' );
$table->enable_edit( '#edit/{id}' );
$table->enable_delete( '#delete/{id}' );
$table->add_row( array( 'id' => '1', 'name' => 'John Doe', 'email' => 'john@example.com' ) );
$table->display();
CODE;
        echo $this->example( 'Data Table with Actions', $output, $code, 'Automatically adds Edit and Delete buttons:' );

        // Data Table with Add Button
        ob_start();
        $table = new Mosaic_Data_Table( array( 'striped' => true, 'hover' => true ) );
        $table->set_title( 'Clients' );
        $table->set_add_button( 'Add Client', '#add-client' );
        $table->set_columns( array( 'id' => 'ID', 'company' => 'Company', 'contact' => 'Contact' ) );
        $table->set_id_key( 'id' );
        $table->enable_edit( '#edit-client/{id}' );
        $table->enable_delete( '#delete-client/{id}' );
        $table->add_row( array( 'id' => '101', 'company' => 'Acme Corp', 'contact' => 'Alice Johnson' ) );
        $table->add_row( array( 'id' => '102', 'company' => 'TechStart Inc', 'contact' => 'Bob Smith' ) );
        $table->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$table = new Mosaic_Data_Table();
$table->set_title( 'Clients' );
$table->set_add_button( 'Add Client', '#add-client' );
$table->set_columns( array( ... ) );
$table->set_id_key( 'id' );
$table->enable_edit( '#edit-client/{id}' );
$table->enable_delete( '#delete-client/{id}' );
$table->add_row( array( ... ) );
$table->display();
CODE;
        echo $this->example( 'Data Table with Title & Add Button', $output, $code );

        // Data Table with Empty State
        ob_start();
        $table = new Mosaic_Data_Table();
        $table->set_title( 'Projects' );
        $table->set_add_button( 'Create Project', '#new-project' );
        $table->set_columns( array( 'id' => 'ID', 'name' => 'Name', 'status' => 'Status' ) );
        $table->set_empty_state( 'No projects yet', 'Create your first project to get started.', 'portfolio' );
        $table->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$table = new Mosaic_Data_Table();
$table->set_title( 'Projects' );
$table->set_add_button( 'Create Project', '#new-project' );
$table->set_columns( array( ... ) );
$table->set_empty_state(
    'No projects yet',
    'Create your first project to get started.',
    'portfolio'
);
$table->display();
CODE;
        echo $this->example( 'Data Table with Empty State', $output, $code );

        // Loading States Section
        echo '<hr style="margin: 40px 0;"><h2>Table Loading States</h2>';
        echo '<p>Add loading overlays to tables while fetching data.</p>';

        // Table with Loading Overlay
        ob_start();
        $table = new Mosaic_Table( array( 'class' => 'mosaic-loading', 'striped' => true, 'hover' => true ) );
        $table->set_columns( array( 'name' => 'Name', 'email' => 'Email', 'status' => 'Status' ) );
        $table->add_row( array( 'name' => 'John Doe', 'email' => 'john@example.com', 'status' => Mosaic::health_badge( 'healthy', 'Active' ) ) );
        $table->add_row( array( 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'status' => Mosaic::health_badge( 'warning', 'Pending' ) ) );
        $table->add_row( array( 'name' => 'Bob Wilson', 'email' => 'bob@example.com', 'status' => Mosaic::health_badge( 'healthy', 'Active' ) ) );
        $table->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
// Via CSS class
$table = new Mosaic_Table( array( 'class' => 'mosaic-loading' ) );

// Via JavaScript
Mosaic.startLoading('#my-table');

// After data loads
Mosaic.stopLoading('#my-table');
CODE;
        echo $this->example( 'Table with Loading Overlay', $output, $code );

        // Interactive Table Loading
        ob_start();
        echo '<div id="interactive-table-loading">';
        $table = new Mosaic_Table( array( 'striped' => true, 'hover' => true ) );
        $table->set_columns( array( 'id' => 'ID', 'product' => 'Product', 'price' => 'Price', 'stock' => 'Stock' ) );
        $table->add_row( array( 'id' => '001', 'product' => 'Widget Pro', 'price' => '$29.99', 'stock' => '150' ) );
        $table->add_row( array( 'id' => '002', 'product' => 'Gadget Plus', 'price' => '$49.99', 'stock' => '75' ) );
        $table->add_row( array( 'id' => '003', 'product' => 'Tool Master', 'price' => '$19.99', 'stock' => '200' ) );
        $table->display();
        echo '</div>';
        echo '<div style="display: flex; gap: 10px; margin-top: 15px;">';
        echo '<button type="button" class="mosaic-btn mosaic-btn-primary" onclick="Mosaic.startLoading(\'#interactive-table-loading\')">Start Loading</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.stopLoading(\'#interactive-table-loading\')">Stop Loading</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.startLoading(\'#interactive-table-loading\', { dark: true })">Dark Overlay</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Simulate loading data
Mosaic.startLoading('#my-table');

fetch('/api/products')
    .then(response => response.json())
    .then(data => {
        updateTable(data);
        Mosaic.stopLoading('#my-table');
    });
CODE;
        echo $this->example( 'Interactive Table Loading', $output, $code, 'Click buttons to toggle loading state:' );

        // Table Loading with AJAX
        ob_start();
        echo '<p style="margin-bottom: 15px;">When using Mosaic.ajax(), you can automatically show loading on any element:</p>';
        echo '<div style="background: #f5f5f5; padding: 15px; border-radius: 4px;">';
        echo '<code style="display: block; white-space: pre-wrap;">// Automatic loading state during AJAX request
Mosaic.ajax(\'get_table_data\', { page: 1 }, {
    loadingElement: \'#my-table\'
}).then(data => {
    // Table loading automatically stops when request completes
    renderTable(data);
});</code>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// With Mosaic AJAX - automatic loading state
Mosaic.ajax('get_table_data', { page: 1 }, {
    loadingElement: '#my-table'
}).then(data => {
    renderTable(data);
});

// Loading automatically starts and stops
CODE;
        echo $this->example( 'Table Loading with AJAX', $output, $code );

        echo '</div>';
        return ob_get_clean();
    }

    // =========================================================================
    // CARDS SECTION
    // =========================================================================
    private function render_cards_section() {
        ob_start();
        echo '<div class="mosaic-showcase">';

        // Basic Card
        ob_start();
        $card = new Mosaic_Card();
        $card->set_header( 'Card Title' );
        $card->set_body( '<p>This is a basic card with a header and body.</p>' );
        $card->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$card = new Mosaic_Card();
$card->set_header( 'Card Title' );
$card->set_body( '<p>Card content goes here.</p>' );
$card->display();
CODE;
        echo $this->example( 'Basic Card', $output, $code );

        // Card with Footer and Icon
        ob_start();
        $card = new Mosaic_Card();
        $card->set_header( 'User Profile', '', 'admin-users' );
        $card->set_body( '<p><strong>Name:</strong> John Doe</p><p><strong>Email:</strong> john@example.com</p>' );
        $card->set_footer( Mosaic::button( 'Edit', array( 'variant' => 'primary', 'size' => 'sm' ) ) );
        $card->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$card = new Mosaic_Card();
$card->set_header( 'User Profile', '', 'admin-users' );  // title, actions, icon
$card->set_body( '<p>Content...</p>' );
$card->set_footer( Mosaic::button( 'Edit', array( 'variant' => 'primary' ) ) );
$card->display();
CODE;
        echo $this->example( 'Card with Footer & Icon', $output, $code );

        // Stat Cards
        ob_start();
        echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">';
        echo Mosaic_Card::stat( 'Total Clients', '1,234' );
        echo Mosaic_Card::stat( 'Revenue', '$45k', array( 'variant' => 'success', 'icon' => 'chart-line' ) );
        echo Mosaic_Card::stat( 'Pending', '23', array( 'variant' => 'warning', 'icon' => 'clock' ) );
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic_Card::stat( 'Total Clients', '1,234' );
Mosaic_Card::stat( 'Revenue', '$45k', array(
    'variant' => 'success',
    'icon'    => 'chart-line',
) );
Mosaic_Card::stat( 'Pending', '23', array(
    'variant' => 'warning',
    'icon'    => 'clock',
) );
CODE;
        echo $this->example( 'Stat Cards', $output, $code, 'Variants: primary, success, warning, danger' );

        // Status Cards
        ob_start();
        echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">';
        echo Mosaic_Card::status( 'success', 'All Systems Go', 'Services running normally.' );
        echo Mosaic_Card::status( 'warning', 'Degraded', 'Some delays.' );
        echo Mosaic_Card::status( 'error', 'Outage', 'Service unavailable.' );
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic_Card::status( 'success', 'All Systems Go', 'Services running normally.' );
Mosaic_Card::status( 'warning', 'Degraded', 'Some delays.' );
Mosaic_Card::status( 'error', 'Outage', 'Service unavailable.' );
CODE;
        echo $this->example( 'Status Cards', $output, $code );

        // Metric Tiles
        ob_start();
        echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 15px;">';
        echo Mosaic_Card::metric( 'CPU', '45', '%' );
        echo Mosaic_Card::metric( 'Memory', '8.2', 'GB' );
        echo Mosaic_Card::metric( 'Disk', '234', 'GB' );
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic_Card::metric( 'CPU', '45', '%' );
Mosaic_Card::metric( 'Memory', '8.2', 'GB' );
Mosaic_Card::metric( 'Disk', '234', 'GB' );
CODE;
        echo $this->example( 'Metric Tiles', $output, $code );

        // Collapsible Cards
        ob_start();
        echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">';
        $card = new Mosaic_Card( array( 'collapsible' => true ) );
        $card->set_header( 'Expanded by Default' );
        $card->set_body( '<p>Click the header to collapse.</p>' );
        $card->display();
        $card = new Mosaic_Card( array( 'collapsible' => true, 'collapsed' => true ) );
        $card->set_header( 'Collapsed by Default' );
        $card->set_body( '<p>Click to expand.</p>' );
        $card->display();
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
$card = new Mosaic_Card( array( 'collapsible' => true ) );
$card->set_header( 'Expanded by Default' );
$card->set_body( '<p>Content...</p>' );
$card->display();

$card = new Mosaic_Card( array(
    'collapsible' => true,
    'collapsed'   => true,  // Start collapsed
) );
CODE;
        echo $this->example( 'Collapsible Cards', $output, $code );

        // Dismissible Cards
        ob_start();
        $card = new Mosaic_Card( array( 'dismissible' => true ) );
        $card->set_header( 'Dismissible Notice' );
        $card->set_body( '<p>Click the X button to dismiss this card.</p>' );
        $card->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$card = new Mosaic_Card( array( 'dismissible' => true ) );
$card->set_header( 'Dismissible Notice' );
$card->set_body( '<p>Click the X button to dismiss.</p>' );
$card->display();

// Combined collapsible + dismissible
$card = new Mosaic_Card( array(
    'collapsible'  => true,
    'dismissible'  => true,
) );
CODE;
        echo $this->example( 'Dismissible Cards', $output, $code );

        // Loading States Section
        echo '<hr style="margin: 40px 0;"><h2>Card Loading States</h2>';
        echo '<p>Add loading overlays to cards while content is being fetched or processed.</p>';

        // Card with Loading Overlay
        ob_start();
        echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">';
        $card = new Mosaic_Card( array( 'class' => 'mosaic-loading' ) );
        $card->set_header( 'Loading Content' );
        $card->set_body( '<p>This card is currently loading data.</p><p>The overlay dims the content.</p>' );
        $card->display();
        $card = new Mosaic_Card();
        $card->set_header( 'Ready' );
        $card->set_body( '<p>This card has finished loading.</p><p>Content is fully visible.</p>' );
        $card->display();
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Via CSS class
$card = new Mosaic_Card( array( 'class' => 'mosaic-loading' ) );

// Via JavaScript
Mosaic.startLoading('#my-card');
Mosaic.stopLoading('#my-card');
CODE;
        echo $this->example( 'Card with Loading Overlay', $output, $code );

        // Loading Size Variants on Cards
        ob_start();
        echo '<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">';
        $card = new Mosaic_Card( array( 'class' => 'mosaic-loading mosaic-loading-sm' ) );
        $card->set_header( 'Small Spinner' );
        $card->set_body( '<p>Compact loading indicator.</p>' );
        $card->display();
        $card = new Mosaic_Card( array( 'class' => 'mosaic-loading' ) );
        $card->set_header( 'Default Spinner' );
        $card->set_body( '<p>Standard loading indicator.</p>' );
        $card->display();
        $card = new Mosaic_Card( array( 'class' => 'mosaic-loading mosaic-loading-lg' ) );
        $card->set_header( 'Large Spinner' );
        $card->set_body( '<p>Prominent loading indicator.</p>' );
        $card->display();
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Size variants
<div class="mosaic-card mosaic-loading mosaic-loading-sm">...</div>
<div class="mosaic-card mosaic-loading">...</div>
<div class="mosaic-card mosaic-loading mosaic-loading-lg">...</div>

// Via JavaScript
Mosaic.startLoading('#card', { size: 'sm' });
Mosaic.startLoading('#card', { size: 'lg' });
CODE;
        echo $this->example( 'Card Loading Size Variants', $output, $code );

        // Dark Overlay on Cards
        ob_start();
        echo '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">';
        $card = new Mosaic_Card( array( 'class' => 'mosaic-loading' ) );
        $card->set_header( 'Light Overlay' );
        $card->set_body( '<p>Default white semi-transparent overlay works well on light backgrounds.</p>' );
        $card->display();
        $card = new Mosaic_Card( array( 'class' => 'mosaic-loading mosaic-loading-dark' ) );
        $card->set_header( 'Dark Overlay' );
        $card->set_body( '<p>Dark overlay provides better contrast on certain designs.</p>' );
        $card->display();
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Light overlay (default)
<div class="mosaic-card mosaic-loading">...</div>

// Dark overlay
<div class="mosaic-card mosaic-loading mosaic-loading-dark">...</div>

// Via JavaScript
Mosaic.startLoading('#card', { dark: true });
CODE;
        echo $this->example( 'Card Loading - Light vs Dark Overlay', $output, $code );

        // Interactive Card Loading Demo
        ob_start();
        echo '<div id="interactive-card-demo" style="max-width: 400px;">';
        $card = new Mosaic_Card();
        $card->set_header( 'User Dashboard', '', 'dashboard' );
        $card->set_body( '<p><strong>Welcome back!</strong></p><p>Your account is active and in good standing.</p><p>Last login: 2 hours ago</p>' );
        $card->set_footer( Mosaic::button( 'View Details', array( 'variant' => 'primary', 'size' => 'sm' ) ) );
        $card->display();
        echo '</div>';
        echo '<div style="display: flex; gap: 10px; margin-top: 15px;">';
        echo '<button type="button" class="mosaic-btn mosaic-btn-primary" onclick="Mosaic.startLoading(\'#interactive-card-demo\')">Start Loading</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.stopLoading(\'#interactive-card-demo\')">Stop Loading</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.startLoading(\'#interactive-card-demo\', { dark: true })">Dark Overlay</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.startLoading(\'#interactive-card-demo\', { size: \'lg\' })">Large Spinner</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Start loading
Mosaic.startLoading('#my-card');

// Stop loading
Mosaic.stopLoading('#my-card');

// Options
Mosaic.startLoading('#my-card', { dark: true });
Mosaic.startLoading('#my-card', { size: 'lg' });
Mosaic.startLoading('#my-card', { size: 'sm', dark: true });
CODE;
        echo $this->example( 'Interactive Card Loading Demo', $output, $code, 'Click buttons to see different loading states:' );

        // Stat Cards with Loading
        ob_start();
        echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">';
        echo '<div class="mosaic-loading">' . Mosaic_Card::stat( 'Revenue', '$--', array( 'variant' => 'success', 'icon' => 'chart-line' ) ) . '</div>';
        echo Mosaic_Card::stat( 'Users', '1,234', array( 'variant' => 'primary', 'icon' => 'admin-users' ) );
        echo '<div class="mosaic-loading">' . Mosaic_Card::stat( 'Orders', '--', array( 'variant' => 'warning', 'icon' => 'cart' ) ) . '</div>';
        echo Mosaic_Card::stat( 'Active', '89%', array( 'variant' => 'success', 'icon' => 'yes-alt' ) );
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Wrap stat cards in loading container
<div class="mosaic-loading">
    <?php echo Mosaic_Card::stat( 'Revenue', '$--', array(
        'variant' => 'success',
        'icon'    => 'chart-line',
    ) ); ?>
</div>

// Or use JavaScript
Mosaic.startLoading('#revenue-stat');
CODE;
        echo $this->example( 'Stat Cards with Loading', $output, $code, 'Show loading while fetching individual metrics:' );

        echo '</div>';
        return ob_get_clean();
    }

    // =========================================================================
    // TABS SECTION
    // =========================================================================
    private function render_tabs_section() {
        ob_start();
        echo '<div class="mosaic-showcase">';

        // Basic Tabs
        ob_start();
        $t = new Mosaic_Tabs( array( 'hash_navigation' => false ) );
        $t->add_tab( 'demo1-overview', 'Overview', '<p>This is the overview tab content. Tabs provide a way to organize content into separate views.</p>' );
        $t->add_tab( 'demo1-details', 'Details', '<p>This is the details tab content. Only one tab panel is visible at a time.</p>' );
        $t->add_tab( 'demo1-settings', 'Settings', '<p>This is the settings tab content. Click tabs to switch between panels.</p>' );
        $t->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$tabs = new Mosaic_Tabs();
$tabs->add_tab( 'overview', 'Overview', '<p>Content...</p>' );
$tabs->add_tab( 'details', 'Details', '<p>Content...</p>' );
$tabs->add_tab( 'settings', 'Settings', '<p>Content...</p>' );
$tabs->display();
CODE;
        echo $this->example( 'Basic Tabs', $output, $code );

        // Tabs with Icons
        ob_start();
        $t = new Mosaic_Tabs( array( 'hash_navigation' => false ) );
        $t->add_tab( 'demo2-home', 'Home', '<p>Welcome to the home tab with a dashicon.</p>', array( 'icon' => 'admin-home' ) );
        $t->add_tab( 'demo2-users', 'Users', '<p>Manage your users from this tab.</p>', array( 'icon' => 'admin-users' ) );
        $t->add_tab( 'demo2-settings', 'Settings', '<p>Configure your settings here.</p>', array( 'icon' => 'admin-settings' ) );
        $t->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$tabs = new Mosaic_Tabs();
$tabs->add_tab( 'home', 'Home', '<p>...</p>', array( 'icon' => 'admin-home' ) );
$tabs->add_tab( 'users', 'Users', '<p>...</p>', array( 'icon' => 'admin-users' ) );
$tabs->add_tab( 'settings', 'Settings', '<p>...</p>', array( 'icon' => 'admin-settings' ) );
$tabs->display();
CODE;
        echo $this->example( 'Tabs with Icons', $output, $code );

        // Tabs with Counts
        ob_start();
        $t = new Mosaic_Tabs( array( 'hash_navigation' => false ) );
        $t->add_tab( 'demo3-all', 'All Items', '<p>Showing all 156 items.</p>', array( 'count' => 156 ) );
        $t->add_tab( 'demo3-active', 'Active', '<p>Showing 89 active items.</p>', array( 'count' => 89 ) );
        $t->add_tab( 'demo3-pending', 'Pending', '<p>Showing 23 pending items.</p>', array( 'count' => 23 ) );
        $t->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$tabs = new Mosaic_Tabs();
$tabs->add_tab( 'all', 'All Items', '<p>...</p>', array( 'count' => 156 ) );
$tabs->add_tab( 'active', 'Active', '<p>...</p>', array( 'count' => 89 ) );
$tabs->add_tab( 'pending', 'Pending', '<p>...</p>', array( 'count' => 23 ) );
$tabs->display();
CODE;
        echo $this->example( 'Tabs with Counts', $output, $code );

        // Tab Styles Section
        echo '<hr style="margin: 40px 0;"><h2>Tab Styles</h2>';

        // Pills Style
        ob_start();
        $t = new Mosaic_Tabs( array( 'style' => 'pills', 'hash_navigation' => false ) );
        $t->add_tab( 'demo4-dashboard', 'Dashboard', '<p>Dashboard content with pills style navigation.</p>' );
        $t->add_tab( 'demo4-analytics', 'Analytics', '<p>Analytics content panel.</p>' );
        $t->add_tab( 'demo4-reports', 'Reports', '<p>Reports content panel.</p>' );
        $t->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$tabs = new Mosaic_Tabs( array( 'style' => 'pills' ) );
$tabs->add_tab( 'dashboard', 'Dashboard', '<p>...</p>' );
$tabs->add_tab( 'analytics', 'Analytics', '<p>...</p>' );
$tabs->add_tab( 'reports', 'Reports', '<p>...</p>' );
$tabs->display();
CODE;
        echo $this->example( 'Pills Style', $output, $code );

        // Vertical Style
        ob_start();
        $t = new Mosaic_Tabs( array( 'style' => 'vertical', 'hash_navigation' => false ) );
        $t->add_tab( 'demo5-general', 'General', '<p>General settings for your application.</p>', array( 'icon' => 'admin-settings' ) );
        $t->add_tab( 'demo5-security', 'Security', '<p>Security and access control settings.</p>', array( 'icon' => 'shield' ) );
        $t->add_tab( 'demo5-notifications', 'Notifications', '<p>Email and notification preferences.</p>', array( 'icon' => 'bell' ) );
        $t->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$tabs = new Mosaic_Tabs( array( 'style' => 'vertical' ) );
$tabs->add_tab( 'general', 'General', '<p>...</p>', array( 'icon' => 'admin-settings' ) );
$tabs->add_tab( 'security', 'Security', '<p>...</p>', array( 'icon' => 'shield' ) );
$tabs->add_tab( 'notifications', 'Notifications', '<p>...</p>', array( 'icon' => 'bell' ) );
$tabs->display();
CODE;
        echo $this->example( 'Vertical Style', $output, $code, 'Vertical tabs work great for settings pages:' );

        // Options Section
        echo '<hr style="margin: 40px 0;"><h2>Tab Options</h2>';

        // Hash Navigation (code only - don't enable it)
        ob_start();
        echo '<p>Hash navigation persists the active tab in the URL so users stay on the same tab after refresh:</p>';
        echo '<div style="background: #f5f5f5; padding: 15px; border-radius: 4px; margin-top: 10px;">';
        echo '<code style="display: block; white-space: pre-wrap;">$tabs = new Mosaic_Tabs( array( \'hash_navigation\' => true ) );

// The URL updates to #tab-id when switching tabs
// On page load, the tab matching the URL hash is activated
// Browser back/forward buttons navigate between tabs</code>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// PHP
$tabs = new Mosaic_Tabs( array( 'hash_navigation' => true ) );

// HTML data attribute
<div data-mosaic-tabs data-mosaic-tabs-hash>...</div>

// JavaScript
const tabs = Mosaic.tabs('#my-tabs', { hashNavigation: true });
CODE;
        echo $this->example( 'Hash Navigation', $output, $code );

        // JavaScript API Section
        echo '<hr style="margin: 40px 0;"><h2>JavaScript API</h2>';

        // Programmatic Control
        ob_start();
        $t = new Mosaic_Tabs( array( 'id' => 'demo-js-api-tabs', 'hash_navigation' => false ) );
        $t->add_tab( 'demo6-one', 'Tab One', '<p>First tab panel content.</p>' );
        $t->add_tab( 'demo6-two', 'Tab Two', '<p>Second tab panel content.</p>' );
        $t->add_tab( 'demo6-three', 'Tab Three', '<p>Third tab panel content.</p>' );
        $t->display();
        echo '<div style="display: flex; gap: 10px; margin-top: 15px;">';
        echo '<button type="button" class="mosaic-btn mosaic-btn-primary mosaic-btn-sm" onclick="Mosaic.tabs(\'#demo-js-api-tabs\').activate(\'demo6-one\')">Tab 1</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary mosaic-btn-sm" onclick="Mosaic.tabs(\'#demo-js-api-tabs\').activate(\'demo6-two\')">Tab 2</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary mosaic-btn-sm" onclick="Mosaic.tabs(\'#demo-js-api-tabs\').activate(\'demo6-three\')">Tab 3</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Initialize tabs with JavaScript
const tabs = Mosaic.tabs('#my-tabs', {
    onChange: (current, previous) => {
        console.log('Switched from', previous, 'to', current);
    }
});

// Programmatically switch tabs
tabs.activate('settings');
CODE;
        echo $this->example( 'Programmatic Control', $output, $code, 'Control tabs with JavaScript:' );

        // Tab Events (code only)
        ob_start();
        echo '<div style="background: #f5f5f5; padding: 15px; border-radius: 4px;">';
        echo '<code style="display: block; white-space: pre-wrap;">document.querySelector(\'#my-tabs\').addEventListener(\'mosaic:tab:change\', (e) => {
    console.log(\'Changed to:\', e.detail.current);
    console.log(\'Changed from:\', e.detail.previous);
});</code>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
element.addEventListener('mosaic:tab:change', (e) => {
    console.log('Current:', e.detail.current);
    console.log('Previous:', e.detail.previous);
});
CODE;
        echo $this->example( 'Tab Events', $output, $code );

        // Tab Loading States
        echo '<hr style="margin: 40px 0;"><h2>Tab Loading States</h2>';

        ob_start();
        $t = new Mosaic_Tabs( array( 'hash_navigation' => false ) );
        $t->add_tab( 'demo7-ready', 'Ready', '<p>This tab content is fully loaded.</p>' );
        $t->add_tab( 'demo7-loading', 'Loading', '<div class="mosaic-loading" style="min-height: 80px; position: relative;"><p>Loading data...</p></div>' );
        $t->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
// Via JavaScript
Mosaic.startLoading('.mosaic-tab-content[data-tab="my-tab"]');
Mosaic.stopLoading('.mosaic-tab-content[data-tab="my-tab"]');

// Via CSS class
<div class="mosaic-tab-content mosaic-loading">...</div>
CODE;
        echo $this->example( 'Tab Content with Loading', $output, $code );

        // Options Reference
        ob_start();
        echo '<div style="overflow-x: auto;">';
        $table = new Mosaic_Table( array( 'striped' => true ) );
        $table->set_columns( array( 'option' => 'Option', 'default' => 'Default', 'description' => 'Description' ) );
        $table->add_row( array( 'option' => '<code>id</code>', 'default' => '<code>\'\'</code>', 'description' => 'Container ID attribute' ) );
        $table->add_row( array( 'option' => '<code>class</code>', 'default' => '<code>\'\'</code>', 'description' => 'Additional CSS classes' ) );
        $table->add_row( array( 'option' => '<code>style</code>', 'default' => '<code>\'\'</code>', 'description' => 'Tab style: default, pills, vertical' ) );
        $table->add_row( array( 'option' => '<code>hash_navigation</code>', 'default' => '<code>false</code>', 'description' => 'Persist active tab in URL hash' ) );
        $table->add_row( array( 'option' => '<code>mobile_select</code>', 'default' => '<code>true</code>', 'description' => 'Show select dropdown on mobile' ) );
        $table->add_row( array( 'option' => '<code>overflow_menu</code>', 'default' => '<code>true</code>', 'description' => 'Show overflow menu when tabs don\'t fit' ) );
        $table->display();
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
$tabs = new Mosaic_Tabs( array(
    'id'              => 'my-tabs',
    'class'           => 'custom-class',
    'style'           => 'pills',      // default, pills, vertical
    'hash_navigation' => true,
    'mobile_select'   => true,
    'overflow_menu'   => true,
) );
CODE;
        echo $this->example( 'All Options Reference', $output, $code );

        echo '</div>';
        return ob_get_clean();
    }

    // =========================================================================
    // ACCORDIONS SECTION
    // =========================================================================
    private function render_accordions_section() {
        ob_start();
        echo '<div class="mosaic-showcase">';

        // Basic Accordion
        ob_start();
        $accordion = new Mosaic_Accordion();
        $accordion->add_item( 'What is Mosaic?', '<p>A UI design system for WordPress plugins.</p>' );
        $accordion->add_item( 'How do I install it?', '<p>Include via Wordsmith and enqueue assets.</p>' );
        $accordion->add_item( 'Is it free?', '<p>Yes! GPL-2.0+ license.</p>' );
        $accordion->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$accordion = new Mosaic_Accordion();
$accordion->add_item( 'What is Mosaic?', '<p>Content...</p>' );
$accordion->add_item( 'How do I install it?', '<p>Content...</p>' );
$accordion->add_item( 'Is it free?', '<p>Content...</p>' );
$accordion->display();
CODE;
        echo $this->example( 'Basic Accordion', $output, $code, 'Only one item open at a time:' );

        // Accordion with Default Open
        ob_start();
        $accordion = new Mosaic_Accordion();
        $accordion->add_item( 'First Section', '<p>Closed by default.</p>' );
        $accordion->add_item( 'Second Section', '<p>This starts open.</p>', array( 'open' => true ) );
        $accordion->add_item( 'Third Section', '<p>Also closed.</p>' );
        $accordion->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$accordion->add_item( 'First', '<p>...</p>' );
$accordion->add_item( 'Second', '<p>...</p>', array( 'open' => true ) );
$accordion->add_item( 'Third', '<p>...</p>' );
CODE;
        echo $this->example( 'Default Open Item', $output, $code );

        // Multiple Open
        ob_start();
        $accordion = new Mosaic_Accordion( array( 'multiple' => true ) );
        $accordion->add_item( 'Section A', '<p>Can be open with others.</p>', array( 'open' => true ) );
        $accordion->add_item( 'Section B', '<p>Also open.</p>', array( 'open' => true ) );
        $accordion->add_item( 'Section C', '<p>Click to expand.</p>' );
        $accordion->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$accordion = new Mosaic_Accordion( array( 'multiple' => true ) );
$accordion->add_item( 'Section A', '<p>...</p>', array( 'open' => true ) );
$accordion->add_item( 'Section B', '<p>...</p>', array( 'open' => true ) );
$accordion->add_item( 'Section C', '<p>...</p>' );
$accordion->display();
CODE;
        echo $this->example( 'Allow Multiple Open', $output, $code );

        // Accordion with Icons
        ob_start();
        $accordion = new Mosaic_Accordion();
        $accordion->add_item( 'General', '<p>General options.</p>', array( 'icon' => 'admin-settings' ) );
        $accordion->add_item( 'Security', '<p>Security settings.</p>', array( 'icon' => 'shield' ) );
        $accordion->add_item( 'Advanced', '<p>Advanced options.</p>', array( 'icon' => 'admin-tools' ) );
        $accordion->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$accordion->add_item( 'General', '<p>...</p>', array( 'icon' => 'admin-settings' ) );
$accordion->add_item( 'Security', '<p>...</p>', array( 'icon' => 'shield' ) );
$accordion->add_item( 'Advanced', '<p>...</p>', array( 'icon' => 'admin-tools' ) );
CODE;
        echo $this->example( 'Accordion with Icons', $output, $code );

        // Flush Style
        ob_start();
        $accordion = new Mosaic_Accordion( array( 'flush' => true ) );
        $accordion->add_item( 'Flush Item One', '<p>No outer border.</p>' );
        $accordion->add_item( 'Flush Item Two', '<p>Clean look for embedding.</p>' );
        $accordion->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$accordion = new Mosaic_Accordion( array( 'flush' => true ) );
$accordion->add_item( 'Item One', '<p>...</p>' );
$accordion->add_item( 'Item Two', '<p>...</p>' );
$accordion->display();
CODE;
        echo $this->example( 'Flush Style', $output, $code, 'No outer border - good for embedding in cards:' );

        echo '</div>';
        return ob_get_clean();
    }

    // =========================================================================
    // FORMS SECTION
    // =========================================================================
    private function render_forms_section() {
        ob_start();
        echo '<div class="mosaic-showcase">';

        // Basic Form
        ob_start();
        $form = new Mosaic_Form( array( 'nonce' => 'test_form' ) );
        $form->add_text( 'name', 'Full Name', array( 'placeholder' => 'Enter your name' ) );
        $form->add_email( 'email', 'Email', array( 'placeholder' => 'you@example.com' ) );
        $form->add_html( Mosaic_Form::actions( 'Submit' ) );
        $form->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$form = new Mosaic_Form( array( 'nonce' => 'test_form' ) );
$form->add_text( 'name', 'Full Name', array( 'placeholder' => '...' ) );
$form->add_email( 'email', 'Email', array( 'placeholder' => '...' ) );
$form->add_html( Mosaic_Form::actions( 'Submit' ) );
$form->display();
CODE;
        echo $this->example( 'Basic Form', $output, $code );

        // Help Text & Errors
        ob_start();
        $form = new Mosaic_Form();
        $form->add_text( 'user', 'Username', array( 'help' => 'Letters, numbers, underscores only.' ) );
        $form->add_text( 'err', 'With Error', array( 'value' => 'x', 'error' => 'Must be at least 2 characters.' ) );
        $form->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$form->add_text( 'user', 'Username', array(
    'help' => 'Letters, numbers, underscores only.',
) );
$form->add_text( 'err', 'With Error', array(
    'value' => 'x',
    'error' => 'Must be at least 2 characters.',
) );
CODE;
        echo $this->example( 'Help Text & Errors', $output, $code );

        // Select & Textarea
        ob_start();
        $form = new Mosaic_Form();
        $form->add_select( 'country', 'Country', array( '' => 'Select...', 'us' => 'USA', 'uk' => 'UK' ) );
        $form->add_textarea( 'notes', 'Notes', array( 'placeholder' => 'Enter notes...', 'rows' => 3 ) );
        $form->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$form->add_select( 'country', 'Country', array(
    '' => 'Select...',
    'us' => 'USA',
    'uk' => 'UK',
) );
$form->add_textarea( 'notes', 'Notes', array(
    'placeholder' => 'Enter notes...',
    'rows' => 3,
) );
CODE;
        echo $this->example( 'Select & Textarea', $output, $code );

        // Checkboxes & Toggles
        ob_start();
        $form = new Mosaic_Form();
        $form->add_checkbox( 'agree', 'I agree to terms' );
        $form->add_checkbox( 'news', 'Subscribe', array( 'checked' => true ) );
        $form->add_html( '<hr style="margin: 15px 0;">' );
        $form->add_toggle( 'notif', 'Enable notifications', array( 'checked' => true ) );
        $form->add_toggle( 'dark', 'Dark mode' );
        $form->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$form->add_checkbox( 'agree', 'I agree to terms' );
$form->add_checkbox( 'news', 'Subscribe', array( 'checked' => true ) );

$form->add_toggle( 'notif', 'Enable notifications', array( 'checked' => true ) );
$form->add_toggle( 'dark', 'Dark mode' );
CODE;
        echo $this->example( 'Checkboxes & Toggles', $output, $code );

        // Radio Buttons
        ob_start();
        $form = new Mosaic_Form();
        $form->add_radio( 'plan', 'Plan', array( 'free' => 'Free', 'pro' => 'Pro - $29/mo' ), array( 'value' => 'free' ) );
        $form->add_radio( 'freq', 'Billing', array( 'mo' => 'Monthly', 'yr' => 'Yearly' ), array( 'inline' => true ) );
        $form->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
$form->add_radio( 'plan', 'Plan', array(
    'free' => 'Free',
    'pro'  => 'Pro - $29/mo',
), array( 'value' => 'free' ) );

$form->add_radio( 'freq', 'Billing', array(
    'mo' => 'Monthly',
    'yr' => 'Yearly',
), array( 'inline' => true ) );
CODE;
        echo $this->example( 'Radio Buttons', $output, $code );

        // Media Selector
        ob_start();
        echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">';
        echo Mosaic::media_selector( 'img', array( 'label' => 'Profile Image', 'button_text' => 'Select' ) );
        echo Mosaic::media_selector( 'doc', array( 'label' => 'Document', 'type' => 'file', 'button_text' => 'Choose' ) );
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic::media_selector( 'img', array(
    'label'       => 'Profile Image',
    'button_text' => 'Select',
    'type'        => 'image',  // image, video, audio, file
    'help'        => 'Recommended: 200x200px',
) );
CODE;
        echo $this->example( 'Media Selector', $output, $code, 'WordPress media library integration:' );

        echo '</div>';
        return ob_get_clean();
    }

    // =========================================================================
    // DIALOGS SECTION
    // =========================================================================
    private function render_dialogs_section() {
        ob_start();
        echo '<div class="mosaic-showcase">';

        // Alert Dialogs
        ob_start();
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.alert(\'Simple alert message.\')">Alert</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-success" onclick="Mosaic.success(\'Operation completed!\')">Success</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-warning" onclick="Mosaic.warning(\'Please review.\')">Warning</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-danger" onclick="Mosaic.error(\'Something went wrong.\')">Error</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Each type has a default title and icon
Mosaic.alert('Simple alert message.');   // "Notice" + info icon
Mosaic.success('Operation completed!');  // "Success" + checkmark icon
Mosaic.warning('Please review.');        // "Warning" + warning icon
Mosaic.error('Something went wrong.');   // "Error" + dismiss icon
CODE;
        echo $this->example( 'Alert Dialogs', $output, $code, 'Each type has default title and icon' );

        // Custom Titles
        ob_start();
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.alert(\'Your session will expire in 5 minutes.\', {title: \'Session Timeout\'})">Custom Title</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-success" onclick="Mosaic.success(\'Your profile has been updated.\', {title: \'Profile Saved\'})">Success Title</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-danger" onclick="Mosaic.error(\'Could not connect to the server.\', {title: \'Connection Failed\'})">Error Title</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Override default title with options object
Mosaic.alert('Your session will expire in 5 minutes.', {
    title: 'Session Timeout'
});

Mosaic.success('Your profile has been updated.', {
    title: 'Profile Saved'
});

Mosaic.error('Could not connect to the server.', {
    title: 'Connection Failed'
});
CODE;
        echo $this->example( 'Custom Titles', $output, $code );

        // Confirm Dialogs
        ob_start();
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.confirm(\'Are you sure?\').then(r => r && Mosaic.success(\'Confirmed!\'))">Confirm</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-danger" onclick="Mosaic.confirmDanger(\'Delete permanently?\').then(r => r && Mosaic.success(\'Deleted!\'))">Danger Confirm</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary-outline" onclick="Mosaic.confirm(\'Publish this post?\', \'Publish Post\').then(r => r && Mosaic.success(\'Published!\'))">Custom Title</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Standard confirm with help icon
Mosaic.confirm('Are you sure?').then(confirmed => {
    if (confirmed) {
        // User clicked OK
    }
});

// Danger confirm with warning icon
Mosaic.confirmDanger('Delete permanently?').then(confirmed => {
    if (confirmed) {
        // Proceed with deletion
    }
});

// With custom title
Mosaic.confirm('Publish this post?', 'Publish Post');
CODE;
        echo $this->example( 'Confirmation Dialogs', $output, $code, 'confirm uses help icon, confirmDanger uses warning icon' );

        // Prompt Dialogs
        ob_start();
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.prompt(\'Your name?\').then(n => n && Mosaic.success(\'Hello, \' + n))">Prompt</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.prompt(\'Project name:\', \'My Project\').then(n => n && Mosaic.success(n))">With Default</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary-outline" onclick="Mosaic.prompt(\'Enter new name:\', \'\', \'Rename File\').then(n => n && Mosaic.success(\'Renamed to: \' + n))">Custom Title</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Basic prompt with edit icon
Mosaic.prompt('Your name?').then(value => {
    if (value !== null) {
        console.log('Entered:', value);
    }
});

// With default value
Mosaic.prompt('Project name:', 'My Project');

// With custom title
Mosaic.prompt('Enter new name:', '', 'Rename File');
CODE;
        echo $this->example( 'Prompt Dialogs', $output, $code, 'Prompts show edit icon by default' );

        // Custom Dialogs
        ob_start();
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
        echo '<button type="button" class="mosaic-btn mosaic-btn-primary" onclick="Mosaic.dialog({title:\'Custom\',message:\'Choose an action:\',icon:\'admin-generic\',type:\'info\',closable:true,buttons:[{label:\'Save\',action:\'save\',class:\'mosaic-btn-primary\'},{label:\'Cancel\',action:\'cancel\',class:\'mosaic-btn-secondary-outline\'}]}).then(r=>r===\'save\'&&Mosaic.success(\'Saved!\'))">With Icon</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.dialog({title:\'Upload Complete\',message:\'3 files uploaded successfully.\',icon:\'cloud-upload\',type:\'success\',buttons:[{label:\'View Files\',action:\'view\',class:\'mosaic-btn-primary\'},{label:\'Close\',action:\'close\',class:\'mosaic-btn-secondary-outline\'}]})">Success Icon</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.dialog({title:\'Multiple Actions\',message:\'What would you like to do?\',icon:\'editor-help\',type:\'info\',buttons:[{label:\'Save\',action:\'save\',class:\'mosaic-btn-primary\'},{label:\'Save Draft\',action:\'draft\',class:\'mosaic-btn-secondary\'},{label:\'Cancel\',action:\'cancel\',class:\'mosaic-btn-secondary-outline\'}]}).then(r=>Mosaic.alert(\'Action: \'+r))">Multiple Buttons</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic.dialog({
    title: 'Custom Dialog',
    message: 'Choose an action:',
    icon: 'admin-generic',    // Any dashicon name
    type: 'info',             // info, success, warning, error (colors the icon)
    closable: true,
    buttons: [
        { label: 'Save', action: 'save', class: 'mosaic-btn-primary' },
        { label: 'Save Draft', action: 'draft', class: 'mosaic-btn-secondary' },
        { label: 'Cancel', action: 'cancel', class: 'mosaic-btn-secondary-outline' }
    ]
}).then(action => {
    // action = 'save', 'draft', 'cancel', or 'close'
});
CODE;
        echo $this->example( 'Custom Dialogs', $output, $code, 'Full control over icon, type, and buttons' );

        // Custom Modals with Forms
        ob_start();
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
        echo '<button type="button" class="mosaic-btn mosaic-btn-primary" id="demo-form-modal">Form Modal</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" id="demo-large-modal">Large Modal</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" id="demo-no-buttons-modal">No Buttons</button>';
        echo '</div>';
        echo <<<'SCRIPT'
<script>
document.getElementById('demo-form-modal').addEventListener('click', function() {
    Mosaic.modal({
        title: 'Edit User',
        size: 'lg',
        content: `
            <form id="demo-user-form">
                <div class="mosaic-form-group">
                    <label class="mosaic-label">Name</label>
                    <input type="text" class="mosaic-input" name="name" value="John Doe">
                </div>
                <div class="mosaic-form-group">
                    <label class="mosaic-label">Email</label>
                    <input type="email" class="mosaic-input" name="email" value="john@example.com">
                </div>
                <div class="mosaic-form-group">
                    <label class="mosaic-label">Role</label>
                    <select class="mosaic-select" name="role">
                        <option value="admin">Administrator</option>
                        <option value="editor" selected>Editor</option>
                        <option value="viewer">Viewer</option>
                    </select>
                </div>
            </form>
        `,
        buttons: [
            { label: 'Save Changes', action: 'save', class: 'mosaic-btn-primary' },
            { label: 'Cancel', action: 'cancel', class: 'mosaic-btn-secondary-outline' }
        ]
    }).then(action => {
        if (action === 'save') {
            Mosaic.success('User saved!');
        }
    });
});

document.getElementById('demo-large-modal').addEventListener('click', function() {
    Mosaic.modal({
        title: 'Large Content Modal',
        size: 'xl',
        content: `
            <p>This modal uses the <code>xl</code> size for larger content.</p>
            <p>Available sizes: <code>sm</code>, <code>lg</code>, <code>xl</code>, <code>full</code></p>
            <div class="mosaic-alert mosaic-alert-info" style="margin-top: 16px;">
                <span class="dashicons dashicons-info"></span>
                <div class="mosaic-alert-content">You can put any content here - tables, forms, rich text, etc.</div>
            </div>
        `,
        buttons: [
            { label: 'Close', action: 'close', class: 'mosaic-btn-secondary' }
        ]
    });
});

document.getElementById('demo-no-buttons-modal').addEventListener('click', function() {
    Mosaic.modal({
        title: 'Preview',
        content: `
            <div style="text-align: center; padding: 20px;">
                <span class="dashicons dashicons-format-image" style="font-size: 64px; width: 64px; height: 64px; color: var(--mosaic-text-tertiary);"></span>
                <p style="margin-top: 16px; color: var(--mosaic-text-secondary);">
                    Modals without buttons can be closed via the X button, clicking the overlay, or pressing Escape.
                </p>
            </div>
        `
    });
});
</script>
SCRIPT;
        $output = ob_get_clean();
        $code = <<<'CODE'
// Form in a modal
Mosaic.modal({
    title: 'Edit User',
    size: 'lg',
    content: `
        <form id="user-form">
            <div class="mosaic-form-group">
                <label class="mosaic-label">Name</label>
                <input type="text" class="mosaic-input" name="name">
            </div>
            <div class="mosaic-form-group">
                <label class="mosaic-label">Email</label>
                <input type="email" class="mosaic-input" name="email">
            </div>
        </form>
    `,
    buttons: [
        { label: 'Save', action: 'save', class: 'mosaic-btn-primary' },
        { label: 'Cancel', action: 'cancel', class: 'mosaic-btn-secondary-outline' }
    ]
}).then(action => {
    if (action === 'save') {
        const form = document.querySelector('#user-form');
        // Process form data...
    }
});

// Modal without footer buttons
Mosaic.modal({
    title: 'Preview',
    size: 'xl',
    content: document.getElementById('preview-content')
});
CODE;
        echo $this->example( 'Custom Modals', $output, $code, 'Use Mosaic.modal() for forms, editors, or any custom content' );

        // Modal with Callbacks
        ob_start();
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" id="demo-callback-modal">Modal with Callbacks</button>';
        echo <<<'SCRIPT'
<script>
document.getElementById('demo-callback-modal').addEventListener('click', function() {
    Mosaic.modal({
        title: 'Initialize on Open',
        content: '<div id="callback-content"><p>Loading...</p></div>',
        onOpen: function(modal, body) {
            // Simulate async content loading
            setTimeout(function() {
                body.querySelector('#callback-content').innerHTML = `
                    <p style="color: var(--mosaic-success);">✓ Content loaded via onOpen callback!</p>
                    <p>The <code>onOpen</code> callback receives the modal element and body element.</p>
                `;
            }, 500);
        },
        onClose: function(action) {
            console.log('Modal closed via:', action);
        },
        buttons: [
            { label: 'Done', action: 'done', class: 'mosaic-btn-primary' }
        ]
    });
});
</script>
SCRIPT;
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic.modal({
    title: 'Editor',
    size: 'xl',
    content: '<div id="editor-container"></div>',
    onOpen: (modal, body) => {
        // Initialize editor, load data, etc.
        initEditor(body.querySelector('#editor-container'));
    },
    onClose: (action) => {
        // Cleanup, save draft, etc.
        console.log('Closed via:', action);
        // action = 'close', 'escape', 'overlay', or button action
    }
});

// Close all modals programmatically
Mosaic.closeModals();
CODE;
        echo $this->example( 'Modal Callbacks', $output, $code, 'onOpen and onClose callbacks for initialization and cleanup' );

        echo '</div>';
        return ob_get_clean();
    }

    // =========================================================================
    // ALERTS SECTION
    // =========================================================================
    private function render_alerts_section() {
        ob_start();
        echo '<div class="mosaic-showcase">';

        // Basic Alerts
        ob_start();
        echo Mosaic::alert( 'This is an informational alert.', 'info' );
        echo Mosaic::alert( 'Your changes have been saved.', 'success' );
        echo Mosaic::alert( 'Please review before continuing.', 'warning' );
        echo Mosaic::alert( 'Something went wrong.', 'error' );
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic::alert( 'Informational message.', 'info' );
Mosaic::alert( 'Success message.', 'success' );
Mosaic::alert( 'Warning message.', 'warning' );
Mosaic::alert( 'Error message.', 'error' );
CODE;
        echo $this->example( 'Basic Alerts', $output, $code, 'Types: info, success, warning, error' );

        // Dismissible Alerts
        ob_start();
        echo Mosaic::alert( 'Click the X to dismiss.', 'info', array( 'dismissible' => true ) );
        echo Mosaic::alert( 'Dismissible success.', 'success', array( 'dismissible' => true ) );
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic::alert( 'Click the X to dismiss.', 'info', array(
    'dismissible' => true,
) );
CODE;
        echo $this->example( 'Dismissible Alerts', $output, $code );

        // Alerts without Icons
        ob_start();
        echo Mosaic::alert( 'Info alert without icon.', 'info', array( 'icon' => false ) );
        echo Mosaic::alert( 'Success alert without icon.', 'success', array( 'icon' => false ) );
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic::alert( 'Alert without icon.', 'info', array(
    'icon' => false,
) );
CODE;
        echo $this->example( 'Alerts without Icons', $output, $code );

        // HTML Alert Variants (using mosaic-alert classes)
        echo '<hr style="margin: 40px 0;"><h2>HTML Alert Variants</h2>';
        echo '<p>For more control, use HTML with the <code>mosaic-alert</code> classes:</p>';

        ob_start();
        echo '<div class="mosaic-alert mosaic-alert-info mosaic-alert-accent">';
        echo '<div class="mosaic-alert-icon"><span class="dashicons dashicons-info"></span></div>';
        echo '<div class="mosaic-alert-content">';
        echo '<div class="mosaic-alert-title">Information</div>';
        echo '<div class="mosaic-alert-message">This is an informational message with helpful details.</div>';
        echo '</div></div>';
        echo '<div class="mosaic-alert mosaic-alert-success">';
        echo '<div class="mosaic-alert-icon"><span class="dashicons dashicons-yes-alt"></span></div>';
        echo '<div class="mosaic-alert-content">';
        echo '<div class="mosaic-alert-message">Settings saved successfully.</div>';
        echo '</div></div>';
        echo '<div class="mosaic-alert mosaic-alert-warning">';
        echo '<div class="mosaic-alert-icon"><span class="dashicons dashicons-warning"></span></div>';
        echo '<div class="mosaic-alert-content">';
        echo '<div class="mosaic-alert-message">Your session will expire in 5 minutes.</div>';
        echo '</div>';
        echo '<button class="mosaic-alert-close"><span class="dashicons dashicons-no-alt"></span></button>';
        echo '</div>';
        echo '<div class="mosaic-alert mosaic-alert-error">';
        echo '<div class="mosaic-alert-icon"><span class="dashicons dashicons-dismiss"></span></div>';
        echo '<div class="mosaic-alert-content">';
        echo '<div class="mosaic-alert-title">Error</div>';
        echo '<div class="mosaic-alert-message">Failed to save changes. Please try again.</div>';
        echo '</div></div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<div class="mosaic-alert mosaic-alert-info mosaic-alert-accent">
    <div class="mosaic-alert-icon"><span class="dashicons dashicons-info"></span></div>
    <div class="mosaic-alert-content">
        <div class="mosaic-alert-title">Information</div>
        <div class="mosaic-alert-message">Message text here.</div>
    </div>
</div>

<!-- Variants: mosaic-alert-success, mosaic-alert-warning, mosaic-alert-error -->
<!-- Add mosaic-alert-accent for left border -->
CODE;
        echo $this->example( 'Alert Variants with HTML', $output, $code, 'Use mosaic-alert-accent for a left border accent style' );

        echo '</div>';
        return ob_get_clean();
    }

    // =========================================================================
    // PROGRESS & LOADERS SECTION
    // =========================================================================
    private function render_progress_section() {
        ob_start();
        echo '<div class="mosaic-showcase">';

        // Progress Bars
        ob_start();
        echo '<div style="display: flex; flex-direction: column; gap: 10px;">';
        echo Mosaic::progress( 25 );
        echo Mosaic::progress( 50 );
        echo Mosaic::progress( 75 );
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic::progress( 25 );
Mosaic::progress( 50 );
Mosaic::progress( 75 );
CODE;
        echo $this->example( 'Progress Bars', $output, $code );

        // Progress with Labels & Colors
        ob_start();
        echo '<div style="display: flex; flex-direction: column; gap: 10px;">';
        echo Mosaic::progress( 80, array( 'variant' => 'primary', 'show_label' => true ) );
        echo Mosaic::progress( 60, array( 'variant' => 'success', 'show_label' => true ) );
        echo Mosaic::progress( 40, array( 'variant' => 'warning', 'show_label' => true ) );
        echo Mosaic::progress( 20, array( 'variant' => 'danger', 'show_label' => true ) );
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic::progress( 80, array(
    'variant'    => 'primary',  // primary, success, warning, danger
    'show_label' => true,
) );
CODE;
        echo $this->example( 'Progress with Labels & Colors', $output, $code );

        // Animated Progress
        ob_start();
        echo '<div style="display: flex; flex-direction: column; gap: 10px;">';
        echo Mosaic::progress( 50, array( 'striped' => true, 'animated' => true ) );
        echo Mosaic::progress( 70, array( 'striped' => true, 'animated' => true, 'variant' => 'success' ) );
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic::progress( 50, array(
    'striped'  => true,
    'animated' => true,
    'variant'  => 'success',
) );
CODE;
        echo $this->example( 'Animated Striped Progress', $output, $code );

        // Spinners
        ob_start();
        echo '<div style="display: flex; gap: 30px; align-items: center;">';
        echo Mosaic::spinner( array( 'size' => 'sm' ) );
        echo Mosaic::spinner();
        echo Mosaic::spinner( array( 'size' => 'lg' ) );
        echo Mosaic::spinner( array( 'variant' => 'success' ) );
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic::spinner();
Mosaic::spinner( array( 'size' => 'sm' ) );
Mosaic::spinner( array( 'size' => 'lg' ) );
Mosaic::spinner( array( 'variant' => 'success' ) );
CODE;
        echo $this->example( 'Spinners', $output, $code, 'Sizes: sm, default, lg. Variants: primary, secondary, success, warning, danger' );

        // Loading States Section
        echo '<hr style="margin: 40px 0;"><h2>Loading States</h2>';
        echo '<p>Add loading overlays to any element with the <code>mosaic-loading</code> class or JavaScript API.</p>';

        // Card with Loading
        ob_start();
        echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">';
        $card = new Mosaic_Card( array( 'class' => 'mosaic-loading' ) );
        $card->set_header( 'Loading Card' );
        $card->set_body( '<p>This card has a loading overlay.</p><p>Content is still visible but dimmed.</p>' );
        $card->display();
        $card = new Mosaic_Card();
        $card->set_header( 'Normal Card' );
        $card->set_body( '<p>This card is not loading.</p><p>Regular appearance.</p>' );
        $card->display();
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Via CSS class
$card = new Mosaic_Card( array( 'class' => 'mosaic-loading' ) );

// Via JavaScript
Mosaic.startLoading('#my-card');
Mosaic.stopLoading('#my-card');
CODE;
        echo $this->example( 'Card with Loading Overlay', $output, $code );

        // Table with Loading
        ob_start();
        echo '<div id="loading-table-demo">';
        $table = new Mosaic_Table( array( 'class' => 'mosaic-loading', 'striped' => true ) );
        $table->set_columns( array( 'name' => 'Name', 'status' => 'Status', 'date' => 'Date' ) );
        $table->add_row( array( 'name' => 'John Doe', 'status' => 'Active', 'date' => '2024-01-15' ) );
        $table->add_row( array( 'name' => 'Jane Smith', 'status' => 'Pending', 'date' => '2024-01-14' ) );
        $table->add_row( array( 'name' => 'Bob Wilson', 'status' => 'Active', 'date' => '2024-01-13' ) );
        $table->display();
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Via CSS class
$table = new Mosaic_Table( array( 'class' => 'mosaic-loading' ) );

// Via JavaScript
Mosaic.startLoading('#my-table');
Mosaic.stopLoading('#my-table');
CODE;
        echo $this->example( 'Table with Loading Overlay', $output, $code );

        // Loading Size Variants
        ob_start();
        echo '<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">';
        $card = new Mosaic_Card( array( 'class' => 'mosaic-loading mosaic-loading-sm' ) );
        $card->set_header( 'Small' );
        $card->set_body( '<p>Small spinner</p>' );
        $card->display();
        $card = new Mosaic_Card( array( 'class' => 'mosaic-loading' ) );
        $card->set_header( 'Default' );
        $card->set_body( '<p>Default spinner</p>' );
        $card->display();
        $card = new Mosaic_Card( array( 'class' => 'mosaic-loading mosaic-loading-lg' ) );
        $card->set_header( 'Large' );
        $card->set_body( '<p>Large spinner</p>' );
        $card->display();
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Size variants via CSS classes
<div class="mosaic-card mosaic-loading mosaic-loading-sm">...</div>
<div class="mosaic-card mosaic-loading">...</div>
<div class="mosaic-card mosaic-loading mosaic-loading-lg">...</div>

// Via JavaScript
Mosaic.startLoading('#el', { size: 'sm' });
Mosaic.startLoading('#el', { size: 'lg' });
CODE;
        echo $this->example( 'Loading Size Variants', $output, $code );

        // Dark Loading Overlay
        ob_start();
        echo '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">';
        $card = new Mosaic_Card( array( 'class' => 'mosaic-loading' ) );
        $card->set_header( 'Light Overlay' );
        $card->set_body( '<p>Default semi-transparent white overlay.</p>' );
        $card->display();
        $card = new Mosaic_Card( array( 'class' => 'mosaic-loading mosaic-loading-dark' ) );
        $card->set_header( 'Dark Overlay' );
        $card->set_body( '<p>Dark semi-transparent overlay.</p>' );
        $card->display();
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Dark overlay via CSS
<div class="mosaic-card mosaic-loading mosaic-loading-dark">...</div>

// Via JavaScript
Mosaic.startLoading('#el', { dark: true });
CODE;
        echo $this->example( 'Dark Loading Overlay', $output, $code );

        // Interactive Loading Demo
        ob_start();
        echo '<div id="interactive-loading-card">';
        $card = new Mosaic_Card();
        $card->set_header( 'Interactive Demo' );
        $card->set_body( '<p>Click the buttons below to toggle loading state on this card.</p>' );
        $card->display();
        echo '</div>';
        echo '<div style="display: flex; gap: 10px; margin-top: 10px;">';
        echo '<button type="button" class="mosaic-btn mosaic-btn-primary" onclick="Mosaic.startLoading(\'#interactive-loading-card\')">Start Loading</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.stopLoading(\'#interactive-loading-card\')">Stop Loading</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.startLoading(\'#interactive-loading-card\', { dark: true })">Dark Loading</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Start loading
Mosaic.startLoading('#my-element');

// Stop loading
Mosaic.stopLoading('#my-element');

// With options
Mosaic.startLoading('#my-element', { size: 'lg' });
Mosaic.startLoading('#my-element', { dark: true });
CODE;
        echo $this->example( 'Interactive Loading Demo', $output, $code, 'Click the buttons to see the loading state in action:' );

        // Page Loading
        ob_start();
        echo '<div style="display: flex; gap: 10px;">';
        echo '<button type="button" class="mosaic-btn mosaic-btn-primary" onclick="Mosaic.startPage(); setTimeout(() => Mosaic.stopPage(), 2000);">Page Loading (2s)</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.startPage({ dark: true }); setTimeout(() => Mosaic.stopPage(), 2000);">Dark Page Loading</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Show full-page loading overlay
Mosaic.startPage();

// Do async work...
await fetch('/api/data');

// Hide loading
Mosaic.stopPage();

// With dark overlay
Mosaic.startPage({ dark: true });
CODE;
        echo $this->example( 'Page Loading', $output, $code, 'Cover the entire page with a loading overlay:' );

        // Standalone Spinners & Loaders
        ob_start();
        echo '<div style="display: flex; gap: 40px; align-items: center; flex-wrap: wrap;">';
        echo '<div style="text-align: center;"><div class="mosaic-spinner mosaic-spinner-xs"></div><small style="display: block; margin-top: 5px;">XS</small></div>';
        echo '<div style="text-align: center;"><div class="mosaic-spinner mosaic-spinner-sm"></div><small style="display: block; margin-top: 5px;">SM</small></div>';
        echo '<div style="text-align: center;"><div class="mosaic-spinner"></div><small style="display: block; margin-top: 5px;">Default</small></div>';
        echo '<div style="text-align: center;"><div class="mosaic-spinner mosaic-spinner-lg"></div><small style="display: block; margin-top: 5px;">LG</small></div>';
        echo '<div style="text-align: center;"><div class="mosaic-spinner mosaic-spinner-xl"></div><small style="display: block; margin-top: 5px;">XL</small></div>';
        echo '</div>';
        echo '<div style="display: flex; gap: 40px; align-items: center; flex-wrap: wrap; margin-top: 20px;">';
        echo '<div style="text-align: center;"><div class="mosaic-spinner mosaic-spinner-success"></div><small style="display: block; margin-top: 5px;">Success</small></div>';
        echo '<div style="text-align: center;"><div class="mosaic-spinner mosaic-spinner-warning"></div><small style="display: block; margin-top: 5px;">Warning</small></div>';
        echo '<div style="text-align: center;"><div class="mosaic-spinner mosaic-spinner-critical"></div><small style="display: block; margin-top: 5px;">Critical</small></div>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<!-- Size variants -->
<div class="mosaic-spinner mosaic-spinner-xs"></div>
<div class="mosaic-spinner mosaic-spinner-sm"></div>
<div class="mosaic-spinner"></div>
<div class="mosaic-spinner mosaic-spinner-lg"></div>
<div class="mosaic-spinner mosaic-spinner-xl"></div>

<!-- Color variants -->
<div class="mosaic-spinner mosaic-spinner-success"></div>
<div class="mosaic-spinner mosaic-spinner-warning"></div>
<div class="mosaic-spinner mosaic-spinner-critical"></div>
CODE;
        echo $this->example( 'Standalone Spinners', $output, $code );

        // Dots Loader & Spinning Icon
        ob_start();
        echo '<div style="display: flex; gap: 60px; align-items: center; flex-wrap: wrap;">';
        echo '<div style="text-align: center;"><div class="mosaic-dots"><span class="mosaic-dot"></span><span class="mosaic-dot"></span><span class="mosaic-dot"></span></div><small style="display: block; margin-top: 10px;">Dots Loader</small></div>';
        echo '<div style="text-align: center;"><span class="dashicons dashicons-update mosaic-icon-spin" style="font-size: 24px; width: 24px; height: 24px;"></span><small style="display: block; margin-top: 10px;">Spinning Icon</small></div>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<!-- Dots loader -->
<div class="mosaic-dots">
    <span class="mosaic-dot"></span>
    <span class="mosaic-dot"></span>
    <span class="mosaic-dot"></span>
</div>

<!-- Rotating dashicon -->
<span class="dashicons dashicons-update mosaic-icon-spin"></span>
CODE;
        echo $this->example( 'Alternative Loaders', $output, $code );

        // Tab Content Loading
        ob_start();
        $tabs = new Mosaic_Tabs( array( 'id' => 'loading-tabs-demo' ) );
        $tabs->add_tab( 'loaded', 'Loaded Tab', '<p>This tab content is fully loaded and visible.</p>' );
        $tabs->add_tab( 'loading', 'Loading Tab', '<div class="mosaic-loading" style="min-height: 100px; position: relative;"><p>This tab content has a loading overlay.</p></div>' );
        $tabs->display();
        $output = ob_get_clean();
        $code = <<<'CODE'
// Add loading to tab content via JavaScript
Mosaic.startLoading('.mosaic-tab-content[data-tab="my-tab"]');

// Or via CSS class
<div class="mosaic-tab-content mosaic-loading">...</div>
CODE;
        echo $this->example( 'Tab Content Loading', $output, $code, 'Apply loading states to individual tab panels:' );

        echo '</div>';
        return ob_get_clean();
    }

    // =========================================================================
    // UTILITIES SECTION
    // =========================================================================
    private function render_utilities_section() {
        ob_start();
        echo '<div class="mosaic-showcase">';

        // Icons (Dashicons)
        ob_start();
        echo '<div style="display: flex; gap: 20px; flex-wrap: wrap;">';
        $icons = array( 'admin-users', 'admin-settings', 'edit', 'trash', 'plus-alt', 'yes', 'no',
                       'warning', 'info', 'star-filled', 'heart', 'chart-bar', 'calendar-alt',
                       'email', 'phone', 'location', 'clock', 'search', 'menu', 'dashboard' );
        foreach ( $icons as $icon ) {
            echo '<span style="display: inline-flex; flex-direction: column; align-items: center; width: 80px;">';
            echo Mosaic::icon( $icon, array( 'size' => 'lg' ) );
            echo '<small style="margin-top: 5px; color: #666;">' . $icon . '</small>';
            echo '</span>';
        }
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic::icon( 'admin-users' );
Mosaic::icon( 'edit' );
Mosaic::icon( 'trash' );
Mosaic::icon( 'warning' );
CODE;
        echo $this->example( 'Icons (Dashicons)', $output, $code, 'Common icons available via Mosaic::icon():' );

        // Icon Sizes
        ob_start();
        echo '<div style="display: flex; gap: 30px; align-items: center;">';
        echo '<span style="display: flex; align-items: center; gap: 5px;">SM: ' . Mosaic::icon( 'admin-users', array( 'size' => 'sm' ) ) . '</span>';
        echo '<span style="display: flex; align-items: center; gap: 5px;">Default: ' . Mosaic::icon( 'admin-users' ) . '</span>';
        echo '<span style="display: flex; align-items: center; gap: 5px;">LG: ' . Mosaic::icon( 'admin-users', array( 'size' => 'lg' ) ) . '</span>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic::icon( 'admin-users', array( 'size' => 'sm' ) );
Mosaic::icon( 'admin-users' );  // default size
Mosaic::icon( 'admin-users', array( 'size' => 'lg' ) );
CODE;
        echo $this->example( 'Icon Sizes', $output, $code );

        // Clipboard - Copy Text
        ob_start();
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" data-mosaic-copy="Hello, World!">';
        echo Mosaic::icon( 'clipboard' ) . ' Copy "Hello, World!"</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" data-mosaic-copy="sk-1234567890abcdef">';
        echo Mosaic::icon( 'clipboard' ) . ' Copy API Key</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<!-- Copy static text -->
<button data-mosaic-copy="Text to copy">Copy</button>

// JavaScript API
const success = await Mosaic.copy('Text to copy', buttonElement);
CODE;
        echo $this->example( 'Clipboard - Copy Text', $output, $code, 'Click to copy text to clipboard:' );

        // Clipboard - Copy from Element
        ob_start();
        echo '<code id="code-sample" style="display: block; padding: 15px; background: #f5f5f5; border-radius: 4px; margin-bottom: 10px;">';
        echo 'npm install @wordpress/scripts --save-dev</code>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary mosaic-btn-sm" data-mosaic-copy-target="#code-sample">';
        echo Mosaic::icon( 'clipboard' ) . ' Copy Command</button>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<!-- Copy from another element -->
<code id="my-code">npm install...</code>
<button data-mosaic-copy-target="#my-code">Copy</button>

// JavaScript API
Mosaic.copyFromElement('#my-code', buttonElement);
CODE;
        echo $this->example( 'Clipboard - Copy from Element', $output, $code );

        // JavaScript Utilities - Formatting
        ob_start();
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 15px;">';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary mosaic-btn-sm" onclick="Mosaic.alert(\'Formatted: \' + Mosaic.formatNumber(1234567))">formatNumber(1234567)</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary mosaic-btn-sm" onclick="Mosaic.alert(\'Formatted: \' + Mosaic.formatBytes(1536000))">formatBytes(1536000)</button>';
        echo '</div>';
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary mosaic-btn-sm" onclick="Mosaic.alert(Mosaic.formatRelativeTime(new Date(Date.now() - 60000)))">1 minute ago</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary mosaic-btn-sm" onclick="Mosaic.alert(Mosaic.formatRelativeTime(new Date(Date.now() - 3600000)))">1 hour ago</button>';
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary mosaic-btn-sm" onclick="Mosaic.alert(Mosaic.formatRelativeTime(new Date(Date.now() - 86400000)))">1 day ago</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic.formatNumber(1234567);
// "1,234,567"

Mosaic.formatBytes(1536000);
// "1.46 MB"

Mosaic.formatRelativeTime(new Date(Date.now() - 60000));
// "1 minute ago"
CODE;
        echo $this->example( 'JavaScript Utilities - Formatting', $output, $code, 'Click to see formatted output:' );

        // Unique ID Generator
        ob_start();
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.alert(\'Generated: \' + Mosaic.uniqueId(\'field_\'))">Generate Unique ID</button>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic.uniqueId('field_');
// "field_a1b2c3d4e"

Mosaic.uniqueId('input_');
// "input_f5g6h7i8j"
CODE;
        echo $this->example( 'Unique ID Generator', $output, $code );

        // Debounce & Throttle
        ob_start();
        echo '<p style="margin-bottom: 10px;">These utilities help control function execution frequency:</p>';
        echo '<div style="background: #f5f5f5; padding: 15px; border-radius: 4px;">';
        echo '<code style="display: block; white-space: pre-wrap;">// Debounce - wait until user stops typing
const search = Mosaic.debounce((query) => {
    console.log(\'Searching:\', query);
}, 300);

// Throttle - limit to once per interval
const scroll = Mosaic.throttle(() => {
    console.log(\'Scrolled\');
}, 100);</code>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Debounce - wait until user stops typing
const search = Mosaic.debounce((query) => {
    fetchResults(query);
}, 300);
input.addEventListener('input', (e) => search(e.target.value));

// Throttle - limit to once per interval
const scroll = Mosaic.throttle(() => {
    updatePosition();
}, 100);
window.addEventListener('scroll', scroll);
CODE;
        echo $this->example( 'Debounce & Throttle', $output, $code );

        // Escape HTML
        ob_start();
        echo '<button type="button" class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.alert(Mosaic.escapeHtml(\'<script>alert(1)</script>\'))">Escape HTML</button>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic.escapeHtml('<script>alert("xss")</script>');
// "&lt;script&gt;alert("xss")&lt;/script&gt;"

// Use when inserting user content into the DOM
element.innerHTML = Mosaic.escapeHtml(userInput);
CODE;
        echo $this->example( 'Escape HTML', $output, $code, 'Prevent XSS by escaping HTML entities:' );

        // Re-initialize Components
        ob_start();
        echo '<p style="margin-bottom: 15px;">After dynamically adding content, re-initialize Mosaic components:</p>';
        echo '<div style="background: #f5f5f5; padding: 15px; border-radius: 4px;">';
        echo '<code style="display: block; white-space: pre-wrap;">// After inserting new HTML
container.innerHTML = newContent;

// Re-initialize all components in container
Mosaic.init(container);
Mosaic.init(\'#my-container\');

// Or initialize specific features
Mosaic.initCopyButtons();</code>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Re-initialize after AJAX content load
fetch('/api/content')
    .then(response => response.text())
    .then(html => {
        container.innerHTML = html;
        Mosaic.init(container);  // Initialize components
    });
CODE;
        echo $this->example( 'Re-initialize Dynamic Content', $output, $code );

        echo '</div>';
        return ob_get_clean();
    }

    // =========================================================================
    // TOOLTIPS SECTION
    // =========================================================================
    private function render_tooltips_section() {
        ob_start();
        echo '<div class="mosaic-showcase">';

        // Basic Tooltips
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-lg" style="margin-bottom: 20px;">';
        echo '<button class="mosaic-btn mosaic-btn-secondary" data-mosaic-tooltip="This is a simple tooltip">Hover me</button>';
        echo '<button class="mosaic-btn mosaic-btn-primary" data-mosaic-tooltip="Another tooltip example">Primary button</button>';
        echo '<span data-mosaic-tooltip="Tooltips work on any element">Plain text with tooltip</span>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<!-- Simple data attribute tooltip -->
<button data-mosaic-tooltip="Tooltip text">Hover me</button>

<!-- Works on any element -->
<span data-mosaic-tooltip="Tooltip here">Text with tooltip</span>
CODE;
        echo $this->example( 'Basic Tooltips', $output, $code, 'Add data-mosaic-tooltip attribute to any element' );

        // Tooltip Positions
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-lg" style="margin-bottom: 20px; justify-content: center; padding: 40px 0;">';
        echo '<span class="mosaic-tooltip mosaic-tooltip-top">';
        echo '<button class="mosaic-btn mosaic-btn-secondary">Top</button>';
        echo '<span class="mosaic-tooltip-content">Tooltip on top</span>';
        echo '</span>';
        echo '<span class="mosaic-tooltip mosaic-tooltip-bottom">';
        echo '<button class="mosaic-btn mosaic-btn-secondary">Bottom</button>';
        echo '<span class="mosaic-tooltip-content">Tooltip on bottom</span>';
        echo '</span>';
        echo '<span class="mosaic-tooltip mosaic-tooltip-left">';
        echo '<button class="mosaic-btn mosaic-btn-secondary">Left</button>';
        echo '<span class="mosaic-tooltip-content">Tooltip on left</span>';
        echo '</span>';
        echo '<span class="mosaic-tooltip mosaic-tooltip-right">';
        echo '<button class="mosaic-btn mosaic-btn-secondary">Right</button>';
        echo '<span class="mosaic-tooltip-content">Tooltip on right</span>';
        echo '</span>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<!-- Position classes: mosaic-tooltip-top (default), bottom, left, right -->
<span class="mosaic-tooltip mosaic-tooltip-bottom">
    <button>Trigger</button>
    <span class="mosaic-tooltip-content">Tooltip text</span>
</span>
CODE;
        echo $this->example( 'Tooltip Positions', $output, $code, 'Use position classes for different placements' );

        // Tooltip Variants
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-lg" style="margin-bottom: 20px;">';
        echo '<span class="mosaic-tooltip">';
        echo '<button class="mosaic-btn mosaic-btn-secondary">Dark (Default)</button>';
        echo '<span class="mosaic-tooltip-content">Dark tooltip</span>';
        echo '</span>';
        echo '<span class="mosaic-tooltip mosaic-tooltip-light">';
        echo '<button class="mosaic-btn mosaic-btn-secondary">Light</button>';
        echo '<span class="mosaic-tooltip-content">Light tooltip with border</span>';
        echo '</span>';
        echo '<span class="mosaic-tooltip mosaic-tooltip-primary">';
        echo '<button class="mosaic-btn mosaic-btn-primary">Primary</button>';
        echo '<span class="mosaic-tooltip-content">Primary colored tooltip</span>';
        echo '</span>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<!-- Dark (default) -->
<span class="mosaic-tooltip">...</span>

<!-- Light variant -->
<span class="mosaic-tooltip mosaic-tooltip-light">...</span>

<!-- Primary variant -->
<span class="mosaic-tooltip mosaic-tooltip-primary">...</span>
CODE;
        echo $this->example( 'Tooltip Variants', $output, $code );

        // Tooltips on Icons
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-lg" style="margin-bottom: 20px; align-items: center;">';
        echo '<span class="mosaic-tooltip">';
        echo '<span class="dashicons dashicons-info" style="cursor: help; color: var(--mosaic-primary);"></span>';
        echo '<span class="mosaic-tooltip-content">Click for more information</span>';
        echo '</span>';
        echo '<span class="mosaic-tooltip">';
        echo '<span class="dashicons dashicons-editor-help" style="cursor: help; color: var(--mosaic-text-muted);"></span>';
        echo '<span class="mosaic-tooltip-content">Need help? Check the documentation</span>';
        echo '</span>';
        echo '<span class="mosaic-tooltip mosaic-tooltip-primary">';
        echo '<span class="dashicons dashicons-warning" style="cursor: help; color: var(--mosaic-warning);"></span>';
        echo '<span class="mosaic-tooltip-content">This action cannot be undone</span>';
        echo '</span>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<span class="mosaic-tooltip">
    <span class="dashicons dashicons-info"></span>
    <span class="mosaic-tooltip-content">Help text here</span>
</span>
CODE;
        echo $this->example( 'Tooltips on Icons', $output, $code, 'Great for help icons and info badges' );

        // Rich Content Tooltips
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-lg" style="margin-bottom: 20px;">';
        echo '<span class="mosaic-tooltip mosaic-tooltip-light">';
        echo '<button class="mosaic-btn mosaic-btn-secondary">Multi-line Tooltip</button>';
        echo '<span class="mosaic-tooltip-content" style="width: 200px; white-space: normal;">This is a longer tooltip that wraps to multiple lines for more detailed information.</span>';
        echo '</span>';
        echo '<span class="mosaic-tooltip mosaic-tooltip-light mosaic-tooltip-bottom">';
        echo '<button class="mosaic-btn mosaic-btn-secondary">With Formatting</button>';
        echo '<span class="mosaic-tooltip-content" style="width: 180px; white-space: normal;"><strong>Pro Tip:</strong> You can use HTML in tooltips for rich content.</span>';
        echo '</span>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<!-- Multi-line tooltip with custom width -->
<span class="mosaic-tooltip mosaic-tooltip-light">
    <button>Trigger</button>
    <span class="mosaic-tooltip-content" style="width: 200px; white-space: normal;">
        Longer text that wraps...
    </span>
</span>

<!-- With HTML formatting -->
<span class="mosaic-tooltip-content">
    <strong>Bold:</strong> Regular text
</span>
CODE;
        echo $this->example( 'Rich Content Tooltips', $output, $code );

        // JavaScript API
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-lg" style="margin-bottom: 20px;">';
        echo '<button class="mosaic-btn mosaic-btn-secondary" id="js-tooltip-demo">JS Controlled Tooltip</button>';
        echo '<button class="mosaic-btn mosaic-btn-primary" onclick="Mosaic.showTooltip(document.getElementById(\'js-tooltip-demo\'), \'Tooltip shown via JS!\', {position: \'top\', duration: 2000})">Show Tooltip</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Show tooltip programmatically
Mosaic.showTooltip(element, 'Message', {
    position: 'top',     // top, bottom, left, right
    duration: 2000,      // auto-hide after ms (0 = manual)
    variant: 'primary'   // dark, light, primary
});

// Initialize tooltip on element
Mosaic.tooltip(element, {
    content: 'Tooltip text',
    position: 'bottom'
});
CODE;
        echo $this->example( 'JavaScript API', $output, $code );

        echo '</div>';
        return ob_get_clean();
    }

    // =========================================================================
    // DROPDOWNS SECTION
    // =========================================================================
    private function render_dropdowns_section() {
        ob_start();
        echo '<div class="mosaic-showcase">';

        // Basic Dropdown
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-md" style="margin-bottom: 20px;">';
        echo '<div class="mosaic-dropdown" data-mosaic-dropdown>';
        echo '<button class="mosaic-btn mosaic-btn-secondary mosaic-dropdown-trigger">Options <span class="dashicons dashicons-arrow-down-alt2"></span></button>';
        echo '<div class="mosaic-dropdown-menu">';
        echo '<button class="mosaic-dropdown-item">View Details</button>';
        echo '<button class="mosaic-dropdown-item">Edit</button>';
        echo '<button class="mosaic-dropdown-item">Duplicate</button>';
        echo '</div></div>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<div class="mosaic-dropdown" data-mosaic-dropdown>
    <button class="mosaic-btn mosaic-btn-secondary mosaic-dropdown-trigger">
        Options <span class="dashicons dashicons-arrow-down-alt2"></span>
    </button>
    <div class="mosaic-dropdown-menu">
        <button class="mosaic-dropdown-item">View Details</button>
        <button class="mosaic-dropdown-item">Edit</button>
        <button class="mosaic-dropdown-item">Duplicate</button>
    </div>
</div>
CODE;
        echo $this->example( 'Basic Dropdown', $output, $code, 'Add data-mosaic-dropdown for auto-initialization' );

        // Dropdown with Icons
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-md" style="margin-bottom: 20px;">';
        echo '<div class="mosaic-dropdown" data-mosaic-dropdown>';
        echo '<button class="mosaic-btn mosaic-btn-primary mosaic-dropdown-trigger"><span class="dashicons dashicons-admin-generic"></span> Actions <span class="dashicons dashicons-arrow-down-alt2"></span></button>';
        echo '<div class="mosaic-dropdown-menu">';
        echo '<button class="mosaic-dropdown-item"><span class="dashicons dashicons-visibility"></span> View</button>';
        echo '<button class="mosaic-dropdown-item"><span class="dashicons dashicons-edit"></span> Edit</button>';
        echo '<button class="mosaic-dropdown-item"><span class="dashicons dashicons-admin-page"></span> Duplicate</button>';
        echo '<button class="mosaic-dropdown-item"><span class="dashicons dashicons-download"></span> Export</button>';
        echo '<div class="mosaic-dropdown-divider"></div>';
        echo '<button class="mosaic-dropdown-item mosaic-dropdown-item-danger"><span class="dashicons dashicons-trash"></span> Delete</button>';
        echo '</div></div>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<div class="mosaic-dropdown-menu">
    <button class="mosaic-dropdown-item">
        <span class="dashicons dashicons-edit"></span> Edit
    </button>
    <div class="mosaic-dropdown-divider"></div>
    <button class="mosaic-dropdown-item mosaic-dropdown-item-danger">
        <span class="dashicons dashicons-trash"></span> Delete
    </button>
</div>
CODE;
        echo $this->example( 'Dropdown with Icons & Dividers', $output, $code );

        // Dropdown Positions
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-md" style="margin-bottom: 20px;">';
        echo '<div class="mosaic-dropdown" data-mosaic-dropdown>';
        echo '<button class="mosaic-btn mosaic-btn-secondary mosaic-dropdown-trigger">Left (Default) <span class="dashicons dashicons-arrow-down-alt2"></span></button>';
        echo '<div class="mosaic-dropdown-menu">';
        echo '<button class="mosaic-dropdown-item">Option 1</button>';
        echo '<button class="mosaic-dropdown-item">Option 2</button>';
        echo '</div></div>';
        echo '<div class="mosaic-dropdown mosaic-dropdown-right" data-mosaic-dropdown>';
        echo '<button class="mosaic-btn mosaic-btn-secondary mosaic-dropdown-trigger">Right Aligned <span class="dashicons dashicons-arrow-down-alt2"></span></button>';
        echo '<div class="mosaic-dropdown-menu">';
        echo '<button class="mosaic-dropdown-item">Option 1</button>';
        echo '<button class="mosaic-dropdown-item">Option 2</button>';
        echo '</div></div>';
        echo '<div class="mosaic-dropdown mosaic-dropdown-up" data-mosaic-dropdown>';
        echo '<button class="mosaic-btn mosaic-btn-secondary mosaic-dropdown-trigger">Opens Up <span class="dashicons dashicons-arrow-up-alt2"></span></button>';
        echo '<div class="mosaic-dropdown-menu">';
        echo '<button class="mosaic-dropdown-item">Option 1</button>';
        echo '<button class="mosaic-dropdown-item">Option 2</button>';
        echo '</div></div>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<!-- Default (left aligned, opens down) -->
<div class="mosaic-dropdown">...</div>

<!-- Right aligned -->
<div class="mosaic-dropdown mosaic-dropdown-right">...</div>

<!-- Opens upward -->
<div class="mosaic-dropdown mosaic-dropdown-up">...</div>
CODE;
        echo $this->example( 'Dropdown Positions', $output, $code );

        // Dropdown with Headers
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-md" style="margin-bottom: 20px;">';
        echo '<div class="mosaic-dropdown" data-mosaic-dropdown>';
        echo '<button class="mosaic-btn mosaic-btn-secondary mosaic-dropdown-trigger">User Menu <span class="dashicons dashicons-arrow-down-alt2"></span></button>';
        echo '<div class="mosaic-dropdown-menu">';
        echo '<div class="mosaic-dropdown-header">Account</div>';
        echo '<button class="mosaic-dropdown-item"><span class="dashicons dashicons-admin-users"></span> Profile</button>';
        echo '<button class="mosaic-dropdown-item"><span class="dashicons dashicons-admin-settings"></span> Settings</button>';
        echo '<div class="mosaic-dropdown-header">Actions</div>';
        echo '<button class="mosaic-dropdown-item"><span class="dashicons dashicons-download"></span> Downloads</button>';
        echo '<button class="mosaic-dropdown-item"><span class="dashicons dashicons-clipboard"></span> Activity</button>';
        echo '<div class="mosaic-dropdown-divider"></div>';
        echo '<button class="mosaic-dropdown-item mosaic-dropdown-item-danger"><span class="dashicons dashicons-exit"></span> Sign Out</button>';
        echo '</div></div>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<div class="mosaic-dropdown-menu">
    <div class="mosaic-dropdown-header">Account</div>
    <button class="mosaic-dropdown-item">Profile</button>
    <button class="mosaic-dropdown-item">Settings</button>
    <div class="mosaic-dropdown-header">Actions</div>
    <button class="mosaic-dropdown-item">Downloads</button>
</div>
CODE;
        echo $this->example( 'Dropdown with Headers', $output, $code );

        // Dropdown with Checkboxes
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-md" style="margin-bottom: 20px;">';
        echo '<div class="mosaic-dropdown" data-mosaic-dropdown>';
        echo '<button class="mosaic-btn mosaic-btn-secondary mosaic-dropdown-trigger"><span class="dashicons dashicons-filter"></span> Filter <span class="dashicons dashicons-arrow-down-alt2"></span></button>';
        echo '<div class="mosaic-dropdown-menu">';
        echo '<div class="mosaic-dropdown-header">Status</div>';
        echo '<label class="mosaic-dropdown-check"><input type="checkbox" checked> Active</label>';
        echo '<label class="mosaic-dropdown-check"><input type="checkbox" checked> Pending</label>';
        echo '<label class="mosaic-dropdown-check"><input type="checkbox"> Inactive</label>';
        echo '<div class="mosaic-dropdown-header">Type</div>';
        echo '<label class="mosaic-dropdown-check"><input type="checkbox" checked> Standard</label>';
        echo '<label class="mosaic-dropdown-check"><input type="checkbox"> Premium</label>';
        echo '<label class="mosaic-dropdown-check"><input type="checkbox"> Enterprise</label>';
        echo '</div></div>';
        echo '<div class="mosaic-dropdown" data-mosaic-dropdown>';
        echo '<button class="mosaic-btn mosaic-btn-secondary mosaic-dropdown-trigger"><span class="dashicons dashicons-sort"></span> Sort By <span class="dashicons dashicons-arrow-down-alt2"></span></button>';
        echo '<div class="mosaic-dropdown-menu">';
        echo '<label class="mosaic-dropdown-radio"><input type="radio" name="sort" checked> Name (A-Z)</label>';
        echo '<label class="mosaic-dropdown-radio"><input type="radio" name="sort"> Name (Z-A)</label>';
        echo '<label class="mosaic-dropdown-radio"><input type="radio" name="sort"> Date (Newest)</label>';
        echo '<label class="mosaic-dropdown-radio"><input type="radio" name="sort"> Date (Oldest)</label>';
        echo '</div></div>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<!-- Checkboxes -->
<label class="mosaic-dropdown-check">
    <input type="checkbox" checked> Active
</label>

<!-- Radio buttons -->
<label class="mosaic-dropdown-radio">
    <input type="radio" name="sort" checked> Name (A-Z)
</label>
CODE;
        echo $this->example( 'Dropdown with Form Elements', $output, $code );

        // Dropdown Sizes
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-md" style="margin-bottom: 20px;">';
        echo '<div class="mosaic-dropdown mosaic-dropdown-sm" data-mosaic-dropdown>';
        echo '<button class="mosaic-btn mosaic-btn-sm mosaic-btn-secondary mosaic-dropdown-trigger">Small <span class="dashicons dashicons-arrow-down-alt2"></span></button>';
        echo '<div class="mosaic-dropdown-menu">';
        echo '<button class="mosaic-dropdown-item">Option A</button>';
        echo '<button class="mosaic-dropdown-item">Option B</button>';
        echo '</div></div>';
        echo '<div class="mosaic-dropdown" data-mosaic-dropdown>';
        echo '<button class="mosaic-btn mosaic-btn-secondary mosaic-dropdown-trigger">Default <span class="dashicons dashicons-arrow-down-alt2"></span></button>';
        echo '<div class="mosaic-dropdown-menu">';
        echo '<button class="mosaic-dropdown-item">Option A</button>';
        echo '<button class="mosaic-dropdown-item">Option B</button>';
        echo '</div></div>';
        echo '<div class="mosaic-dropdown mosaic-dropdown-lg" data-mosaic-dropdown>';
        echo '<button class="mosaic-btn mosaic-btn-secondary mosaic-dropdown-trigger">Large <span class="dashicons dashicons-arrow-down-alt2"></span></button>';
        echo '<div class="mosaic-dropdown-menu">';
        echo '<button class="mosaic-dropdown-item">Option A</button>';
        echo '<button class="mosaic-dropdown-item">Option B</button>';
        echo '</div></div>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<!-- Small -->
<div class="mosaic-dropdown mosaic-dropdown-sm">...</div>

<!-- Default -->
<div class="mosaic-dropdown">...</div>

<!-- Large -->
<div class="mosaic-dropdown mosaic-dropdown-lg">...</div>
CODE;
        echo $this->example( 'Dropdown Sizes', $output, $code );

        // Icon-only Dropdown
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-md" style="margin-bottom: 20px;">';
        echo '<div class="mosaic-dropdown" data-mosaic-dropdown>';
        echo '<button class="mosaic-btn mosaic-btn-icon mosaic-btn-secondary mosaic-dropdown-trigger"><span class="dashicons dashicons-ellipsis"></span></button>';
        echo '<div class="mosaic-dropdown-menu">';
        echo '<button class="mosaic-dropdown-item"><span class="dashicons dashicons-edit"></span> Edit</button>';
        echo '<button class="mosaic-dropdown-item"><span class="dashicons dashicons-admin-page"></span> Clone</button>';
        echo '<div class="mosaic-dropdown-divider"></div>';
        echo '<button class="mosaic-dropdown-item mosaic-dropdown-item-danger"><span class="dashicons dashicons-trash"></span> Delete</button>';
        echo '</div></div>';
        echo '<div class="mosaic-dropdown" data-mosaic-dropdown>';
        echo '<button class="mosaic-btn mosaic-btn-icon mosaic-btn-ghost mosaic-dropdown-trigger"><span class="dashicons dashicons-admin-generic"></span></button>';
        echo '<div class="mosaic-dropdown-menu">';
        echo '<button class="mosaic-dropdown-item">Settings</button>';
        echo '<button class="mosaic-dropdown-item">Preferences</button>';
        echo '</div></div>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<!-- Icon-only trigger -->
<div class="mosaic-dropdown" data-mosaic-dropdown>
    <button class="mosaic-btn mosaic-btn-icon mosaic-btn-secondary mosaic-dropdown-trigger">
        <span class="dashicons dashicons-ellipsis"></span>
    </button>
    <div class="mosaic-dropdown-menu">...</div>
</div>
CODE;
        echo $this->example( 'Icon-only Dropdowns', $output, $code, 'Perfect for table row actions and compact UIs' );

        // JavaScript API
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-md" style="margin-bottom: 20px;">';
        echo '<div class="mosaic-dropdown" id="js-dropdown-demo">';
        echo '<button class="mosaic-btn mosaic-btn-secondary mosaic-dropdown-trigger">JS Controlled <span class="dashicons dashicons-arrow-down-alt2"></span></button>';
        echo '<div class="mosaic-dropdown-menu">';
        echo '<button class="mosaic-dropdown-item">Item 1</button>';
        echo '<button class="mosaic-dropdown-item">Item 2</button>';
        echo '</div></div>';
        echo '<button class="mosaic-btn mosaic-btn-primary" onclick="Mosaic.dropdown(\'#js-dropdown-demo\').open()">Open</button>';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.dropdown(\'#js-dropdown-demo\').close()">Close</button>';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.dropdown(\'#js-dropdown-demo\').toggle()">Toggle</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Get dropdown instance
const dropdown = Mosaic.dropdown('#my-dropdown');

// Control methods
dropdown.open();
dropdown.close();
dropdown.toggle();

// Initialize all dropdowns
Mosaic.initDropdowns();

// Event handling
element.addEventListener('mosaic:dropdown:open', (e) => {
    console.log('Dropdown opened');
});
CODE;
        echo $this->example( 'JavaScript API', $output, $code );

        echo '</div>';
        return ob_get_clean();
    }

    // =========================================================================
    // PAGINATION SECTION
    // =========================================================================
    private function render_pagination_section() {
        ob_start();
        echo '<div class="mosaic-showcase">';

        // Basic Pagination
        ob_start();
        echo '<nav class="mosaic-pagination">';
        echo '<a href="#" class="mosaic-pagination-item mosaic-pagination-prev"><span class="dashicons dashicons-arrow-left-alt2"></span></a>';
        echo '<a href="#" class="mosaic-pagination-item">1</a>';
        echo '<a href="#" class="mosaic-pagination-item mosaic-active">2</a>';
        echo '<a href="#" class="mosaic-pagination-item">3</a>';
        echo '<a href="#" class="mosaic-pagination-item">4</a>';
        echo '<a href="#" class="mosaic-pagination-item">5</a>';
        echo '<a href="#" class="mosaic-pagination-item mosaic-pagination-next"><span class="dashicons dashicons-arrow-right-alt2"></span></a>';
        echo '</nav>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<nav class="mosaic-pagination">
    <a href="#" class="mosaic-pagination-item mosaic-pagination-prev">
        <span class="dashicons dashicons-arrow-left-alt2"></span>
    </a>
    <a href="#" class="mosaic-pagination-item">1</a>
    <a href="#" class="mosaic-pagination-item mosaic-active">2</a>
    <a href="#" class="mosaic-pagination-item">3</a>
    <a href="#" class="mosaic-pagination-item mosaic-pagination-next">
        <span class="dashicons dashicons-arrow-right-alt2"></span>
    </a>
</nav>
CODE;
        echo $this->example( 'Basic Pagination', $output, $code );

        // Pagination with Ellipsis
        ob_start();
        echo '<nav class="mosaic-pagination">';
        echo '<a href="#" class="mosaic-pagination-item mosaic-pagination-prev"><span class="dashicons dashicons-arrow-left-alt2"></span></a>';
        echo '<a href="#" class="mosaic-pagination-item">1</a>';
        echo '<span class="mosaic-pagination-ellipsis">...</span>';
        echo '<a href="#" class="mosaic-pagination-item">5</a>';
        echo '<a href="#" class="mosaic-pagination-item mosaic-active">6</a>';
        echo '<a href="#" class="mosaic-pagination-item">7</a>';
        echo '<span class="mosaic-pagination-ellipsis">...</span>';
        echo '<a href="#" class="mosaic-pagination-item">20</a>';
        echo '<a href="#" class="mosaic-pagination-item mosaic-pagination-next"><span class="dashicons dashicons-arrow-right-alt2"></span></a>';
        echo '</nav>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<nav class="mosaic-pagination">
    <a href="#" class="mosaic-pagination-item">1</a>
    <span class="mosaic-pagination-ellipsis">...</span>
    <a href="#" class="mosaic-pagination-item">5</a>
    <a href="#" class="mosaic-pagination-item mosaic-active">6</a>
    <a href="#" class="mosaic-pagination-item">7</a>
    <span class="mosaic-pagination-ellipsis">...</span>
    <a href="#" class="mosaic-pagination-item">20</a>
</nav>
CODE;
        echo $this->example( 'Pagination with Ellipsis', $output, $code, 'Use ellipsis for large page counts' );

        // Pagination with Text Buttons
        ob_start();
        echo '<nav class="mosaic-pagination">';
        echo '<a href="#" class="mosaic-pagination-item mosaic-pagination-prev"><span class="dashicons dashicons-arrow-left-alt2"></span> Previous</a>';
        echo '<a href="#" class="mosaic-pagination-item">1</a>';
        echo '<a href="#" class="mosaic-pagination-item mosaic-active">2</a>';
        echo '<a href="#" class="mosaic-pagination-item">3</a>';
        echo '<a href="#" class="mosaic-pagination-item mosaic-pagination-next">Next <span class="dashicons dashicons-arrow-right-alt2"></span></a>';
        echo '</nav>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<a href="#" class="mosaic-pagination-item mosaic-pagination-prev">
    <span class="dashicons dashicons-arrow-left-alt2"></span> Previous
</a>
<a href="#" class="mosaic-pagination-item mosaic-pagination-next">
    Next <span class="dashicons dashicons-arrow-right-alt2"></span>
</a>
CODE;
        echo $this->example( 'Pagination with Text Labels', $output, $code );

        // Compact Pagination
        ob_start();
        echo '<nav class="mosaic-pagination mosaic-pagination-compact">';
        echo '<a href="#" class="mosaic-pagination-item">1</a>';
        echo '<a href="#" class="mosaic-pagination-item mosaic-active">2</a>';
        echo '<a href="#" class="mosaic-pagination-item">3</a>';
        echo '<a href="#" class="mosaic-pagination-item">4</a>';
        echo '<a href="#" class="mosaic-pagination-item">5</a>';
        echo '</nav>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<nav class="mosaic-pagination mosaic-pagination-compact">
    <a href="#" class="mosaic-pagination-item">1</a>
    <a href="#" class="mosaic-pagination-item mosaic-active">2</a>
    <a href="#" class="mosaic-pagination-item">3</a>
</nav>
CODE;
        echo $this->example( 'Compact Pagination', $output, $code, 'Smaller padding for tight spaces' );

        // Pagination Sizes
        ob_start();
        echo '<p style="margin-bottom: 10px;"><strong>Small:</strong></p>';
        echo '<nav class="mosaic-pagination mosaic-pagination-sm" style="margin-bottom: 20px;">';
        echo '<a href="#" class="mosaic-pagination-item mosaic-pagination-prev"><span class="dashicons dashicons-arrow-left-alt2"></span></a>';
        echo '<a href="#" class="mosaic-pagination-item">1</a>';
        echo '<a href="#" class="mosaic-pagination-item mosaic-active">2</a>';
        echo '<a href="#" class="mosaic-pagination-item">3</a>';
        echo '<a href="#" class="mosaic-pagination-item mosaic-pagination-next"><span class="dashicons dashicons-arrow-right-alt2"></span></a>';
        echo '</nav>';
        echo '<p style="margin-bottom: 10px;"><strong>Default:</strong></p>';
        echo '<nav class="mosaic-pagination" style="margin-bottom: 20px;">';
        echo '<a href="#" class="mosaic-pagination-item mosaic-pagination-prev"><span class="dashicons dashicons-arrow-left-alt2"></span></a>';
        echo '<a href="#" class="mosaic-pagination-item">1</a>';
        echo '<a href="#" class="mosaic-pagination-item mosaic-active">2</a>';
        echo '<a href="#" class="mosaic-pagination-item">3</a>';
        echo '<a href="#" class="mosaic-pagination-item mosaic-pagination-next"><span class="dashicons dashicons-arrow-right-alt2"></span></a>';
        echo '</nav>';
        echo '<p style="margin-bottom: 10px;"><strong>Large:</strong></p>';
        echo '<nav class="mosaic-pagination mosaic-pagination-lg">';
        echo '<a href="#" class="mosaic-pagination-item mosaic-pagination-prev"><span class="dashicons dashicons-arrow-left-alt2"></span></a>';
        echo '<a href="#" class="mosaic-pagination-item">1</a>';
        echo '<a href="#" class="mosaic-pagination-item mosaic-active">2</a>';
        echo '<a href="#" class="mosaic-pagination-item">3</a>';
        echo '<a href="#" class="mosaic-pagination-item mosaic-pagination-next"><span class="dashicons dashicons-arrow-right-alt2"></span></a>';
        echo '</nav>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<!-- Small -->
<nav class="mosaic-pagination mosaic-pagination-sm">...</nav>

<!-- Default -->
<nav class="mosaic-pagination">...</nav>

<!-- Large -->
<nav class="mosaic-pagination mosaic-pagination-lg">...</nav>
CODE;
        echo $this->example( 'Pagination Sizes', $output, $code );

        // Pagination Alignment
        ob_start();
        echo '<p style="margin-bottom: 10px;"><strong>Left (default):</strong></p>';
        echo '<nav class="mosaic-pagination" style="margin-bottom: 20px;">';
        echo '<a href="#" class="mosaic-pagination-item">1</a>';
        echo '<a href="#" class="mosaic-pagination-item mosaic-active">2</a>';
        echo '<a href="#" class="mosaic-pagination-item">3</a>';
        echo '</nav>';
        echo '<p style="margin-bottom: 10px;"><strong>Center:</strong></p>';
        echo '<nav class="mosaic-pagination mosaic-pagination-center" style="margin-bottom: 20px;">';
        echo '<a href="#" class="mosaic-pagination-item">1</a>';
        echo '<a href="#" class="mosaic-pagination-item mosaic-active">2</a>';
        echo '<a href="#" class="mosaic-pagination-item">3</a>';
        echo '</nav>';
        echo '<p style="margin-bottom: 10px;"><strong>Right:</strong></p>';
        echo '<nav class="mosaic-pagination mosaic-pagination-right">';
        echo '<a href="#" class="mosaic-pagination-item">1</a>';
        echo '<a href="#" class="mosaic-pagination-item mosaic-active">2</a>';
        echo '<a href="#" class="mosaic-pagination-item">3</a>';
        echo '</nav>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<!-- Left (default) -->
<nav class="mosaic-pagination">...</nav>

<!-- Centered -->
<nav class="mosaic-pagination mosaic-pagination-center">...</nav>

<!-- Right aligned -->
<nav class="mosaic-pagination mosaic-pagination-right">...</nav>
CODE;
        echo $this->example( 'Pagination Alignment', $output, $code );

        // Disabled States
        ob_start();
        echo '<nav class="mosaic-pagination">';
        echo '<a href="#" class="mosaic-pagination-item mosaic-pagination-prev mosaic-disabled"><span class="dashicons dashicons-arrow-left-alt2"></span></a>';
        echo '<a href="#" class="mosaic-pagination-item mosaic-active">1</a>';
        echo '<a href="#" class="mosaic-pagination-item">2</a>';
        echo '<a href="#" class="mosaic-pagination-item">3</a>';
        echo '<a href="#" class="mosaic-pagination-item mosaic-pagination-next"><span class="dashicons dashicons-arrow-right-alt2"></span></a>';
        echo '</nav>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<!-- Disabled prev button on first page -->
<a href="#" class="mosaic-pagination-item mosaic-pagination-prev mosaic-disabled">
    <span class="dashicons dashicons-arrow-left-alt2"></span>
</a>
CODE;
        echo $this->example( 'Disabled States', $output, $code, 'Disable prev on first page, next on last page' );

        // With Page Info
        ob_start();
        echo '<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">';
        echo '<span class="mosaic-text-muted">Showing 11-20 of 156 results</span>';
        echo '<nav class="mosaic-pagination">';
        echo '<a href="#" class="mosaic-pagination-item mosaic-pagination-prev"><span class="dashicons dashicons-arrow-left-alt2"></span></a>';
        echo '<a href="#" class="mosaic-pagination-item">1</a>';
        echo '<a href="#" class="mosaic-pagination-item mosaic-active">2</a>';
        echo '<a href="#" class="mosaic-pagination-item">3</a>';
        echo '<span class="mosaic-pagination-ellipsis">...</span>';
        echo '<a href="#" class="mosaic-pagination-item">16</a>';
        echo '<a href="#" class="mosaic-pagination-item mosaic-pagination-next"><span class="dashicons dashicons-arrow-right-alt2"></span></a>';
        echo '</nav>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<div style="display: flex; justify-content: space-between; align-items: center;">
    <span>Showing 11-20 of 156 results</span>
    <nav class="mosaic-pagination">...</nav>
</div>
CODE;
        echo $this->example( 'Pagination with Info', $output, $code );

        echo '</div>';
        return ob_get_clean();
    }

    // =========================================================================
    // BREADCRUMBS SECTION
    // =========================================================================
    private function render_breadcrumbs_section() {
        ob_start();
        echo '<div class="mosaic-showcase">';

        // Basic Breadcrumbs
        ob_start();
        echo '<nav class="mosaic-breadcrumbs">';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Home</a></span>';
        echo '<span class="mosaic-breadcrumb-separator">/</span>';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Products</a></span>';
        echo '<span class="mosaic-breadcrumb-separator">/</span>';
        echo '<span class="mosaic-breadcrumb-item">Widget Pro</span>';
        echo '</nav>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<nav class="mosaic-breadcrumbs">
    <span class="mosaic-breadcrumb-item"><a href="#">Home</a></span>
    <span class="mosaic-breadcrumb-separator">/</span>
    <span class="mosaic-breadcrumb-item"><a href="#">Products</a></span>
    <span class="mosaic-breadcrumb-separator">/</span>
    <span class="mosaic-breadcrumb-item">Widget Pro</span>
</nav>
CODE;
        echo $this->example( 'Basic Breadcrumbs', $output, $code, 'Last item is current page (no link)' );

        // Auto Separator Breadcrumbs
        ob_start();
        echo '<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-auto">';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Dashboard</a></span>';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Settings</a></span>';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Security</a></span>';
        echo '<span class="mosaic-breadcrumb-item">Two-Factor Auth</span>';
        echo '</nav>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<!-- Auto separators with CSS -->
<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-auto">
    <span class="mosaic-breadcrumb-item"><a href="#">Dashboard</a></span>
    <span class="mosaic-breadcrumb-item"><a href="#">Settings</a></span>
    <span class="mosaic-breadcrumb-item">Security</span>
</nav>
CODE;
        echo $this->example( 'Auto Separator (Slash)', $output, $code, 'Separators added via CSS' );

        // Arrow Separator Breadcrumbs
        ob_start();
        echo '<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-arrows">';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Home</a></span>';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Library</a></span>';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Documents</a></span>';
        echo '<span class="mosaic-breadcrumb-item">Report.pdf</span>';
        echo '</nav>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-arrows">
    <span class="mosaic-breadcrumb-item"><a href="#">Home</a></span>
    <span class="mosaic-breadcrumb-item"><a href="#">Library</a></span>
    <span class="mosaic-breadcrumb-item">Documents</span>
</nav>
CODE;
        echo $this->example( 'Arrow Separators', $output, $code );

        // With Icons
        ob_start();
        echo '<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-auto">';
        echo '<span class="mosaic-breadcrumb-item"><a href="#"><span class="dashicons dashicons-admin-home"></span> Home</a></span>';
        echo '<span class="mosaic-breadcrumb-item"><a href="#"><span class="dashicons dashicons-admin-users"></span> Users</a></span>';
        echo '<span class="mosaic-breadcrumb-item"><span class="dashicons dashicons-admin-users"></span> John Doe</span>';
        echo '</nav>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-auto">
    <span class="mosaic-breadcrumb-item">
        <a href="#">
            <span class="dashicons dashicons-admin-home"></span> Home
        </a>
    </span>
    <span class="mosaic-breadcrumb-item">
        <a href="#">
            <span class="dashicons dashicons-admin-users"></span> Users
        </a>
    </span>
</nav>
CODE;
        echo $this->example( 'Breadcrumbs with Icons', $output, $code );

        // Background Variant
        ob_start();
        echo '<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-auto mosaic-breadcrumbs-bg">';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Dashboard</a></span>';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Analytics</a></span>';
        echo '<span class="mosaic-breadcrumb-item">Traffic Report</span>';
        echo '</nav>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-auto mosaic-breadcrumbs-bg">
    <span class="mosaic-breadcrumb-item"><a href="#">Dashboard</a></span>
    <span class="mosaic-breadcrumb-item">Analytics</span>
</nav>
CODE;
        echo $this->example( 'Background Variant', $output, $code, 'Adds subtle background and padding' );

        // Bordered Variant
        ob_start();
        echo '<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-arrows mosaic-breadcrumbs-bordered">';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Home</a></span>';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Category</a></span>';
        echo '<span class="mosaic-breadcrumb-item">Current Page</span>';
        echo '</nav>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-arrows mosaic-breadcrumbs-bordered">
    ...
</nav>
CODE;
        echo $this->example( 'Bordered Variant', $output, $code );

        // Combined Styles
        ob_start();
        echo '<p style="margin-bottom: 10px;"><strong>Arrows + Background:</strong></p>';
        echo '<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-arrows mosaic-breadcrumbs-bg" style="margin-bottom: 15px;">';
        echo '<span class="mosaic-breadcrumb-item"><a href="#"><span class="dashicons dashicons-admin-home"></span></a></span>';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Products</a></span>';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Electronics</a></span>';
        echo '<span class="mosaic-breadcrumb-item">Laptops</span>';
        echo '</nav>';
        echo '<p style="margin-bottom: 10px;"><strong>Auto + Bordered:</strong></p>';
        echo '<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-auto mosaic-breadcrumbs-bordered">';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Admin</a></span>';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Settings</a></span>';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">General</a></span>';
        echo '<span class="mosaic-breadcrumb-item">Appearance</span>';
        echo '</nav>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<!-- Arrows with background -->
<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-arrows mosaic-breadcrumbs-bg">
    ...
</nav>

<!-- Auto separators with border -->
<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-auto mosaic-breadcrumbs-bordered">
    ...
</nav>
CODE;
        echo $this->example( 'Combined Styles', $output, $code );

        // Custom Separators
        ob_start();
        echo '<p style="margin-bottom: 10px;"><strong>Chevron:</strong></p>';
        echo '<nav class="mosaic-breadcrumbs" style="margin-bottom: 15px;">';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Home</a></span>';
        echo '<span class="mosaic-breadcrumb-separator">›</span>';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Category</a></span>';
        echo '<span class="mosaic-breadcrumb-separator">›</span>';
        echo '<span class="mosaic-breadcrumb-item">Page</span>';
        echo '</nav>';
        echo '<p style="margin-bottom: 10px;"><strong>Bullet:</strong></p>';
        echo '<nav class="mosaic-breadcrumbs" style="margin-bottom: 15px;">';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Home</a></span>';
        echo '<span class="mosaic-breadcrumb-separator">•</span>';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Category</a></span>';
        echo '<span class="mosaic-breadcrumb-separator">•</span>';
        echo '<span class="mosaic-breadcrumb-item">Page</span>';
        echo '</nav>';
        echo '<p style="margin-bottom: 10px;"><strong>Pipe:</strong></p>';
        echo '<nav class="mosaic-breadcrumbs">';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Home</a></span>';
        echo '<span class="mosaic-breadcrumb-separator">|</span>';
        echo '<span class="mosaic-breadcrumb-item"><a href="#">Category</a></span>';
        echo '<span class="mosaic-breadcrumb-separator">|</span>';
        echo '<span class="mosaic-breadcrumb-item">Page</span>';
        echo '</nav>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<!-- Custom separator characters -->
<span class="mosaic-breadcrumb-separator">›</span>  <!-- Chevron -->
<span class="mosaic-breadcrumb-separator">•</span>  <!-- Bullet -->
<span class="mosaic-breadcrumb-separator">|</span>  <!-- Pipe -->
<span class="mosaic-breadcrumb-separator">→</span>  <!-- Arrow -->
CODE;
        echo $this->example( 'Custom Separators', $output, $code );

        echo '</div>';
        return ob_get_clean();
    }

    // =========================================================================
    // AVATARS SECTION
    // =========================================================================
    private function render_avatars_section() {
        ob_start();
        echo '<div class="mosaic-showcase">';

        // Avatar Sizes
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-lg" style="align-items: center; margin-bottom: 20px;">';
        echo '<div style="text-align: center;"><span class="mosaic-avatar mosaic-avatar-xs">XS</span><div class="mosaic-text-muted mosaic-text-sm" style="margin-top: 5px;">24px</div></div>';
        echo '<div style="text-align: center;"><span class="mosaic-avatar mosaic-avatar-sm">SM</span><div class="mosaic-text-muted mosaic-text-sm" style="margin-top: 5px;">32px</div></div>';
        echo '<div style="text-align: center;"><span class="mosaic-avatar">MD</span><div class="mosaic-text-muted mosaic-text-sm" style="margin-top: 5px;">40px</div></div>';
        echo '<div style="text-align: center;"><span class="mosaic-avatar mosaic-avatar-lg">LG</span><div class="mosaic-text-muted mosaic-text-sm" style="margin-top: 5px;">48px</div></div>';
        echo '<div style="text-align: center;"><span class="mosaic-avatar mosaic-avatar-xl">XL</span><div class="mosaic-text-muted mosaic-text-sm" style="margin-top: 5px;">64px</div></div>';
        echo '<div style="text-align: center;"><span class="mosaic-avatar mosaic-avatar-2xl">2X</span><div class="mosaic-text-muted mosaic-text-sm" style="margin-top: 5px;">96px</div></div>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<span class="mosaic-avatar mosaic-avatar-xs">XS</span>  <!-- 24px -->
<span class="mosaic-avatar mosaic-avatar-sm">SM</span>  <!-- 32px -->
<span class="mosaic-avatar">MD</span>                   <!-- 40px (default) -->
<span class="mosaic-avatar mosaic-avatar-lg">LG</span>  <!-- 48px -->
<span class="mosaic-avatar mosaic-avatar-xl">XL</span>  <!-- 64px -->
<span class="mosaic-avatar mosaic-avatar-2xl">2X</span> <!-- 96px -->
CODE;
        echo $this->example( 'Avatar Sizes', $output, $code );

        // Avatar Colors
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-md" style="align-items: center; margin-bottom: 20px;">';
        echo '<span class="mosaic-avatar">AB</span>';
        echo '<span class="mosaic-avatar mosaic-avatar-primary">CD</span>';
        echo '<span class="mosaic-avatar mosaic-avatar-success">EF</span>';
        echo '<span class="mosaic-avatar mosaic-avatar-warning">GH</span>';
        echo '<span class="mosaic-avatar mosaic-avatar-danger">IJ</span>';
        echo '<span class="mosaic-avatar mosaic-avatar-info">KL</span>';
        echo '<span class="mosaic-avatar mosaic-avatar-gray">MN</span>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<span class="mosaic-avatar">AB</span>                        <!-- Default -->
<span class="mosaic-avatar mosaic-avatar-primary">CD</span>  <!-- Primary blue -->
<span class="mosaic-avatar mosaic-avatar-success">EF</span>  <!-- Green -->
<span class="mosaic-avatar mosaic-avatar-warning">GH</span>  <!-- Orange -->
<span class="mosaic-avatar mosaic-avatar-danger">IJ</span>   <!-- Red -->
<span class="mosaic-avatar mosaic-avatar-info">KL</span>     <!-- Cyan -->
<span class="mosaic-avatar mosaic-avatar-gray">MN</span>     <!-- Gray -->
CODE;
        echo $this->example( 'Avatar Colors', $output, $code );

        // Avatars with Icons
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-md" style="align-items: center; margin-bottom: 20px;">';
        echo '<span class="mosaic-avatar mosaic-avatar-gray"><span class="dashicons dashicons-admin-users"></span></span>';
        echo '<span class="mosaic-avatar mosaic-avatar-primary"><span class="dashicons dashicons-businessman"></span></span>';
        echo '<span class="mosaic-avatar mosaic-avatar-success"><span class="dashicons dashicons-yes"></span></span>';
        echo '<span class="mosaic-avatar mosaic-avatar-warning"><span class="dashicons dashicons-star-filled"></span></span>';
        echo '<span class="mosaic-avatar mosaic-avatar-danger mosaic-avatar-lg"><span class="dashicons dashicons-admin-users"></span></span>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<span class="mosaic-avatar mosaic-avatar-gray">
    <span class="dashicons dashicons-admin-users"></span>
</span>
<span class="mosaic-avatar mosaic-avatar-primary">
    <span class="dashicons dashicons-businessman"></span>
</span>
CODE;
        echo $this->example( 'Avatars with Icons', $output, $code );

        // Avatars with Images
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-md" style="align-items: center; margin-bottom: 20px;">';
        echo '<span class="mosaic-avatar mosaic-avatar-sm"><img src="https://i.pravatar.cc/32?img=1" alt="User"></span>';
        echo '<span class="mosaic-avatar"><img src="https://i.pravatar.cc/40?img=2" alt="User"></span>';
        echo '<span class="mosaic-avatar mosaic-avatar-lg"><img src="https://i.pravatar.cc/48?img=3" alt="User"></span>';
        echo '<span class="mosaic-avatar mosaic-avatar-xl"><img src="https://i.pravatar.cc/64?img=4" alt="User"></span>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<span class="mosaic-avatar">
    <img src="path/to/image.jpg" alt="User Name">
</span>
<span class="mosaic-avatar mosaic-avatar-lg">
    <img src="path/to/image.jpg" alt="User Name">
</span>
CODE;
        echo $this->example( 'Avatars with Images', $output, $code );

        // Avatar Status Indicators
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-lg" style="align-items: center; margin-bottom: 20px;">';
        echo '<div style="text-align: center;"><span class="mosaic-avatar-wrapper"><span class="mosaic-avatar">JD</span><span class="mosaic-avatar-status mosaic-avatar-status-online"></span></span><div class="mosaic-text-muted mosaic-text-sm" style="margin-top: 5px;">Online</div></div>';
        echo '<div style="text-align: center;"><span class="mosaic-avatar-wrapper"><span class="mosaic-avatar mosaic-avatar-primary">AB</span><span class="mosaic-avatar-status mosaic-avatar-status-offline"></span></span><div class="mosaic-text-muted mosaic-text-sm" style="margin-top: 5px;">Offline</div></div>';
        echo '<div style="text-align: center;"><span class="mosaic-avatar-wrapper"><span class="mosaic-avatar mosaic-avatar-success">CD</span><span class="mosaic-avatar-status mosaic-avatar-status-busy"></span></span><div class="mosaic-text-muted mosaic-text-sm" style="margin-top: 5px;">Busy</div></div>';
        echo '<div style="text-align: center;"><span class="mosaic-avatar-wrapper"><span class="mosaic-avatar mosaic-avatar-warning">EF</span><span class="mosaic-avatar-status mosaic-avatar-status-away"></span></span><div class="mosaic-text-muted mosaic-text-sm" style="margin-top: 5px;">Away</div></div>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<span class="mosaic-avatar-wrapper">
    <span class="mosaic-avatar">JD</span>
    <span class="mosaic-avatar-status mosaic-avatar-status-online"></span>
</span>

<!-- Status options: online, offline, busy, away -->
CODE;
        echo $this->example( 'Status Indicators', $output, $code );

        // Avatar Groups
        ob_start();
        echo '<p style="margin-bottom: 15px;"><strong>Stacked Group:</strong></p>';
        echo '<div class="mosaic-avatar-group" style="margin-bottom: 20px;">';
        echo '<span class="mosaic-avatar mosaic-avatar-success">A</span>';
        echo '<span class="mosaic-avatar mosaic-avatar-primary">B</span>';
        echo '<span class="mosaic-avatar mosaic-avatar-warning">C</span>';
        echo '<span class="mosaic-avatar mosaic-avatar-danger">D</span>';
        echo '<span class="mosaic-avatar mosaic-avatar-info">E</span>';
        echo '</div>';
        echo '<p style="margin-bottom: 15px;"><strong>With Count:</strong></p>';
        echo '<div class="mosaic-avatar-group" style="margin-bottom: 20px;">';
        echo '<span class="mosaic-avatar mosaic-avatar-primary">JD</span>';
        echo '<span class="mosaic-avatar mosaic-avatar-success">AB</span>';
        echo '<span class="mosaic-avatar mosaic-avatar-warning">CD</span>';
        echo '<span class="mosaic-avatar-group-count">+5</span>';
        echo '</div>';
        echo '<p style="margin-bottom: 15px;"><strong>Large Group:</strong></p>';
        echo '<div class="mosaic-avatar-group">';
        echo '<span class="mosaic-avatar mosaic-avatar-lg mosaic-avatar-primary">A</span>';
        echo '<span class="mosaic-avatar mosaic-avatar-lg mosaic-avatar-success">B</span>';
        echo '<span class="mosaic-avatar mosaic-avatar-lg mosaic-avatar-warning">C</span>';
        echo '<span class="mosaic-avatar-group-count" style="width: 48px; height: 48px; font-size: 14px;">+12</span>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<!-- Basic group (avatars overlap) -->
<div class="mosaic-avatar-group">
    <span class="mosaic-avatar mosaic-avatar-success">A</span>
    <span class="mosaic-avatar mosaic-avatar-primary">B</span>
    <span class="mosaic-avatar mosaic-avatar-warning">C</span>
</div>

<!-- With remaining count -->
<div class="mosaic-avatar-group">
    <span class="mosaic-avatar">JD</span>
    <span class="mosaic-avatar">AB</span>
    <span class="mosaic-avatar-group-count">+5</span>
</div>
CODE;
        echo $this->example( 'Avatar Groups', $output, $code );

        // Avatar with Name
        ob_start();
        echo '<div style="display: flex; flex-direction: column; gap: 15px;">';
        echo '<div style="display: flex; align-items: center; gap: 12px;">';
        echo '<span class="mosaic-avatar mosaic-avatar-primary">JD</span>';
        echo '<div><div style="font-weight: 500;">John Doe</div><div class="mosaic-text-muted mosaic-text-sm">Administrator</div></div>';
        echo '</div>';
        echo '<div style="display: flex; align-items: center; gap: 12px;">';
        echo '<span class="mosaic-avatar-wrapper"><span class="mosaic-avatar mosaic-avatar-success">AS</span><span class="mosaic-avatar-status mosaic-avatar-status-online"></span></span>';
        echo '<div><div style="font-weight: 500;">Alice Smith</div><div class="mosaic-text-muted mosaic-text-sm">Editor • Online</div></div>';
        echo '</div>';
        echo '<div style="display: flex; align-items: center; gap: 12px;">';
        echo '<span class="mosaic-avatar mosaic-avatar-lg"><img src="https://i.pravatar.cc/48?img=5" alt="User"></span>';
        echo '<div><div style="font-weight: 500;">Bob Johnson</div><div class="mosaic-text-muted mosaic-text-sm">bob@example.com</div></div>';
        echo '</div>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<div style="display: flex; align-items: center; gap: 12px;">
    <span class="mosaic-avatar mosaic-avatar-primary">JD</span>
    <div>
        <div style="font-weight: 500;">John Doe</div>
        <div class="mosaic-text-muted mosaic-text-sm">Administrator</div>
    </div>
</div>
CODE;
        echo $this->example( 'Avatar with Name & Info', $output, $code );

        // Rounded Variants
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-lg" style="align-items: center; margin-bottom: 20px;">';
        echo '<div style="text-align: center;"><span class="mosaic-avatar mosaic-avatar-primary">AB</span><div class="mosaic-text-muted mosaic-text-sm" style="margin-top: 5px;">Circle</div></div>';
        echo '<div style="text-align: center;"><span class="mosaic-avatar mosaic-avatar-primary mosaic-avatar-rounded">CD</span><div class="mosaic-text-muted mosaic-text-sm" style="margin-top: 5px;">Rounded</div></div>';
        echo '<div style="text-align: center;"><span class="mosaic-avatar mosaic-avatar-primary mosaic-avatar-square">EF</span><div class="mosaic-text-muted mosaic-text-sm" style="margin-top: 5px;">Square</div></div>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<span class="mosaic-avatar">AB</span>                       <!-- Circle (default) -->
<span class="mosaic-avatar mosaic-avatar-rounded">CD</span> <!-- Rounded corners -->
<span class="mosaic-avatar mosaic-avatar-square">EF</span>  <!-- Square -->
CODE;
        echo $this->example( 'Shape Variants', $output, $code );

        echo '</div>';
        return ob_get_clean();
    }

    // =========================================================================
    // TOASTS SECTION
    // =========================================================================
    private function render_toasts_section() {
        ob_start();
        echo '<div class="mosaic-showcase">';

        // Basic Toasts
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-sm mosaic-flex-wrap">';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.toast(\'This is a default toast notification\')">Default</button>';
        echo '<button class="mosaic-btn mosaic-btn-success" onclick="Mosaic.toastSuccess(\'Operation completed successfully!\')">Success</button>';
        echo '<button class="mosaic-btn mosaic-btn-danger" onclick="Mosaic.toastError(\'Oops! Something went wrong.\')">Error</button>';
        echo '<button class="mosaic-btn mosaic-btn-warning" onclick="Mosaic.toastWarning(\'Please check your input.\')">Warning</button>';
        echo '<button class="mosaic-btn mosaic-btn-info" onclick="Mosaic.toastInfo(\'Here is some useful information.\')">Info</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Basic toast
Mosaic.toast('Message here');

// Typed toasts
Mosaic.toastSuccess('Operation completed!');
Mosaic.toastError('Something went wrong');
Mosaic.toastWarning('Please check your input');
Mosaic.toastInfo('Here is some info');
CODE;
        echo $this->example( 'Basic Toast Types', $output, $code );

        // Toasts with Titles
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-sm mosaic-flex-wrap">';
        echo '<button class="mosaic-btn mosaic-btn-success" onclick="Mosaic.toastSuccess(\'Your file has been uploaded and is ready to share.\', {title: \'Upload Complete\'})">Success with Title</button>';
        echo '<button class="mosaic-btn mosaic-btn-danger" onclick="Mosaic.toastError(\'Unable to connect to the server. Please try again.\', {title: \'Connection Error\'})">Error with Title</button>';
        echo '<button class="mosaic-btn mosaic-btn-info" onclick="Mosaic.toastInfo(\'A new version is available. Refresh to update.\', {title: \'Update Available\'})">Info with Title</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic.toastSuccess('Your file has been uploaded.', {
    title: 'Upload Complete'
});

Mosaic.toastError('Unable to connect to server.', {
    title: 'Connection Error'
});
CODE;
        echo $this->example( 'Toasts with Titles', $output, $code );

        // Toast Duration
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-sm mosaic-flex-wrap">';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.toast(\'This disappears quickly\', {duration: 2000})">2 Seconds</button>';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.toast(\'Default duration\', {duration: 4000})">4 Seconds (Default)</button>';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.toast(\'Longer display time\', {duration: 8000})">8 Seconds</button>';
        echo '<button class="mosaic-btn mosaic-btn-primary" onclick="Mosaic.toast(\'This won\\\'t auto-close\', {duration: 0, type: \'warning\', title: \'Persistent\'})">Persistent (No Auto-close)</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Quick toast (2 seconds)
Mosaic.toast('Quick message', { duration: 2000 });

// Default (4 seconds)
Mosaic.toast('Normal message');

// Longer (8 seconds)
Mosaic.toast('Important message', { duration: 8000 });

// Persistent (never auto-closes)
Mosaic.toast('Must be manually closed', { duration: 0 });
CODE;
        echo $this->example( 'Toast Duration', $output, $code );

        // Progress Bar
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-sm mosaic-flex-wrap">';
        echo '<button class="mosaic-btn mosaic-btn-info" onclick="Mosaic.toastInfo(\'Processing your request...\', {progress: true, duration: 5000})">Info with Progress</button>';
        echo '<button class="mosaic-btn mosaic-btn-success" onclick="Mosaic.toastSuccess(\'Uploading files...\', {progress: true, duration: 6000, title: \'Upload in Progress\'})">Success with Progress</button>';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.toast(\'Syncing data...\', {progress: true, duration: 4000})">Default with Progress</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic.toast('Processing...', {
    progress: true,
    duration: 5000
});

Mosaic.toastSuccess('Uploading files...', {
    progress: true,
    duration: 6000,
    title: 'Upload in Progress'
});
CODE;
        echo $this->example( 'Progress Bar', $output, $code, 'Visual indicator of remaining time' );

        // Light Variant
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-sm mosaic-flex-wrap">';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.toast(\'Light default toast\', {variant: \'light\'})">Light Default</button>';
        echo '<button class="mosaic-btn mosaic-btn-success" onclick="Mosaic.toastSuccess(\'Light success toast\', {variant: \'light\'})">Light Success</button>';
        echo '<button class="mosaic-btn mosaic-btn-danger" onclick="Mosaic.toastError(\'Light error toast\', {variant: \'light\', title: \'Error\'})">Light Error</button>';
        echo '<button class="mosaic-btn mosaic-btn-warning" onclick="Mosaic.toastWarning(\'Light warning toast\', {variant: \'light\'})">Light Warning</button>';
        echo '<button class="mosaic-btn mosaic-btn-info" onclick="Mosaic.toastInfo(\'Light info toast\', {variant: \'light\', title: \'Notice\'})">Light Info</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Light variant (white background)
Mosaic.toast('Message', { variant: 'light' });

Mosaic.toastSuccess('Success!', {
    variant: 'light',
    title: 'Complete'
});
CODE;
        echo $this->example( 'Light Variant', $output, $code, 'Lighter background for subtle notifications' );

        // Toast with Actions
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-sm mosaic-flex-wrap">';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.toast(\'Item deleted\', {type: \'info\', actions: [{label: \'Undo\', onClick: function(){ Mosaic.toastSuccess(\'Restored!\'); }}]})">With Undo Action</button>';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.toast(\'New message received\', {type: \'info\', title: \'Notification\', actions: [{label: \'View\', onClick: function(){ Mosaic.toastInfo(\'Opening message...\'); }}, {label: \'Dismiss\'}]})">Multiple Actions</button>';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.toastWarning(\'Your session expires soon\', {title: \'Warning\', duration: 0, actions: [{label: \'Extend Session\', onClick: function(){ Mosaic.toastSuccess(\'Session extended!\'); }}, {label: \'Logout\', onClick: function(){ Mosaic.toastInfo(\'Logging out...\'); }}]})">Persistent with Actions</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Single action
Mosaic.toast('Item deleted', {
    type: 'info',
    actions: [{
        label: 'Undo',
        onClick: () => restoreItem()
    }]
});

// Multiple actions
Mosaic.toast('New message', {
    title: 'Notification',
    actions: [
        { label: 'View', onClick: () => viewMessage() },
        { label: 'Dismiss' }  // Just closes toast
    ]
});
CODE;
        echo $this->example( 'Toasts with Actions', $output, $code );

        // Toast Positions
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-sm mosaic-flex-wrap">';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.setToastPosition(\'top-right\'); Mosaic.toast(\'Top Right (Default)\')">Top Right</button>';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.setToastPosition(\'top-left\'); Mosaic.toast(\'Top Left\')">Top Left</button>';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.setToastPosition(\'top-center\'); Mosaic.toast(\'Top Center\')">Top Center</button>';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.setToastPosition(\'bottom-right\'); Mosaic.toast(\'Bottom Right\')">Bottom Right</button>';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.setToastPosition(\'bottom-left\'); Mosaic.toast(\'Bottom Left\')">Bottom Left</button>';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.setToastPosition(\'bottom-center\'); Mosaic.toast(\'Bottom Center\')">Bottom Center</button>';
        echo '<button class="mosaic-btn mosaic-btn-primary" onclick="Mosaic.setToastPosition(\'top-right\'); Mosaic.toast(\'Reset to Top Right\')">Reset Position</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Set position for all toasts
Mosaic.setToastPosition('top-right');    // default
Mosaic.setToastPosition('top-left');
Mosaic.setToastPosition('top-center');
Mosaic.setToastPosition('bottom-right');
Mosaic.setToastPosition('bottom-left');
Mosaic.setToastPosition('bottom-center');
CODE;
        echo $this->example( 'Toast Positions', $output, $code );

        // Custom Icons
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-sm mosaic-flex-wrap">';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.toast(\'File downloaded\', {icon: \'download\'})">Download Icon</button>';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.toast(\'Email sent\', {icon: \'email\', type: \'success\'})">Email Icon</button>';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.toast(\'Settings updated\', {icon: \'admin-generic\'})">Settings Icon</button>';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.toast(\'User logged in\', {icon: \'admin-users\', type: \'info\'})">User Icon</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Use any Dashicon
Mosaic.toast('File downloaded', { icon: 'download' });
Mosaic.toast('Email sent', { icon: 'email', type: 'success' });
Mosaic.toast('Settings updated', { icon: 'admin-generic' });
CODE;
        echo $this->example( 'Custom Icons', $output, $code, 'Use any WordPress Dashicon name' );

        // Programmatic Control
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-sm mosaic-flex-wrap">';
        echo '<button class="mosaic-btn mosaic-btn-primary" id="persistent-toast-btn" onclick="window.persistentToast = Mosaic.toast(\'This toast stays until closed\', {duration: 0, closable: false, type: \'info\', title: \'Persistent Toast\'}); this.disabled = true;">Create Persistent Toast</button>';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="if(window.persistentToast) { window.persistentToast.close(); document.getElementById(\'persistent-toast-btn\').disabled = false; }">Close Programmatically</button>';
        echo '<button class="mosaic-btn mosaic-btn-danger" onclick="Mosaic.clearToasts()">Clear All Toasts</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Store reference to control later
const toast = Mosaic.toast('Processing...', {
    duration: 0,      // Persistent
    closable: false   // No X button
});

// Close programmatically when done
toast.close();

// Clear all toasts
Mosaic.clearToasts();
CODE;
        echo $this->example( 'Programmatic Control', $output, $code );

        // Callback on Close
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-sm mosaic-flex-wrap">';
        echo '<button class="mosaic-btn mosaic-btn-secondary" onclick="Mosaic.toast(\'Close me to see the callback\', {onClose: function(){ Mosaic.toastInfo(\'onClose callback fired!\', {duration: 2000}); }})">Toast with Callback</button>';
        echo '<button class="mosaic-btn mosaic-btn-success" onclick="Mosaic.toastSuccess(\'Action logged\', {title: \'Logged\', onClose: function(){ console.log(\'Toast closed at\', new Date().toISOString()); Mosaic.toastInfo(\'Check console for log\', {duration: 2000}); }})">Log on Close</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic.toast('Message', {
    onClose: () => {
        console.log('Toast was closed');
        // Perform cleanup, analytics, etc.
    }
});
CODE;
        echo $this->example( 'Callback on Close', $output, $code );

        // Full Example
        ob_start();
        echo '<div class="mosaic-flex mosaic-gap-sm mosaic-flex-wrap">';
        echo '<button class="mosaic-btn mosaic-btn-primary" onclick="Mosaic.toast(\'New comment on your post from John Doe\', {type: \'info\', title: \'New Comment\', duration: 8000, closable: true, progress: true, icon: \'admin-comments\', variant: \'light\', actions: [{label: \'View\', onClick: function(){ Mosaic.toastInfo(\'Opening comment...\'); }}, {label: \'Dismiss\', closeOnClick: true}], onClose: function(){ console.log(\'Comment notification dismissed\'); }})">Full Featured Toast</button>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic.toast('New comment on your post', {
    type: 'info',
    title: 'New Comment',
    duration: 8000,
    closable: true,
    progress: true,
    icon: 'admin-comments',
    variant: 'light',
    actions: [
        {
            label: 'View',
            onClick: () => window.location.href = '/comments'
        },
        {
            label: 'Dismiss',
            closeOnClick: true
        }
    ],
    onClose: () => {
        console.log('Notification dismissed');
    }
});
CODE;
        echo $this->example( 'Full Featured Example', $output, $code );

        echo '</div>';
        return ob_get_clean();
    }

    private function render_tags_section() {
        ob_start();
        echo '<div class="mosaic-showcase">';

        // Basic Tag Editor
        ob_start();
        echo '<div id="tags-basic" data-mosaic-tags data-tags="JavaScript,React,Node.js" data-placeholder="Add a skill..."></div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<div id="my-tags"
     data-mosaic-tags
     data-tags="JavaScript,React,Node.js"
     data-placeholder="Add a skill...">
</div>
CODE;
        echo $this->example( 'Basic Tag Editor', $output, $code, 'Type and press Enter or comma to add tags. Click X to remove.' );

        // With Typeahead Suggestions
        ob_start();
        echo '<div id="tags-suggestions"></div>';
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Mosaic.tagEditor("#tags-suggestions", {
                    tags: ["Python"],
                    suggestions: ["JavaScript", "TypeScript", "Python", "Ruby", "Go", "Rust", "Java", "C++", "C#", "PHP", "Swift", "Kotlin"],
                    placeholder: "Start typing a language..."
                });
            });
        </script>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic.tagEditor('#container', {
    tags: ['Python'],
    suggestions: [
        'JavaScript', 'TypeScript', 'Python',
        'Ruby', 'Go', 'Rust', 'Java'
    ],
    placeholder: 'Start typing...'
});
CODE;
        echo $this->example( 'With Typeahead Suggestions', $output, $code, 'Autocomplete as you type from a predefined list' );

        // Show All on Focus
        ob_start();
        echo '<div id="tags-show-all"></div>';
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Mosaic.tagEditor("#tags-show-all", {
                    tags: [],
                    suggestions: ["Technology", "Design", "Business", "Marketing", "Finance", "Healthcare", "Education", "Entertainment"],
                    showAllOnFocus: true,
                    placeholder: "Click to see all categories..."
                });
            });
        </script>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic.tagEditor('#container', {
    suggestions: ['Technology', 'Design', 'Business', 'Marketing'],
    showAllOnFocus: true,
    placeholder: 'Click to see all...'
});
CODE;
        echo $this->example( 'Show All on Focus', $output, $code, 'Display all available options when input is focused' );

        // Restricted to Suggestions Only
        ob_start();
        echo '<div id="tags-restricted"></div>';
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Mosaic.tagEditor("#tags-restricted", {
                    tags: ["Engineering"],
                    suggestions: ["Engineering", "Sales", "Marketing", "Finance", "Human Resources", "Legal", "Operations"],
                    allowFreeText: false,
                    showAllOnFocus: true,
                    placeholder: "Select a department..."
                });
            });
        </script>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic.tagEditor('#container', {
    suggestions: ['Engineering', 'Sales', 'Marketing'],
    allowFreeText: false,  // Only allow tags from list
    showAllOnFocus: true
});
CODE;
        echo $this->example( 'Restricted Input', $output, $code, 'Only allow tags from the predefined suggestions list' );

        // Color Variants
        ob_start();
        echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">';
        echo '<div><label class="mosaic-label">Primary</label><div data-mosaic-tags data-tags="Primary,Tags" data-variant="primary"></div></div>';
        echo '<div><label class="mosaic-label">Success</label><div data-mosaic-tags data-tags="Approved,Complete" data-variant="success"></div></div>';
        echo '<div><label class="mosaic-label">Warning</label><div data-mosaic-tags data-tags="Pending,Review" data-variant="warning"></div></div>';
        echo '<div><label class="mosaic-label">Critical</label><div data-mosaic-tags data-tags="Urgent,Blocked" data-variant="critical"></div></div>';
        echo '<div><label class="mosaic-label">Info</label><div data-mosaic-tags data-tags="Note,Info" data-variant="info"></div></div>';
        echo '<div><label class="mosaic-label">Default</label><div data-mosaic-tags data-tags="Neutral,Tags" data-variant="default"></div></div>';
        echo '</div>';
        $output = ob_get_clean();
        $code = <<<'CODE'
<div data-mosaic-tags
     data-tags="Tag1,Tag2"
     data-variant="success">
</div>

// Or via JavaScript
Mosaic.tagEditor('#container', {
    variant: 'success'  // primary, success, warning, critical, info, default
});
CODE;
        echo $this->example( 'Color Variants', $output, $code );

        // Read-only Mode
        ob_start();
        echo '<div id="tags-readonly"></div>';
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Mosaic.tagEditor("#tags-readonly", {
                    tags: ["Published", "Featured", "Verified"],
                    variant: "success",
                    readOnly: true
                });
            });
        </script>';
        $output = ob_get_clean();
        $code = <<<'CODE'
Mosaic.tagEditor('#container', {
    tags: ['Published', 'Featured', 'Verified'],
    variant: 'success',
    readOnly: true
});

// Or via data attribute
<div data-mosaic-tags
     data-tags="Tag1,Tag2"
     data-read-only="true">
</div>
CODE;
        echo $this->example( 'Read-only Mode', $output, $code, 'Display tags without editing capability' );

        // Validation
        ob_start();
        echo '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">';
        echo '<div><label class="mosaic-label">Max 5 Tags</label><div data-mosaic-tags data-tags="One,Two,Three" data-max-tags="5" data-placeholder="Max 5 tags..."></div></div>';
        echo '<div><label class="mosaic-label">Max 10 Characters</label><div id="tags-maxlen"></div></div>';
        echo '</div>';
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Mosaic.tagEditor("#tags-maxlen", {
                    tags: ["short"],
                    maxLength: 10,
                    placeholder: "Max 10 chars per tag..."
                });
            });
        </script>';
        $output = ob_get_clean();
        $code = <<<'CODE'
// Limit number of tags
Mosaic.tagEditor('#container', {
    maxTags: 5
});

// Limit tag length
Mosaic.tagEditor('#container', {
    maxLength: 10
});

// Via data attributes
<div data-mosaic-tags data-max-tags="5"></div>
CODE;
        echo $this->example( 'Validation', $output, $code );

        // JavaScript API
        ob_start();
        echo '<div id="tags-api"></div>';
        echo '<div class="mosaic-flex mosaic-gap-sm mosaic-mt-md">';
        echo '<button class="mosaic-btn mosaic-btn-primary mosaic-btn-sm" onclick="window.apiTagEditor.add(\'Tag\' + Math.floor(Math.random()*100))">Add Random</button>';
        echo '<button class="mosaic-btn mosaic-btn-secondary mosaic-btn-sm" onclick="var t=window.apiTagEditor.getTags(); if(t.length) window.apiTagEditor.remove(t[t.length-1])">Remove Last</button>';
        echo '<button class="mosaic-btn mosaic-btn-secondary mosaic-btn-sm" onclick="window.apiTagEditor.removeAll()">Clear All</button>';
        echo '<button class="mosaic-btn mosaic-btn-secondary mosaic-btn-sm" onclick="alert(JSON.stringify(window.apiTagEditor.getTags()))">Get Tags</button>';
        echo '</div>';
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                window.apiTagEditor = Mosaic.tagEditor("#tags-api", {
                    tags: ["example", "tags"],
                    variant: "primary"
                });
            });
        </script>';
        $output = ob_get_clean();
        $code = <<<'CODE'
const editor = Mosaic.tagEditor('#container', {
    tags: ['initial'],
    onAdd: (tag) => console.log('Added:', tag),
    onRemove: (tag) => console.log('Removed:', tag),
    onChange: (tags) => console.log('Tags:', tags)
});

// API Methods
editor.add('new-tag');
editor.remove('old-tag');
editor.getTags();         // ['current', 'tags']
editor.setTags(['a','b']);
editor.removeAll();
editor.hasTag('test');    // true/false
editor.setSuggestions(['x','y','z']);
editor.disable();
editor.enable();
editor.focus();
CODE;
        echo $this->example( 'JavaScript API', $output, $code );

        echo '</div>';
        return ob_get_clean();
    }

}
