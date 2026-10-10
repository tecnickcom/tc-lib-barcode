<?php

/**
 * BarcodeTest.php
 *
 * @since       2015-02-21
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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

/**
 * Barcode class test
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2026 Nicola Asuni - Tecnick.com LTD
 * @license     https://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 */
class BarcodeTest extends TestUtil
{
    protected function getTestObject(): \Com\Tecnick\Barcode\Barcode
    {
        return new \Com\Tecnick\Barcode\Barcode();
    }

    /**
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    public function testGetBarcodeObjException(): void
    {
        $this->bcExpectException(\Com\Tecnick\Barcode\Exception::class);
        $barcode = $this->getTestObject();
        $barcode->getBarcodeObj('ERROR', '01001100011100001111,10110011100011110000', -2, -2, 'purple');
    }

    /**
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    public function testSetPaddingException(): void
    {
        $this->bcExpectException(\Com\Tecnick\Barcode\Exception::class);
        $barcode = $this->getTestObject();
        /** @var array{0: int, 1: int, 2: int, 3: int} $invalidPadding */
        $invalidPadding = [10];
        $barcode->getBarcodeObj(
            'LRAW,AB,12,E3F',
            '01001100011100001111,10110011100011110000',
            -2,
            -2,
            'purple',
            $invalidPadding,
        );
    }

    /**
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    public function testEmptyColumns(): void
    {
        $this->bcExpectException(\Com\Tecnick\Barcode\Exception::class);
        $barcode = $this->getTestObject();
        $barcode->getBarcodeObj('LRAW', '');
    }

    /**
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    public function testEmptyInput(): void
    {
        $this->bcExpectException(\Com\Tecnick\Barcode\Exception::class);
        $barcode = $this->getTestObject();
        $barcode->getBarcodeObj('LRAW', '');
    }

    /**
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    public function testSpotColor(): void
    {
        $barcode = $this->getTestObject();
        $type = $barcode->getBarcodeObj('LRAW', '01001100011100001111,10110011100011110000', -2, -2, 'all', [
            -2,
            3,
            0,
            1,
        ]);
        $bobjarr = $type->getArray();
        $this->assertEquals('#000000ff', $bobjarr['color_obj']->getRgbaHexColor());
        $this->assertNUll($bobjarr['bg_color_obj']);
    }

    /**
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    public function testBackgroundColor(): void
    {
        $barcode = $this->getTestObject();
        $type = $barcode->getBarcodeObj('LRAW', '01001100011100001111,10110011100011110000', -2, -2, 'all', [
            -2,
            3,
            0,
            1,
        ])->setBackgroundColor('mediumaquamarine');
        $bobjarr = $type->getArray();
        $this->assertNotNull($bobjarr['bg_color_obj']);
        $this->assertEquals('#66cdaaff', $bobjarr['bg_color_obj']->getRgbaHexColor());
    }

    /**
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    #[DataProvider('getColorProvider')]
    public function testColorResolutionOrder(string $color, string $expected): void
    {
        $barcode = $this->getTestObject();
        $type = $barcode->getBarcodeObj('LRAW', '0101', -2, -2, $color);
        $this->assertEquals($expected, $type->getArray()['color_obj']->getRgbaHexColor());
    }

    /**
     * @return array<array{string, string}>
     */
    public static function getColorProvider(): array
    {
        return [
            ['green',             '#008000ff'], // web color name, not the spot color of the same name
            ['#00ff00',           '#00ff00ff'],
            ['rgb(0,128,0)',      '#008000ff'],
            ['hsl(120,100%,25%)', '#008000ff'],
            ['all',               '#000000ff'], // spot-only color name
            ['none',              '#ffffffff'], // spot-only color name
        ];
    }

    /**
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    #[DataProvider('getInvalidColorProvider')]
    public function testInvalidColorException(string $color): void
    {
        $this->bcExpectException(\Com\Tecnick\Color\Exception::class);
        $barcode = $this->getTestObject();
        $barcode->getBarcodeObj('LRAW', '0101', -2, -2, $color);
    }

    /**
     * @return array<array{string}>
     */
    public static function getInvalidColorProvider(): array
    {
        return [
            ['#GGG'],
            ['notacolor'],
            ['rgb(1.2.3,4,5)'],
            ['unsupported(1,2,3)'],
        ];
    }

    /**
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    public function testInvalidBackgroundColorException(): void
    {
        $this->bcExpectException(\Com\Tecnick\Color\Exception::class);
        $barcode = $this->getTestObject();
        $barcode->getBarcodeObj('LRAW', '0101', -2, -2, 'black')->setBackgroundColor('notacolor');
    }

    /**
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    public function testNoColorException(): void
    {
        $this->bcExpectException(\Com\Tecnick\Barcode\Exception::class);
        $barcode = $this->getTestObject();
        $barcode->getBarcodeObj('LRAW', '01001100011100001111,10110011100011110000', -2, -2, '', [-2, 3, 0, 1]);
    }

    /**
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    public function testExportMethods(): void
    {
        $barcode = $this->getTestObject();
        $type = $barcode->getBarcodeObj(
            'LRAW,AB,12,E3F',
            '01001100011100001111,10110011100011110000',
            -2,
            -2,
            'purple',
            [-2, 3, 0, 1],
        );

        $this->assertEquals('01001100011100001111,10110011100011110000', $type->getExtendedCode());

        $barr = $type->getArray();
        $this->assertEquals('linear', $barr['type']);
        $this->assertEquals('LRAW', $barr['format']);
        $this->assertEquals(['AB', '12', 'E3F'], $barr['params']);
        $this->assertEquals('01001100011100001111,10110011100011110000', $barr['code']);
        $this->assertEquals('01001100011100001111,10110011100011110000', $barr['extcode']);
        $this->assertEquals(20, $barr['ncols']);
        $this->assertEquals(2, $barr['nrows']);
        $this->assertEquals(40, $barr['width']);
        $this->assertEquals(4, $barr['height']);
        $this->assertEquals(2, $barr['width_ratio']);
        $this->assertEquals(2, $barr['height_ratio']);
        $this->assertEquals(
            [
                'T' => 4,
                'R' => 3,
                'B' => 0,
                'L' => 1,
            ],
            $barr['padding'],
        );
        $this->assertEquals(44, $barr['full_width']);
        $this->assertEquals(8, $barr['full_height']);

        $expected = [
            [1, 0, 1, 1],
            [4, 0, 2, 1],
            [9, 0, 3, 1],
            [16, 0, 4, 1],
            [0, 1, 1, 1],
            [2, 1, 2, 1],
            [6, 1, 3, 1],
            [12, 1, 4, 1],
        ];
        $this->assertEquals($expected, $barr['bars']);
        $this->assertEquals('#800080ff', $barr['color_obj']->getRgbaHexColor());

        $grid = $type->getGrid('A', 'B');
        $expected = "ABAABBAAABBBAAAABBBB\nBABBAABBBAAABBBBAAAA\n";
        $this->assertEquals($expected, $grid);

        $svg = $type->setBackgroundColor('yellow')->getSvgCode();
        $expected =
            '<?xml version="1.0" standalone="no" ?>
<svg'
            . ' version="1.2"'
            . ' baseProfile="full"'
            . ' xmlns="http://www.w3.org/2000/svg"'
            . ' xmlns:xlink="http://www.w3.org/1999/xlink"'
            . ' xmlns:ev="http://www.w3.org/2001/xml-events"'
            . ' width="44.000000"'
            . ' height="8.000000"'
            . ' viewBox="0 0 44.000000 8.000000">
	<desc>01001100011100001111,10110011100011110000</desc>
	<rect x="0" y="0" width="44.000000" height="8.000000" fill="#ffff00"'
            . ' stroke="none" stroke-width="0" stroke-linecap="square" />
	<g id="bars" fill="#800080" stroke="none" stroke-width="0" stroke-linecap="square">
		<rect x="3.000000" y="4.000000" width="2.000000" height="2.000000" />
		<rect x="9.000000" y="4.000000" width="4.000000" height="2.000000" />
		<rect x="19.000000" y="4.000000" width="6.000000" height="2.000000" />
		<rect x="33.000000" y="4.000000" width="8.000000" height="2.000000" />
		<rect x="1.000000" y="6.000000" width="2.000000" height="2.000000" />
		<rect x="5.000000" y="6.000000" width="4.000000" height="2.000000" />
		<rect x="13.000000" y="6.000000" width="6.000000" height="2.000000" />
		<rect x="25.000000" y="6.000000" width="8.000000" height="2.000000" />
		<rect x="1.000000" y="6.000000" width="2.000000" height="2.000000" />
		<rect x="3.000000" y="4.000000" width="2.000000" height="2.000000" />
		<rect x="5.000000" y="6.000000" width="2.000000" height="2.000000" />
		<rect x="7.000000" y="6.000000" width="2.000000" height="2.000000" />
		<rect x="9.000000" y="4.000000" width="2.000000" height="2.000000" />
		<rect x="11.000000" y="4.000000" width="2.000000" height="2.000000" />
		<rect x="13.000000" y="6.000000" width="2.000000" height="2.000000" />
		<rect x="15.000000" y="6.000000" width="2.000000" height="2.000000" />
		<rect x="17.000000" y="6.000000" width="2.000000" height="2.000000" />
		<rect x="19.000000" y="4.000000" width="2.000000" height="2.000000" />
		<rect x="21.000000" y="4.000000" width="2.000000" height="2.000000" />
		<rect x="23.000000" y="4.000000" width="2.000000" height="2.000000" />
		<rect x="25.000000" y="6.000000" width="2.000000" height="2.000000" />
		<rect x="27.000000" y="6.000000" width="2.000000" height="2.000000" />
		<rect x="29.000000" y="6.000000" width="2.000000" height="2.000000" />
		<rect x="31.000000" y="6.000000" width="2.000000" height="2.000000" />
		<rect x="33.000000" y="4.000000" width="2.000000" height="2.000000" />
		<rect x="35.000000" y="4.000000" width="2.000000" height="2.000000" />
		<rect x="37.000000" y="4.000000" width="2.000000" height="2.000000" />
		<rect x="39.000000" y="4.000000" width="2.000000" height="2.000000" />
	</g>
</svg>
';
        $this->assertEquals($expected, $svg);

        $hdiv = $type->setBackgroundColor('lightcoral')->getHtmlDiv();
        $expected =
            '<div style="width:44.000000px;height:8.000000px;position:relative;font-size:0;'
            . 'border:none;padding:0;margin:0;background-color:rgb(240,128,128);">
	<div style="background-color:rgb(128,0,128);left:3.000000px;top:4.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:9.000000px;top:4.000000px;'
            . 'width:4.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:19.000000px;top:4.000000px;'
            . 'width:6.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:33.000000px;top:4.000000px;'
            . 'width:8.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:1.000000px;top:6.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:5.000000px;top:6.000000px;'
            . 'width:4.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:13.000000px;top:6.000000px;'
            . 'width:6.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:25.000000px;top:6.000000px;'
            . 'width:8.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:1.000000px;top:6.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:3.000000px;top:4.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:5.000000px;top:6.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:7.000000px;top:6.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:9.000000px;top:4.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:11.000000px;top:4.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:13.000000px;top:6.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:15.000000px;top:6.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:17.000000px;top:6.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:19.000000px;top:4.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:21.000000px;top:4.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:23.000000px;top:4.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:25.000000px;top:6.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:27.000000px;top:6.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:29.000000px;top:6.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:31.000000px;top:6.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:33.000000px;top:4.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:35.000000px;top:4.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:37.000000px;top:4.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
	<div style="background-color:rgb(128,0,128);left:39.000000px;top:4.000000px;'
            . 'width:2.000000px;height:2.000000px;position:absolute;border:none;padding:0;margin:0;">&nbsp;</div>
</div>
';
        $this->assertEquals($expected, $hdiv);

        if (\extension_loaded('imagick')) {
            $pngik = $type->setBackgroundColor('white')->getPngData(true);
            $this->assertEquals('PNG', \substr($pngik, 1, 3));
        }

        if (\extension_loaded('gd')) {
            $pnggd = $type->setBackgroundColor('white')->getPngData(false);
            $this->assertEquals('PNG', \substr($pnggd, 1, 3));

            $pnggd = $type->setBackgroundColor('')->getPngData(false);
            $this->assertEquals('PNG', \substr($pnggd, 1, 3));
        }
    }

    /**
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    public function testGetSvg(): void
    {
        $barcode = $this->getTestObject();
        $type = $barcode->getBarcodeObj(
            'LRAW,AB,12,E3F',
            '01001100011100001111,10110011100011110000',
            -2,
            -2,
            'purple',
        );

        // empty filename
        \ob_start();
        $type->getSvg();
        $svg = \ob_get_clean();
        $this->assertNotFalse($svg);
        $this->assertEquals('114f33435c265345f7c6cdf673922292', \md5($svg));
        $this->assertContentDisposition('114f33435c265345f7c6cdf673922292', 'svg');

        // invalid filename
        \ob_start();
        $type->getSvg('#~');
        $svg = \ob_get_clean();
        $this->assertNotFalse($svg);
        $this->assertEquals('114f33435c265345f7c6cdf673922292', \md5($svg));
        $this->assertContentDisposition('114f33435c265345f7c6cdf673922292', 'svg');

        // a trailing newline does not make an otherwise valid filename, since
        // the name is written into a response header
        \ob_start();
        $type->getSvg("valid_name\n");
        $svg = \ob_get_clean();
        $this->assertNotFalse($svg);
        $this->assertEquals('114f33435c265345f7c6cdf673922292', \md5($svg));
        $this->assertContentDisposition('114f33435c265345f7c6cdf673922292', 'svg');

        // valid filename
        \ob_start();
        $type->getSvg('test_SVG_filename-001');
        $svg = \ob_get_clean();
        $this->assertNotFalse($svg);
        $this->assertEquals('114f33435c265345f7c6cdf673922292', \md5($svg));
        $this->assertContentDisposition('test_SVG_filename-001', 'svg');
    }

    /**
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    #[RequiresPhpExtension('gd')]
    public function testGetPng(): void
    {
        $barcode = $this->getTestObject();
        $type = $barcode->getBarcodeObj(
            'LRAW,AB,12,E3F',
            '01001100011100001111,10110011100011110000',
            -2,
            -2,
            'purple',
        );

        // empty filename
        \ob_start();
        $type->getPng();
        $png = \ob_get_clean();
        $this->assertNotFalse($png);
        $this->assertEquals('PNG', \substr($png, 1, 3));
        $this->assertContentDisposition(\md5($png), 'png');

        // invalid filename
        \ob_start();
        $type->getPng('#~');
        $png = \ob_get_clean();
        $this->assertNotFalse($png);
        $this->assertEquals('PNG', \substr($png, 1, 3));
        $this->assertContentDisposition(\md5($png), 'png');

        // valid filename
        \ob_start();
        $type->getPng('test_PNG_filename-001');
        $png = \ob_get_clean();
        $this->assertNotFalse($png);
        $this->assertEquals('PNG', \substr($png, 1, 3));
        $this->assertContentDisposition('test_PNG_filename-001', 'png');
    }

    /**
     * A payload longer than Barcode::MAX_CODE_LENGTH is rejected.
     *
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    public function testPayloadTooLong(): void
    {
        $this->bcExpectException(\Com\Tecnick\Barcode\Exception::class);
        $barcode = $this->getTestObject();
        $barcode->getBarcodeObj('QRCODE', \str_pad('', 30001, 'A'));
    }

    /**
     * A size multiplier that exceeds the maximum image dimensions is rejected.
     *
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    public function testImageTooLarge(): void
    {
        $this->bcExpectException(\Com\Tecnick\Barcode\Exception::class);
        $barcode = $this->getTestObject();
        $type = $barcode->getBarcodeObj('QRCODE', 'test', -100000, -100000);
        $type->getGd();
    }

    /**
     * Returns a barcode whose loaded extensions are the given ones.
     *
     * @param array<string, bool> $loaded Loaded state by extension name.
     *
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    private function getTypeWithExtensions(array $loaded): \Com\Tecnick\Barcode\Type
    {
        $type = new class('12345') extends \Com\Tecnick\Barcode\Type\Linear\Codabar {
            /** @var array<string, bool> */
            public array $loaded = [];

            #[\Override]
            protected function isExtensionLoaded(string $extension): bool
            {
                return $this->loaded[$extension] ?? false;
            }
        };
        $type->loaded = $loaded;

        return $type;
    }

    /**
     * @return array<string, array{0: array<string, bool>, 1: string, 2: string}>
     */
    public static function missingExtensionProvider(): array
    {
        return [
            'png without gd and imagick' => [[], 'png', 'PNG output requires the gd or imagick extension'],
            'png without gd' => [[], 'pnggd', 'PNG output requires the gd extension'],
            'gd image without gd' => [['imagick' => true], 'gd', 'The gd extension is required for GD image output'],
            'imagick png without imagick' => [
                ['gd' => true],
                'imagick',
                'The imagick extension is required for Imagick PNG output',
            ],
        ];
    }

    /**
     * Raster output without the extension it needs throws an exception naming it.
     *
     * @param array<string, bool> $loaded
     *
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    #[DataProvider('missingExtensionProvider')]
    public function testRasterOutputRequiresItsExtension(array $loaded, string $output, string $message): void
    {
        $type = $this->getTypeWithExtensions($loaded);

        try {
            match ($output) {
                'png' => $type->getPngData(),
                'pnggd' => $type->getPngData(false),
                'gd' => $type->getGd(),
                default => $type->getPngDataImagick(),
            };
        } catch (\Com\Tecnick\Barcode\Exception $exc) {
            $this->assertSame($message, $exc->getMessage());
            return;
        }

        $this->fail('Expected exception: ' . $message);
    }

    /**
     * Without Imagick the PNG output uses GD.
     *
     * @throws \Com\Tecnick\Barcode\Exception
     * @throws \Com\Tecnick\Color\Exception
     */
    #[RequiresPhpExtension('gd')]
    public function testPngOutputFallsBackToGd(): void
    {
        $type = $this->getTypeWithExtensions(['gd' => true]);
        $this->assertSame('PNG', \substr($type->getPngData(), 1, 3));
    }
}
