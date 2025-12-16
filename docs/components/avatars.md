# Avatars

User avatar display with images and initials fallbacks.

## Basic Avatar

### With Image

```html
<span class="mosaic-avatar">
    <img src="https://example.com/avatar.jpg" alt="User">
</span>
```

### With Initials

```html
<span class="mosaic-avatar">JD</span>
```

### With Icon

```html
<span class="mosaic-avatar">
    <span class="dashicons dashicons-admin-users"></span>
</span>
```

## Sizes

```html
<span class="mosaic-avatar mosaic-avatar-xs">XS</span>  <!-- 24px -->
<span class="mosaic-avatar mosaic-avatar-sm">SM</span>  <!-- 32px -->
<span class="mosaic-avatar">MD</span>                   <!-- 40px (default) -->
<span class="mosaic-avatar mosaic-avatar-lg">LG</span>  <!-- 56px -->
<span class="mosaic-avatar mosaic-avatar-xl">XL</span>  <!-- 80px -->
<span class="mosaic-avatar mosaic-avatar-2xl">2X</span> <!-- 120px -->
```

## Colors

```html
<span class="mosaic-avatar mosaic-avatar-primary">AB</span>
<span class="mosaic-avatar mosaic-avatar-success">CD</span>
<span class="mosaic-avatar mosaic-avatar-warning">EF</span>
<span class="mosaic-avatar mosaic-avatar-danger">GH</span>
<span class="mosaic-avatar mosaic-avatar-info">IJ</span>
<span class="mosaic-avatar mosaic-avatar-gray">KL</span>
```

## Shapes

```html
<!-- Circle (default) -->
<span class="mosaic-avatar">JD</span>

<!-- Square -->
<span class="mosaic-avatar mosaic-avatar-square">JD</span>

<!-- Rounded -->
<span class="mosaic-avatar mosaic-avatar-rounded">JD</span>
```

## With Border

```html
<span class="mosaic-avatar mosaic-avatar-bordered">
    <img src="avatar.jpg" alt="User">
</span>
```

## With Status Indicator

```html
<span class="mosaic-avatar-wrapper">
    <span class="mosaic-avatar">JD</span>
    <span class="mosaic-avatar-status mosaic-avatar-status-online"></span>
</span>

<span class="mosaic-avatar-wrapper">
    <span class="mosaic-avatar">AB</span>
    <span class="mosaic-avatar-status mosaic-avatar-status-offline"></span>
</span>

<span class="mosaic-avatar-wrapper">
    <span class="mosaic-avatar">CD</span>
    <span class="mosaic-avatar-status mosaic-avatar-status-busy"></span>
</span>

<span class="mosaic-avatar-wrapper">
    <span class="mosaic-avatar">EF</span>
    <span class="mosaic-avatar-status mosaic-avatar-status-away"></span>
</span>
```

## Avatar Group

```html
<div class="mosaic-avatar-group">
    <span class="mosaic-avatar"><img src="user1.jpg" alt="User 1"></span>
    <span class="mosaic-avatar"><img src="user2.jpg" alt="User 2"></span>
    <span class="mosaic-avatar"><img src="user3.jpg" alt="User 3"></span>
    <span class="mosaic-avatar-group-count">+5</span>
</div>
```

## Avatar with Name

```html
<div class="mosaic-avatar-name">
    <span class="mosaic-avatar">JD</span>
    <div>
        <div class="mosaic-avatar-name-text">John Doe</div>
        <div class="mosaic-avatar-name-subtitle">Administrator</div>
    </div>
</div>
```

## CSS Classes

| Class | Description |
|-------|-------------|
| `.mosaic-avatar` | Base avatar |
| `.mosaic-avatar-xs` | Extra small (24px) |
| `.mosaic-avatar-sm` | Small (32px) |
| `.mosaic-avatar-lg` | Large (56px) |
| `.mosaic-avatar-xl` | Extra large (80px) |
| `.mosaic-avatar-2xl` | 2X large (120px) |
| `.mosaic-avatar-primary` | Primary color |
| `.mosaic-avatar-success` | Success color |
| `.mosaic-avatar-warning` | Warning color |
| `.mosaic-avatar-danger` | Danger color |
| `.mosaic-avatar-info` | Info color |
| `.mosaic-avatar-gray` | Gray color |
| `.mosaic-avatar-square` | Square shape |
| `.mosaic-avatar-rounded` | Rounded corners |
| `.mosaic-avatar-bordered` | With border |
| `.mosaic-avatar-wrapper` | Wrapper for status |
| `.mosaic-avatar-status` | Status indicator |
| `.mosaic-avatar-status-online` | Online status (green) |
| `.mosaic-avatar-status-offline` | Offline status (gray) |
| `.mosaic-avatar-status-busy` | Busy status (red) |
| `.mosaic-avatar-status-away` | Away status (yellow) |
| `.mosaic-avatar-group` | Avatar group container |
| `.mosaic-avatar-group-count` | Group count badge |
| `.mosaic-avatar-name` | Avatar with name layout |
