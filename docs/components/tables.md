# Tables

Mosaic provides a fluent PHP API for building consistent, feature-rich tables.

## Basic Usage

### PHP

```php
$table = new Mosaic_Table();

// Define columns
$table->set_columns( array(
    'name'   => 'Site Name',
    'status' => 'Status',
    'actions' => array(
        'label' => 'Actions',
        'align' => 'right',
    ),
) );

// Add rows
$table->add_row( array(
    'name'   => 'Production Site',
    'status' => Mosaic::health_badge( 'healthy' ),
    'actions' => Mosaic::button( 'View', array( 'size' => 'sm' ) ),
) );

$table->add_row( array(
    'name'   => 'Staging Site',
    'status' => Mosaic::health_badge( 'warning' ),
    'actions' => Mosaic::button( 'View', array( 'size' => 'sm' ) ),
) );

echo $table->render();
```

### HTML

```html
<table class="mosaic-table mosaic-table-striped mosaic-table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th class="mosaic-text-right">Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Production Site</td>
            <td><span class="mosaic-health-badge mosaic-health-badge-healthy">Healthy</span></td>
            <td class="mosaic-text-right">
                <button class="mosaic-btn mosaic-btn-sm mosaic-btn-secondary">View</button>
            </td>
        </tr>
    </tbody>
</table>
```

## Table Options

```php
$table = new Mosaic_Table( array(
    'id'            => 'sites-table',
    'class'         => 'my-custom-class',
    'striped'       => true,   // Alternating row colors
    'hover'         => true,   // Hover effect
    'sticky_header' => true,   // Sticky header on scroll
    'responsive'    => false,  // Card layout on mobile
    'compact'       => false,  // Reduced padding
    'bordered'      => false,  // Cell borders
) );
```

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `id` | string | `''` | Table ID |
| `class` | string | `''` | Additional classes |
| `striped` | bool | `true` | Alternating row colors |
| `hover` | bool | `true` | Row hover effect |
| `sticky_header` | bool | `true` | Sticky header |
| `responsive` | bool | `false` | Mobile card layout |
| `compact` | bool | `false` | Reduced padding |
| `bordered` | bool | `false` | Cell borders |

## Column Options

```php
$table->add_column( 'key', 'Label', array(
    'class'    => 'custom-class',
    'sortable' => false,
    'align'    => 'left', // left, center, right
    'width'    => '200px',
) );
```

## Row Options

```php
$table->add_row( $data, array(
    'class'  => 'custom-row-class',
    'id'     => 'row-123',
    'status' => 'success', // selected, success, warning, critical, disabled
    'attrs'  => array(
        'data-id' => '123',
    ),
) );
```

## Row States

```html
<tr class="mosaic-row-selected">...</tr>  <!-- Blue highlight -->
<tr class="mosaic-row-success">...</tr>   <!-- Green highlight -->
<tr class="mosaic-row-warning">...</tr>   <!-- Yellow highlight -->
<tr class="mosaic-row-critical">...</tr>  <!-- Red highlight -->
<tr class="mosaic-row-disabled">...</tr>  <!-- Dimmed -->
```

## Empty State

```php
$table->set_empty_state(
    'No sites found',           // Title
    'Add a site to get started', // Message
    'admin-site',               // Dashicon
    Mosaic::button( 'Add Site', array( 'variant' => 'primary' ) )
);
```

## Footer

```php
$table->set_footer( 'Showing 10 of 42 sites' );
```

## Action Buttons

For consistent action button styling in table rows:

```php
$actions = '
    <div class="mosaic-table-actions">
        ' . Mosaic::button( '', array(
            'variant' => 'secondary',
            'size'    => 'sm',
            'icon'    => 'edit',
            'class'   => 'mosaic-btn-icon',
        ) ) . '
        ' . Mosaic::button( '', array(
            'variant' => 'danger',
            'size'    => 'sm',
            'icon'    => 'trash',
            'class'   => 'mosaic-btn-icon',
        ) ) . '
    </div>
';

$table->add_row( array(
    'name'    => 'Example',
    'actions' => $actions,
) );
```

## Responsive Tables

When `responsive` is enabled, tables transform to a card layout on mobile:

```php
$table = new Mosaic_Table( array( 'responsive' => true ) );
```

The table cells use `data-label` attributes to show column names on mobile. This is handled automatically by the Mosaic_Table class.

## Sortable Headers

```php
$table->add_column( 'name', 'Name', array( 'sortable' => true ) );
```

Sortable columns get a visual indicator and cursor. Actual sorting logic should be implemented in your JavaScript or server-side code.

## CSS Classes

| Class | Description |
|-------|-------------|
| `.mosaic-table` | Base table class |
| `.mosaic-table-striped` | Alternating row colors |
| `.mosaic-table-hover` | Row hover effect |
| `.mosaic-table-sticky` | Sticky header |
| `.mosaic-table-responsive` | Mobile card layout |
| `.mosaic-table-compact` | Reduced padding |
| `.mosaic-table-bordered` | Cell borders |
| `.mosaic-table-container` | Wrapper for horizontal scroll |
