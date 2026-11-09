<?php

declare(strict_types=1);

namespace Sherotree\Uwarp\Contrast;

/**
 * Global functions mirroring the uwarp-contrast npm package API.
 *
 * Prefer importing these with `use function Sherotree\Uwarp\Contrast\…`.
 */

function parseHex(string $hex): Rgb
{
    return Color::parseHex($hex);
}

function normalizeColor(string|Rgb $input): Rgb
{
    return Color::normalizeColor($input);
}

function relativeLuminance(Rgb $rgb): float
{
    return Color::relativeLuminance($rgb);
}

function contrastRatio(Rgb $foreground, Rgb $background): float
{
    return Color::contrastRatio($foreground, $background);
}

function evaluateContrast(string|Rgb $foreground, string|Rgb $background): ContrastResult
{
    return Contrast::evaluateContrast($foreground, $background);
}

function getContrastRatio(string|Rgb $foreground, string|Rgb $background): float
{
    return Contrast::getContrastRatio($foreground, $background);
}

function meetsWcag(
    string|Rgb $foreground,
    string|Rgb $background,
    string $level,
    string $textSize,
): bool {
    return Contrast::meetsWcag($foreground, $background, $level, $textSize);
}
