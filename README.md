# Mosaic

A comprehensive UI design system for WordPress plugins.

## Overview

Mosaic provides consistent styling, JavaScript utilities, and PHP helper classes for building professional WordPress admin interfaces. It's designed to be shared across multiple plugins while maintaining a unified look and feel.

## Features

- **CSS Framework**: Design tokens, utility classes, and component styles
- **JavaScript Modules**: Dialogs, tabs, clipboard, AJAX, and filtering
- **PHP Helpers**: Fluent builders for tables, forms, cards, and tabs
- **Documentation**: Comprehensive guides and component references

## Quick Start

```php
// 1. Include Mosaic
$mosaic_path = __DIR__ . '/mosaic';
require_once $mosaic_path . '/includes/class-mosaic.php';
require_once $mosaic_path . '/includes/class-mosaic-table.php';

Mosaic::load( $mosaic_path );

// 2. Enqueue assets
add_action( 'admin_enqueue_scripts', function() {
    $mosaic_url = plugin_dir_url( __FILE__ ) . 'mosaic/';
    $version    = Mosaic::version();

    wp_enqueue_style( 'mosaic', $mosaic_url . Mosaic::css(), array(), $version );
    wp_enqueue_script( 'mosaic', $mosaic_url . Mosaic::js(), array(), $version, true );
} );

// 3. Use the components
$table = new Mosaic_Table();
$table->set_columns( array( 'name' => 'Name', 'status' => 'Status' ) );
$table->add_row( array(
    'name'   => 'Example',
    'status' => Mosaic::health_badge( 'healthy' ),
) );
echo $table->render();
```

## Components

| Component | CSS | JavaScript | PHP |
|-----------|-----|------------|-----|
| Buttons | Yes | - | `Mosaic::button()` |
| Tables | Yes | - | `Mosaic_Table` |
| Cards | Yes | - | `Mosaic_Card` |
| Tabs | Yes | `Mosaic.tabs()` | `Mosaic_Tabs` |
| Forms | Yes | - | `Mosaic_Form` |
| Badges | Yes | - | `Mosaic::badge()` |
| Dialogs | Yes | `Mosaic.confirm()` | - |
| AJAX | - | `Mosaic.ajax()` | - |
| Clipboard | - | `Mosaic.copy()` | - |
| Progress | Yes | - | `Mosaic::progress()` |
| Loaders | Yes | - | `Mosaic::spinner()` |

## Directory Structure

```
mosaic/
├── assets/
│   ├── css/              # Stylesheets
│   │   ├── mosaic.css    # Main bundle (imports all)
│   │   ├── variables.css # Design tokens
│   │   └── components/   # Individual components
│   └── js/
│       ├── mosaic.js     # Main bundle
│       └── modules/      # Individual modules
├── includes/             # PHP classes
│   ├── class-mosaic.php       # Main class (config + UI)
│   ├── class-mosaic-table.php
│   ├── class-mosaic-card.php
│   ├── class-mosaic-tabs.php
│   └── class-mosaic-form.php
├── docs/                 # Documentation
└── mosaic.properties     # Metadata
```

## Documentation

See the [docs/](docs/) directory for detailed documentation:

- [Getting Started](docs/getting-started.md)
- [JavaScript API](docs/javascript.md)
- [Colors & Design Tokens](docs/colors.md)
- [Typography](docs/typography.md)
- [Components](docs/components/)

## Integration

Mosaic is designed to be shared across plugins. Place it as a sibling directory:

```
your-workspace/
├── mosaic/           # This design system
└── your-plugin/      # Your plugin
```

Then reference it via relative path in your plugin.

## License

GPL-2.0+
