# Alerts & Notices

Inline notification banners for messages, warnings, and errors.

## Basic Alert

```html
<div class="mosaic-alert">
    <div class="mosaic-alert-icon">
        <span class="dashicons dashicons-info"></span>
    </div>
    <div class="mosaic-alert-content">
        <div class="mosaic-alert-title">Information</div>
        <div class="mosaic-alert-message">This is an informational message.</div>
    </div>
</div>
```

## Alert Variants

```html
<!-- Info -->
<div class="mosaic-alert mosaic-alert-info">
    <div class="mosaic-alert-icon">
        <span class="dashicons dashicons-info"></span>
    </div>
    <div class="mosaic-alert-content">
        <div class="mosaic-alert-title">Information</div>
        <div class="mosaic-alert-message">This is an informational message.</div>
    </div>
</div>

<!-- Success -->
<div class="mosaic-alert mosaic-alert-success">
    <div class="mosaic-alert-icon">
        <span class="dashicons dashicons-yes-alt"></span>
    </div>
    <div class="mosaic-alert-content">
        <div class="mosaic-alert-title">Success</div>
        <div class="mosaic-alert-message">Your changes have been saved successfully.</div>
    </div>
</div>

<!-- Warning -->
<div class="mosaic-alert mosaic-alert-warning">
    <div class="mosaic-alert-icon">
        <span class="dashicons dashicons-warning"></span>
    </div>
    <div class="mosaic-alert-content">
        <div class="mosaic-alert-title">Warning</div>
        <div class="mosaic-alert-message">Please review your settings before continuing.</div>
    </div>
</div>

<!-- Error -->
<div class="mosaic-alert mosaic-alert-error">
    <div class="mosaic-alert-icon">
        <span class="dashicons dashicons-dismiss"></span>
    </div>
    <div class="mosaic-alert-content">
        <div class="mosaic-alert-title">Error</div>
        <div class="mosaic-alert-message">There was an error processing your request.</div>
    </div>
</div>
```

## Dismissible Alert

```html
<div class="mosaic-alert mosaic-alert-info" id="my-alert">
    <div class="mosaic-alert-icon">
        <span class="dashicons dashicons-info"></span>
    </div>
    <div class="mosaic-alert-content">
        <div class="mosaic-alert-message">This alert can be dismissed.</div>
    </div>
    <button type="button" class="mosaic-alert-close" onclick="this.parentElement.remove()">
        <span class="dashicons dashicons-no-alt"></span>
    </button>
</div>
```

## With Actions

```html
<div class="mosaic-alert mosaic-alert-warning">
    <div class="mosaic-alert-icon">
        <span class="dashicons dashicons-warning"></span>
    </div>
    <div class="mosaic-alert-content">
        <div class="mosaic-alert-title">Update Available</div>
        <div class="mosaic-alert-message">A new version is available. Would you like to update now?</div>
        <div class="mosaic-alert-actions">
            <button class="mosaic-btn mosaic-btn-primary mosaic-btn-sm">Update Now</button>
            <button class="mosaic-btn mosaic-btn-secondary-outline mosaic-btn-sm">Later</button>
        </div>
    </div>
</div>
```

## Simple Message (No Title)

```html
<div class="mosaic-alert mosaic-alert-success">
    <div class="mosaic-alert-icon">
        <span class="dashicons dashicons-yes-alt"></span>
    </div>
    <div class="mosaic-alert-content">
        <div class="mosaic-alert-message">Settings saved successfully.</div>
    </div>
</div>
```

## Accent Style

```html
<div class="mosaic-alert mosaic-alert-info mosaic-alert-accent">
    <div class="mosaic-alert-icon">
        <span class="dashicons dashicons-info"></span>
    </div>
    <div class="mosaic-alert-content">
        <div class="mosaic-alert-message">Alert with left border accent.</div>
    </div>
</div>
```

## Compact Alert

```html
<div class="mosaic-alert mosaic-alert-compact mosaic-alert-info">
    <div class="mosaic-alert-content">
        <div class="mosaic-alert-message">Compact alert without icon.</div>
    </div>
</div>
```

## Inline Alert

```html
<p>
    Status:
    <span class="mosaic-alert mosaic-alert-inline mosaic-alert-success">
        <span class="dashicons dashicons-yes-alt"></span>
        Connected
    </span>
</p>
```

## Banner Alert

```html
<div class="mosaic-alert mosaic-alert-banner mosaic-alert-warning">
    <div class="mosaic-alert-icon">
        <span class="dashicons dashicons-warning"></span>
    </div>
    <div class="mosaic-alert-content">
        <div class="mosaic-alert-message">Full-width banner alert spanning the page.</div>
    </div>
</div>
```

## JavaScript Dismissal

```javascript
// Dismiss with animation
function dismissAlert(element) {
    element.classList.add('mosaic-dismissing');
    setTimeout(() => element.remove(), 200);
}

// Usage
document.querySelector('.mosaic-alert-close').addEventListener('click', function() {
    dismissAlert(this.closest('.mosaic-alert'));
});
```

## CSS Classes

| Class | Description |
|-------|-------------|
| `.mosaic-alert` | Base alert container |
| `.mosaic-alert-icon` | Icon container |
| `.mosaic-alert-content` | Content wrapper |
| `.mosaic-alert-title` | Alert title |
| `.mosaic-alert-message` | Alert message |
| `.mosaic-alert-close` | Close/dismiss button |
| `.mosaic-alert-actions` | Actions container |
| `.mosaic-alert-info` | Info variant (blue) |
| `.mosaic-alert-success` | Success variant (green) |
| `.mosaic-alert-warning` | Warning variant (yellow) |
| `.mosaic-alert-error` | Error variant (red) |
| `.mosaic-alert-danger` | Alias for error |
| `.mosaic-alert-accent` | Left border accent |
| `.mosaic-alert-compact` | Compact padding |
| `.mosaic-alert-inline` | Inline display |
| `.mosaic-alert-banner` | Full-width banner |
| `.mosaic-dismissing` | Dismiss animation |
