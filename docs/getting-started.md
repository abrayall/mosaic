# Getting Started with Mosaic

Mosaic is a comprehensive UI design system for WordPress plugins. It provides consistent styling, JavaScript utilities, and PHP helper classes for building professional admin interfaces.

## Plugin Starter Template

Copy these files to create a new plugin using Mosaic.

### Directory Structure

```
your-plugin/
├── your-plugin.php
├── includes/
│   └── class-your-plugin-admin.php
└── plugin.properties
```

### plugin.properties (for Wordsmith)

```properties
name=Your Plugin
slug=your-plugin
description=A WordPress plugin using Mosaic
author=Your Name
license=GPL-2.0+
main=your-plugin.php
include=includes/**/*.php
libraries:
  - name: mosaic
    url: ../mosaic/build
```

### your-plugin.php

```php
<?php
/**
 * Plugin Name: Your Plugin
 * Description: A WordPress plugin using Mosaic
 * Version: 1.0.0
 * Author: Your Name
 * License: GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'YOUR_PLUGIN_VERSION', '1.0.0' );
define( 'YOUR_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'YOUR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Load Mosaic
require_once YOUR_PLUGIN_PATH . 'mosaic/includes/class-mosaic.php';
require_once YOUR_PLUGIN_PATH . 'mosaic/includes/class-mosaic-table.php';
require_once YOUR_PLUGIN_PATH . 'mosaic/includes/class-mosaic-card.php';
require_once YOUR_PLUGIN_PATH . 'mosaic/includes/class-mosaic-tabs.php';
require_once YOUR_PLUGIN_PATH . 'mosaic/includes/class-mosaic-form.php';

Mosaic::load( YOUR_PLUGIN_PATH . 'mosaic' );

// Load plugin files
require_once YOUR_PLUGIN_PATH . 'includes/class-your-plugin-admin.php';

// Initialize
add_action( 'plugins_loaded', function() {
    if ( is_admin() ) {
        Your_Plugin_Admin::instance();
    }
});
```

### includes/class-your-plugin-admin.php

```php
<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Your_Plugin_Admin {

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
            'Your Plugin',
            'Your Plugin',
            'manage_options',
            'your-plugin',
            array( $this, 'render_page' ),
            'dashicons-admin-generic',
            30
        );
    }

    public function enqueue_assets( $hook ) {
        if ( 'toplevel_page_your-plugin' !== $hook ) {
            return;
        }

        // Enqueue Mosaic CSS
        wp_enqueue_style(
            'mosaic',
            YOUR_PLUGIN_URL . 'mosaic/' . Mosaic::css(),
            array(),
            Mosaic::version()
        );

        // Enqueue Mosaic JS modules
        $modules = array( 'dialog', 'tabs', 'clipboard', 'ajax', 'filters', 'theme' );
        foreach ( $modules as $module ) {
            wp_enqueue_script(
                'mosaic-' . $module,
                YOUR_PLUGIN_URL . 'mosaic/assets/js/modules/' . $module . '.js',
                array(),
                Mosaic::version(),
                true
            );
        }

        // Enqueue main Mosaic JS
        wp_enqueue_script(
            'mosaic',
            YOUR_PLUGIN_URL . 'mosaic/' . Mosaic::js(),
            array(),
            Mosaic::version(),
            true
        );

        // Pass data to JavaScript
        wp_localize_script( 'mosaic', 'mosaicContext', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'your_plugin_nonce' ),
        ) );
    }

    public function render_page() {
        echo Mosaic::page_start( 'Your Plugin', array(
            'icon' => 'admin-generic',
        ) );

        // Your content here
        echo '<p>Welcome to your plugin!</p>';

        // Example: Stats cards
        echo Mosaic_Card::stats_grid( array(
            array( 'label' => 'Total', 'value' => 42, 'icon' => 'admin-site', 'variant' => 'primary' ),
            array( 'label' => 'Active', 'value' => 38, 'icon' => 'yes-alt', 'variant' => 'success' ),
        ) );

        // Example: Table
        $table = new Mosaic_Table( array( 'striped' => true ) );
        $table->set_columns( array(
            'name'   => 'Name',
            'status' => 'Status',
        ) );
        $table->add_row( array(
            'name'   => 'Example Item',
            'status' => Mosaic::health_badge( 'healthy' ),
        ) );
        echo $table->render();

        echo Mosaic::page_end();
    }
}
```

### Deploy

```bash
cd your-plugin
wordsmith deploy
```

Your plugin will appear in WordPress admin with the Mosaic UI.

---

## Directory Structure

Mosaic should be placed as a sibling directory to your plugins:

```
/your-workspace/
├── mosaic/           # Design system
├── constellation/    # Your plugins directory
│   ├── plugin-a/
│   └── plugin-b/
```

## How to Use Mosaic in a Plugin

### 1. Include the PHP classes you need

```php
// In your plugin's main file
$mosaic_path = __DIR__ . '/mosaic';

require_once $mosaic_path . '/includes/class-mosaic.php';
require_once $mosaic_path . '/includes/class-mosaic-table.php';
require_once $mosaic_path . '/includes/class-mosaic-card.php';
require_once $mosaic_path . '/includes/class-mosaic-tabs.php';
require_once $mosaic_path . '/includes/class-mosaic-form.php';

// Initialize Mosaic
Mosaic::load( $mosaic_path );
```

### 2. Enqueue the CSS and JavaScript

```php
add_action( 'admin_enqueue_scripts', function( $hook ) {
    // Only load on your plugin's pages
    if ( strpos( $hook, 'your-plugin' ) === false ) {
        return;
    }

    $mosaic_url = plugin_dir_url( __FILE__ ) . 'mosaic/';
    $version    = Mosaic::version();

    // Enqueue styles
    wp_enqueue_style( 'mosaic', $mosaic_url . Mosaic::css(), array(), $version );

    // Enqueue scripts
    wp_enqueue_script( 'mosaic-dialog', $mosaic_url . 'assets/js/modules/dialog.js', array(), $version, true );
    wp_enqueue_script( 'mosaic-tabs', $mosaic_url . 'assets/js/modules/tabs.js', array(), $version, true );
    wp_enqueue_script( 'mosaic-clipboard', $mosaic_url . 'assets/js/modules/clipboard.js', array(), $version, true );
    wp_enqueue_script( 'mosaic-ajax', $mosaic_url . 'assets/js/modules/ajax.js', array( 'jquery' ), $version, true );
    wp_enqueue_script( 'mosaic-filters', $mosaic_url . 'assets/js/modules/filters.js', array(), $version, true );
    wp_enqueue_script( 'mosaic', $mosaic_url . Mosaic::js(), array(), $version, true );

    // Pass data to JavaScript (for AJAX, etc.)
    wp_localize_script( 'mosaic', 'mosaicContext', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'your_plugin_nonce' ),
    ) );
} );
```

### 3. Wrap your page in the `.mosaic` container

```php
function my_plugin_page() {
    ?>
    <div class="wrap mosaic">
        <h1>My Plugin</h1>

        <?php
        // Your Mosaic components here
        $table = new Mosaic_Table();
        $table->set_columns( array( 'name' => 'Name', 'status' => 'Status' ) );
        $table->add_row( array(
            'name'   => 'Example',
            'status' => Mosaic::health_badge( 'healthy' ),
        ) );
        echo $table->render();
        ?>
    </div>
    <?php
}
```

The `.mosaic` class:
- Applies `box-sizing: border-box` to all children
- Scopes WordPress button/table overrides to your plugin only
- Provides the foundation for theming

Or use the helper method:

```php
echo Mosaic::page_header( 'My Plugin', array(
    'actions' => Mosaic::button( 'Add New', array( 'variant' => 'primary' ) ),
) );
// Returns: <div class="wrap mosaic">...
```

That's it. No magic - just include what you need and enqueue the assets.

## Quick Start

### PHP Components

```php
// Load component classes as needed
require_once $mosaic_path . '/includes/class-mosaic-table.php';

// Create a table
$table = new Mosaic_Table( array( 'striped' => true ) );
$table->set_columns( array(
    'name'   => 'Name',
    'status' => 'Status',
    'actions' => array( 'label' => 'Actions', 'align' => 'right' ),
) );
$table->add_row( array(
    'name'   => 'Example Site',
    'status' => Mosaic::health_badge( 'healthy' ),
    'actions' => Mosaic::button( 'Edit', array( 'variant' => 'secondary', 'size' => 'sm' ) ),
) );
echo $table->render();

// Create stat cards
echo Mosaic_Card::stats_grid( array(
    array( 'label' => 'Total Sites', 'value' => 42, 'icon' => 'admin-site', 'variant' => 'primary' ),
    array( 'label' => 'Healthy', 'value' => 38, 'icon' => 'yes-alt', 'variant' => 'success' ),
    array( 'label' => 'Warnings', 'value' => 4, 'icon' => 'warning', 'variant' => 'warning' ),
) );

// Create tabs
$tabs = new Mosaic_Tabs();
$tabs->add_tab( 'overview', 'Overview', '<p>Overview content here</p>', array( 'icon' => 'admin-home' ) );
$tabs->add_tab( 'settings', 'Settings', '<p>Settings content here</p>', array( 'icon' => 'admin-settings' ) );
$tabs->set_active( 'overview' );
echo $tabs->render();

// Create a form
$form = new Mosaic_Form( array( 'nonce_action' => 'my_plugin_save' ) );
$form->add_text( 'site_name', 'Site Name', array( 'required' => true ) );
$form->add_select( 'status', 'Status', array(
    'active'   => 'Active',
    'inactive' => 'Inactive',
) );
$form->add_toggle( 'notifications', 'Enable Notifications' );
$form->add_html( Mosaic_Form::actions( 'Save Settings' ) );
echo $form->render();
```

### JavaScript

```javascript
// Dialogs
Mosaic.confirm('Are you sure?').then(confirmed => {
    if (confirmed) {
        // User clicked confirm
    }
});

Mosaic.alert('Operation complete!');
Mosaic.error('Something went wrong');
Mosaic.prompt('Enter your name:').then(name => {
    console.log('Name:', name);
});

// AJAX
Mosaic.ajax('my_plugin_action', { id: 123 }, {
    loadingElement: '#my-button'
}).then(response => {
    console.log(response);
}).catch(error => {
    Mosaic.error(error.message);
});

// Clipboard
Mosaic.copy('Text to copy', document.getElementById('copy-btn'));

// Tabs (auto-initialized with data-mosaic-tabs attribute)
const tabs = Mosaic.tabs('#my-tabs', {
    onChange: (current, previous) => {
        console.log('Tab changed:', previous, '->', current);
    }
});
```

### CSS Classes

```html
<!-- Page Container -->
<div class="wrap mosaic">
    <!-- All Mosaic content goes here -->
</div>

<!-- Buttons -->
<button class="mosaic-btn mosaic-btn-primary">Primary</button>
<button class="mosaic-btn mosaic-btn-secondary">Secondary</button>
<button class="mosaic-btn mosaic-btn-danger">Danger</button>

<!-- Badges -->
<span class="mosaic-badge mosaic-badge-success">Active</span>
<span class="mosaic-health-badge mosaic-health-badge-healthy">
    <span class="dashicons dashicons-yes-alt"></span> Healthy
</span>

<!-- Cards -->
<div class="mosaic-card">
    <div class="mosaic-card-header">
        <h3 class="mosaic-card-header-title">Card Title</h3>
    </div>
    <div class="mosaic-card-body">
        Card content here
    </div>
</div>

<!-- Grid -->
<div class="mosaic-stats-grid">
    <div class="mosaic-stat-card mosaic-stat-card-primary">
        <h3 class="mosaic-stat-card-label">Total</h3>
        <div class="mosaic-stat-card-value">42</div>
    </div>
</div>
```

## File Structure

```
mosaic/
├── assets/
│   ├── css/
│   │   ├── variables.css      # Design tokens
│   │   ├── base.css           # Typography, layout
│   │   ├── utilities.css      # Utility classes
│   │   ├── components/        # Component styles
│   │   └── mosaic.css         # Combined bundle
│   └── js/
│       ├── modules/           # JS modules
│       └── mosaic.js          # Main bundle
├── includes/
│   ├── class-mosaic.php       # Main class (config + UI)
│   ├── class-mosaic-table.php
│   ├── class-mosaic-card.php
│   ├── class-mosaic-tabs.php
│   └── class-mosaic-form.php
├── docs/                      # Documentation
└── mosaic.properties          # Mosaic metadata
```

## Next Steps

- [JavaScript API](javascript.md)
- [Colors & Design Tokens](colors.md)
- [Typography](typography.md)
- [Components](components/)
