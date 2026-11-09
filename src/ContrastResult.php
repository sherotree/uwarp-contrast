<?php

declare(strict_types=1);

namespace Sherotree\Uwarp\Contrast;

final class ContrastResult
{
    /**
     * @param list<WcagPassResult> $wcag
     */
    public function __construct(
        public readonly Rgb $foreground,
        public readonly Rgb $background,
        public readonly float $ratio,
        public readonly string $ratioFormatted,
        public readonly array $wcag,
    ) {
    }
}
