# Colors & Design Tokens

Mosaic uses CSS custom properties (variables) for all colors and design tokens. This allows for consistent theming and easy customization.

## Color Palette

### Primary Colors

| Variable | Value | Usage |
|----------|-------|-------|
| `--mosaic-primary` | `#2271b1` | Primary actions, links, active states |
| `--mosaic-primary-hover` | `#135e96` | Primary hover states |
| `--mosaic-primary-dark` | `#0a4166` | Primary active states |
| `--mosaic-primary-light` | `#e5f3ff` | Primary backgrounds |

### Status Colors

| Variable | Value | Usage |
|----------|-------|-------|
| `--mosaic-success` | `#00a32a` | Success, healthy states |
| `--mosaic-success-dark` | `#007a20` | Success hover |
| `--mosaic-success-light` | `#d5f3e5` | Success backgrounds |
| `--mosaic-warning` | `#dba617` | Warning states |
| `--mosaic-warning-dark` | `#b98900` | Warning hover |
| `--mosaic-warning-light` | `#fcf0d2` | Warning backgrounds |
| `--mosaic-critical` | `#d63638` | Error, critical states |
| `--mosaic-critical-dark` | `#b91c1c` | Error hover |
| `--mosaic-critical-light` | `#fcdddd` | Error backgrounds |
| `--mosaic-info` | `#72aee6` | Info states |
| `--mosaic-info-dark` | `#4f94d4` | Info hover |
| `--mosaic-info-light` | `#e7f4fd` | Info backgrounds |

### Neutral Colors

| Variable | Value | Usage |
|----------|-------|-------|
| `--mosaic-text` | `#1d2327` | Primary text |
| `--mosaic-text-secondary` | `#50575e` | Secondary text |
| `--mosaic-text-tertiary` | `#646970` | Tertiary/muted text |
| `--mosaic-text-disabled` | `#8c8f94` | Disabled text |
| `--mosaic-text-inverse` | `#ffffff` | Text on dark backgrounds |
| `--mosaic-border` | `#ccd0d4` | Default borders |
| `--mosaic-border-light` | `#dcdcde` | Light borders |
| `--mosaic-border-dark` | `#8c8f94` | Dark borders |
| `--mosaic-bg` | `#ffffff` | Default background |
| `--mosaic-bg-alt` | `#f6f7f7` | Alternate background |
| `--mosaic-bg-header` | `#f0f0f1` | Header backgrounds |
| `--mosaic-bg-hover` | `#f6f7f7` | Hover states |

## Usage in CSS

```css
.my-element {
    color: var(--mosaic-text);
    background-color: var(--mosaic-bg);
    border: 1px solid var(--mosaic-border);
}

.my-element:hover {
    background-color: var(--mosaic-bg-hover);
}

.my-success-element {
    color: var(--mosaic-success);
    background-color: var(--mosaic-success-light);
}
```

## Spacing Scale

| Variable | Value | Usage |
|----------|-------|-------|
| `--mosaic-space-xs` | `4px` | Extra small spacing |
| `--mosaic-space-sm` | `8px` | Small spacing |
| `--mosaic-space-md` | `12px` | Medium spacing |
| `--mosaic-space-lg` | `16px` | Large spacing |
| `--mosaic-space-xl` | `20px` | Extra large spacing |
| `--mosaic-space-2xl` | `24px` | 2x extra large |
| `--mosaic-space-3xl` | `32px` | 3x extra large |
| `--mosaic-space-4xl` | `40px` | 4x extra large |

## Border Radius

| Variable | Value | Usage |
|----------|-------|-------|
| `--mosaic-radius-sm` | `3px` | Small elements |
| `--mosaic-radius-md` | `4px` | Default |
| `--mosaic-radius-lg` | `8px` | Cards, modals |
| `--mosaic-radius-xl` | `12px` | Large elements |
| `--mosaic-radius-full` | `9999px` | Pills, circles |

## Shadows

| Variable | Value | Usage |
|----------|-------|-------|
| `--mosaic-shadow-sm` | `0 1px 2px rgba(0,0,0,.05)` | Subtle shadow |
| `--mosaic-shadow-md` | `0 2px 4px rgba(0,0,0,.06)` | Default shadow |
| `--mosaic-shadow-lg` | `0 4px 8px rgba(0,0,0,.1)` | Elevated |
| `--mosaic-shadow-xl` | `0 6px 12px rgba(0,0,0,.12)` | Hover states |
| `--mosaic-shadow-2xl` | `0 10px 40px rgba(0,0,0,.3)` | Modals |
| `--mosaic-shadow-primary` | `0 4px 8px rgba(34,113,177,.2)` | Primary highlight |

## Transitions

| Variable | Value | Usage |
|----------|-------|-------|
| `--mosaic-transition-fast` | `0.15s ease` | Hover states |
| `--mosaic-transition-normal` | `0.2s ease` | Default |
| `--mosaic-transition-slow` | `0.3s ease` | Complex animations |

## Z-Index Scale

| Variable | Value | Usage |
|----------|-------|-------|
| `--mosaic-z-dropdown` | `1000` | Dropdowns |
| `--mosaic-z-sticky` | `1020` | Sticky headers |
| `--mosaic-z-fixed` | `1030` | Fixed elements |
| `--mosaic-z-modal-backdrop` | `1040` | Modal overlays |
| `--mosaic-z-modal` | `100000` | Modals (WordPress admin) |
| `--mosaic-z-tooltip` | `100010` | Tooltips |

## Utility Classes

Mosaic provides utility classes for common color applications:

```html
<!-- Text colors -->
<span class="mosaic-color-primary">Primary text</span>
<span class="mosaic-color-success">Success text</span>
<span class="mosaic-color-warning">Warning text</span>
<span class="mosaic-color-critical">Critical text</span>
<span class="mosaic-color-muted">Muted text</span>

<!-- Background colors -->
<div class="mosaic-bg-white">White background</div>
<div class="mosaic-bg-alt">Alternate background</div>
<div class="mosaic-bg-success-light">Success light background</div>
<div class="mosaic-bg-warning-light">Warning light background</div>
<div class="mosaic-bg-critical-light">Critical light background</div>
```
