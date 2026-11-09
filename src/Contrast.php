<?php

declare(strict_types=1);

namespace Sherotree\Uwarp\Contrast;

final class Contrast
{
    /** WCAG 2.x minimum contrast ratios for text. */
    public const WCAG_THRESHOLDS = [
        ['level' => 'AA', 'textSize' => 'normal', 'minRatio' => 4.5],
        ['level' => 'AA', 'textSize' => 'large', 'minRatio' => 3.0],
        ['level' => 'AAA', 'textSize' => 'normal', 'minRatio' => 7.0],
        ['level' => 'AAA', 'textSize' => 'large', 'minRatio' => 4.5],
    ];

    /**
     * Compute contrast ratio and WCAG AA/AAA pass/fail for normal and large text.
     *
     * @param string|Rgb $foreground
     * @param string|Rgb $background
     */
    public static function evaluateContrast(string|Rgb $foreground, string|Rgb $background): ContrastResult
    {
        $fg = Color::normalizeColor($foreground);
        $bg = Color::normalizeColor($background);
        $ratio = Color::contrastRatio($fg, $bg);
        $rounded = self::roundRatio($ratio);

        $wcag = [];
        foreach (self::WCAG_THRESHOLDS as $req) {
            $wcag[] = new WcagPassResult(
                $req['level'],
                $req['textSize'],
                $req['minRatio'],
                $ratio >= $req['minRatio'],
            );
        }

        return new ContrastResult(
            $fg,
            $bg,
            $rounded,
            number_format($rounded, 2, '.', ''),
            $wcag,
        );
    }

    /**
     * Shorthand: get numeric contrast ratio only.
     *
     * @param string|Rgb $foreground
     * @param string|Rgb $background
     */
    public static function getContrastRatio(string|Rgb $foreground, string|Rgb $background): float
    {
        return self::roundRatio(
            Color::contrastRatio(
                Color::normalizeColor($foreground),
                Color::normalizeColor($background),
            ),
        );
    }

    /**
     * Check a single WCAG requirement.
     *
     * @param string|Rgb $foreground
     * @param string|Rgb $background
     */
    public static function meetsWcag(
        string|Rgb $foreground,
        string|Rgb $background,
        string $level,
        string $textSize,
    ): bool {
        $ratio = Color::contrastRatio(
            Color::normalizeColor($foreground),
            Color::normalizeColor($background),
        );

        foreach (self::WCAG_THRESHOLDS as $req) {
            if ($req['level'] === $level && $req['textSize'] === $textSize) {
                return $ratio >= $req['minRatio'];
            }
        }

        return false;
    }

    public static function roundRatio(float $ratio): float
    {
        return round($ratio, 2);
    }
}
