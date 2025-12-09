# Typography

Mosaic uses the WordPress default system font stack for optimal performance and native feel.

## Font Families

| Variable | Value | Usage |
|----------|-------|-------|
| `--mosaic-font-family` | System fonts | Body text |
| `--mosaic-font-mono` | `Menlo, Consolas, monaco, monospace` | Code |

The system font stack includes:
- Apple: San Francisco
- Windows: Segoe UI
- Android: Roboto
- Linux: Ubuntu, Cantarell
- Fallback: Helvetica Neue, sans-serif

## Font Sizes

| Variable | Value | Usage |
|----------|-------|-------|
| `--mosaic-font-size-xs` | `11px` | Badges, labels |
| `--mosaic-font-size-sm` | `12px` | Small text, help text |
| `--mosaic-font-size-md` | `13px` | Buttons, table headers |
| `--mosaic-font-size-base` | `14px` | Body text |
| `--mosaic-font-size-lg` | `15px` | Large body text |
| `--mosaic-font-size-xl` | `16px` | Subheadings |
| `--mosaic-font-size-2xl` | `20px` | H4 |
| `--mosaic-font-size-3xl` | `24px` | H3 |
| `--mosaic-font-size-4xl` | `28px` | H2 |
| `--mosaic-font-size-5xl` | `32px` | H1 |
| `--mosaic-font-size-6xl` | `42px` | Stat values |

## Font Weights

| Variable | Value | Usage |
|----------|-------|-------|
| `--mosaic-font-weight-normal` | `400` | Body text |
| `--mosaic-font-weight-medium` | `500` | Buttons, emphasized |
| `--mosaic-font-weight-semibold` | `600` | Headings, labels |
| `--mosaic-font-weight-bold` | `700` | Strong emphasis |

## Line Heights

| Variable | Value | Usage |
|----------|-------|-------|
| `--mosaic-line-height-tight` | `1.2` | Headings |
| `--mosaic-line-height-normal` | `1.4` | Default |
| `--mosaic-line-height-relaxed` | `1.6` | Body text |

## Heading Classes

```html
<h1 class="mosaic-heading-1">Page Title</h1>
<h2 class="mosaic-heading-2">Section Header</h2>
<h3 class="mosaic-heading-3">Subsection</h3>
<h4 class="mosaic-heading-4">Card Title</h4>
```

| Class | Font Size | Weight |
|-------|-----------|--------|
| `.mosaic-heading-1` | 32px | 400 |
| `.mosaic-heading-2` | 28px | 600 |
| `.mosaic-heading-3` | 24px | 600 |
| `.mosaic-heading-4` | 20px | 600 |

## Text Classes

```html
<p class="mosaic-text">Regular body text</p>
<p class="mosaic-text-sm">Small text for descriptions</p>
<p class="mosaic-text-muted">Muted/secondary text</p>
<code class="mosaic-text-mono">Monospace code</code>
```

## Text Utilities

### Alignment

```html
<p class="mosaic-text-left">Left aligned</p>
<p class="mosaic-text-center">Center aligned</p>
<p class="mosaic-text-right">Right aligned</p>
```

### Transformation

```html
<span class="mosaic-uppercase">UPPERCASE</span>
<span class="mosaic-lowercase">lowercase</span>
<span class="mosaic-capitalize">Capitalized</span>
```

### Weight

```html
<span class="mosaic-font-normal">Normal weight</span>
<span class="mosaic-font-medium">Medium weight</span>
<span class="mosaic-font-semibold">Semibold weight</span>
<span class="mosaic-font-bold">Bold weight</span>
```

### Truncation

```html
<!-- Single line truncation with ellipsis -->
<p class="mosaic-truncate">Very long text that will be truncated...</p>

<!-- Word wrap for long words -->
<p class="mosaic-break-word">Verylongwordthatneedstobreak</p>
```

## Links

```html
<a href="#" class="mosaic-link">Standard link</a>
```

Links use the primary color (`--mosaic-primary`) and darken on hover.

## Usage in PHP

```php
// Using Mosaic for page headers
echo Mosaic::page_start( 'My Plugin Settings' );

// Content goes here

echo Mosaic::page_end();
```
