# uwarp-contrast

WCAG 2.x relative luminance contrast ratios and AA/AAA pass/fail for foreground/background color pairs.

PHP Composer package — API-aligned with the npm package [`uwarp-contrast`](https://www.npmjs.com/package/uwarp-contrast).

**Live tool:** [Uwarp Colour Contrast Checker](https://www.uwarp.design/colour-contrast-checker)

## Install

```bash
composer require sherotree/uwarp-contrast
```

## API

Import the functional API (mirrors npm) or use the `Contrast` / `Color` classes directly.

```php
<?php

use function Sherotree\Uwarp\Contrast\evaluateContrast;
use function Sherotree\Uwarp\Contrast\getContrastRatio;
use function Sherotree\Uwarp\Contrast\meetsWcag;
use function Sherotree\Uwarp\Contrast\parseHex;
use Sherotree\Uwarp\Contrast\Contrast;
use Sherotree\Uwarp\Contrast\Rgb;

// Hex (#RGB / #RRGGBB, optional #) or Rgb (0–255 per channel)
getContrastRatio('#000000', '#ffffff'); // 21.0

$report = evaluateContrast('767676', '#ffffff');
// $report->ratio, $report->ratioFormatted, $report->wcag (AA/AAA × normal/large)

meetsWcag('#000', '#fff', 'AA', 'normal'); // true

// Class-based equivalent
Contrast::getContrastRatio(new Rgb(0, 0, 0), '#fff');

// WCAG minimum ratios
Contrast::WCAG_THRESHOLDS;
```

### Functions

| Function | Description |
|----------|-------------|
| `parseHex(string): Rgb` | Parse `#RGB` / `#RRGGBB` (optional `#`) |
| `normalizeColor(string\|Rgb): Rgb` | Hex string or validated `Rgb` |
| `relativeLuminance(Rgb): float` | WCAG relative luminance (0–1) |
| `contrastRatio(Rgb, Rgb): float` | Raw contrast ratio |
| `getContrastRatio(fg, bg): float` | Ratio rounded to 2 decimals |
| `evaluateContrast(fg, bg): ContrastResult` | Ratio + WCAG pass/fail rows |
| `meetsWcag(fg, bg, level, textSize): bool` | Single AA/AAA check |

**WCAG thresholds:** AA normal 4.5, AA large 3, AAA normal 7, AAA large 4.5.

## Development

```bash
composer install
composer test
```

Requires PHP 8.1+.

## Links

- npm: [uwarp-contrast](https://www.npmjs.com/package/uwarp-contrast)
- Source (npm monorepo): [sherotree/uwarp-packages — packages/contrast](https://github.com/sherotree/uwarp-packages/tree/main/packages/contrast)

## License

MIT · [Sherotree](https://github.com/sherotree)
