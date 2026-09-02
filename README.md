# هوشینکس — قالب و افزونهٔ وردپرس

تبدیل طرح تک‌صفحه‌ای «هوشینکس» به یک قالب کامل وردپرس + افزونهٔ ویجت المنتور،
برای فروشگاه آنلاین محصولات دیجیتال.

## محتوای مخزن

| مسیر | توضیح |
| --- | --- |
| `build/hooshinex/` | قالب وردپرس — [راهنما](build/hooshinex/README.md) |
| `build/hooshinex-widgets/` | افزونهٔ ویجت‌های المنتور — [راهنما](build/hooshinex-widgets/README.md) |
| `skill-themp-wordpress.zip` | بستهٔ اصلی مهارت `elementor-theme-builder` |

قالب و افزونه دو بستهٔ مستقل‌اند: ویجت‌ها عمداً بیرون از قالب نگه داشته شده‌اند
تا با عوض کردن قالب، محتوای صفحه‌های ساخته‌شده در المنتور از بین نرود.

## نصب سریع

```bash
cp -r build/hooshinex        /path/to/wp-content/themes/
cp -r build/hooshinex-widgets /path/to/wp-content/plugins/
```

سپس قالب و افزونه را فعال کنید و به **نمایش ← راه‌اندازی هوشینکس** بروید تا
صفحات ضروری و منوی اصلی یک‌بار ساخته شوند.

جزئیات پیش‌نیازها، تنظیمات سفارشی‌سازی و فهرست ویجت‌ها در README هر بسته آمده است.

## بررسی سلامت بسته

```bash
python3 ~/.claude/skills/elementor-theme-builder/scripts/verify.py \
  build/hooshinex build/hooshinex-widgets
```

## پروانه

GPL-2.0-or-later.
