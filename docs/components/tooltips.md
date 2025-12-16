# Tooltips

Hover tooltips for buttons, icons, and other elements.

## CSS-Only Tooltips (Data Attribute)

The simplest way to add a tooltip - just add the `data-mosaic-tooltip` attribute:

```html
<button class="mosaic-btn mosaic-btn-primary" data-mosaic-tooltip="Click to save">
    Save
</button>

<span class="dashicons dashicons-info" data-mosaic-tooltip="More information"></span>
```

## HTML Tooltips

For more control, use the tooltip HTML structure:

```html
<span class="mosaic-tooltip">
    <button class="mosaic-btn mosaic-btn-primary">Hover me</button>
    <span class="mosaic-tooltip-content">Tooltip text here</span>
</span>
```

## Positions

```html
<!-- Top (default) -->
<span class="mosaic-tooltip">
    <button>Top</button>
    <span class="mosaic-tooltip-content">Top tooltip</span>
</span>

<!-- Bottom -->
<span class="mosaic-tooltip mosaic-tooltip-bottom">
    <button>Bottom</button>
    <span class="mosaic-tooltip-content">Bottom tooltip</span>
</span>

<!-- Left -->
<span class="mosaic-tooltip mosaic-tooltip-left">
    <button>Left</button>
    <span class="mosaic-tooltip-content">Left tooltip</span>
</span>

<!-- Right -->
<span class="mosaic-tooltip mosaic-tooltip-right">
    <button>Right</button>
    <span class="mosaic-tooltip-content">Right tooltip</span>
</span>
```

## Variants

```html
<!-- Dark (default) -->
<span class="mosaic-tooltip">
    <button>Dark</button>
    <span class="mosaic-tooltip-content">Dark tooltip</span>
</span>

<!-- Light -->
<span class="mosaic-tooltip mosaic-tooltip-light">
    <button>Light</button>
    <span class="mosaic-tooltip-content">Light tooltip</span>
</span>

<!-- Primary -->
<span class="mosaic-tooltip mosaic-tooltip-primary">
    <button>Primary</button>
    <span class="mosaic-tooltip-content">Primary tooltip</span>
</span>
```

## Multiline Tooltips

```html
<!-- Standard multiline (200px) -->
<span class="mosaic-tooltip mosaic-tooltip-multiline">
    <button>Info</button>
    <span class="mosaic-tooltip-content">
        This is a longer tooltip that wraps to multiple lines for more detailed information.
    </span>
</span>

<!-- Wide multiline (300px) -->
<span class="mosaic-tooltip mosaic-tooltip-multiline-wide">
    <button>Details</button>
    <span class="mosaic-tooltip-content">
        This is an even wider tooltip for when you need to display more content.
    </span>
</span>
```

## JavaScript API

### Create Tooltip Programmatically

```javascript
// Wrap an element with a tooltip
const tooltip = Mosaic.tooltip('#my-button', 'Tooltip text', {
    position: 'bottom',  // 'top', 'bottom', 'left', 'right'
    variant: 'light',    // 'dark', 'light', 'primary'
    multiline: false
});

// Control the tooltip
tooltip.show();
tooltip.hide();
tooltip.setText('New text');
tooltip.destroy();
```

### Show Temporary Tooltip

```javascript
// Show a tooltip that auto-hides
Mosaic.showTooltip('#element', 'Copied!', {
    duration: 2000,  // ms
    position: 'top'
});
```

### Example: Copy Button Feedback

```javascript
document.querySelector('#copy-btn').addEventListener('click', function() {
    navigator.clipboard.writeText('text to copy');
    Mosaic.showTooltip(this, 'Copied!', { duration: 1500 });
});
```

## CSS Classes

| Class | Description |
|-------|-------------|
| `.mosaic-tooltip` | Tooltip wrapper |
| `.mosaic-tooltip-content` | Tooltip content box |
| `.mosaic-tooltip-top` | Position above (default) |
| `.mosaic-tooltip-bottom` | Position below |
| `.mosaic-tooltip-left` | Position to left |
| `.mosaic-tooltip-right` | Position to right |
| `.mosaic-tooltip-light` | Light background variant |
| `.mosaic-tooltip-primary` | Primary color variant |
| `.mosaic-tooltip-multiline` | Allow text wrapping (200px) |
| `.mosaic-tooltip-multiline-wide` | Wide multiline (300px) |

## Data Attributes

| Attribute | Description |
|-----------|-------------|
| `data-mosaic-tooltip` | Tooltip text (CSS-only, always top position) |

## Accessibility

- Tooltips appear on both hover and focus
- Use `aria-describedby` for complex tooltips
- Keep tooltip text concise
- Don't put essential information only in tooltips
