<?php

declare(strict_types=1);

namespace Sherotree\Uwarp\Contrast\Tests;

use PHPUnit\Framework\TestCase;
use Sherotree\Uwarp\Contrast\Rgb;

use function Sherotree\Uwarp\Contrast\evaluateContrast;
use function Sherotree\Uwarp\Contrast\getContrastRatio;
use function Sherotree\Uwarp\Contrast\meetsWcag;
use function Sherotree\Uwarp\Contrast\parseHex;
use function Sherotree\Uwarp\Contrast\relativeLuminance;

final class ContrastTest extends TestCase
{
    public function testParseHexSixDigitWithAndWithoutHash(): void
    {
        $this->assertEquals(new Rgb(255, 255, 255), parseHex('#ffffff'));
        $this->assertEquals(new Rgb(0, 0, 0), parseHex('000000'));
    }

    public function testParseHexExpandsThreeDigitShorthand(): void
    {
        $this->assertEquals(new Rgb(255, 255, 255), parseHex('#fff'));
        $this->assertEquals(new Rgb(0, 0, 0), parseHex('000'));
    }

    public function testRelativeLuminanceMatchesKnownSrgbValues(): void
    {
        $this->assertEqualsWithDelta(1.0, relativeLuminance(new Rgb(255, 255, 255)), 0.00001);
        $this->assertEqualsWithDelta(0.0, relativeLuminance(new Rgb(0, 0, 0)), 0.00001);
    }

    public function testBlackOnWhiteIsTwentyOneToOne(): void
    {
        $this->assertSame(21.0, getContrastRatio('#000', '#fff'));
    }

    public function testEvaluateContrastIncludesWcagRows(): void
    {
        $result = evaluateContrast('#767676', '#ffffff');
        $this->assertGreaterThan(4, $result->ratio);
        $this->assertLessThan(5, $result->ratio);

        $aaNormal = null;
        foreach ($result->wcag as $w) {
            if ($w->level === 'AA' && $w->textSize === 'normal') {
                $aaNormal = $w;
                break;
            }
        }

        $this->assertNotNull($aaNormal);
        $this->assertTrue($aaNormal->pass);
    }

    public function testMeetsWcagForAaLargeText(): void
    {
        $this->assertTrue(meetsWcag('#000', '#fff', 'AA', 'large'));
        $this->assertFalse(meetsWcag('#777', '#888', 'AA', 'normal'));
    }

    public function testAcceptsRgbObjects(): void
    {
        $this->assertSame(
            21.0,
            getContrastRatio(new Rgb(0, 0, 0), new Rgb(255, 255, 255)),
        );
    }
}
