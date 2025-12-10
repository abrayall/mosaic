# Badges

Status badges, labels, tags, and indicators for displaying status information.

## Basic Badge

```html
<span class="mosaic-badge mosaic-badge-default">Default</span>
<span class="mosaic-badge mosaic-badge-primary">Primary</span>
<span class="mosaic-badge mosaic-badge-success">Success</span>
<span class="mosaic-badge mosaic-badge-warning">Warning</span>
<span class="mosaic-badge mosaic-badge-critical">Critical</span>
<span class="mosaic-badge mosaic-badge-info">Info</span>
```

## Badge Variants

### Light (Default)

Subtle background with colored text:

```html
<span class="mosaic-badge mosaic-badge-success">Active</span>
```

### Solid

Filled background with white text:

```html
<span class="mosaic-badge mosaic-badge-solid mosaic-badge-success">Active</span>
<span class="mosaic-badge mosaic-badge-solid mosaic-badge-critical">Error</span>
```

### With Icon

```html
<span class="mosaic-badge mosaic-badge-success">
    <span class="dashicons dashicons-yes"></span>
    Connected
</span>
```

## Health Badge

Larger, more prominent badge for health/status displays:

```html
<span class="mosaic-health-badge mosaic-health-badge-healthy">
    <span class="dashicons dashicons-yes-alt"></span>
    Healthy
</span>

<span class="mosaic-health-badge mosaic-health-badge-warning">
    <span class="dashicons dashicons-warning"></span>
    Warning
</span>

<span class="mosaic-health-badge mosaic-health-badge-critical">
    <span class="dashicons dashicons-dismiss"></span>
    Critical
</span>

<span class="mosaic-health-badge mosaic-health-badge-unknown">
    <span class="dashicons dashicons-editor-help"></span>
    Unknown
</span>
```

## Status Dot

Simple colored dot indicator:

```html
<span class="mosaic-status-dot mosaic-status-dot-success"></span>
<span class="mosaic-status-dot mosaic-status-dot-warning"></span>
<span class="mosaic-status-dot mosaic-status-dot-critical"></span>
<span class="mosaic-status-dot mosaic-status-dot-inactive"></span>
```

### Pulsing Animation

```html
<span class="mosaic-status-dot mosaic-status-dot-success mosaic-status-dot-pulse"></span>
```

## Count Badge

Circular badge for displaying counts:

```html
<span class="mosaic-count-badge">5</span>
<span class="mosaic-count-badge mosaic-count-badge-primary">12</span>
<span class="mosaic-count-badge mosaic-count-badge-success">99+</span>
<span class="mosaic-count-badge mosaic-count-badge-critical">3</span>
```

## Tag

Removable tag with close button:

```html
<span class="mosaic-tag">
    WordPress
    <button type="button" class="mosaic-tag-remove">
        <span class="dashicons dashicons-no-alt"></span>
    </button>
</span>
```

## Version Badge

For displaying version numbers:

```html
<span class="mosaic-version-badge">v1.2.3</span>
<span class="mosaic-version-badge mosaic-version-badge-current">v2.0.0</span>
<span class="mosaic-version-badge mosaic-version-badge-outdated">v1.0.0</span>
```

## Badge Group

Group multiple badges together:

```html
<div class="mosaic-badge-group">
    <span class="mosaic-badge mosaic-badge-primary">PHP</span>
    <span class="mosaic-badge mosaic-badge-primary">MySQL</span>
    <span class="mosaic-badge mosaic-badge-primary">WordPress</span>
</div>
```

## CSS Classes

| Class | Description |
|-------|-------------|
| `.mosaic-badge` | Base badge styling |
| `.mosaic-badge-default` | Neutral/gray badge |
| `.mosaic-badge-primary` | Primary color badge |
| `.mosaic-badge-success` | Green success badge |
| `.mosaic-badge-warning` | Yellow warning badge |
| `.mosaic-badge-critical` | Red error/critical badge |
| `.mosaic-badge-info` | Blue info badge |
| `.mosaic-badge-solid` | Solid filled variant |
| `.mosaic-health-badge` | Larger health indicator |
| `.mosaic-status-dot` | Simple dot indicator |
| `.mosaic-status-dot-pulse` | Pulsing animation |
| `.mosaic-count-badge` | Circular count badge |
| `.mosaic-tag` | Removable tag |
| `.mosaic-version-badge` | Version number badge |
| `.mosaic-badge-group` | Badge container |
