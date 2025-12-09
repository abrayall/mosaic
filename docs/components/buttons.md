# Buttons

Mosaic provides consistent button styling that integrates well with the WordPress admin.

## Basic Usage

### HTML

```html
<button class="mosaic-btn mosaic-btn-primary">Primary Button</button>
<button class="mosaic-btn mosaic-btn-secondary">Secondary Button</button>
<button class="mosaic-btn mosaic-btn-danger">Danger Button</button>
<button class="mosaic-btn mosaic-btn-success">Success Button</button>
<button class="mosaic-btn mosaic-btn-warning">Warning Button</button>
<button class="mosaic-btn mosaic-btn-ghost">Ghost Button</button>
<button class="mosaic-btn mosaic-btn-link">Link Button</button>
```

### PHP

```php
// Simple button
echo Mosaic::button( 'Save', array( 'variant' => 'primary' ) );

// Button with icon
echo Mosaic::button( 'Add New', array(
    'variant' => 'primary',
    'icon'    => 'plus-alt',
) );

// Button as link
echo Mosaic::button( 'View Details', array(
    'variant' => 'secondary',
    'href'    => '/details',
) );

// Disabled button
echo Mosaic::button( 'Unavailable', array(
    'variant'  => 'secondary',
    'disabled' => true,
) );
```

## Variants

| Variant | Class | Usage |
|---------|-------|-------|
| Primary | `.mosaic-btn-primary` | Main actions |
| Secondary | `.mosaic-btn-secondary` | Secondary actions |
| Danger | `.mosaic-btn-danger` | Destructive actions |
| Success | `.mosaic-btn-success` | Positive actions |
| Warning | `.mosaic-btn-warning` | Cautionary actions |
| Ghost | `.mosaic-btn-ghost` | Subtle actions |
| Link | `.mosaic-btn-link` | Text-style buttons |

## Sizes

```html
<button class="mosaic-btn mosaic-btn-primary mosaic-btn-sm">Small</button>
<button class="mosaic-btn mosaic-btn-primary">Default</button>
<button class="mosaic-btn mosaic-btn-primary mosaic-btn-lg">Large</button>
```

| Size | Class | Padding |
|------|-------|---------|
| Small | `.mosaic-btn-sm` | 4px 8px |
| Default | - | 6px 16px |
| Large | `.mosaic-btn-lg` | 10px 24px |

## Icons

### Icon with Text

```html
<button class="mosaic-btn mosaic-btn-primary">
    <span class="dashicons dashicons-plus-alt"></span>
    Add New
</button>
```

### Icon Position

```php
// Icon on left (default)
echo Mosaic::button( 'Edit', array(
    'icon'     => 'edit',
    'icon_pos' => 'left',
) );

// Icon on right
echo Mosaic::button( 'Next', array(
    'icon'     => 'arrow-right-alt',
    'icon_pos' => 'right',
) );
```

### Icon-Only Button

```html
<button class="mosaic-btn mosaic-btn-secondary mosaic-btn-icon">
    <span class="dashicons dashicons-trash"></span>
</button>
```

## States

### Loading

```html
<button class="mosaic-btn mosaic-btn-primary mosaic-btn-loading">
    Saving...
</button>
```

```php
echo Mosaic::button( 'Processing', array(
    'variant' => 'primary',
    'loading' => true,
) );
```

### Disabled

```html
<button class="mosaic-btn mosaic-btn-primary" disabled>Disabled</button>
```

## Button Groups

### Connected Group

```html
<div class="mosaic-btn-group">
    <button class="mosaic-btn mosaic-btn-secondary">Left</button>
    <button class="mosaic-btn mosaic-btn-secondary">Center</button>
    <button class="mosaic-btn mosaic-btn-secondary">Right</button>
</div>
```

### Spaced Group

```html
<div class="mosaic-btn-group-spaced">
    <button class="mosaic-btn mosaic-btn-secondary">Cancel</button>
    <button class="mosaic-btn mosaic-btn-primary">Save</button>
</div>
```

## Options Reference

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `type` | string | `'button'` | Button type attribute |
| `variant` | string | `'secondary'` | Button style variant |
| `size` | string | `''` | Size: 'sm', 'lg' |
| `icon` | string | `''` | Dashicon name |
| `icon_pos` | string | `'left'` | Icon position |
| `class` | string | `''` | Additional CSS classes |
| `id` | string | `''` | HTML id attribute |
| `disabled` | bool | `false` | Disabled state |
| `loading` | bool | `false` | Loading state |
| `href` | string | `''` | Render as link |
| `target` | string | `''` | Link target |
| `attrs` | array | `array()` | Extra HTML attributes |
