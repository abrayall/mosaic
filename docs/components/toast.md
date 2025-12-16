# Toast Notifications

Non-blocking popup notifications that appear temporarily.

## Basic Usage

```javascript
// Simple toast
Mosaic.toast('Your changes have been saved.');

// With type
Mosaic.toast('Operation successful!', { type: 'success' });

// Shorthand methods
Mosaic.toastSuccess('File uploaded successfully');
Mosaic.toastError('Failed to save changes');
Mosaic.toastWarning('Your session will expire soon');
Mosaic.toastInfo('New updates available');
```

## With Title

```javascript
Mosaic.toast('Your file has been uploaded and is ready to use.', {
    type: 'success',
    title: 'Upload Complete'
});
```

## Duration

```javascript
// Default: 4 seconds
Mosaic.toast('Quick message');

// Custom duration (ms)
Mosaic.toast('Longer message', { duration: 8000 });

// Persistent (no auto-close)
Mosaic.toast('Important message', { duration: 0 });
```

## With Progress Bar

```javascript
Mosaic.toast('Processing your request...', {
    type: 'info',
    progress: true,
    duration: 5000
});
```

## With Actions

```javascript
Mosaic.toast('File deleted', {
    type: 'info',
    actions: [
        {
            label: 'Undo',
            onClick: () => {
                console.log('Undo clicked');
                restoreFile();
            }
        }
    ]
});
```

## Light Variant

```javascript
Mosaic.toastSuccess('Settings saved', {
    variant: 'light'
});

Mosaic.toastError('Connection lost', {
    variant: 'light',
    title: 'Network Error'
});
```

## Position

```javascript
// Set position for all toasts
Mosaic.setToastPosition('top-right');    // default
Mosaic.setToastPosition('top-left');
Mosaic.setToastPosition('top-center');
Mosaic.setToastPosition('bottom-right');
Mosaic.setToastPosition('bottom-left');
Mosaic.setToastPosition('bottom-center');
```

## Custom Icon

```javascript
Mosaic.toast('Download starting...', {
    icon: 'download'
});
```

## Programmatic Control

```javascript
// Store reference to close later
const toast = Mosaic.toast('Processing...', {
    duration: 0,  // Persistent
    closable: false
});

// Later...
toast.close();

// Clear all toasts
Mosaic.clearToasts();
```

## Callback on Close

```javascript
Mosaic.toast('Task completed', {
    type: 'success',
    onClose: () => {
        console.log('Toast was closed');
    }
});
```

## Pause on Hover

Toasts automatically pause their timer when hovered, giving users time to read longer messages.

## Full Options Example

```javascript
Mosaic.toast('New comment on your post', {
    type: 'info',
    title: 'Notification',
    duration: 6000,
    closable: true,
    progress: true,
    icon: 'admin-comments',
    variant: 'light',
    actions: [
        {
            label: 'View',
            onClick: () => window.location.href = '/comments'
        },
        {
            label: 'Dismiss',
            onClick: () => {},
            closeOnClick: true
        }
    ],
    onClose: () => {
        console.log('Notification dismissed');
    }
});
```

## API Reference

### Mosaic.toast(message, options)

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `type` | string | `'default'` | `'default'`, `'success'`, `'error'`, `'warning'`, `'info'` |
| `title` | string | `''` | Optional title above message |
| `duration` | number | `4000` | Auto-close delay in ms (0 = persistent) |
| `closable` | boolean | `true` | Show close button |
| `progress` | boolean | `false` | Show progress bar |
| `icon` | string | auto | Dashicon name |
| `variant` | string | `'dark'` | `'dark'` or `'light'` |
| `actions` | array | `[]` | Action buttons `[{label, onClick, closeOnClick}]` |
| `onClose` | function | - | Callback when toast closes |

### Shorthand Methods

- `Mosaic.toastSuccess(message, options)`
- `Mosaic.toastError(message, options)`
- `Mosaic.toastWarning(message, options)`
- `Mosaic.toastInfo(message, options)`

### Utility Methods

- `Mosaic.setToastPosition(position)` - Set default position
- `Mosaic.clearToasts()` - Remove all toasts

## CSS Classes

| Class | Description |
|-------|-------------|
| `.mosaic-toast-container` | Container for all toasts |
| `.mosaic-toast` | Individual toast |
| `.mosaic-toast-success` | Success variant |
| `.mosaic-toast-error` | Error variant |
| `.mosaic-toast-warning` | Warning variant |
| `.mosaic-toast-info` | Info variant |
| `.mosaic-toast-light` | Light background variant |
| `.mosaic-toast-icon` | Icon container |
| `.mosaic-toast-content` | Content wrapper |
| `.mosaic-toast-title` | Title text |
| `.mosaic-toast-message` | Message text |
| `.mosaic-toast-close` | Close button |
| `.mosaic-toast-progress` | Progress bar |
| `.mosaic-toast-actions` | Actions container |
| `.mosaic-toast-action` | Action button |
