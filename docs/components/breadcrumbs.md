# Breadcrumbs

Navigation path indicators showing the user's location in the site hierarchy.

## Basic Breadcrumbs

```html
<nav class="mosaic-breadcrumbs">
    <span class="mosaic-breadcrumb-item">
        <a href="#">Home</a>
    </span>
    <span class="mosaic-breadcrumb-separator">/</span>
    <span class="mosaic-breadcrumb-item">
        <a href="#">Settings</a>
    </span>
    <span class="mosaic-breadcrumb-separator">/</span>
    <span class="mosaic-breadcrumb-item mosaic-active">
        General
    </span>
</nav>
```

## Auto Separators

Use `mosaic-breadcrumbs-auto` for automatic slash separators:

```html
<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-auto">
    <span class="mosaic-breadcrumb-item"><a href="#">Dashboard</a></span>
    <span class="mosaic-breadcrumb-item"><a href="#">Users</a></span>
    <span class="mosaic-breadcrumb-item">Edit User</span>
</nav>
```

## Arrow Separators

```html
<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-arrows">
    <span class="mosaic-breadcrumb-item"><a href="#">Dashboard</a></span>
    <span class="mosaic-breadcrumb-item"><a href="#">Products</a></span>
    <span class="mosaic-breadcrumb-item">Add New</span>
</nav>
```

## With Icons

```html
<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-auto">
    <span class="mosaic-breadcrumb-item">
        <a href="#"><span class="dashicons dashicons-admin-home"></span> Home</a>
    </span>
    <span class="mosaic-breadcrumb-item">
        <a href="#"><span class="dashicons dashicons-admin-settings"></span> Settings</a>
    </span>
    <span class="mosaic-breadcrumb-item">
        <span class="dashicons dashicons-admin-generic"></span> General
    </span>
</nav>
```

## With Dashicon Separators

```html
<nav class="mosaic-breadcrumbs">
    <span class="mosaic-breadcrumb-item"><a href="#">Home</a></span>
    <span class="mosaic-breadcrumb-separator">
        <span class="dashicons dashicons-arrow-right-alt2"></span>
    </span>
    <span class="mosaic-breadcrumb-item"><a href="#">Category</a></span>
    <span class="mosaic-breadcrumb-separator">
        <span class="dashicons dashicons-arrow-right-alt2"></span>
    </span>
    <span class="mosaic-breadcrumb-item">Current Page</span>
</nav>
```

## Sizes

```html
<!-- Small -->
<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-sm mosaic-breadcrumbs-auto">
    <span class="mosaic-breadcrumb-item"><a href="#">Home</a></span>
    <span class="mosaic-breadcrumb-item">Page</span>
</nav>

<!-- Default -->
<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-auto">
    <span class="mosaic-breadcrumb-item"><a href="#">Home</a></span>
    <span class="mosaic-breadcrumb-item">Page</span>
</nav>

<!-- Large -->
<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-lg mosaic-breadcrumbs-auto">
    <span class="mosaic-breadcrumb-item"><a href="#">Home</a></span>
    <span class="mosaic-breadcrumb-item">Page</span>
</nav>
```

## Variants

```html
<!-- With background -->
<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-bg mosaic-breadcrumbs-auto">
    <span class="mosaic-breadcrumb-item"><a href="#">Home</a></span>
    <span class="mosaic-breadcrumb-item">Settings</span>
</nav>

<!-- With border -->
<nav class="mosaic-breadcrumbs mosaic-breadcrumbs-bordered mosaic-breadcrumbs-auto">
    <span class="mosaic-breadcrumb-item"><a href="#">Home</a></span>
    <span class="mosaic-breadcrumb-item">Settings</span>
</nav>
```

## CSS Classes

| Class | Description |
|-------|-------------|
| `.mosaic-breadcrumbs` | Container |
| `.mosaic-breadcrumb-item` | Individual breadcrumb |
| `.mosaic-breadcrumb-separator` | Manual separator |
| `.mosaic-breadcrumbs-auto` | Auto slash separators |
| `.mosaic-breadcrumbs-arrows` | Auto arrow separators |
| `.mosaic-breadcrumbs-sm` | Small size |
| `.mosaic-breadcrumbs-lg` | Large size |
| `.mosaic-breadcrumbs-bg` | With background |
| `.mosaic-breadcrumbs-bordered` | With border |
| `.mosaic-active` | Current/active item |
