# Tabs

Tab navigation with overflow handling, mobile select, and hash navigation support.

## PHP Usage

```php
$tabs = new Mosaic_Tabs( array(
    'id'              => 'my-tabs',
    'hash_navigation' => true,  // Persist active tab in URL
) );

$tabs->add_tab( 'overview', 'Overview', '<p>Overview content</p>', array(
    'icon' => 'admin-home',
) );

$tabs->add_tab( 'settings', 'Settings', '<p>Settings content</p>', array(
    'icon'  => 'admin-settings',
    'count' => 5,  // Show badge with count
) );

$tabs->add_tab( 'advanced', 'Advanced', '<p>Advanced content</p>' );

$tabs->set_active( 'overview' );

echo $tabs->render();
```

## Options

| Option | Default | Description |
|--------|---------|-------------|
| `id` | `''` | HTML ID for the container |
| `class` | `''` | Additional CSS classes |
| `style` | `''` | Tab style: `''` (default), `pills`, `vertical` |
| `mobile_select` | `true` | Show select dropdown on mobile |
| `overflow_menu` | `true` | Show "More" menu when tabs overflow |
| `hash_navigation` | `false` | Persist active tab in URL hash |

## Tab Options

```php
$tabs->add_tab( 'key', 'Label', 'Content', array(
    'icon'   => 'dashicons-name',  // Dashicon name
    'count'  => 10,                // Badge count
    'active' => true,              // Set as active
) );
```

## Tab Styles

### Default (Underline)

Standard horizontal tabs with underline indicator:

```php
$tabs = new Mosaic_Tabs();  // Default style
```

### Pills

Compact, rounded tabs in a pill container:

```php
$tabs = new Mosaic_Tabs( array( 'style' => 'pills' ) );
```

Pills are best for:
- Secondary navigation within a section
- Filtering or segmented controls
- Compact interfaces

### Vertical

Side-by-side layout with tabs on the left:

```php
$tabs = new Mosaic_Tabs( array( 'style' => 'vertical' ) );
```

Vertical tabs are best for:
- Settings pages with many sections
- Sidebars with navigation
- Long lists of categories

**Note:** Mobile select is disabled for vertical tabs.

## Hash Navigation

Enable URL persistence so users stay on the same tab after refresh:

```php
$tabs = new Mosaic_Tabs( array( 'hash_navigation' => true ) );
```

- URL updates to `#tab-id` when switching tabs
- On page load, activates tab matching URL hash
- Browser back/forward buttons work

**Note:** Only enable hash navigation on one set of tabs. Nested tabs with hash navigation will conflict.

## Nested Tabs

Tabs can be nested inside other tabs:

```php
// Outer tabs
$outer = new Mosaic_Tabs( array( 'hash_navigation' => true ) );

// Inner tabs (no hash navigation to avoid conflicts)
$inner = new Mosaic_Tabs();
$inner->add_tab( 'general', 'General', '<p>General settings</p>' );
$inner->add_tab( 'advanced', 'Advanced', '<p>Advanced settings</p>' );

$outer->add_tab( 'overview', 'Overview', '<p>Overview</p>' );
$outer->add_tab( 'settings', 'Settings', $inner->render() );

echo $outer->render();
```

## JavaScript API

```javascript
// Initialize manually
const tabs = Mosaic.tabs('#my-tabs', {
    hashNavigation: true,
    onChange: (current, previous) => {
        console.log('Switched from', previous, 'to', current);
    }
});

// Programmatic control
tabs.activate('settings');
```

### Events

```javascript
document.querySelector('#my-tabs').addEventListener('mosaic:tab:change', (e) => {
    console.log('Tab changed:', e.detail.current);
    console.log('Previous tab:', e.detail.previous);
});
```

## CSS Classes

| Class | Description |
|-------|-------------|
| `.mosaic-tabs-container` | Main container |
| `.mosaic-tabs-wrapper` | Tab buttons wrapper |
| `.mosaic-tabs` | Tab button list |
| `.mosaic-tab` | Individual tab button |
| `.mosaic-tab-active` | Active tab button |
| `.mosaic-tab-content` | Tab content panel |
| `.mosaic-tab-content-active` | Visible content panel |
| `.mosaic-tabs-overflow` | Overflow "More" menu container |

## Overflow Behavior

When tabs don't fit in the available width:
1. Extra tabs move to a "More" dropdown menu
2. Clicking "More" shows the hidden tabs
3. Selecting a tab from the dropdown activates it

The overflow recalculates on window resize.
