# Mosaic JavaScript API

Mosaic provides a global `Mosaic` object with modules for dialogs, AJAX, tabs, clipboard, and utilities.

## Setup

Include the scripts in your plugin:

```php
add_action( 'admin_enqueue_scripts', function() {
    $mosaic_url = plugin_dir_url( __FILE__ ) . 'mosaic/';
    $version    = Mosaic::version();

    // Include modules
    wp_enqueue_script( 'mosaic-dialog', $mosaic_url . 'assets/js/modules/dialog.js', array(), $version, true );
    wp_enqueue_script( 'mosaic-tabs', $mosaic_url . 'assets/js/modules/tabs.js', array(), $version, true );
    wp_enqueue_script( 'mosaic-clipboard', $mosaic_url . 'assets/js/modules/clipboard.js', array(), $version, true );
    wp_enqueue_script( 'mosaic-ajax', $mosaic_url . 'assets/js/modules/ajax.js', array( 'jquery' ), $version, true );
    wp_enqueue_script( 'mosaic', $mosaic_url . Mosaic::js(), array(), $version, true );

    // Pass context for AJAX
    wp_localize_script( 'mosaic', 'mosaicContext', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'my_plugin_nonce' ),
    ) );
} );
```

## Dialogs

All dialog methods return Promises.

### Alert Dialogs

```javascript
// Basic alert
Mosaic.alert('Something happened');
Mosaic.alert('Custom message', 'Custom Title');

// Typed alerts
Mosaic.success('Operation completed!');
Mosaic.warning('Please check your input');
Mosaic.error('Something went wrong');
```

### Confirm Dialogs

```javascript
// Standard confirm
Mosaic.confirm('Are you sure?').then(confirmed => {
    if (confirmed) {
        // User clicked Confirm
    }
});

// Destructive confirm (red Delete button)
Mosaic.confirmDanger('Delete this item permanently?').then(confirmed => {
    if (confirmed) {
        // Proceed with deletion
    }
});
```

### Prompt Dialog

```javascript
Mosaic.prompt('Enter your name:', 'Default Value').then(value => {
    if (value !== null) {
        console.log('User entered:', value);
    } else {
        console.log('User cancelled');
    }
});
```

### Custom Dialog

```javascript
Mosaic.dialog({
    title: 'Custom Dialog',
    message: 'Choose an action:',
    type: 'info',           // info, success, warning, error
    icon: 'admin-generic',  // dashicon name
    buttons: [
        { label: 'Cancel', action: 'cancel', class: 'mosaic-btn-secondary' },
        { label: 'Save Draft', action: 'draft', class: 'mosaic-btn-secondary' },
        { label: 'Publish', action: 'confirm', class: 'mosaic-btn-primary' }
    ]
}).then(action => {
    // action = 'cancel', 'draft', or 'confirm'
});
```

## AJAX

WordPress AJAX wrapper with automatic nonce handling and loading states.

### Basic Requests

```javascript
// POST request (default)
Mosaic.ajax('my_plugin_action', { id: 123 })
    .then(data => console.log(data))
    .catch(error => console.error(error));

// GET request
Mosaic.get('my_plugin_action', { id: 123 });

// POST request (explicit)
Mosaic.post('my_plugin_action', { id: 123 });
```

### With Loading State

```javascript
// Button shows spinner and is disabled during request
Mosaic.ajax('my_plugin_save', { data: formData }, {
    loadingElement: '#save-button'
}).then(data => {
    Mosaic.success('Saved!');
});

// Using a selector
Mosaic.ajax('my_plugin_save', { data: formData }, {
    loadingElement: '.submit-btn'
});
```

### Auto Error Handling

```javascript
// Automatically shows Mosaic.error() on failure
Mosaic.request('my_plugin_action', { id: 123 });

// Disable auto error display
Mosaic.request('my_plugin_action', { id: 123 }, { showError: false });
```

### Polling

For long-running operations:

```javascript
Mosaic.poll('check_job_status', { job_id: 456 }, {
    interval: 2000,                              // Check every 2 seconds
    maxAttempts: 30,                             // Give up after 30 attempts
    shouldStop: (response) => response.complete, // Stop condition
    onProgress: (response, attempt) => {
        console.log(`Attempt ${attempt}: ${response.progress}%`);
    }
}).then(result => {
    Mosaic.success('Job complete!');
}).catch(error => {
    Mosaic.error('Job failed or timed out');
});
```

### AJAX Options

| Option | Default | Description |
|--------|---------|-------------|
| `method` | `'POST'` | HTTP method |
| `timeout` | `30000` | Request timeout in ms |
| `loadingElement` | `null` | Element to show loading state |
| `loadingClass` | `'mosaic-btn-loading'` | Class added during loading |
| `disableOnLoading` | `true` | Disable element during request |
| `showLoading` | `true` | Whether to show loading state |
| `nonceKey` | `'nonce'` | Key in mosaicContext for nonce |

## Tabs

### JavaScript Initialization

```javascript
const tabs = Mosaic.tabs('#my-tabs', {
    onChange: (current, previous) => {
        console.log('Switched from', previous, 'to', current);
    }
});

// Programmatic control
tabs.activate('settings');
```

### Auto-Initialization

Add `data-mosaic-tabs` to auto-initialize:

```html
<div data-mosaic-tabs>
    <div class="mosaic-tabs-wrapper">
        <div class="mosaic-tabs">
            <button class="mosaic-tab mosaic-tab-active" data-tab="overview">
                <span class="dashicons dashicons-admin-home"></span> Overview
            </button>
            <button class="mosaic-tab" data-tab="settings">
                <span class="dashicons dashicons-admin-settings"></span> Settings
            </button>
        </div>
    </div>
    <div class="mosaic-tab-content mosaic-tab-content-active" data-tab="overview">
        Overview content here
    </div>
    <div class="mosaic-tab-content" data-tab="settings">
        Settings content here
    </div>
</div>
```

### Tab Events

```javascript
// Listen for tab changes
document.querySelector('#my-tabs').addEventListener('mosaic:tab:change', (e) => {
    console.log('Changed to:', e.detail.current);
    console.log('Changed from:', e.detail.previous);
});
```

### Tab Options

| Option | Default | Description |
|--------|---------|-------------|
| `tabSelector` | `'.mosaic-tab'` | Selector for tab buttons |
| `contentSelector` | `'.mosaic-tab-content'` | Selector for content panels |
| `activeClass` | `'mosaic-tab-active'` | Class for active tab |
| `contentActiveClass` | `'mosaic-tab-content-active'` | Class for active content |
| `overflowEnabled` | `true` | Show overflow menu on small screens |
| `mobileSelectEnabled` | `true` | Show select dropdown on mobile |
| `onChange` | `null` | Callback function |

## Clipboard

### Copy Text

```javascript
// Copy text with visual feedback on button
Mosaic.copy('Text to copy', document.getElementById('copy-btn'));

// Copy and check result
const success = await Mosaic.copy('Text to copy');
if (success) {
    console.log('Copied!');
}
```

### Copy From Element

```javascript
// Copy value from an input/textarea
Mosaic.copyFromElement('#api-key-input', document.getElementById('copy-btn'));

// Copy text content from any element
Mosaic.copyFromElement('#code-block', document.getElementById('copy-btn'));
```

### Data Attribute Initialization

```html
<!-- Copy static text -->
<button data-mosaic-copy="Text to copy">
    <span class="dashicons dashicons-clipboard"></span> Copy
</button>

<!-- Copy from another element -->
<input type="text" id="api-key" value="abc123" readonly>
<button data-mosaic-copy-target="#api-key">
    <span class="dashicons dashicons-clipboard"></span> Copy
</button>
```

The button icon automatically changes to a checkmark on success.

## Utilities

### Debounce & Throttle

```javascript
// Debounce - wait until user stops typing
const search = Mosaic.debounce((query) => {
    console.log('Searching:', query);
}, 300);

input.addEventListener('input', (e) => search(e.target.value));

// Throttle - limit to once per interval
const scroll = Mosaic.throttle(() => {
    console.log('Scrolled');
}, 100);

window.addEventListener('scroll', scroll);
```

### Formatting

```javascript
Mosaic.formatNumber(1234567);
// "1,234,567"

Mosaic.formatBytes(1536);
// "1.5 KB"

Mosaic.formatBytes(1073741824);
// "1 GB"

Mosaic.formatRelativeTime('2024-01-15T10:30:00');
// "5 minutes ago" / "2 hours ago" / "3 days ago"
```

### Other Utilities

```javascript
// Escape HTML entities
Mosaic.escapeHtml('<script>alert("xss")</script>');
// "&lt;script&gt;alert("xss")&lt;/script&gt;"

// Generate unique IDs
Mosaic.uniqueId('field');
// "field-a1b2c3d4e"

// Initialize all Mosaic components in a container
Mosaic.init('#my-container');
Mosaic.init(document.getElementById('dynamic-content'));
```

## Re-initializing Dynamic Content

When adding content dynamically, re-initialize Mosaic components:

```javascript
// After inserting new HTML
container.innerHTML = newContent;

// Re-initialize all components
Mosaic.init(container);

// Or initialize specific features
Mosaic.initCopyButtons();
```
