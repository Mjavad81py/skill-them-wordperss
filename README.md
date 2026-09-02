# هوشینکس — قالب و افزونهٔ وردپرس

تبدیل طرح تک‌صفحه‌ای «هوشینکس» به یک قالب کامل وردپرس + افزونهٔ ویجت المنتور،
برای فروشگاه آنلاین محصولات دیجیتال.

## محتوای مخزن

| مسیر | توضیح |
| --- | --- |
| `hooshinex/` | قالب وردپرس — [راهنما](hooshinex/README.md) |
| `hooshinex-widgets/` | افزونهٔ ویجت‌های المنتور — [راهنما](hooshinex-widgets/README.md) |
| `release/` | فایل‌های zip آمادهٔ نصب |
| `skill-themp-wordpress.zip` | بستهٔ اصلی مهارت `elementor-theme-builder` |

قالب و افزونه دو بستهٔ مستقل‌اند: ویجت‌ها عمداً بیرون از قالب نگه داشته شده‌اند
تا با عوض کردن قالب، محتوای صفحه‌های ساخته‌شده در المنتور از بین نرود.

## نصب سریع

دو راه دارید.

**الف) از پیشخوان وردپرس (ساده‌ترین)**

فایل‌های آمادهٔ نصب در پوشهٔ `release/` هستند:

| فایل | جای نصب |
| --- | --- |
| `release/hooshinex-theme.zip` | نمایش ← پوسته‌ها ← افزودن ← بارگذاری پوسته |
| `release/hooshinex-widgets-plugin.zip` | افزونه‌ها ← افزودن ← بارگذاری افزونه |
| `release/hooshinex-full-package.zip` | هر دو با هم (برای آرشیو؛ برای نصب از دو فایل بالا استفاده کنید) |

**ب) کپی مستقیم روی سرور**

```bash
cp -r hooshinex         /path/to/wp-content/themes/
cp -r hooshinex-widgets /path/to/wp-content/plugins/
```

سپس قالب و افزونه را فعال کنید و به **نمایش ← راه‌اندازی هوشینکس** بروید تا
صفحات ضروری و منوی اصلی یک‌بار ساخته شوند.

جزئیات پیش‌نیازها، تنظیمات سفارشی‌سازی و فهرست ویجت‌ها در README هر بسته آمده است.

## بررسی سلامت بسته

```bash
python3 ~/.claude/skills/elementor-theme-builder/scripts/verify.py \
  hooshinex hooshinex-widgets
```

## پروانه

GPL-2.0-or-later.
