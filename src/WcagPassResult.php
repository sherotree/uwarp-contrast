<?php

declare(strict_types=1);

namespace Sherotree\Uwarp\Contrast;

final class WcagPassResult
{
    public function __construct(
        public readonly string $level,
        public readonly string $textSize,
        public readonly float $minRatio,
        public readonly bool $pass,
    ) {
    }
}
