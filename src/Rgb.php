<?php

declare(strict_types=1);

namespace Sherotree\Uwarp\Contrast;

/**
 * sRGB color channel values in the 0–255 range.
 */
final class Rgb
{
    public function __construct(
        public readonly int $r,
        public readonly int $g,
        public readonly int $b,
    ) {
    }

    /**
     * @return array{r: int, g: int, b: int}
     */
    public function toArray(): array
    {
        return [
            'r' => $this->r,
            'g' => $this->g,
            'b' => $this->b,
        ];
    }
}
