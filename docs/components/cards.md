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

## Collapsible Cards

Cards can be made collapsible to save space.

### PHP

```php
$card = new Mosaic_Card( array(
    'collapsible' => true,
    'collapsed'   => false,  // Start expanded (default)
) );
$card->set_header( 'Collapsible Section' );
$card->set_body( '<p>This content can be collapsed.</p>' );
echo $card->render();

// Start collapsed
$card = new Mosaic_Card( array(
    'collapsible' => true,
    'collapsed'   => true,
) );
```

### HTML

```html
<div class="mosaic-card mosaic-card-collapsible">
    <div class="mosaic-card-header mosaic-card-header-collapsible">
        <h3 class="mosaic-card-header-title">Collapsible Section</h3>
        <div class="mosaic-card-header-actions">
            <button type="button" class="mosaic-card-collapse-btn" aria-label="Toggle">
                <span class="dashicons dashicons-arrow-down-alt2"></span>
            </button>
        </div>
    </div>
    <div class="mosaic-card-collapsible-content">
        <div class="mosaic-card-body">
            <p>This content can be collapsed.</p>
        </div>
    </div>
</div>

<!-- Collapsed state -->
<div class="mosaic-card mosaic-card-collapsible mosaic-card-collapsed">
    ...
</div>
```

## Dismissible Cards

Cards can be dismissed (removed from the page).

### PHP

```php
$card = new Mosaic_Card( array(
    'dismissible' => true,
) );
$card->set_header( 'Dismissible Notice' );
$card->set_body( '<p>Click the X to dismiss this card.</p>' );
echo $card->render();

// Combine with collapsible
$card = new Mosaic_Card( array(
    'collapsible' => true,
    'dismissible' => true,
) );
```

### HTML

```html
<div class="mosaic-card mosaic-card-dismissible">
    <div class="mosaic-card-header">
        <h3 class="mosaic-card-header-title">Dismissible Notice</h3>
        <div class="mosaic-card-header-actions">
            <button type="button" class="mosaic-card-close-btn" aria-label="Close">
                <span class="dashicons dashicons-no-alt"></span>
            </button>
        </div>
    </div>
    <div class="mosaic-card-body">
        <p>Click the X to dismiss this card.</p>
    </div>
</div>
```

## Accordion

Accordions display collapsible content sections.

### PHP

```php
$accordion = new Mosaic_Accordion();
$accordion->add_item( 'Section One', '<p>Content for section one.</p>' );
$accordion->add_item( 'Section Two', '<p>Content for section two.</p>' );
$accordion->add_item( 'Section Three', '<p>Content for section three.</p>' );
echo $accordion->render();
```

### With Options

```php
// Allow multiple items open at once
$accordion = new Mosaic_Accordion( array(
    'multiple' => true,
) );

// Start with an item open
$accordion->add_item( 'First Item', 'Content...', array( 'open' => true ) );
$accordion->add_item( 'Second Item', 'Content...' );

// With icons
$accordion->add_item( 'Settings', 'Content...', array(
    'icon' => 'admin-generic',
) );

// Flush style (no outer border)
$accordion = new Mosaic_Accordion( array(
    'flush' => true,
) );
```

### HTML

```html
<div class="mosaic-accordion">
    <div class="mosaic-accordion-item">
        <div class="mosaic-accordion-header">
            <h3 class="mosaic-accordion-title">Section One</h3>
            <span class="mosaic-accordion-icon">
                <span class="dashicons dashicons-arrow-down-alt2"></span>
            </span>
        </div>
        <div class="mosaic-accordion-content">
            <div class="mosaic-accordion-body">
                <p>Content for section one.</p>
            </div>
        </div>
    </div>
    <div class="mosaic-accordion-item mosaic-accordion-item-open">
        <div class="mosaic-accordion-header">
            <h3 class="mosaic-accordion-title">Section Two (Open)</h3>
            <span class="mosaic-accordion-icon">
                <span class="dashicons dashicons-arrow-down-alt2"></span>
            </span>
        </div>
        <div class="mosaic-accordion-content">
            <div class="mosaic-accordion-body">
                <p>Content for section two.</p>
            </div>
        </div>
    </div>
</div>

<!-- Flush style -->
<div class="mosaic-accordion mosaic-accordion-flush">
    ...
</div>
```

### Accordion Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `id` | string | `''` | Accordion ID |
| `class` | string | `''` | Additional classes |
| `multiple` | bool | `false` | Allow multiple items open |
| `flush` | bool | `false` | No outer border |

### Accordion Item Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `icon` | string | `''` | Dashicon name |
| `open` | bool | `false` | Start expanded |

## Card Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `id` | string | `''` | Card ID |
| `class` | string | `''` | Additional classes |
| `variant` | string | `''` | Style: flat, hover, interactive |
| `active` | bool | `false` | Show active state (for interactive) |
| `collapsible` | bool | `false` | Make card collapsible |
| `collapsed` | bool | `false` | Start in collapsed state |
| `dismissible` | bool | `false` | Show close button |

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
| `.mosaic-card-collapsible` | Collapsible card |
| `.mosaic-card-collapsed` | Collapsed state |
| `.mosaic-card-dismissible` | Dismissible card |
| `.mosaic-stat-card` | Stat card base |
| `.mosaic-stat-card-primary` | Primary accent |
| `.mosaic-stat-card-success` | Success accent |
| `.mosaic-stat-card-warning` | Warning accent |
| `.mosaic-stat-card-critical` | Critical accent |
| `.mosaic-status-card` | Status card base |
| `.mosaic-metric-tile` | Metric tile |
| `.mosaic-stats-grid` | Auto-grid for stat cards |
| `.mosaic-accordion` | Accordion container |
| `.mosaic-accordion-flush` | Accordion without outer border |
| `.mosaic-accordion-item` | Accordion item |
| `.mosaic-accordion-item-open` | Open accordion item |
