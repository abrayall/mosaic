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
    wp_enqueue_script( 'mosaic-media', $mosaic_url . 'assets/js/modules/media.js', array(), $version, true );
    wp_enqueue_script( 'mosaic', $mosaic_url . Mosaic::js(), array(), $version, true );

    // For media selector, also enqueue WordPress media library
    wp_enqueue_media();

    // Pass context for AJAX
    wp_localize_script( 'mosaic', 'mosaicContext', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'my_plugin_nonce' ),
    ) );
} );
```

## Dialogs

All dialog methods return Promises. Dialogs are simple message boxes without icons by default, with right-aligned buttons.

### Alert Dialogs

```javascript
// Basic alert
Mosaic.alert('Something happened');

// With title
Mosaic.alert('Custom message', 'Custom Title');

// With options (closable adds X button)
Mosaic.alert('This can be dismissed', { title: 'Notice', closable: true });

// Typed alerts (same styling, semantic distinction)
Mosaic.success('Operation completed!');
Mosaic.warning('Please check your input');
Mosaic.error('Something went wrong');
```

### Confirm Dialogs

OK button appears on the left, Cancel on the right:

```javascript
// Standard confirm
Mosaic.confirm('Are you sure?').then(confirmed => {
    if (confirmed) {
        // User clicked OK
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
    title: 'Custom Dialog',        // Optional title
    message: 'Choose an action:',
    closable: true,                // Show X close button
    type: 'warning',               // Optional: info, success, warning, error
    icon: 'admin-generic',         // Optional: dashicon name
    buttons: [
        { label: 'Publish', action: 'confirm', class: 'mosaic-btn-primary' },
        { label: 'Save Draft', action: 'draft', class: 'mosaic-btn-secondary' },
        { label: 'Cancel', action: 'cancel', class: 'mosaic-btn-secondary-outline' }
    ]
}).then(action => {
    // action = 'confirm', 'draft', or 'cancel'
});
```

### Button Classes

| Class | Style |
|-------|-------|
| `mosaic-btn-primary` | Solid blue (primary action) |
| `mosaic-btn-secondary` | Gray background |
| `mosaic-btn-secondary-outline` | Transparent with border (cancel) |
| `mosaic-btn-danger` | Solid red (destructive) |
| `mosaic-btn-danger-outline` | Red border (destructive secondary) |

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

### Hash Navigation

Persist the active tab in the URL hash so users stay on the same tab after refresh:

```javascript
// Enable via JavaScript
const tabs = Mosaic.tabs('#my-tabs', {
    hashNavigation: true
});
```

Or enable via data attribute:

```html
<div data-mosaic-tabs data-mosaic-tabs-hash>
    <!-- tabs content -->
</div>
```

With hash navigation enabled:
- The URL updates to `#tab-id` when switching tabs
- On page load, the tab matching the URL hash is activated
- Browser back/forward buttons navigate between tabs

### Tab Options

| Option | Default | Description |
|--------|---------|-------------|
| `tabSelector` | `'.mosaic-tab'` | Selector for tab buttons |
| `contentSelector` | `'.mosaic-tab-content'` | Selector for content panels |
| `activeClass` | `'mosaic-tab-active'` | Class for active tab |
| `contentActiveClass` | `'mosaic-tab-content-active'` | Class for active content |
| `overflowEnabled` | `true` | Show overflow menu on small screens |
| `mobileSelectEnabled` | `true` | Show select dropdown on mobile |
| `hashNavigation` | `false` | Persist active tab in URL hash |
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

## Loading States

Add loading overlays to any element with a spinning indicator.

### Element Loading

```javascript
// Start loading on any element
Mosaic.startLoading('#my-table');
Mosaic.startLoading('.my-card');
Mosaic.startLoading(document.querySelector('.tab-content'));

// Stop loading
Mosaic.stopLoading('#my-table');

// With options
Mosaic.startLoading('#content', { size: 'sm' });  // Small spinner
Mosaic.startLoading('#content', { size: 'lg' });  // Large spinner
Mosaic.startLoading('#content', { dark: true });  // Dark overlay
```

### Page Loading

Cover the entire `.mosaic` container:

```javascript
// Show full-page loading
Mosaic.startPage();

// Do async work...
await fetch('/api/data');

// Hide loading
Mosaic.stopPage();

// With options
Mosaic.startPage({ dark: true });
```

### CSS Classes

You can also add classes directly:

```html
<!-- Any element -->
<div class="mosaic-card mosaic-loading">...</div>
<table class="mosaic-table mosaic-loading">...</table>
<div class="mosaic-tab-content mosaic-loading">...</div>

<!-- Size variants -->
<div class="mosaic-card mosaic-loading mosaic-loading-sm">...</div>
<div class="mosaic-card mosaic-loading mosaic-loading-lg">...</div>

<!-- Dark overlay -->
<div class="mosaic-card mosaic-loading mosaic-loading-dark">...</div>
```

### Standalone Spinners

For inline or custom layouts:

```html
<!-- Basic spinner -->
<div class="mosaic-spinner"></div>

<!-- Sizes -->
<div class="mosaic-spinner mosaic-spinner-xs"></div>
<div class="mosaic-spinner mosaic-spinner-sm"></div>
<div class="mosaic-spinner mosaic-spinner-lg"></div>
<div class="mosaic-spinner mosaic-spinner-xl"></div>

<!-- Colors -->
<div class="mosaic-spinner mosaic-spinner-success"></div>
<div class="mosaic-spinner mosaic-spinner-warning"></div>
<div class="mosaic-spinner mosaic-spinner-critical"></div>

<!-- Dots loader -->
<div class="mosaic-dots">
    <span class="mosaic-dot"></span>
    <span class="mosaic-dot"></span>
    <span class="mosaic-dot"></span>
</div>

<!-- Rotating icon -->
<span class="dashicons dashicons-update mosaic-icon-spin"></span>
```

## Filters & Search

Client-side filtering and search for tables, cards, and lists.

### Category Filter

Filter items by category buttons:

```javascript
const filter = Mosaic.filter({
    containerSelector: '#my-cards',
    itemSelector: '.card',
    onFilter: (filterValue, visibleCount, totalCount) => {
        console.log(`Showing ${visibleCount} of ${totalCount}`);
    }
});

// Programmatic control
filter.filter('category-name');
filter.reset();
filter.getVisibleItems();
filter.getHiddenItems();
```

### HTML Setup for Filters

```html
<!-- Filter buttons -->
<div class="mosaic-filter-buttons">
    <button data-filter="all" data-filter-target="#my-cards" class="mosaic-filter-active">All</button>
    <button data-filter="active" data-filter-target="#my-cards">Active</button>
    <button data-filter="inactive" data-filter-target="#my-cards">Inactive</button>
</div>

<!-- Filterable items -->
<div id="my-cards">
    <div class="card" data-filter="active">Active item</div>
    <div class="card" data-filter="inactive">Inactive item</div>
    <div class="card" data-filter="active,featured">Active & Featured</div>
</div>
```

### Search

Live search with debouncing and optional highlighting:

```javascript
const search = Mosaic.search({
    inputSelector: '#search-input',
    containerSelector: '#my-table tbody',
    itemSelector: 'tr',
    searchAttr: 'data-search',      // Optional: attribute to search (defaults to textContent)
    minChars: 2,                    // Minimum characters before searching
    debounce: 200,                  // Debounce delay in ms
    highlightMatches: true,         // Highlight matching text
    onSearch: (query, visibleCount, totalCount) => {
        console.log(`Found ${visibleCount} results for "${query}"`);
    }
});

// Programmatic control
search.search('query');
search.clear();
search.getValue();
```

### HTML Setup for Search

```html
<input type="text" id="search-input" class="mosaic-input" placeholder="Search...">

<table class="mosaic-table">
    <tbody id="my-table">
        <tr class="mosaic-search-item" data-search="john doe john@example.com">
            <td>John Doe</td>
            <td>john@example.com</td>
        </tr>
        <tr class="mosaic-search-item" data-search="jane smith jane@example.com">
            <td>Jane Smith</td>
            <td>jane@example.com</td>
        </tr>
    </tbody>
</table>
```

### Filter Events

```javascript
// Listen for filter changes
container.addEventListener('mosaic:filter', (e) => {
    console.log('Filter:', e.detail.filter);
    console.log('Visible:', e.detail.visibleCount);
    console.log('Total:', e.detail.totalCount);
});

// Listen for search
container.addEventListener('mosaic:search', (e) => {
    console.log('Query:', e.detail.query);
    console.log('Results:', e.detail.visibleCount);
});
```

### Filter Options

| Option | Default | Description |
|--------|---------|-------------|
| `containerSelector` | `null` | Container element selector |
| `itemSelector` | `'.mosaic-filter-item'` | Selector for filterable items |
| `filterAttr` | `'data-filter'` | Attribute containing filter values |
| `activeFilterClass` | `'mosaic-filter-active'` | Class for active filter button |
| `hiddenClass` | `'mosaic-hidden'` | Class added to hidden items |
| `animateItems` | `true` | Animate items when filtering |
| `onFilter` | `null` | Callback function |

### Search Options

| Option | Default | Description |
|--------|---------|-------------|
| `inputSelector` | `null` | Search input selector |
| `containerSelector` | `null` | Container element selector |
| `itemSelector` | `'.mosaic-search-item'` | Selector for searchable items |
| `searchAttr` | `'data-search'` | Attribute to search (or textContent) |
| `minChars` | `1` | Minimum characters to trigger search |
| `debounce` | `200` | Debounce delay in milliseconds |
| `highlightMatches` | `false` | Highlight matching text |
| `highlightClass` | `'mosaic-search-highlight'` | Class for highlighted text |
| `onSearch` | `null` | Callback function |

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

## Media Selector

WordPress media library integration for selecting images and files.

**Requirement:** Call `wp_enqueue_media()` on your admin page.

### PHP (Rendering)

```php
// Basic image selector
echo Mosaic::media_selector( 'featured_image', array(
    'label' => 'Featured Image',
    'value' => get_post_meta( $post_id, 'featured_image', true ),
) );

// With all options
echo Mosaic::media_selector( 'logo', array(
    'label'       => 'Company Logo',
    'value'       => $attachment_id,
    'button_text' => 'Choose Logo',
    'remove_text' => 'Remove',
    'type'        => 'image',  // image, video, audio, file
    'preview'     => true,
    'help'        => 'Recommended size: 200x50px',
    'class'       => '',       // Add mosaic-media-selector-sm, -lg, or -compact
) );

// File selector (non-image)
echo Mosaic::media_selector( 'document', array(
    'label'       => 'PDF Document',
    'type'        => 'file',
    'button_text' => 'Select File',
) );
```

### Size Variants

| Class | Preview Size |
|-------|--------------|
| (default) | 150x150px |
| `mosaic-media-selector-sm` | 80x80px |
| `mosaic-media-selector-lg` | 200x200px |
| `mosaic-media-selector-compact` | Full width, stacked layout |

### JavaScript API

```javascript
// Get current attachment ID
const id = Mosaic.media.get('.mosaic-media-selector');
const id = Mosaic.media.get('#logo-selector');

// Programmatically set attachment
Mosaic.media.set('.mosaic-media-selector', attachmentId);

// Clear selection
Mosaic.media.clear('.mosaic-media-selector');

// Initialize dynamically added selectors
Mosaic.media.init();
Mosaic.media.initSelector(element);
```

### Events

```javascript
// When user selects media
element.addEventListener('mosaic:media:select', (e) => {
    console.log('Selected:', e.detail.attachment);
    console.log('ID:', e.detail.attachment.id);
    console.log('URL:', e.detail.attachment.url);
    console.log('Filename:', e.detail.attachment.filename);
});

// When user removes media
element.addEventListener('mosaic:media:remove', (e) => {
    console.log('Media removed');
});
```

### Saving the Value

The hidden input contains the attachment ID. Save it like any form field:

```php
// In your save handler
if ( isset( $_POST['featured_image'] ) ) {
    $attachment_id = absint( $_POST['featured_image'] );
    update_post_meta( $post_id, 'featured_image', $attachment_id );
}
```
