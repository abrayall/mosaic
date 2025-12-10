# Tables

Mosaic provides a fluent PHP API for building consistent, feature-rich tables.

## Basic Usage

```php
$table = new Mosaic_Table();

$table->set_columns( array(
    'name'   => 'Site Name',
    'status' => 'Status',
    'actions' => 'Actions',  // Auto right-aligned
) );

$table->add_row( array(
    'name'   => 'Production Site',
    'status' => Mosaic::health_badge( 'healthy' ),
    'actions' => Mosaic::edit_button(),
) );

echo $table->render();
```

## Table Header

Add a title and action buttons above the table:

```php
$table->set_header(
    'Sites',  // Title (left)
    Mosaic::button( 'Add Site', array( 'variant' => 'primary', 'icon' => 'plus-alt' ) )  // Actions (right)
);
```

## Table Footer

Add content below the table (not as a table row):

```php
// Left and right content
$table->set_footer( 'Last updated: Today', 'Page 1 of 3' );

// Just left content
$table->set_footer( 'Last updated: Today' );

// Enable row count (appears on right)
$table->show_row_count();  // "Showing 5 entries"

// Custom row count format
$table->show_row_count( '%d sites' );  // "5 sites"

// Combined
$table->set_footer( 'Last updated: Today' );
$table->show_row_count();  // Left: "Last updated: Today", Right: "Showing 5 entries"
```

## Action Columns

Columns named `actions` or `action` are automatically right-aligned:

```php
$table->set_columns( array(
    'name'    => 'Name',
    'actions' => 'Actions',  // Auto right-aligned
) );
```

### Edit and Delete Buttons

Use the built-in button helpers for consistent styling:

```php
$table->add_row( array(
    'name'    => 'Example',
    'actions' => '<div class="mosaic-table-actions">'
        . Mosaic::edit_button()      // Solid blue, edit icon
        . Mosaic::delete_button()    // Red outline, trash icon
        . '</div>',
) );

// With custom labels
Mosaic::edit_button( 'Modify' );
Mosaic::delete_button( 'Remove' );

// With links
Mosaic::edit_button( 'Edit', array( 'href' => '/edit?id=123' ) );
Mosaic::delete_button( 'Delete', array( 'href' => '/delete?id=123' ) );
```

## Data Table (CRUD Support)

For tables with add/edit/delete actions, use `Mosaic_Data_Table`:

```php
$table = new Mosaic_Data_Table();

// Set title and add button
$table->set_title( 'Sites' );
$table->set_add_button( 'Add Site', '/admin.php?page=add-site' );

// Define columns (actions column auto-added)
$table->set_columns( array(
    'name'   => 'Name',
    'status' => 'Status',
) );

// Enable row actions with URL patterns ({id} replaced with row ID)
$table->enable_edit( '/admin.php?page=edit-site&id={id}' );
$table->enable_delete( '/admin.php?action=delete-site&id={id}' );

// Set which field contains the row ID (default: 'id')
$table->set_id_key( 'site_id' );

// Add rows - edit/delete buttons auto-generated
$table->add_row( array(
    'id'     => 1,
    'name'   => 'Production',
    'status' => Mosaic::health_badge( 'healthy' ),
) );

$table->add_row( array(
    'id'     => 2,
    'name'   => 'Staging',
    'status' => Mosaic::health_badge( 'warning' ),
) );

$table->show_row_count( '%d sites' );
echo $table->render();
```

### Using Callbacks for URLs

```php
$table->enable_edit( function( $row, $id ) {
    return admin_url( 'admin.php?page=edit-site&id=' . $id );
} );

$table->enable_delete( function( $row, $id ) {
    return wp_nonce_url(
        admin_url( 'admin.php?action=delete-site&id=' . $id ),
        'delete_site_' . $id
    );
} );
```

### Custom Button Labels

```php
$table->enable_edit( '/edit?id={id}', 'Modify' );
$table->enable_delete( '/delete?id={id}', 'Remove' );
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
    'align'    => 'left',  // left, center, right
    'width'    => '200px',
) );
```

## Row Options

```php
$table->add_row( $data, array(
    'class'  => 'custom-row-class',
    'id'     => 'row-123',
    'status' => 'success',  // selected, success, warning, critical, disabled
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
    'No sites found',            // Title
    'Add a site to get started', // Message
    'admin-site',                // Dashicon
    Mosaic::button( 'Add Site', array( 'variant' => 'primary' ) )
);
```

## Responsive Tables

When `responsive` is enabled, tables transform to a card layout on mobile:

```php
$table = new Mosaic_Table( array( 'responsive' => true ) );
```

## Sortable Headers

```php
$table->add_column( 'name', 'Name', array( 'sortable' => true ) );
```

Sortable columns get a visual indicator and cursor. Implement sorting logic in JavaScript or server-side.

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
| `.mosaic-table-header` | Header above table (title + actions) |
| `.mosaic-table-footer` | Footer below table |
| `.mosaic-table-actions` | Action button container |
