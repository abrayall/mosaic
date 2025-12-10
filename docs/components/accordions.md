# Accordions

Collapsible content panels for organizing and revealing information.

## PHP Usage

```php
$accordion = new Mosaic_Accordion( array(
    'id'       => 'my-accordion',
    'multiple' => false,  // Allow multiple items open
) );

$accordion->add_item( 'General Settings', '<p>General content here</p>', array(
    'icon' => 'admin-settings',
    'open' => true,  // Open by default
) );

$accordion->add_item( 'Advanced Options', '<p>Advanced content here</p>' );

$accordion->add_item( 'Help & Support', '<p>Support content here</p>', array(
    'icon' => 'editor-help',
) );

echo $accordion->render();
```

## Options

| Option | Default | Description |
|--------|---------|-------------|
| `id` | `''` | HTML ID for the accordion |
| `class` | `''` | Additional CSS classes |
| `multiple` | `false` | Allow multiple items to be open simultaneously |
| `flush` | `false` | Remove outer border styling |

## Item Options

```php
$accordion->add_item( 'Title', 'Content', array(
    'icon' => 'dashicons-name',  // Dashicon name
    'open' => true,              // Open by default
) );
```

## Styles

### Default

Standard accordion with borders:

```php
$accordion = new Mosaic_Accordion();
```

### Flush (No Borders)

Borderless variant for embedding in other containers:

```php
$accordion = new Mosaic_Accordion( array( 'flush' => true ) );
```

### Multiple Open

Allow users to open multiple sections:

```php
$accordion = new Mosaic_Accordion( array( 'multiple' => true ) );
```

## CSS Classes

| Class | Description |
|-------|-------------|
| `.mosaic-accordion` | Main container |
| `.mosaic-accordion-flush` | Flush style (no outer border) |
| `.mosaic-accordion-item` | Individual item |
| `.mosaic-accordion-item-open` | Open item state |
| `.mosaic-accordion-header` | Clickable header |
| `.mosaic-accordion-title` | Title text |
| `.mosaic-accordion-icon` | Expand/collapse icon |
| `.mosaic-accordion-content` | Collapsible content area |
| `.mosaic-accordion-body` | Content padding wrapper |

## JavaScript

Accordions are auto-initialized when using the PHP class. The JavaScript handles:

- Click events on headers
- Smooth height animations
- Multiple open state management
- Initial open state for items with `mosaic-accordion-item-open` class
