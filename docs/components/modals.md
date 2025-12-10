# Modals & Dialogs

Modal dialogs including alerts, confirms, prompts, and custom modals.

## JavaScript API

### Alert

Simple alert with OK button:

```javascript
await Mosaic.alert('Your changes have been saved.');

// With title
await Mosaic.alert('Your changes have been saved.', 'Success');

// With options
await Mosaic.alert('Your changes have been saved.', {
    title: 'Success',
    closable: true
});
```

### Confirm

Confirmation dialog with OK/Cancel:

```javascript
const confirmed = await Mosaic.confirm('Are you sure you want to continue?');
if (confirmed) {
    // User clicked OK
}

// With title
const confirmed = await Mosaic.confirm('Delete this item?', 'Confirm Delete');
```

### Confirm Danger

Destructive action confirmation:

```javascript
const confirmed = await Mosaic.confirmDanger(
    'This action cannot be undone. All data will be permanently deleted.',
    'Delete Item?'
);
if (confirmed) {
    // User confirmed deletion
}
```

### Prompt

Input dialog:

```javascript
const value = await Mosaic.prompt('Enter your name:');
if (value !== null) {
    console.log('Name:', value);
}

// With default value
const value = await Mosaic.prompt('Enter your name:', 'John Doe');

// With title
const value = await Mosaic.prompt('Enter your name:', '', 'User Info');
```

### Custom Dialog

Full control over dialog options:

```javascript
const result = await Mosaic.dialog({
    title: 'Custom Dialog',
    message: 'This is a custom dialog with multiple options.',
    type: 'info',           // info, success, warning, alert
    icon: 'admin-users',    // Dashicon name
    closable: true,
    buttons: [
        { label: 'Save', action: 'save', class: 'mosaic-btn-primary' },
        { label: 'Save Draft', action: 'draft', class: 'mosaic-btn-secondary' },
        { label: 'Cancel', action: 'cancel', class: 'mosaic-btn-secondary-outline' }
    ]
});

if (result === 'save') {
    // Save action
} else if (result === 'draft') {
    // Save draft action
}
```

## Custom Modals

For rich content modals beyond simple dialogs, use `Mosaic.modal()`:

### Form in a Modal

```javascript
Mosaic.modal({
    title: 'Edit User',
    size: 'lg',
    content: `
        <form id="user-form">
            <div class="mosaic-form-group">
                <label class="mosaic-label">Name</label>
                <input type="text" class="mosaic-input" name="name" value="John">
            </div>
            <div class="mosaic-form-group">
                <label class="mosaic-label">Email</label>
                <input type="email" class="mosaic-input" name="email">
            </div>
        </form>
    `,
    buttons: [
        { label: 'Save', action: 'save', class: 'mosaic-btn-primary' },
        { label: 'Cancel', action: 'cancel', class: 'mosaic-btn-secondary-outline' }
    ]
}).then(action => {
    if (action === 'save') {
        const form = document.querySelector('#user-form');
        // process form...
    }
});
```

### Rich Content Without Footer

```javascript
Mosaic.modal({
    title: 'Preview',
    size: 'xl',
    content: document.getElementById('preview-content')
});
```

### With Callbacks

```javascript
Mosaic.modal({
    title: 'Editor',
    size: 'full',
    content: '<div id="editor"></div>',
    onOpen: (modal, body) => {
        // Initialize your editor here
        initEditor(body.querySelector('#editor'));
    },
    onClose: (action) => {
        console.log('Closed via:', action); // 'close', 'escape', 'overlay', or button action
    }
});
```

### Close Programmatically

```javascript
// Close all open modals
Mosaic.closeModals();
```

### Modal Options

| Option | Default | Description |
|--------|---------|-------------|
| `title` | `''` | Modal title |
| `content` | - | HTML string or DOM element |
| `size` | - | `'sm'`, `'lg'`, `'xl'`, `'full'` |
| `closable` | `true` | Show X close button |
| `closeOnOverlay` | `true` | Close when clicking backdrop |
| `closeOnEscape` | `true` | Close on Escape key |
| `buttons` | `[]` | Footer buttons (optional) |
| `onOpen` | - | Callback with `(modal, body)` |
| `onClose` | - | Callback with `(action)` |

## Dialog Options

| Option | Type | Description |
|--------|------|-------------|
| `title` | string | Dialog title |
| `message` | string | Dialog message/body |
| `type` | string | Icon type: `info`, `success`, `warning`, `alert` |
| `icon` | string | Dashicon name |
| `input` | boolean | Show input field |
| `inputValue` | string | Default input value |
| `inputPlaceholder` | string | Input placeholder text |
| `closable` | boolean | Show close button |
| `buttons` | array | Custom button configuration |

## Button Configuration

```javascript
{
    label: 'Button Text',
    action: 'action-name',  // Returned when clicked
    class: 'mosaic-btn-primary'  // Button CSS class
}
```

Special actions:
- `confirm` - Returns `true` (or input value if prompt)
- `cancel` - Returns `false` (or `null` if prompt)
- `close` - Same as cancel
- Any other string - Returns that string

## Modal HTML Structure

For custom modals, use this structure:

```html
<div class="mosaic-modal-overlay">
    <div class="mosaic-modal">
        <div class="mosaic-modal-header">
            <h2 class="mosaic-modal-title">Modal Title</h2>
            <button type="button" class="mosaic-modal-close">
                <span class="dashicons dashicons-no-alt"></span>
            </button>
        </div>
        <div class="mosaic-modal-body">
            <p>Modal content goes here.</p>
        </div>
        <div class="mosaic-modal-footer">
            <button class="mosaic-btn mosaic-btn-secondary-outline">Cancel</button>
            <button class="mosaic-btn mosaic-btn-primary">Save</button>
        </div>
    </div>
</div>
```

## Modal Sizes

```html
<div class="mosaic-modal mosaic-modal-sm">...</div>  <!-- 360px -->
<div class="mosaic-modal">...</div>                  <!-- 500px (default) -->
<div class="mosaic-modal mosaic-modal-lg">...</div>  <!-- 720px -->
<div class="mosaic-modal mosaic-modal-xl">...</div>  <!-- 960px -->
<div class="mosaic-modal mosaic-modal-full">...</div> <!-- Full screen -->
```

## Slide Panel (Side Sheet)

Full-height panel sliding in from the right:

```html
<div class="mosaic-panel-overlay"></div>
<div class="mosaic-panel">
    <div class="mosaic-panel-header">
        <h2 class="mosaic-panel-title">Panel Title</h2>
        <button type="button" class="mosaic-panel-close">
            <span class="dashicons dashicons-no-alt"></span>
        </button>
    </div>
    <div class="mosaic-panel-body">
        <!-- Panel content -->
    </div>
    <div class="mosaic-panel-footer">
        <button class="mosaic-btn mosaic-btn-primary">Save</button>
    </div>
</div>
```

### Panel Sizes

```html
<div class="mosaic-panel">...</div>                   <!-- 480px -->
<div class="mosaic-panel mosaic-panel-lg">...</div>   <!-- 640px -->
<div class="mosaic-panel mosaic-panel-xl">...</div>   <!-- 800px -->
```

## CSS Classes

| Class | Description |
|-------|-------------|
| `.mosaic-modal-overlay` | Backdrop overlay |
| `.mosaic-modal` | Modal container |
| `.mosaic-modal-sm/lg/xl/full` | Modal sizes |
| `.mosaic-modal-header` | Header section |
| `.mosaic-modal-title` | Title text |
| `.mosaic-modal-close` | Close button |
| `.mosaic-modal-body` | Content section |
| `.mosaic-modal-footer` | Footer with buttons |
| `.mosaic-modal-footer-left` | Left-aligned footer |
| `.mosaic-modal-footer-center` | Center-aligned footer |
| `.mosaic-modal-footer-between` | Space-between footer |
| `.mosaic-dialog` | Compact dialog variant |
| `.mosaic-panel` | Side panel |
| `.mosaic-panel-overlay` | Panel backdrop |

## Behavior

- Clicking the overlay closes the dialog (returns `false`/`null`)
- Pressing `Escape` closes the dialog
- For prompts, pressing `Enter` in the input confirms
- Focus is automatically set to the input or last button
- Smooth animations on open/close
