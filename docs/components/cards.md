# Cards

Mosaic provides various card components for displaying content, statistics, and status information.

## Basic Card

### PHP

```php
$card = new Mosaic_Card();
$card->set_header( 'Card Title', '', 'admin-generic' );
$card->set_body( '<p>Card content goes here.</p>' );
$card->set_footer( 'Footer content' );
echo $card->render();
```

### HTML

```html
<div class="mosaic-card">
    <div class="mosaic-card-header">
        <h3 class="mosaic-card-header-title">
            <span class="dashicons dashicons-admin-generic"></span>
            Card Title
        </h3>
    </div>
    <div class="mosaic-card-body">
        <p>Card content goes here.</p>
    </div>
    <div class="mosaic-card-footer">
        Footer content
    </div>
</div>
```

## Card Variants

```php
// Flat card (no shadow)
$card = new Mosaic_Card( array( 'variant' => 'flat' ) );

// Hoverable card
$card = new Mosaic_Card( array( 'variant' => 'hover' ) );

// Interactive/clickable card
$card = new Mosaic_Card( array(
    'variant' => 'interactive',
    'active'  => true,  // Shows active state
) );
```

| Variant | Class | Behavior |
|---------|-------|----------|
| default | `.mosaic-card` | Standard card with shadow |
| flat | `.mosaic-card-flat` | No shadow |
| hover | `.mosaic-card-hover` | Lifts on hover |
| interactive | `.mosaic-card-interactive` | Clickable, shows active state |

## Stat Cards

Stat cards display key metrics with colored accents.

### PHP

```php
echo Mosaic_Card::stat( 'Total Sites', 42, array(
    'variant' => 'primary',
    'icon'    => 'admin-site-alt3',
    'meta'    => 'Updated 5 min ago',
) );

echo Mosaic_Card::stat( 'Healthy', 38, array(
    'variant' => 'success',
    'icon'    => 'yes-alt',
) );

echo Mosaic_Card::stat( 'Warnings', 4, array(
    'variant' => 'warning',
    'icon'    => 'warning',
) );
```

### Stats Grid

```php
echo Mosaic_Card::stats_grid( array(
    array(
        'label'   => 'Total Sites',
        'value'   => 42,
        'variant' => 'primary',
        'icon'    => 'admin-site-alt3',
    ),
    array(
        'label'   => 'Healthy',
        'value'   => 38,
        'variant' => 'success',
        'icon'    => 'yes-alt',
    ),
    array(
        'label'   => 'Warnings',
        'value'   => 4,
        'variant' => 'warning',
        'icon'    => 'warning',
    ),
    array(
        'label'   => 'Critical',
        'value'   => 0,
        'variant' => 'critical',
        'icon'    => 'dismiss',
    ),
) );
```

### HTML

```html
<div class="mosaic-stats-grid">
    <div class="mosaic-stat-card mosaic-stat-card-primary">
        <h3 class="mosaic-stat-card-label">
            Total Sites
            <span class="dashicons dashicons-admin-site-alt3"></span>
        </h3>
        <div class="mosaic-stat-card-value">42</div>
        <div class="mosaic-stat-card-meta">Updated 5 min ago</div>
    </div>
</div>
```

### Clickable Stat Cards (for filtering)

```php
echo Mosaic_Card::stat( 'Healthy', 38, array(
    'variant'   => 'success',
    'clickable' => true,
    'active'    => true,
    'filter'    => 'healthy',  // Sets data-filter attribute
) );
```

## Status Cards

Status cards display health/status information with visual indicators.

### PHP

```php
echo Mosaic_Card::status(
    'healthy',                    // Status: healthy, warning, critical
    'System Status',              // Title
    'All systems operational'     // Message
);

echo Mosaic_Card::status(
    'warning',
    'Updates Available',
    '3 plugins have updates pending'
);
```

### HTML

```html
<div class="mosaic-status-card mosaic-status-card-healthy">
    <div class="mosaic-status-card-icon">
        <span class="dashicons dashicons-yes-alt"></span>
    </div>
    <div class="mosaic-status-card-content">
        <h4 class="mosaic-status-card-title">System Status</h4>
        <p class="mosaic-status-card-message">All systems operational</p>
    </div>
</div>
```

## Metric Tiles

For displaying individual metrics.

### PHP

```php
echo Mosaic_Card::metric( 'Disk Usage', '45.2', 'GB' );
echo Mosaic_Card::metric( 'Memory', '78', '%' );
echo Mosaic_Card::metric( 'Uptime', '99.9', '%' );
```

### HTML

```html
<div class="mosaic-metric-tile">
    <div class="mosaic-metric-tile-label">Disk Usage</div>
    <div class="mosaic-metric-tile-value">
        45.2<span class="mosaic-metric-tile-unit">GB</span>
    </div>
</div>
```

## Info Cards

For displaying informational messages.

```html
<div class="mosaic-info-card">
    <span class="dashicons dashicons-info"></span>
    <div class="mosaic-info-card-content">
        This is an informational message about something important.
    </div>
</div>
```

## Stat Card Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `id` | string | `''` | Card ID |
| `class` | string | `''` | Additional classes |
| `variant` | string | `'primary'` | Color: primary, success, warning, critical |
| `icon` | string | `''` | Dashicon name |
| `meta` | string | `''` | Description text |
| `clickable` | bool | `false` | Make card clickable |
| `active` | bool | `false` | Show active state |
| `filter` | string | `''` | Filter value (data-filter) |
| `attrs` | array | `array()` | Extra HTML attributes |

## CSS Classes

| Class | Description |
|-------|-------------|
| `.mosaic-card` | Base card |
| `.mosaic-card-flat` | No shadow |
| `.mosaic-card-hover` | Lift on hover |
| `.mosaic-card-interactive` | Clickable card |
| `.mosaic-card-active` | Active state |
| `.mosaic-stat-card` | Stat card base |
| `.mosaic-stat-card-primary` | Primary accent |
| `.mosaic-stat-card-success` | Success accent |
| `.mosaic-stat-card-warning` | Warning accent |
| `.mosaic-stat-card-critical` | Critical accent |
| `.mosaic-status-card` | Status card base |
| `.mosaic-metric-tile` | Metric tile |
| `.mosaic-stats-grid` | Auto-grid for stat cards |
