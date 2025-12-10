# Loaders & Spinners

Loading indicators including spinners, skeleton loaders, and loading overlays.

## Spinner

Basic spinning loader:

```html
<span class="mosaic-spinner"></span>
```

### Sizes

```html
<span class="mosaic-spinner mosaic-spinner-xs"></span>
<span class="mosaic-spinner mosaic-spinner-sm"></span>
<span class="mosaic-spinner"></span>
<span class="mosaic-spinner mosaic-spinner-lg"></span>
<span class="mosaic-spinner mosaic-spinner-xl"></span>
```

### Colors

```html
<span class="mosaic-spinner"></span>
<span class="mosaic-spinner mosaic-spinner-success"></span>
<span class="mosaic-spinner mosaic-spinner-warning"></span>
<span class="mosaic-spinner mosaic-spinner-critical"></span>
<span class="mosaic-spinner mosaic-spinner-white"></span>
```

## Dots Loader

Bouncing dots animation:

```html
<div class="mosaic-dots">
    <span class="mosaic-dot"></span>
    <span class="mosaic-dot"></span>
    <span class="mosaic-dot"></span>
</div>
```

### Sizes

```html
<div class="mosaic-dots mosaic-dots-sm">...</div>
<div class="mosaic-dots mosaic-dots-lg">...</div>
```

## Icon Spin

Make any icon spin:

```html
<span class="dashicons dashicons-update mosaic-icon-spin"></span>
<span class="dashicons dashicons-update mosaic-icon-spin-slow"></span>
```

## Universal Loading State

Add `.mosaic-loading` to any container to show a loading overlay:

```html
<div class="mosaic-card mosaic-loading">
    <!-- Content is obscured by loading overlay -->
</div>

<table class="mosaic-table mosaic-loading">
    <!-- Table with loading state -->
</table>
```

### JavaScript API

```javascript
// Start loading on any element
Mosaic.startLoading('.my-container');
Mosaic.startLoading(element);

// Stop loading
Mosaic.stopLoading('.my-container');

// Page-level loading (entire .mosaic container)
Mosaic.startPage();
Mosaic.stopPage();

// Options
Mosaic.startLoading('.my-container', {
    size: 'sm',   // 'sm', 'lg'
    dark: true    // Dark overlay
});
```

### Variants

```html
<!-- Small spinner -->
<div class="mosaic-card mosaic-loading mosaic-loading-sm">...</div>

<!-- Large spinner -->
<div class="mosaic-card mosaic-loading mosaic-loading-lg">...</div>

<!-- Dark overlay -->
<div class="mosaic-card mosaic-loading mosaic-loading-dark">...</div>
```

## Loading Overlay

Explicit overlay for custom positioning:

```html
<div class="mosaic-loading-overlay">
    <span class="mosaic-spinner mosaic-spinner-lg"></span>
    <span class="mosaic-loading-text">Loading...</span>
</div>
```

### Dark Variant

```html
<div class="mosaic-loading-overlay mosaic-loading-overlay-dark">
    <span class="mosaic-spinner mosaic-spinner-lg mosaic-spinner-white"></span>
    <span class="mosaic-loading-text">Loading...</span>
</div>
```

## Skeleton Loaders

Placeholder content while loading:

```html
<!-- Text lines -->
<div class="mosaic-skeleton mosaic-skeleton-heading"></div>
<div class="mosaic-skeleton mosaic-skeleton-text"></div>
<div class="mosaic-skeleton mosaic-skeleton-text"></div>
<div class="mosaic-skeleton mosaic-skeleton-text"></div>

<!-- Avatar -->
<div class="mosaic-skeleton mosaic-skeleton-avatar"></div>
<div class="mosaic-skeleton mosaic-skeleton-avatar-lg"></div>

<!-- Thumbnail -->
<div class="mosaic-skeleton mosaic-skeleton-thumbnail"></div>

<!-- Button -->
<div class="mosaic-skeleton mosaic-skeleton-button"></div>
```

### Skeleton Table

```html
<div class="mosaic-skeleton-table">
    <div class="mosaic-skeleton-table-row">
        <div class="mosaic-skeleton mosaic-skeleton-table-cell"></div>
        <div class="mosaic-skeleton mosaic-skeleton-table-cell"></div>
        <div class="mosaic-skeleton mosaic-skeleton-table-cell"></div>
    </div>
    <!-- Repeat rows as needed -->
</div>
```

### Skeleton Card

```html
<div class="mosaic-skeleton mosaic-skeleton-card">
    <div class="mosaic-skeleton mosaic-skeleton-heading"></div>
    <div class="mosaic-skeleton mosaic-skeleton-text"></div>
    <div class="mosaic-skeleton mosaic-skeleton-text"></div>
</div>
```

## Page Loading

Full page loading state:

```html
<div class="mosaic-page-loading">
    <span class="mosaic-spinner mosaic-spinner-lg"></span>
    <span class="mosaic-page-loading-text">Loading content...</span>
</div>
```

## Inline Loading

Loading indicator inline with text:

```html
<span class="mosaic-loading-inline">
    <span class="mosaic-spinner mosaic-spinner-sm"></span>
    Saving changes...
</span>
```

## Placeholder

Empty state placeholder:

```html
<div class="mosaic-placeholder">
    <div class="mosaic-placeholder-icon">
        <span class="dashicons dashicons-format-image"></span>
    </div>
    <div class="mosaic-placeholder-title">No images found</div>
    <div class="mosaic-placeholder-message">
        Upload images to see them here.
    </div>
    <button class="mosaic-btn mosaic-btn-primary">Upload Image</button>
</div>
```

## CSS Classes

| Class | Description |
|-------|-------------|
| `.mosaic-spinner` | Basic spinner |
| `.mosaic-spinner-xs/sm/lg/xl` | Spinner sizes |
| `.mosaic-spinner-white/success/warning/critical` | Spinner colors |
| `.mosaic-dots` | Dots loader container |
| `.mosaic-icon-spin` | Spinning icon animation |
| `.mosaic-loading` | Universal loading overlay |
| `.mosaic-loading-sm/lg` | Loading spinner sizes |
| `.mosaic-loading-dark` | Dark overlay variant |
| `.mosaic-loading-overlay` | Explicit overlay element |
| `.mosaic-skeleton` | Skeleton loader base |
| `.mosaic-skeleton-text/heading/avatar/button` | Skeleton variants |
| `.mosaic-placeholder` | Empty state container |
