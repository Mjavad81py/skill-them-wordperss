# Hooshinex Widgets

Elementor widgets and dynamic tags for the Hooshinex storefront.

They live in a plugin rather than the theme on purpose: page content built with these
widgets survives a theme switch, which is not true of widgets registered by a theme.

---

## Requirements

| Item | Minimum |
| --- | --- |
| WordPress | 6.0 |
| PHP | 7.4 |
| Elementor | 3.5 |
| WooCommerce | 8.0 (shop widgets only) |

The plugin checks all three on load and shows a dismissible admin notice instead of
fataling when something is missing.

## Install

1. Copy `hooshinex-widgets` into `wp-content/plugins/`.
2. Activate it from **Plugins**.
3. Open any page in Elementor — the widgets sit under the **Hooshinex** panel category.

## Widgets

| Widget | Data source | Notes |
| --- | --- | --- |
| Hero | Manual | Headline, description, search field, stats repeater, illustration |
| Section Heading | Manual | The two-tone "accent + title" heading with an optional link |
| Feature Cards | Manual | Icon/title/description repeater |
| Promo Banners | Manual | Two-up linked promo tiles, navy or gold |
| Seller CTA | Manual | Headline, two buttons, selling-point repeater |
| Category Grid | Any public taxonomy | Grid or carousel, with counts |
| Post Loop | Posts | Query-driven article grid |
| Latest Questions | Approved comments | Carousel of question cards |
| Offer Banner | Manual or WooCommerce | Live countdown; can read the next sale end date |
| Product Grid | WooCommerce | Query-driven product grid |
| Product Carousel | WooCommerce | Latest / featured / on sale / best selling |
| Amazing Product | WooCommerce | Spotlight slider with a thumbnail rail |

Shop widgets are only registered when WooCommerce is active, so the panel never shows
a widget that cannot render.

## Dynamic tags

| Tag | Group | Returns |
| --- | --- | --- |
| Reading Time | Hooshinex | Estimated reading time for the current post |

## Working with a different theme

Every widget renders standalone. When the Hooshinex theme is active the widgets reuse
its markup, icons and CSS so they blend into the page; otherwise `assets/css/widgets.css`
supplies a low-specificity (`:where()`) fallback layout, and `assets/js/widgets.js`
supplies its own carousel, spotlight and countdown behaviour.

## Assets

Styles and scripts are **registered**, never enqueued globally. Each widget declares
them through `get_style_depends()` / `get_script_depends()`, so a page without these
widgets ships none of their CSS or JS.

## Uninstall

`uninstall.php` removes the plugin's own options and transients. Page content is left
alone — that belongs to Elementor.

## Translation

```bash
wp i18n make-pot . languages/hooshinex-widgets.pot --domain=hooshinex-widgets
```

## Changelog

### 1.0.0

- Initial release: hero, section heading, category grid, promo banners, offer banner,
  latest questions, seller CTA, product carousel and amazing-product widgets added to
  the existing feature-cards, post-loop and product-grid set.

## License

GPL-2.0-or-later.
