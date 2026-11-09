<?php

declare(strict_types=1);

namespace Sherotree\Uwarp\Contrast;

use InvalidArgumentException;

final class Color
{
    private const HEX_SHORT = '/^#?([0-9a-fA-F]{3})$/';
    private const HEX_LONG = '/^#?([0-9a-fA-F]{6})$/';

    /**
     * Parse hex (#RGB, #RRGGBB, with or without `#`) into 0–255 RGB.
     */
    public static function parseHex(string $hex): Rgb
    {
        $normalized = self::expandShortHex(trim($hex));
        if (!preg_match(self::HEX_LONG, $normalized, $matches)) {
            throw new InvalidArgumentException("Invalid hex color: {$hex}");
        }

        $n = (int) hexdec($matches[1]);

        return new Rgb(
            ($n >> 16) & 0xFF,
            ($n >> 8) & 0xFF,
            $n & 0xFF,
        );
    }

    /**
     * @param string|Rgb $input
     */
    public static function normalizeColor(string|Rgb $input): Rgb
    {
        if ($input instanceof Rgb) {
            foreach (['r' => $input->r, 'g' => $input->g, 'b' => $input->b] as $name => $v) {
                if (!is_finite((float) $v) || $v < 0 || $v > 255) {
                    throw new InvalidArgumentException("Invalid RGB {$name}: {$v}");
                }
            }

            return $input;
        }

        return self::parseHex($input);
    }

    /** WCAG relative luminance for sRGB (0–1). */
    public static function relativeLuminance(Rgb $rgb): float
    {
        $channel = static function (int $c): float {
            $s = $c / 255.0;

            return $s <= 0.03928
                ? $s / 12.92
                : (($s + 0.055) / 1.055) ** 2.4;
        };

        return 0.2126 * $channel($rgb->r)
            + 0.7152 * $channel($rgb->g)
            + 0.0722 * $channel($rgb->b);
    }

    /** Contrast ratio (1–21) per WCAG 2.x. */
    public static function contrastRatio(Rgb $foreground, Rgb $background): float
    {
        $l1 = self::relativeLuminance($foreground);
        $l2 = self::relativeLuminance($background);
        $lighter = max($l1, $l2);
        $darker = min($l1, $l2);

        return ($lighter + 0.05) / ($darker + 0.05);
    }

    private static function expandShortHex(string $hex): string
    {
        if (!preg_match(self::HEX_SHORT, $hex, $matches)) {
            return $hex;
        }

        $chars = str_split($matches[1]);

        return '#' . $chars[0] . $chars[0] . $chars[1] . $chars[1] . $chars[2] . $chars[2];
    }
}
