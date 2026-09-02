# Fonts

The design is set in **Estedad**, a Persian/Latin variable typeface. The binaries are
**not bundled** with this theme, because Estedad ships under the SIL Open Font License
and we would rather you drop in the exact cut and subset your project needs than carry
a stale copy in version control.

## What the theme expects

`inc/enqueue.php` emits `<link rel="preload">` tags **only if** these two files exist:

```
assets/fonts/Estedad-Regular.woff2
assets/fonts/Estedad-Bold.woff2
```

`style.css` declares the matching `@font-face` rules (weights 400 and 700, with
`font-display: swap` and a `unicode-range` covering Arabic/Persian plus Latin).

## Adding the files

1. Download the latest release from <https://github.com/aminabedi68/Estedad>.
2. Copy the `woff2` files you want into this folder, keeping the names above.
   Add `Estedad-Medium.woff2` / `Estedad-SemiBold.woff2` too if you extend the
   `@font-face` block in `style.css`.
3. Nothing else to configure — the preload hints start firing on the next page load.

## If you skip this step

The theme stays perfectly usable. The font stack degrades in this order:

```
"Estedad", "Vazirmatn", "IRANSans", "Segoe UI", Tahoma, sans-serif
```

so visitors who already have Vazirmatn or IRANSans installed (common on Persian
desktops) get a near-identical result, and everyone else falls back to Tahoma or the
system UI face. Persian digits and RTL layout do not depend on the webfont.

## Serving Estedad from a CDN instead

If you prefer a CDN, remove the `@font-face` block from `style.css` and enqueue the
provider's stylesheet from your child theme:

```php
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'estedad', 'https://cdn.example.com/estedad/estedad.css', array(), null );
}, 5 );
```

The theme already emits `preconnect` hints for external font hosts when such a
stylesheet is queued, so no extra resource-hint work is needed.

## Licence

Estedad is © Amin Abedi and contributors, released under the SIL Open Font License 1.1.
Keep a copy of `OFL.txt` next to the font files when you redistribute the theme.
