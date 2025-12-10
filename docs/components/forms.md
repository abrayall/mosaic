# Forms

Fluent interface for building forms with consistent styling.

## Basic Usage

```php
$form = new Mosaic_Form( array(
    'id'           => 'settings-form',
    'action'       => admin_url( 'admin-post.php' ),
    'nonce_action' => 'my_plugin_save',
) );

$form->add_text( 'site_name', 'Site Name', array(
    'value'       => get_option( 'site_name' ),
    'required'    => true,
    'placeholder' => 'Enter site name',
) );

$form->add_email( 'admin_email', 'Admin Email', array(
    'value' => get_option( 'admin_email' ),
) );

$form->add_select( 'status', 'Status', array(
    'active'   => 'Active',
    'inactive' => 'Inactive',
    'pending'  => 'Pending',
), array(
    'value' => get_option( 'status', 'active' ),
) );

$form->add_toggle( 'notifications', 'Enable Notifications', array(
    'value' => get_option( 'notifications' ),
    'help'  => 'Receive email notifications for important events',
) );

$form->add_html( Mosaic_Form::actions( 'Save Settings' ) );

echo $form->render();
```

## Form Options

| Option | Default | Description |
|--------|---------|-------------|
| `id` | `''` | Form HTML ID |
| `class` | `''` | Additional CSS classes |
| `action` | `''` | Form action URL |
| `method` | `'post'` | Form method (get/post) |
| `enctype` | `''` | Form encoding (e.g., `multipart/form-data`) |
| `wide` | `false` | Full-width labels (stacked layout) |
| `nonce` | `''` | Nonce field name |
| `nonce_action` | `''` | Nonce action for wp_nonce_field() |

## Field Types

### Text Input

```php
$form->add_text( 'field_name', 'Label', array(
    'value'       => 'default value',
    'placeholder' => 'Enter text...',
    'required'    => true,
    'readonly'    => false,
    'disabled'    => false,
    'help'        => 'Help text below the field',
    'class'       => 'custom-class',
) );
```

### Email, Password, Number

```php
$form->add_email( 'email', 'Email Address' );
$form->add_password( 'password', 'Password' );
$form->add_number( 'quantity', 'Quantity', array(
    'min'  => 0,
    'max'  => 100,
    'step' => 1,
) );
```

### Textarea

```php
$form->add_textarea( 'description', 'Description', array(
    'value' => $description,
    'rows'  => 5,
) );
```

### Select Dropdown

```php
$form->add_select( 'country', 'Country', array(
    'us' => 'United States',
    'uk' => 'United Kingdom',
    'ca' => 'Canada',
), array(
    'value' => 'us',
) );
```

### Checkbox

```php
$form->add_checkbox( 'agree', 'I agree to the terms', array(
    'value' => true,  // checked by default
) );
```

### Toggle Switch

```php
$form->add_toggle( 'dark_mode', 'Enable Dark Mode', array(
    'value' => get_option( 'dark_mode' ),
    'help'  => 'Switch between light and dark themes',
) );
```

### Radio Buttons

```php
$form->add_radio( 'frequency', 'Update Frequency', array(
    'hourly' => 'Every Hour',
    'daily'  => 'Once Daily',
    'weekly' => 'Once Weekly',
), array(
    'value' => 'daily',
) );
```

### Hidden Field

```php
$form->add_hidden( 'action', 'my_plugin_save' );
$form->add_hidden( 'post_id', $post->ID );
```

### Raw HTML

```php
$form->add_html( '<div class="mosaic-divider"></div>' );
$form->add_html( '<p class="description">Custom HTML content</p>' );
```

## Field Options

All field types support these common options:

| Option | Description |
|--------|-------------|
| `value` | Default/current value |
| `required` | Mark field as required |
| `disabled` | Disable the field |
| `readonly` | Make field read-only |
| `placeholder` | Placeholder text |
| `help` | Help text below field |
| `class` | Additional CSS classes |
| `id` | Custom HTML ID |
| `attrs` | Array of additional HTML attributes |

## Form Actions

Use the static `actions()` method to render submit buttons:

```php
// Simple submit button
$form->add_html( Mosaic_Form::actions( 'Save' ) );

// With cancel link
$form->add_html( Mosaic_Form::actions( 'Save Settings', array(
    'cancel_url' => admin_url( 'admin.php?page=my-plugin' ),
) ) );

// Custom buttons
$form->add_html( Mosaic_Form::actions( 'Publish', array(
    'cancel_url'   => $cancel_url,
    'cancel_label' => 'Discard',
    'variant'      => 'primary',
) ) );
```

## Rendering

```php
// Render as string
$html = $form->render();

// Output directly
$form->display();
```

## CSS Classes

| Class | Description |
|-------|-------------|
| `.mosaic-form` | Form container |
| `.mosaic-form-row` | Individual field row |
| `.mosaic-form-label` | Field label |
| `.mosaic-form-field` | Field input wrapper |
| `.mosaic-form-help` | Help text |
| `.mosaic-input` | Text input styling |
| `.mosaic-select` | Select dropdown styling |
| `.mosaic-textarea` | Textarea styling |
| `.mosaic-toggle` | Toggle switch |
| `.mosaic-checkbox` | Checkbox styling |
| `.mosaic-radio` | Radio button styling |
