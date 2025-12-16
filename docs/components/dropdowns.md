# Dropdowns

Dropdown menus for actions and navigation.

## Basic Dropdown

```html
<div class="mosaic-dropdown" data-mosaic-dropdown>
    <button class="mosaic-btn mosaic-btn-secondary mosaic-dropdown-trigger">
        Options
        <span class="mosaic-dropdown-caret dashicons dashicons-arrow-down-alt2"></span>
    </button>
    <div class="mosaic-dropdown-menu">
        <button class="mosaic-dropdown-item" data-value="edit">Edit</button>
        <button class="mosaic-dropdown-item" data-value="duplicate">Duplicate</button>
        <button class="mosaic-dropdown-item" data-value="archive">Archive</button>
        <div class="mosaic-dropdown-divider"></div>
        <button class="mosaic-dropdown-item mosaic-dropdown-item-danger" data-value="delete">Delete</button>
    </div>
</div>
```

## With Icons

```html
<div class="mosaic-dropdown" data-mosaic-dropdown>
    <button class="mosaic-btn mosaic-btn-secondary mosaic-dropdown-trigger">
        Actions
        <span class="mosaic-dropdown-caret dashicons dashicons-arrow-down-alt2"></span>
    </button>
    <div class="mosaic-dropdown-menu">
        <button class="mosaic-dropdown-item">
            <span class="dashicons dashicons-edit"></span>
            Edit
        </button>
        <button class="mosaic-dropdown-item">
            <span class="dashicons dashicons-admin-page"></span>
            Duplicate
        </button>
        <button class="mosaic-dropdown-item">
            <span class="dashicons dashicons-download"></span>
            Export
        </button>
    </div>
</div>
```

## With Headers and Sections

```html
<div class="mosaic-dropdown mosaic-dropdown-lg" data-mosaic-dropdown>
    <button class="mosaic-btn mosaic-btn-secondary mosaic-dropdown-trigger">
        Menu
        <span class="mosaic-dropdown-caret dashicons dashicons-arrow-down-alt2"></span>
    </button>
    <div class="mosaic-dropdown-menu">
        <div class="mosaic-dropdown-header">Navigation</div>
        <a href="#" class="mosaic-dropdown-item">Dashboard</a>
        <a href="#" class="mosaic-dropdown-item">Settings</a>
        <div class="mosaic-dropdown-divider"></div>
        <div class="mosaic-dropdown-header">Account</div>
        <a href="#" class="mosaic-dropdown-item">Profile</a>
        <a href="#" class="mosaic-dropdown-item">Logout</a>
    </div>
</div>
```

## Positions

```html
<!-- Right-aligned -->
<div class="mosaic-dropdown mosaic-dropdown-right" data-mosaic-dropdown>
    <button class="mosaic-btn mosaic-btn-secondary mosaic-dropdown-trigger">Right</button>
    <div class="mosaic-dropdown-menu">...</div>
</div>

<!-- Opens upward -->
<div class="mosaic-dropdown mosaic-dropdown-up" data-mosaic-dropdown>
    <button class="mosaic-btn mosaic-btn-secondary mosaic-dropdown-trigger">Up</button>
    <div class="mosaic-dropdown-menu">...</div>
</div>
```

## Sizes

```html
<!-- Small -->
<div class="mosaic-dropdown mosaic-dropdown-sm" data-mosaic-dropdown>...</div>

<!-- Default -->
<div class="mosaic-dropdown" data-mosaic-dropdown>...</div>

<!-- Large -->
<div class="mosaic-dropdown mosaic-dropdown-lg" data-mosaic-dropdown>...</div>

<!-- Extra Large -->
<div class="mosaic-dropdown mosaic-dropdown-xl" data-mosaic-dropdown>...</div>

<!-- Full Width -->
<div class="mosaic-dropdown mosaic-dropdown-full" data-mosaic-dropdown>...</div>
```

## Disabled Items

```html
<div class="mosaic-dropdown-menu">
    <button class="mosaic-dropdown-item">Enabled</button>
    <button class="mosaic-dropdown-item" disabled>Disabled</button>
    <button class="mosaic-dropdown-item mosaic-disabled">Also Disabled</button>
</div>
```

## With Checkboxes

```html
<div class="mosaic-dropdown mosaic-dropdown-lg" data-mosaic-dropdown>
    <button class="mosaic-btn mosaic-btn-secondary mosaic-dropdown-trigger">
        Filter
        <span class="mosaic-dropdown-caret dashicons dashicons-arrow-down-alt2"></span>
    </button>
    <div class="mosaic-dropdown-menu">
        <label class="mosaic-dropdown-check">
            <input type="checkbox" checked> Active
        </label>
        <label class="mosaic-dropdown-check">
            <input type="checkbox"> Pending
        </label>
        <label class="mosaic-dropdown-check">
            <input type="checkbox"> Archived
        </label>
    </div>
</div>
```

## JavaScript API

### Initialize Dropdown

```javascript
const dropdown = Mosaic.dropdown('#my-dropdown', {
    closeOnSelect: true,    // Close when item clicked
    closeOnOutside: true,   // Close when clicking outside
    onSelect: (value, item) => {
        console.log('Selected:', value);
    },
    onOpen: () => {
        console.log('Dropdown opened');
    },
    onClose: () => {
        console.log('Dropdown closed');
    }
});

// Control programmatically
dropdown.open();
dropdown.close();
dropdown.toggle();
dropdown.isOpen(); // Returns boolean
```

### Auto-Initialize

Dropdowns with `data-mosaic-dropdown` attribute are automatically initialized:

```html
<div class="mosaic-dropdown" data-mosaic-dropdown>
    <!-- Auto-initialized -->
</div>
```

Manually initialize new dropdowns:

```javascript
Mosaic.initDropdowns();
```

## CSS Classes

| Class | Description |
|-------|-------------|
| `.mosaic-dropdown` | Dropdown container |
| `.mosaic-dropdown-trigger` | Trigger button/element |
| `.mosaic-dropdown-caret` | Arrow icon |
| `.mosaic-dropdown-menu` | Menu container |
| `.mosaic-dropdown-item` | Menu item |
| `.mosaic-dropdown-item-danger` | Danger/destructive item |
| `.mosaic-dropdown-divider` | Horizontal divider |
| `.mosaic-dropdown-header` | Section header |
| `.mosaic-dropdown-check` | Checkbox/radio item |
| `.mosaic-dropdown-right` | Right-aligned menu |
| `.mosaic-dropdown-up` | Opens upward |
| `.mosaic-dropdown-sm` | Small menu (140px) |
| `.mosaic-dropdown-lg` | Large menu (240px) |
| `.mosaic-dropdown-xl` | Extra large (320px) |
| `.mosaic-dropdown-full` | Full width |
| `.mosaic-open` | Open state |
| `.mosaic-disabled` | Disabled state |

## Keyboard Navigation

- `Enter` / `Space` / `ArrowDown` on trigger - Open dropdown
- `ArrowDown` / `ArrowUp` - Navigate items
- `Enter` on item - Select item
- `Escape` - Close dropdown
