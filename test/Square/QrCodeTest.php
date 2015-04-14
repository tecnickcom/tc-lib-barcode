<?php
/**
 * QrCodeTest.php
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnick.com/tc-lib-barcode
 *
 * This file is part of tc-lib-barcode software library.
 */

namespace Test\Square;

/**
 * Barcode class test
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnick.com/tc-lib-barcode
 */
class QrCodeTest extends \PHPUnit_Framework_TestCase
{
    protected $obj = null;

    public function setUp()
    {
        //$this->markTestSkipped(); // skip this test
        $this->obj = new \Com\Tecnick\Barcode\Barcode;
    }

    public function testGetGrid()
    {
        $bobj = $this->obj->getBarcodeObj('QRCODE', '0123456789');
        $grid = $bobj->getGrid();
        $expected = "111111101111101111111\n"
            ."100000101010101000001\n"
            ."101110101000001011101\n"
            ."101110101111001011101\n"
            ."101110100000101011101\n"
            ."100000101101001000001\n"
            ."111111101010101111111\n"
            ."000000000111100000000\n"
            ."110011100101000101111\n"
            ."000111011001100101111\n"
            ."101010101101001110011\n"
            ."101010000010011010000\n"
            ."100111111110111000011\n"
            ."000000001010111001111\n"
            ."111111100100110001101\n"
            ."100000101101100101101\n"
            ."101110101011001110011\n"
            ."101110100001100001111\n"
            ."101110100101001111100\n"
            ."100000101010011111110\n"
            ."111111101010111001111\n";
        $this->assertEquals($expected, $grid);

        $bobj = $this->obj->getBarcodeObj('QRCODE,L', '0123456789');
        $grid = $bobj->getGrid();
        $this->assertEquals($expected, $grid);

        $bobj = $this->obj->getBarcodeObj('QRCODE,H', '0123456789');
        $grid = $bobj->getGrid();
        $expected = "111111101001001111111\n"
            ."100000101100001000001\n"
            ."101110100111101011101\n"
            ."101110101011001011101\n"
            ."101110101010001011101\n"
            ."100000101100101000001\n"
            ."111111101010101111111\n"
            ."000000000101000000000\n"
            ."000100100001100111011\n"
            ."110100011001101000010\n"
            ."011111101101110001100\n"
            ."101000001100101001011\n"
            ."000000100110111010001\n"
            ."000000001001000110000\n"
            ."111111100011111101001\n"
            ."100000100011101111111\n"
            ."101110100100001110011\n"
            ."101110101011111101011\n"
            ."101110100110001010001\n"
            ."100000100000101111110\n"
            ."111111100111001010100\n";
        $this->assertEquals($expected, $grid);

        $bobj = $this->obj->getBarcodeObj('QRCODE,L,8B,0,0', '123aeiouàèìòù');
        $grid = $bobj->getGrid();
        $expected = "111111101000001111111\n"
            ."100000101101101000001\n"
            ."101110101000001011101\n"
            ."101110101011101011101\n"
            ."101110100111101011101\n"
            ."100000101001001000001\n"
            ."111111101010101111111\n"
            ."000000000100000000000\n"
            ."110011100000100101111\n"
            ."010100000000011010100\n"
            ."101010111110010010011\n"
            ."011100000011111011001\n"
            ."101001101111101100011\n"
            ."000000001001111000111\n"
            ."111111100110100111100\n"
            ."100000101100010101100\n"
            ."101110101010110000001\n"
            ."101110100100011110111\n"
            ."101110100110010101100\n"
            ."100000101011011101001\n"
            ."111111101001101000011\n";
        $this->assertEquals($expected, $grid);

        $bobj = $this->obj->getBarcodeObj('QRCODE,H,KJ,0,0', 'ぎポ亊');
        $grid = $bobj->getGrid();
        $expected = "1111111000011011101111111\n"
            ."1000001011110000101000001\n"
            ."1011101001111010001011101\n"
            ."1011101000001001001011101\n"
            ."1011101001000111101011101\n"
            ."1000001010100001101000001\n"
            ."1111111010101010101111111\n"
            ."0000000010111010000000000\n"
            ."0000111101101110101100010\n"
            ."1010010011100001101110111\n"
            ."1000001101111011101011111\n"
            ."1110110110010100111010011\n"
            ."0001111100100000010110000\n"
            ."1111110000101110101100001\n"
            ."0010101001101010110100010\n"
            ."0010110110010110000001011\n"
            ."1111011110111001111111110\n"
            ."0000000011100001100010101\n"
            ."1111111011001001101010001\n"
            ."1000001010100001100011010\n"
            ."1011101011101000111110111\n"
            ."1011101000100101001110101\n"
            ."1011101001100011100000010\n"
            ."1000001000010101101001010\n"
            ."1111111000100011100010100\n";
        $this->assertEquals($expected, $grid);
    }

    public function testInvalidInput()
    {
        $this->setExpectedException('\Com\Tecnick\Barcode\Exception');
        $this->obj->getBarcodeObj('QRCODE', '');
    }
}
