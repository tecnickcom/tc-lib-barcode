<?php

/**
 * MathTest.php
 *
 * @since       2026-08-06
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2026 Nicola Asuni - Tecnick.com LTD
 * @license     https://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 *
 * This file is part of tc-lib-barcode software library.
 */

namespace Test;

use Com\Tecnick\Barcode\Barcode;
use Com\Tecnick\Barcode\Math;
use PHPUnit\Framework\Attributes\DataProvider;
use Test\Fixture\FallbackMathImb;
use Test\Fixture\FallbackMathPdfFourOneSeven;

/**
 * Math class test
 *
 * @since       2026-08-06
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2026 Nicola Asuni - Tecnick.com LTD
 * @license     https://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 */
class MathTest extends TestUtil
{
    /**
     * Operands with the expected sum, product, quotient and remainder.
     *
     * @return array<int, array{string, string, string, string, string, string}>
     */
    public static function getOperationsProvider(): array
    {
        return [
            ['7', '2', '9', '14', '3', '1'],
            ['000123', '0045', '168', '5535', '2', '33'],
            ['0', '12', '12', '0', '0', '0'],
            ['99', '1', '100', '99', '99', '0'],
            ['5', '5', '10', '25', '1', '0'],
            ['1099511627776', '255', '1099511628031', '280375465082880', '4311810305', '1'],
            [
                '1000000000000000000000000000000',
                '999',
                '1000000000000000000000000000999',
                '999000000000000000000000000000000',
                '1001001001001001001001001001',
                '1',
            ],
            [
                '123456789012345678901234567890',
                '987654321',
                '123456789012345678902222222211',
                '121932631124828532112482853211126352690',
                '124999998873437499901',
                '574845669',
            ],
            [
                '987654321098765432109876543210',
                '123456789012345678901234567890',
                '1111111110111111111011111111100',
                '121932631137021795226185032733622923332237463801111263526900',
                '8',
                '9000000000900000000090',
            ],
            [
                '100000000000000000000000000000000000000000000',
                '900',
                '100000000000000000000000000000000000000000900',
                '90000000000000000000000000000000000000000000000',
                '111111111111111111111111111111111111111111',
                '100',
            ],
            [
                '19999999999999999999999999999999999999999999',
                '900',
                '20000000000000000000000000000000000000000899',
                '17999999999999999999999999999999999999999999100',
                '22222222222222222222222222222222222222222',
                '199',
            ],
        ];
    }

    #[DataProvider('getOperationsProvider')]
    public function testOperations(
        string $left,
        string $right,
        string $sum,
        string $product,
        string $quotient,
        string $remainder,
    ): void {
        $this->assertSame($sum, Math::add($left, $right));
        $this->assertSame($product, Math::mul($left, $right));
        $this->assertSame($quotient, Math::div($left, $right));
        $this->assertSame($remainder, Math::mod($left, $right));
    }

    #[DataProvider('getOperationsProvider')]
    public function testFallbackOperations(
        string $left,
        string $right,
        string $sum,
        string $product,
        string $quotient,
        string $remainder,
    ): void {
        $this->assertSame($sum, Math::fallbackAdd($left, $right));
        $this->assertSame($product, Math::fallbackMul($left, $right));
        $this->assertSame($quotient, Math::fallbackDiv($left, $right));
        $this->assertSame($remainder, Math::fallbackMod($left, $right));
    }

    public function testHasBcmath(): void
    {
        $this->assertSame(\function_exists('bcadd'), Math::hasBcmath());
    }

    public function testZeroOperands(): void
    {
        $this->assertSame('0', Math::add('0', '00'));
        $this->assertSame('0', Math::fallbackAdd('0', '00'));
        $this->assertSame('0', Math::mul('0', '123'));
        $this->assertSame('0', Math::fallbackMul('123', '0'));
    }

    public function testDivisionByZero(): void
    {
        $this->bcExpectException(\DivisionByZeroError::class);
        Math::div('123', '0');
    }

    public function testFallbackDivisionByZero(): void
    {
        $this->bcExpectException(\DivisionByZeroError::class);
        Math::fallbackDiv('123', '0');
    }

    public function testFallbackModuloByZero(): void
    {
        $this->bcExpectException(\DivisionByZeroError::class);
        Math::fallbackMod('123', '0');
    }

    /**
     * @return array<int, array{string}>
     */
    public static function getInvalidNumberProvider(): array
    {
        return [[''], ['abc'], ['-5'], ['1.5'], ['12 '], ['+1']];
    }

    #[DataProvider('getInvalidNumberProvider')]
    public function testInvalidNumber(string $number): void
    {
        $this->bcExpectException(\ValueError::class);
        Math::add($number, '1');
    }

    #[DataProvider('getInvalidNumberProvider')]
    public function testFallbackInvalidNumber(string $number): void
    {
        $this->bcExpectException(\ValueError::class);
        Math::fallbackMul('1', $number);
    }

    /**
     * @return array<int, array{string}>
     */
    public static function getImbCodeProvider(): array
    {
        return [
            ['00000-'],
            ['0123456789'],
            ['01234567094987654321-01234567891'],
            ['01234567094987654321-012345678'],
            ['01234567094987654321-01234'],
        ];
    }

    /**
     * The IMB grid must be the same with and without the bcmath extension.
     *
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    #[DataProvider('getImbCodeProvider')]
    public function testImbFallbackParity(string $code): void
    {
        $barcode = new Barcode();
        $expected = $barcode->getBarcodeObj('IMB', $code)->getGrid();
        $fallback = new FallbackMathImb($code);
        $this->assertSame($expected, $fallback->getGrid());
    }

    /**
     * @return array<int, array{string}>
     */
    public static function getPdfFourOneSevenCodeProvider(): array
    {
        return [
            ['0123456789'],
            ['1234567890123456789012345678901234567890123456789012345678901234567890'],
            ['Tecnick.com PDF417 test 1234567890'],
            ["\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0a\x0b\x0c"],
        ];
    }

    /**
     * The PDF417 grid must be the same with and without the bcmath extension.
     *
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    #[DataProvider('getPdfFourOneSevenCodeProvider')]
    public function testPdfFourOneSevenFallbackParity(string $code): void
    {
        $barcode = new Barcode();
        $expected = $barcode->getBarcodeObj('PDF417', $code)->getGrid();
        $fallback = new FallbackMathPdfFourOneSeven($code);
        $this->assertSame($expected, $fallback->getGrid());
    }
}
