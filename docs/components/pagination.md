# Pagination

Page navigation for tables, lists, and content.

## Basic Pagination

```html
<nav class="mosaic-pagination">
    <a href="?page=1" class="mosaic-pagination-item mosaic-pagination-prev">
        <span class="dashicons dashicons-arrow-left-alt2"></span>
        Prev
    </a>
    <a href="?page=1" class="mosaic-pagination-item">1</a>
    <a href="?page=2" class="mosaic-pagination-item mosaic-active">2</a>
    <a href="?page=3" class="mosaic-pagination-item">3</a>
    <a href="?page=4" class="mosaic-pagination-item">4</a>
    <a href="?page=5" class="mosaic-pagination-item">5</a>
    <a href="?page=3" class="mosaic-pagination-item mosaic-pagination-next">
        Next
        <span class="dashicons dashicons-arrow-right-alt2"></span>
    </a>
</nav>
```

## With Ellipsis

```html
<nav class="mosaic-pagination">
    <a href="?page=4" class="mosaic-pagination-item mosaic-pagination-prev">
        <span class="dashicons dashicons-arrow-left-alt2"></span>
    </a>
    <a href="?page=1" class="mosaic-pagination-item">1</a>
    <span class="mosaic-pagination-ellipsis">...</span>
    <a href="?page=4" class="mosaic-pagination-item">4</a>
    <a href="?page=5" class="mosaic-pagination-item mosaic-active">5</a>
    <a href="?page=6" class="mosaic-pagination-item">6</a>
    <span class="mosaic-pagination-ellipsis">...</span>
    <a href="?page=20" class="mosaic-pagination-item">20</a>
    <a href="?page=6" class="mosaic-pagination-item mosaic-pagination-next">
        <span class="dashicons dashicons-arrow-right-alt2"></span>
    </a>
</nav>
```

## With Info Text

```html
<nav class="mosaic-pagination mosaic-pagination-between">
    <span class="mosaic-pagination-info">
        Showing <strong>41-50</strong> of <strong>248</strong> results
    </span>
    <div class="mosaic-pagination">
        <a href="#" class="mosaic-pagination-item mosaic-pagination-prev">Prev</a>
        <a href="#" class="mosaic-pagination-item">1</a>
        <a href="#" class="mosaic-pagination-item mosaic-active">5</a>
        <a href="#" class="mosaic-pagination-item">25</a>
        <a href="#" class="mosaic-pagination-item mosaic-pagination-next">Next</a>
    </div>
</nav>
```

## Simple Pagination (Prev/Next)

```html
<nav class="mosaic-pagination-simple">
    <a href="?page=4" class="mosaic-pagination-item mosaic-pagination-prev">
        <span class="dashicons dashicons-arrow-left-alt2"></span>
        Previous
    </a>
    <span class="mosaic-pagination-info">Page <strong>5</strong> of <strong>20</strong></span>
    <a href="?page=6" class="mosaic-pagination-item mosaic-pagination-next">
        Next
        <span class="dashicons dashicons-arrow-right-alt2"></span>
    </a>
</nav>
```

## With Per Page Select

```html
<nav class="mosaic-pagination mosaic-pagination-between">
    <div class="mosaic-pagination-per-page">
        <label>Show</label>
        <select>
            <option>10</option>
            <option selected>25</option>
            <option>50</option>
            <option>100</option>
        </select>
        <span>per page</span>
    </div>
    <div class="mosaic-pagination">
        <a href="#" class="mosaic-pagination-item">1</a>
        <a href="#" class="mosaic-pagination-item mosaic-active">2</a>
        <a href="#" class="mosaic-pagination-item">3</a>
    </div>
</nav>
```

## Compact Style

```html
<nav class="mosaic-pagination mosaic-pagination-compact">
    <a href="#" class="mosaic-pagination-item mosaic-pagination-prev">
        <span class="dashicons dashicons-arrow-left-alt2"></span>
    </a>
    <a href="#" class="mosaic-pagination-item">1</a>
    <a href="#" class="mosaic-pagination-item mosaic-active">2</a>
    <a href="#" class="mosaic-pagination-item">3</a>
    <a href="#" class="mosaic-pagination-item mosaic-pagination-next">
        <span class="dashicons dashicons-arrow-right-alt2"></span>
    </a>
</nav>
```

## Sizes

```html
<!-- Small -->
<nav class="mosaic-pagination mosaic-pagination-sm">...</nav>

<!-- Default -->
<nav class="mosaic-pagination">...</nav>

<!-- Large -->
<nav class="mosaic-pagination mosaic-pagination-lg">...</nav>
```

## Alignment

```html
<!-- Left (default) -->
<nav class="mosaic-pagination">...</nav>

<!-- Center -->
<nav class="mosaic-pagination mosaic-pagination-center">...</nav>

<!-- Right -->
<nav class="mosaic-pagination mosaic-pagination-right">...</nav>

<!-- Space between -->
<nav class="mosaic-pagination mosaic-pagination-between">...</nav>
```

## Disabled States

```html
<nav class="mosaic-pagination">
    <!-- Disabled prev (on first page) -->
    <span class="mosaic-pagination-item mosaic-pagination-prev mosaic-disabled">
        <span class="dashicons dashicons-arrow-left-alt2"></span>
    </span>
    <a href="#" class="mosaic-pagination-item mosaic-active">1</a>
    <a href="#" class="mosaic-pagination-item">2</a>
    <a href="#" class="mosaic-pagination-item mosaic-pagination-next">
        <span class="dashicons dashicons-arrow-right-alt2"></span>
    </a>
</nav>
```

## PHP Helper

```php
// In Mosaic class
echo Mosaic::pagination( array(
    'total'    => 248,
    'per_page' => 10,
    'current'  => 5,
    'base_url' => admin_url( 'admin.php?page=my-plugin' ),
) );
```

## CSS Classes

| Class | Description |
|-------|-------------|
| `.mosaic-pagination` | Container |
| `.mosaic-pagination-item` | Page link/button |
| `.mosaic-pagination-prev` | Previous button |
| `.mosaic-pagination-next` | Next button |
| `.mosaic-pagination-ellipsis` | Ellipsis separator |
| `.mosaic-pagination-info` | Info text |
| `.mosaic-pagination-per-page` | Per page selector wrapper |
| `.mosaic-pagination-simple` | Simple prev/next layout |
| `.mosaic-pagination-compact` | Joined buttons style |
| `.mosaic-pagination-sm` | Small size |
| `.mosaic-pagination-lg` | Large size |
| `.mosaic-pagination-center` | Center aligned |
| `.mosaic-pagination-right` | Right aligned |
| `.mosaic-pagination-between` | Space between |
| `.mosaic-active` | Active page |
| `.mosaic-disabled` | Disabled state |
