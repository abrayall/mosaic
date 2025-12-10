# Progress Indicators

Progress bars and step indicators for showing completion status.

## Progress Bar

Basic progress bar:

```html
<div class="mosaic-progress">
    <div class="mosaic-progress-bar" style="width: 60%"></div>
</div>
```

### Variants

```html
<!-- Default (primary) -->
<div class="mosaic-progress">
    <div class="mosaic-progress-bar" style="width: 60%"></div>
</div>

<!-- Success -->
<div class="mosaic-progress mosaic-progress-success">
    <div class="mosaic-progress-bar" style="width: 100%"></div>
</div>

<!-- Warning -->
<div class="mosaic-progress mosaic-progress-warning">
    <div class="mosaic-progress-bar" style="width: 75%"></div>
</div>

<!-- Critical -->
<div class="mosaic-progress mosaic-progress-critical">
    <div class="mosaic-progress-bar" style="width: 90%"></div>
</div>

<!-- Info -->
<div class="mosaic-progress mosaic-progress-info">
    <div class="mosaic-progress-bar" style="width: 50%"></div>
</div>
```

### Sizes

```html
<div class="mosaic-progress mosaic-progress-sm">...</div>  <!-- 4px -->
<div class="mosaic-progress">...</div>                     <!-- 8px (default) -->
<div class="mosaic-progress mosaic-progress-lg">...</div>  <!-- 12px -->
<div class="mosaic-progress mosaic-progress-xl">...</div>  <!-- 20px -->
```

### With Label

Progress bar with centered percentage label:

```html
<div class="mosaic-progress-labeled">
    <div class="mosaic-progress">
        <div class="mosaic-progress-bar" style="width: 65%"></div>
    </div>
    <span class="mosaic-progress-label">65%</span>
</div>
```

### With Header

Progress bar with title and value:

```html
<div class="mosaic-progress-wrapper">
    <div class="mosaic-progress-header">
        <span class="mosaic-progress-title">Storage Used</span>
        <span class="mosaic-progress-value">6.5 GB / 10 GB</span>
    </div>
    <div class="mosaic-progress">
        <div class="mosaic-progress-bar" style="width: 65%"></div>
    </div>
</div>
```

### Striped

Striped progress bar pattern:

```html
<div class="mosaic-progress mosaic-progress-striped">
    <div class="mosaic-progress-bar" style="width: 60%"></div>
</div>
```

### Animated Stripes

Animated striped progress:

```html
<div class="mosaic-progress mosaic-progress-striped mosaic-progress-animated">
    <div class="mosaic-progress-bar" style="width: 60%"></div>
</div>
```

### Indeterminate

For unknown completion time:

```html
<div class="mosaic-progress mosaic-progress-indeterminate">
    <div class="mosaic-progress-bar"></div>
</div>
```

## Circular Progress

Circular progress indicator:

```html
<div class="mosaic-progress-circle">
    <svg width="80" height="80">
        <circle class="mosaic-progress-circle-track"
                cx="40" cy="40" r="36" stroke-width="8"></circle>
        <circle class="mosaic-progress-circle-bar"
                cx="40" cy="40" r="36" stroke-width="8"
                stroke-dasharray="226" stroke-dashoffset="90"></circle>
    </svg>
    <span class="mosaic-progress-circle-value">60%</span>
</div>
```

### Circular Progress Variants

```html
<div class="mosaic-progress-circle mosaic-progress-circle-success">...</div>
<div class="mosaic-progress-circle mosaic-progress-circle-warning">...</div>
<div class="mosaic-progress-circle mosaic-progress-circle-critical">...</div>
```

### Circular Progress Sizes

```html
<div class="mosaic-progress-circle mosaic-progress-circle-sm">...</div>
<div class="mosaic-progress-circle mosaic-progress-circle-lg">...</div>
```

## Step Progress

Multi-step progress indicator:

```html
<div class="mosaic-steps">
    <div class="mosaic-step mosaic-step-completed">
        <div class="mosaic-step-indicator">
            <span class="dashicons dashicons-yes"></span>
        </div>
        <span class="mosaic-step-label">Account</span>
    </div>

    <div class="mosaic-step mosaic-step-active">
        <div class="mosaic-step-indicator">2</div>
        <span class="mosaic-step-label">Details</span>
    </div>

    <div class="mosaic-step">
        <div class="mosaic-step-indicator">3</div>
        <span class="mosaic-step-label">Review</span>
    </div>

    <div class="mosaic-step">
        <div class="mosaic-step-indicator">4</div>
        <span class="mosaic-step-label">Complete</span>
    </div>
</div>
```

### Step States

| Class | Description |
|-------|-------------|
| (none) | Pending step |
| `.mosaic-step-active` | Current step (highlighted) |
| `.mosaic-step-completed` | Completed step (green) |

## PHP Helper

Create progress bars in PHP:

```php
// Simple progress bar
echo '<div class="mosaic-progress">';
echo '<div class="mosaic-progress-bar" style="width: ' . esc_attr( $percentage ) . '%"></div>';
echo '</div>';

// With wrapper
echo '<div class="mosaic-progress-wrapper">';
echo '<div class="mosaic-progress-header">';
echo '<span class="mosaic-progress-title">' . esc_html( $title ) . '</span>';
echo '<span class="mosaic-progress-value">' . esc_html( $value ) . '</span>';
echo '</div>';
echo '<div class="mosaic-progress">';
echo '<div class="mosaic-progress-bar" style="width: ' . esc_attr( $percentage ) . '%"></div>';
echo '</div>';
echo '</div>';
```

## CSS Classes

| Class | Description |
|-------|-------------|
| `.mosaic-progress` | Progress bar container |
| `.mosaic-progress-bar` | Progress fill |
| `.mosaic-progress-sm/lg/xl` | Size variants |
| `.mosaic-progress-success/warning/critical/info` | Color variants |
| `.mosaic-progress-striped` | Striped pattern |
| `.mosaic-progress-animated` | Animated stripes |
| `.mosaic-progress-indeterminate` | Unknown progress |
| `.mosaic-progress-labeled` | With centered label |
| `.mosaic-progress-wrapper` | With header wrapper |
| `.mosaic-progress-circle` | Circular progress |
| `.mosaic-steps` | Step container |
| `.mosaic-step` | Individual step |
| `.mosaic-step-active` | Current step |
| `.mosaic-step-completed` | Completed step |
